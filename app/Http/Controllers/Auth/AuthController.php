<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users;
use App\Models\Unit;
use App\Models\GoogleConfig;
use App\Models\MailConfig;
use App\Models\RecaptchaConfig;
use App\Models\SettingAdmin;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class AuthController extends Controller
{
    const OTP_EXPIRY_MINUTES            = 15;
    const RESEND_COOLDOWN_SECONDS       = 900;
    const PASSWORD_RESET_EXPIRY_MINUTES = 15;
    const MAX_LOGO_UNIT                 = 1;

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function getExpoSettings(): array
    {
        $settings = SettingAdmin::first();

        $logoPath = null;
        if ($settings && $settings->logo_expo) {
            $path = storage_path('app/public/' . $settings->logo_expo);
            if (file_exists($path)) {
                $logoPath = $path;
            }
        }

        if (!$logoPath) {
            $defaultPath = public_path('images/default-logo.png');
            if (file_exists($defaultPath)) {
                $logoPath = $defaultPath;
            }
        }

        return [
            'nama_expo' => $settings?->nama_expo ?? config('app.name', 'YBM UMKM'),
            'logo_expo' => $logoPath,
        ];
    }

    private function loadMailConfig(): bool
    {
        try {
            $mailConfig = MailConfig::first();

            if (!$mailConfig) {
                Log::warning('MailConfig: No mail configuration found in database.');
                return false;
            }

            // Validate required fields
            if (empty($mailConfig->MAIL_HOST) || empty($mailConfig->MAIL_USERNAME) || empty($mailConfig->MAIL_PASSWORD)) {
                Log::warning('MailConfig: Missing required fields (HOST, USERNAME, or PASSWORD).');
                return false;
            }

            // Warn if using test/sandbox mail host
            if (str_contains(strtolower($mailConfig->MAIL_HOST), 'mailtrap') || str_contains(strtolower($mailConfig->MAIL_HOST), 'sandbox')) {
                Log::warning('MailConfig: MAIL_HOST is set to a test/sandbox server (' . $mailConfig->MAIL_HOST . '). Emails will NOT be delivered to real inboxes. Please update to smtp.gmail.com or your production SMTP server.');
            }

            config([
                'mail.mailers.smtp.host'       => $mailConfig->MAIL_HOST,
                'mail.mailers.smtp.port'       => $mailConfig->MAIL_PORT ?? 587,
                'mail.mailers.smtp.encryption' => $mailConfig->MAIL_ENCRYPTION ?? 'tls',
                'mail.mailers.smtp.username'   => $mailConfig->MAIL_USERNAME,
                'mail.mailers.smtp.password'   => $mailConfig->MAIL_PASSWORD,
                'mail.from.address'            => $mailConfig->MAIL_FROM_ADDRESS ?? $mailConfig->MAIL_USERNAME,
                'mail.from.name'               => $mailConfig->MAIL_FROM_NAME ?? 'YBM UMKM',
                'mail.mailers.smtp.timeout'    => 30, // Increased timeout
            ]);

            // For local development on Windows/Laragon, we sometimes need to bypass SSL verification 
            // if CA certificates are not properly configured in php.ini
            if (app()->environment('local')) {
                config([
                    'mail.mailers.smtp.stream' => [
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer'       => false,
                            'verify_peer_name'  => false,
                        ],
                    ],
                ]);
            }

            Mail::purge('smtp');

            return true;
        } catch (\Exception $e) {
            Log::error('MailConfig: Failed to load mail configuration - ' . $e->getMessage());
            return false;
        }
    }

    private function maskEmail(string $email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        [$username, $domain] = explode('@', $email);

        $maskedUsername = strlen($username) > 1
            ? $username[0] . str_repeat('*', strlen($username) - 1)
            : $username;

        return $maskedUsername . '@' . $domain;
    }

    private function verifyRecaptcha(?string $token, string $expectedAction = 'login'): bool
    {
        $secretKey = config('services.recaptcha.secret_key');

        if (!$secretKey) {
            try {
                $recaptchaConfig = RecaptchaConfig::first();
                $secretKey       = $recaptchaConfig?->RECAPTCHA_SECRET_KEY ?? null;
            } catch (\Exception $e) {
                // silent
            }
        }

        // Jika tidak ada secret key, lewati verifikasi
        if (!$secretKey) {
            return true;
        }

        if (!$token) {
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (!($result['success'] ?? false)) {
                return false;
            }

            if (($result['score'] ?? 0) < 0.5) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function getRecaptchaSiteKey(): ?string
    {
        try {
            $recaptchaConfig = RecaptchaConfig::first();
            return $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? config('services.recaptcha.site_key');
        } catch (\Exception $e) {
            return config('services.recaptcha.site_key');
        }
    }

    private function cleanupRegistrationCache(string $email, string $token): void
    {
        $keys = [
            'complete_profile_' . $email,
            'google_complete_'  . $email,
            'otp_registration_' . $email,
            'otp_cooldown_'     . $email,
            'token_map_'        . $token,
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    private function generateUniqueUsername(string $email, ?int $excludeUserId = null): string
    {
        $base    = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', explode('@', $email)[0]));
        $username = $base;
        $counter  = 1;

        while (
            Users::where('username', $username)
                ->when($excludeUserId, fn($q) => $q->where('id', '!=', $excludeUserId))
                ->exists()
        ) {
            $username = $base . $counter++;
        }

        return $username;
    }

    private function generateKodeUnit(): string
    {
        $prefix = 'UNIT';
        $year   = date('Y');

        return DB::transaction(function () use ($prefix, $year) {
            $lastUnit = Unit::whereYear('created_at', $year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $number   = $lastUnit ? (int) substr($lastUnit->kode_unit, -4) + 1 : 1;
            $kodeUnit = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);

            $attempts = 0;
            while (Unit::where('kode_unit', $kodeUnit)->exists() && $attempts < 10) {
                $kodeUnit = $prefix . $year . str_pad(++$number, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            if ($attempts >= 10) {
                throw new \Exception('Failed to generate unique kode unit after 10 attempts');
            }

            return $kodeUnit;
        });
    }

    // =========================================================
    // LOGIN
    // =========================================================

    public function showLogin()
    {
        return view('auth.login', [
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function login(Request $request)
    {
        $recaptchaSiteKey = $this->getRecaptchaSiteKey();

        $validator = Validator::make($request->all(), [
            'login'           => 'required|string',
            'password'        => 'required|string',
            'recaptcha_token' => 'nullable|string',
        ], [
            'login.required'    => 'Email atau username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('login', 'remember'));
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'login')) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $loginType   = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$loginType => $request->login, 'password' => $request->password];

        $user = Users::where($loginType, $request->login)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun tidak terdaftar dalam sistem.']);
        }

        if (!$user->email_verified_at) {
            return redirect()->route('verify-otp')
                ->with('email', $user->email)
                ->with('warning', 'Email Anda belum diverifikasi. Silakan cek email Anda untuk kode OTP.');
        }

        if (!$user->is_active) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->withErrors(['login' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        // ✅ TAMBAHAN: Cek status Unit atau UMKM yang menaungi
        if ($user->role === 'umkm') {
            $umkm = $user->umkm;
            if ($umkm) {
                if ($umkm->status === 'nonaktif') {
                    return redirect()->back()
                        ->withInput($request->only('login', 'remember'))
                        ->withErrors(['login' => 'Akun UMKM Anda tidak aktif. Silakan hubungi administrator.']);
                }
                if ($umkm->unit && !$umkm->unit->is_active) {
                    return redirect()->back()
                        ->withInput($request->only('login', 'remember'))
                        ->withErrors(['login' => 'Unit yang menaungi UMKM Anda sedang tidak aktif.']);
                }
            }
        } elseif ($user->role === 'unit') {
            $unit = $user->unit;
            if ($unit && !$unit->is_active) {
                return redirect()->back()
                    ->withInput($request->only('login', 'remember'))
                    ->withErrors(['login' => 'Akun Unit Anda sedang tidak aktif. Silakan hubungi administrator.']);
            }
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            ActivityLogger::logAuth('login', $user);
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . ($user->username ?? $this->maskEmail($user->email)) . '!');
        }

        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['password' => 'Password yang Anda masukkan salah.']);
    }

    // =========================================================
    // REGISTER
    // =========================================================

    public function showRegister()
    {
        return view('auth.register', [
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function register(Request $request)
    {
        $normalizedEmail = strtolower(trim($request->email));

        $validator = Validator::make($request->all(), [
            'email'           => 'required|email|unique:users,email',
            'recaptcha_token' => 'nullable|string',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
            'email.unique'   => 'Email sudah terdaftar. Silakan gunakan email lain.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'register')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        try {
            $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            Cache::put('otp_registration_' . $normalizedEmail, [
                'email'      => $normalizedEmail,
                'otp'        => $otp,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::OTP_EXPIRY_MINUTES * 60);

            Cache::put(
                'otp_cooldown_' . $normalizedEmail,
                Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS),
                self::RESEND_COOLDOWN_SECONDS
            );

            $request->session()->put('otp_email', $normalizedEmail);
            $request->session()->put('email', $normalizedEmail);

            $mailConfigLoaded = $this->loadMailConfig();

            if (!$mailConfigLoaded) {
                if (app()->environment('local')) {
                    return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                        ->with('email', $normalizedEmail)
                        ->with('warning', 'Mode development: OTP Anda adalah: ' . $otp);
                }

                return back()
                    ->with('error', 'Konfigurasi email belum lengkap. Silakan hubungi administrator.')
                    ->withInput();
            }

            try {
                $expo = $this->getExpoSettings();
                Mail::send('emails.otp-verification', array_merge($expo, [
                    'otp'              => $otp,
                    'email'            => $normalizedEmail,
                    'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
                ]), function ($message) use ($normalizedEmail, $expo) {
                    $message->to($normalizedEmail)->subject('Kode OTP Verifikasi - ' . $expo['nama_expo']);
                });

                return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                    ->with('email', $normalizedEmail)
                    ->with('success', 'Kode OTP telah dikirim ke email Anda.');
            } catch (\Exception $e) {
                Log::error('Registration Email Error: ' . $e->getMessage(), [
                    'email' => $normalizedEmail,
                    'trace' => $e->getTraceAsString()
                ]);

                if (app()->environment('local')) {
                    return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                        ->with('email', $normalizedEmail)
                        ->with('warning', 'Email tidak terkirim. OTP Anda adalah: ' . $otp);
                }

                return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                    ->with('email', $normalizedEmail)
                    ->with('error', 'Gagal mengirim email. Gunakan tombol "Kirim Ulang OTP".');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // =========================================================
    // OTP
    // =========================================================

    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email')
            ?? $request->session()->get('otp_email')
            ?? $request->session()->get('email');

        if (!$email) {
            return redirect()->route('register')->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $normalizedEmail = strtolower(trim($email));
        $cacheKey        = 'otp_registration_' . $normalizedEmail;
        $otpData         = Cache::get($cacheKey);

        if (!$otpData) {
            return redirect()->route('register')->with('error', 'OTP telah kedaluwarsa. Silakan daftar ulang.');
        }

        try {
            $expiresAt = Carbon::parse($otpData['expires_at']);

            if (Carbon::now()->isAfter($expiresAt)) {
                Cache::forget($cacheKey);
                return redirect()->route('register')->with('error', 'OTP telah kedaluwarsa. Silakan daftar ulang.');
            }
        } catch (\Exception $e) {
            return redirect()->route('register')->with('error', 'Terjadi kesalahan. Silakan daftar ulang.');
        }

        $request->session()->put('otp_email', $normalizedEmail);
        $request->session()->put('email', $normalizedEmail);

        return view('auth.verify-otp', [
            'email'            => $email,
            'maskedEmail'      => $this->maskEmail($email),
            'expiresAt'        => $expiresAt->toIso8601String(),
            'canResendAt'      => Cache::get('otp_cooldown_' . $normalizedEmail),
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'           => 'required|email',
            'otp'             => 'required|string|size:6',
            'recaptcha_token' => 'nullable|string',
        ], [
            'email.required' => 'Email tidak valid',
            'otp.required'   => 'Kode OTP harus diisi',
            'otp.size'       => 'Kode OTP harus 6 digit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('email', $request->email);
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'verify_otp')) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $cacheKey = 'otp_registration_' . $request->email;
        $otpData  = Cache::get($cacheKey);

        if (!$otpData) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'OTP telah kedaluwarsa. Silakan minta kode baru.']);
        }

        if ($otpData['otp'] !== $request->otp) {
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        DB::beginTransaction();

        try {
            $user = Users::where('email', $request->email)->first();

            if (!$user) {
                $user = Users::create([
                    'uuid'              => (string) Str::uuid(),
                    'email'             => $request->email,
                    'email_verified_at' => now(),
                    'role'              => 'unit',
                    'is_active'         => false,
                    'password'          => Hash::make(Str::random(32)),
                ]);
            } elseif (!$user->email_verified_at) {
                $user->update(['email_verified_at' => now()]);
            }

            Cache::forget($cacheKey);
            Cache::forget('otp_cooldown_' . $request->email);

            $profileToken = (string) Str::uuid();

            Cache::put('complete_profile_' . $request->email, [
                'email'      => $request->email,
                'user_id'    => $user->id,
                'token'      => $profileToken,
                'created_at' => now()->toDateTimeString(),
            ], 3600);

            Cache::put('token_map_' . $profileToken, $request->email, 3600);

            DB::commit();

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('success', 'Email berhasil diverifikasi. Silakan lengkapi profil Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('email', $request->email)
                ->withErrors(['otp' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    public function resendOtp(Request $request)
    {
        $email          = $request->input('email');
        $recaptchaToken = $request->input('recaptcha_token');

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email tidak valid.'], 400);
        }

        if (!$this->verifyRecaptcha($recaptchaToken, 'resend_otp')) {
            return response()->json(['success' => false, 'message' => 'Verifikasi reCAPTCHA gagal.'], 400);
        }

        try {
            $cooldownKey = 'otp_cooldown_' . $email;
            $canResendAt = Cache::get($cooldownKey);

            if ($canResendAt && Carbon::now()->isBefore($canResendAt)) {
                $remaining = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remaining} detik sebelum mengirim ulang OTP.",
                ], 400);
            }

            $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            Cache::put('otp_registration_' . $email, [
                'email'      => $email,
                'otp'        => $otp,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::OTP_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS), self::RESEND_COOLDOWN_SECONDS);

            if ($this->loadMailConfig()) {
                $expo = $this->getExpoSettings();
                Mail::send('emails.otp-verification', array_merge($expo, [
                    'otp'              => $otp,
                    'email'            => $email,
                    'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
                ]), function ($message) use ($email, $expo) {
                    $message->to($email)->subject('Kode OTP Baru - ' . $expo['nama_expo']);
                });
            }

            return response()->json([
                'success'    => true,
                'message'    => 'OTP baru telah dikirim ke email Anda.',
                'expires_at' => $expiresAt->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.'], 500);
        }
    }

    // =========================================================
    // FORGOT PASSWORD
    // =========================================================

    public function showForgotPasswordForm(Request $request)
    {
        $prefillEmail    = $request->session()->get('prefill_email');
        $cooldownSeconds = 0;

        if ($prefillEmail) {
            $canResendAt = Cache::get('password_reset_cooldown_' . $prefillEmail);
            if ($canResendAt && Carbon::now()->isBefore($canResendAt)) {
                $cooldownSeconds = (int) Carbon::now()->diffInSeconds($canResendAt);
            }
        }

        return view('auth.forgot-password', [
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'prefillEmail'     => $prefillEmail,
            'cooldownSeconds'  => $cooldownSeconds,
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $email = strtolower(trim($request->email));

        $validator = Validator::make($request->all(), [
            'email'           => 'required|email|exists:users,email',
            'recaptcha_token' => 'nullable|string',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email'    => 'Format email tidak valid',
            'email.exists'   => 'Email tidak terdaftar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'forgot_password')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        // Cek cooldown
        $cooldownKey    = 'password_reset_cooldown_' . $email;
        $cooldownExpiry = Cache::get($cooldownKey);

        if ($cooldownExpiry && Carbon::now()->isBefore($cooldownExpiry)) {
            $request->session()->put('prefill_email', $email);
            return redirect()->route('password.request');
        }

        $user = Users::where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.')->withInput();
        }

        // Buat / ambil token reset
        $cacheKey     = 'password_reset_' . $user->uuid;
        $existingData = Cache::get($cacheKey);

        $token = ($existingData && Carbon::now()->lt(Carbon::parse($existingData['expires_at'])))
            ? $existingData['token']
            : Str::random(64);

        $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

        Cache::put($cacheKey, [
            'email'      => $email,
            'token'      => $token,
            'user_id'    => $user->id,
            'expires_at' => $expiresAt->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
        ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

        Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

        // Kirim email — gagal tidak menghentikan alur
        $mailSent = false;
        try {
            if ($this->loadMailConfig()) {
                $expo     = $this->getExpoSettings();
                $resetUrl = route('password.reset', ['uuid' => $user->uuid, 'token' => $token]);

                Mail::send('emails.password-reset', array_merge($expo, [
                    'token'            => $token,
                    'email'            => $email,
                    'nama'             => $user->username ?? $this->maskEmail($user->email),
                    'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                    'resetUrl'         => $resetUrl,
                ]), function ($message) use ($email, $expo) {
                    $message->to($email)->subject('Reset Password - ' . $expo['nama_expo']);
                });

                $mailSent = true;
            }
        } catch (\Exception $e) {
            \Log::warning('Password reset email failed: ' . $e->getMessage(), ['email' => $email]);
        }

        $request->session()->forget('prefill_email');
        $request->session()->put('email', $email);
        $request->session()->flash('email', $email);

        $message = $mailSent
            ? 'Link reset password telah dikirim ke email Anda.'
            : 'Link reset password telah dibuat. Jika email tidak diterima dalam beberapa menit, gunakan tombol "Kirim Ulang".';

        return redirect()->route('password.reset-sent')
            ->with('email', $email)
            ->with('success', $message);
    }

    public function showResetSent(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi telah berakhir. Silakan minta reset password ulang.');
        }

        $user = Users::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Pengguna tidak ditemukan.');
        }

        $resetData = Cache::get('password_reset_' . $user->uuid);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa. Silakan minta ulang.');
        }

        $expiresAt   = Carbon::parse($resetData['expires_at']);
        $canResendAt = Cache::get('password_reset_cooldown_' . $email);
        $canResendIn = $canResendAt ? max(0, Carbon::now()->diffInSeconds($canResendAt, false)) : 0;

        $countdownSeconds = max(0, Carbon::now()->diffInSeconds($expiresAt));
        if ($countdownSeconds > (self::PASSWORD_RESET_EXPIRY_MINUTES * 60)) {
            $countdownSeconds = self::PASSWORD_RESET_EXPIRY_MINUTES * 60;
        }

        $request->session()->put('email', $email);
        $request->session()->flash('email', $email);

        return view('auth.reset-password-sent', [
            'email'            => $email,
            'maskedEmail'      => $this->maskEmail($email),
            'expiresAt'        => $expiresAt,
            'canResendAt'      => $canResendAt,
            'canResendIn'      => $canResendIn,
            'countdownSeconds' => $countdownSeconds,
            'user'             => $user,
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function showResetPasswordForm(Request $request, $uuid, $token)
    {
        $cacheKey  = 'password_reset_' . $uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password tidak valid atau telah kadaluarsa.');
        }

        if (!hash_equals($resetData['token'], $token)) {
            return redirect()->route('password.request')->with('error', 'Token reset password tidak valid.');
        }

        if (Carbon::now()->gt(Carbon::parse($resetData['expires_at']))) {
            return redirect()->route('password.request')->with('error', 'Link reset password telah kadaluarsa.');
        }

        return view('auth.reset-password', [
            'email'            => $resetData['email'],
            'maskedEmail'      => $this->maskEmail($resetData['email']),
            'token'            => $token,
            'uuid'             => $uuid,
            'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            'setting'          => SettingAdmin::first(),
        ]);
    }

    public function resendResetLink(Request $request)
    {
        $email          = $request->input('email');
        $recaptchaToken = $request->input('recaptcha_token');

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email tidak valid.'], 400);
        }

        if (!$this->verifyRecaptcha($recaptchaToken, 'resend_reset_link')) {
            return response()->json(['success' => false, 'message' => 'Verifikasi reCAPTCHA gagal.'], 400);
        }

        try {
            $cooldownKey = 'password_reset_cooldown_' . $email;
            $canResendAt = Cache::get($cooldownKey);

            if ($canResendAt && Carbon::now()->isBefore($canResendAt)) {
                $remaining = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remaining} detik sebelum mengirim ulang link reset password.",
                ], 400);
            }

            $user = Users::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
            }

            $token     = Str::random(64);
            $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

            Cache::put('password_reset_' . $user->uuid, [
                'email'      => $email,
                'token'      => $token,
                'user_id'    => $user->id,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

            // Kirim email — gagal tidak menghentikan alur (sama seperti sendResetLink)
            $mailSent = false;
            try {
                if ($this->loadMailConfig()) {
                    $expo     = $this->getExpoSettings();
                    $resetUrl = route('password.reset', ['uuid' => $user->uuid, 'token' => $token]);

                    Mail::send('emails.password-reset', array_merge($expo, [
                        'token'            => $token,
                        'email'            => $email,
                        'nama'             => $user->username ?? $this->maskEmail($user->email),
                        'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                        'resetUrl'         => $resetUrl,
                    ]), function ($message) use ($email, $expo) {
                        $message->to($email)->subject('Reset Password - ' . $expo['nama_expo']);
                    });

                    $mailSent = true;
                }
            } catch (\Exception $e) {
                \Log::warning('Resend reset link email failed: ' . $e->getMessage(), ['email' => $email]);
            }

            $message = $mailSent
                ? 'Link reset password telah dikirim ulang ke email Anda.'
                : 'Link reset password telah diperbarui. Jika email tidak diterima, coba kirim ulang lagi.';

            return response()->json([
                'success'     => true,
                'message'     => $message,
                'canResendIn' => 60,
            ]);
        } catch (\Exception $e) {
            \Log::error('resendResetLink error: ' . $e->getMessage(), ['email' => $email ?? null]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.'], 500);
        }
    }

    public function resetPassword(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'token'           => 'required',
            'email'           => 'required|email',
            'password'        => 'required|min:8|confirmed',
            'recaptcha_token' => 'nullable|string',
        ], [
            'password.required'  => 'Password baru harus diisi',
            'password.min'       => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'reset_password')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $cacheKey  = 'password_reset_' . $uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')->with('error', 'Link reset password telah kadaluarsa.');
        }

        if (!hash_equals($resetData['token'], $request->token)) {
            return redirect()->route('password.request')->with('error', 'Token reset password tidak valid.');
        }

        if ($resetData['email'] !== $request->email) {
            return redirect()->route('password.request')->with('error', 'Email tidak sesuai.');
        }

        if (Carbon::now()->gt(Carbon::parse($resetData['expires_at']))) {
            return redirect()->route('password.request')->with('error', 'Link reset password telah kadaluarsa.');
        }

        DB::beginTransaction();

        try {
            $user = Users::find($resetData['user_id']);

            if (!$user) {
                throw new \Exception('Pengguna tidak ditemukan');
            }

            $user->update(['password' => Hash::make($request->password), 'updated_at' => now()]);

            Cache::forget($cacheKey);
            Cache::forget('password_reset_cooldown_' . $request->email);

            try {
                $this->loadMailConfig();
                $expo = $this->getExpoSettings();

                Mail::send('emails.password-reset-success', array_merge($expo, [
                    'nama'    => $user->username ?? $this->maskEmail($user->email),
                    'email'   => $user->email,
                    'tanggal' => now()->format('d F Y H:i:s'),
                ]), function ($message) use ($user, $expo) {
                    $message->to($user->email)->subject('Password Berhasil Diubah - ' . $expo['nama_expo']);
                });
            } catch (\Exception $e) {
                // silent fail
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // =========================================================
    // COMPLETE PROFILE
    // =========================================================

    public function showCompleteProfile($token)
    {
        if (!$token) {
            return redirect()->route('register')->with('error', 'Token tidak valid. Silakan daftar ulang.');
        }

        $email = Cache::get('token_map_' . $token);

        if (!$email) {
            // Fallback: cari dari user tidak aktif
            $inactiveUsers = Users::where('is_active', false)->whereNotNull('email_verified_at')->get();

            foreach ($inactiveUsers as $user) {
                foreach (['complete_profile_' . $user->email, 'google_complete_' . $user->email] as $key) {
                    $data = Cache::get($key);
                    if ($data && isset($data['token']) && $data['token'] === $token) {
                        $email = $user->email;
                        break 2;
                    }
                }
            }

            if (!$email) {
                return redirect()->route('register')
                    ->with('error', 'Token tidak valid atau sesi telah berakhir. Silakan daftar ulang.');
            }
        }

        $cacheData = Cache::get('complete_profile_' . $email) ?? Cache::get('google_complete_' . $email);

        if (!$cacheData || !isset($cacheData['user_id'])) {
            return redirect()->route('register')->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $user = Users::find($cacheData['user_id']);

        if (!$user) {
            return redirect()->route('register')->with('error', 'Pengguna tidak ditemukan. Silakan daftar ulang.');
        }

        try {
            return view('auth.complete-profile', [
                'user'             => $user,
                'maskedEmail'      => $this->maskEmail($user->email),
                'isGoogleUser'     => !empty($user->google_id),
                'provinces'        => Province::orderBy('name', 'asc')->get(),
                'token'            => $token,
                'recaptchaSiteKey' => $this->getRecaptchaSiteKey(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat memuat data wilayah. Silakan coba lagi.');
        }
    }

    public function storeCompleteProfile(Request $request, $token)
    {
        $user = Users::find($request->user_id);

        if (!$user) {
            return back()->with('error', 'Pengguna tidak ditemukan')->withInput();
        }

        $isGoogleUser = !empty($user->google_id);

        $rules = [
            'username' => $isGoogleUser ? 'nullable' : [
                'required', 'string', 'min:6', 'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:users,username,' . $user->id,
            ],
            'password'        => $isGoogleUser ? 'nullable' : 'required|string|min:8|confirmed',
            'admin_nama'      => 'required|string|max:255',
            'admin_telepon'   => 'required|string|max:20',
            'admin_email'     => 'required|email|max:255',
            'admin_foto'      => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'nama_unit'       => 'required|string|max:255|unique:units,nama_unit',
            'deskripsi'       => 'nullable|string',
            'logo'            => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'alamat'          => 'required|string|min:10|max:500',
            'provinsi_kode'   => 'required|string|exists:indonesia_provinces,code',
            'kota_kode'       => 'required|string|exists:indonesia_cities,code',
            'kecamatan_kode'  => 'required|string|exists:indonesia_districts,code',
            'kelurahan_kode'  => 'required|string|exists:indonesia_villages,code',
            'kode_pos'        => 'required|string|max:5',
            'telepon'         => 'required|string|max:20',
            'email_unit'      => 'required|email|max:255',
            'recaptcha_token' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'provinsi_kode.required'  => 'Provinsi harus dipilih',
            'kota_kode.required'      => 'Kota/Kabupaten harus dipilih',
            'kecamatan_kode.required' => 'Kecamatan harus dipilih',
            'kelurahan_kode.required' => 'Kelurahan harus dipilih',
            'nama_unit.unique'        => 'Nama unit sudah terdaftar',
            'alamat.required'         => 'Alamat lengkap harus diisi',
            'alamat.min'              => 'Alamat minimal 10 karakter',
            'alamat.max'              => 'Alamat maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            $errors    = $validator->errors();
            $errorStep = 1;

            if ($errors->hasAny(['admin_nama', 'admin_telepon', 'admin_email', 'admin_foto'])) {
                $errorStep = 2;
            } elseif ($errors->hasAny([
                'nama_unit', 'deskripsi', 'logo', 'alamat',
                'provinsi_kode', 'kota_kode', 'kecamatan_kode', 'kelurahan_kode',
                'kode_pos', 'telepon', 'email_unit',
            ])) {
                $errorStep = 3;
            }

            return back()->withErrors($validator)->withInput()->with('error_step', $errorStep);
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'complete_profile')) {
            return back()
                ->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.')
                ->with('error_step', 3)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $userData = ['is_active' => true];

            if ($isGoogleUser) {
                $userData['username'] = $this->generateUniqueUsername($user->email, $user->id);
            } else {
                $userData['username'] = $request->username;
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $adminFotoPath = $request->hasFile('admin_foto')
                ? $request->file('admin_foto')->store('admin-fotos', 'public')
                : null;

            $logoPath = $request->hasFile('logo')
                ? $request->file('logo')->store('unit-logos', 'public')
                : null;

            $provinsi  = Province::where('code', $request->provinsi_kode)->first();
            $kota      = City::where('code', $request->kota_kode)->first();
            $kecamatan = District::where('code', $request->kecamatan_kode)->first();
            $kelurahan = Village::where('code', $request->kelurahan_kode)->first();

            if (!$provinsi || !$kota || !$kecamatan || !$kelurahan) {
                DB::rollBack();
                return back()
                    ->with('error', 'Data wilayah tidak valid. Silakan pilih ulang.')
                    ->with('error_step', 3)
                    ->withInput();
            }

            $kodePos  = $request->kode_pos ?? ($kelurahan->meta['postal_code'] ?? null);
            $kodeUnit = $this->generateKodeUnit();

            Unit::create([
                'uuid'           => (string) Str::uuid(),
                'user_id'        => $user->id,
                'kode_unit'      => $kodeUnit,
                'admin_nama'     => $request->admin_nama,
                'admin_telepon'  => $request->admin_telepon,
                'admin_email'    => $request->admin_email ?? $user->email,
                'admin_foto'     => $adminFotoPath,
                'nama_unit'      => $request->nama_unit,
                'deskripsi'      => $request->deskripsi,
                'logo'           => $logoPath,
                'alamat'         => $request->alamat,
                'provinsi_kode'  => $request->provinsi_kode,
                'provinsi_nama'  => $provinsi->name,
                'kota_kode'      => $request->kota_kode,
                'kota_nama'      => $kota->name,
                'kecamatan_kode' => $request->kecamatan_kode,
                'kecamatan_nama' => $kecamatan->name,
                'kelurahan_kode' => $request->kelurahan_kode,
                'kelurahan_nama' => $kelurahan->name,
                'kode_pos'       => $kodePos,
                'telepon'        => $request->telepon,
                'unit_email'     => $request->email_unit,
                'is_active'      => true,
            ]);

            $this->cleanupRegistrationCache($user->email, $token);

            try {
                $this->loadMailConfig();
                $expo = $this->getExpoSettings();

                Mail::send('emails.registration-success', array_merge($expo, [
                    'nama'         => $request->admin_nama,
                    'username'     => $userData['username'],
                    'email'        => $user->email,
                    'nama_unit'    => $request->nama_unit,
                    'kode_unit'    => $kodeUnit,
                    'isGoogleUser' => $isGoogleUser,
                    'password'     => $isGoogleUser ? null : $request->password,
                ]), function ($message) use ($user, $expo) {
                    $message->to($user->email)->subject('Registrasi Berhasil - ' . $expo['nama_expo']);
                });
            } catch (\Exception $e) {
                // silent fail
            }

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->with('error_step', 3)
                ->withInput();
        }
    }

    // =========================================================
    // GOOGLE OAUTH
    // =========================================================

    public function redirectToGoogle(Request $request)
    {
        $action = $request->query('action', 'login');
        session(['oauth_action' => $action]);

        $googleConfig = GoogleConfig::first();

        if (!$googleConfig || !$googleConfig->GOOGLE_CLIENT_ID || !$googleConfig->GOOGLE_CLIENT_SECRET) {
            return redirect()->route($action === 'register' ? 'register' : 'login')
                ->with('error', 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator.');
        }

        config([
            'services.google.client_id'     => $googleConfig->GOOGLE_CLIENT_ID,
            'services.google.client_secret' => $googleConfig->GOOGLE_CLIENT_SECRET,
            'services.google.redirect'      => $googleConfig->GOOGLE_REDIRECT_URI ?? url('/auth/google/callback'),
        ]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleConfig = GoogleConfig::first();

            if ($googleConfig) {
                config([
                    'services.google.client_id'     => $googleConfig->GOOGLE_CLIENT_ID,
                    'services.google.client_secret' => $googleConfig->GOOGLE_CLIENT_SECRET,
                    'services.google.redirect'      => $googleConfig->GOOGLE_REDIRECT_URI ?? url('/auth/google/callback'),
                ]);
            }

            $googleUser = Socialite::driver('google')->user();
            $action     = session('oauth_action', 'login');
            session()->forget('oauth_action');

            return $action === 'register'
                ? $this->handleGoogleRegister($googleUser)
                : $this->handleGoogleLogin($googleUser, $request);
        } catch (\Exception $e) {
            $redirectRoute = session('oauth_action', 'login') === 'register' ? 'register' : 'login';
            session()->forget('oauth_action');

            return redirect()->route($redirectRoute)
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    private function handleGoogleRegister($googleUser)
    {
        if (Users::where('google_id', $googleUser->id)->exists()) {
            return redirect()->route('register')->with('error', 'Akun Google sudah terdaftar. Silakan login.');
        }

        if (Users::where('email', $googleUser->email)->exists()) {
            return redirect()->route('register')->with('error', 'Email sudah terdaftar dengan metode lain. Silakan login.');
        }

        DB::beginTransaction();
        try {
            $user = Users::create([
                'uuid'              => (string) Str::uuid(),
                'email'             => $googleUser->email,
                'google_id'         => $googleUser->id,
                'google_token'      => $googleUser->token,
                'refresh_token'     => $googleUser->refreshToken,
                'email_verified_at' => now(),
                'role'              => 'unit',
                'is_active'         => false,
                'password'          => Hash::make(Str::random(32)),
            ]);

            $profileToken = (string) Str::uuid();
            $cacheData    = [
                'email'      => $googleUser->email,
                'user_id'    => $user->id,
                'token'      => $profileToken,
                'created_at' => now()->toDateTimeString(),
                'source'     => 'google_oauth',
            ];

            Cache::put('complete_profile_' . $googleUser->email, $cacheData, 3600);
            Cache::put('google_complete_' . $googleUser->email, $cacheData, 3600);
            Cache::put('token_map_' . $profileToken, $googleUser->email, 3600);

            DB::commit();

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('success', 'Akun berhasil dibuat dengan Google. Silakan lengkapi profil Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat registrasi dengan Google. Silakan coba lagi.');
        }
    }

    private function handleGoogleLogin($googleUser, Request $request)
    {
        $user = Users::where('google_id', $googleUser->id)->first()
            ?? Users::where('email', $googleUser->email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('login')->with('error', 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.');
        }

        if (!$user->is_active) {
            $profileToken = (string) Str::uuid();
            $cacheData    = [
                'email'      => $user->email,
                'user_id'    => $user->id,
                'token'      => $profileToken,
                'created_at' => now()->toDateTimeString(),
                'source'     => 'google_login_reactivate',
            ];

            Cache::put('complete_profile_' . $user->email, $cacheData, 3600);
            Cache::put('google_complete_' . $user->email, $cacheData, 3600);
            Cache::put('token_map_' . $profileToken, $user->email, 3600);

            return redirect()->route('complete-profile', ['token' => $profileToken])
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        if (!$user->google_id) {
            $user->update([
                'google_id'     => $googleUser->id,
                'google_token'  => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . ($user->username ?? $this->maskEmail($user->email)) . '!');
    }

    // =========================================================
    // LOGOUT
    // =========================================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    // =========================================================
    // AJAX HELPERS
    // =========================================================

    public function checkUsername(Request $request)
    {
        $username = $request->query('username');
        $userId   = $request->query('user_id');

        if (!$username) {
            return response()->json(['available' => false, 'message' => 'Username tidak boleh kosong']);
        }
        if (strlen($username) < 6) {
            return response()->json(['available' => false, 'message' => 'Username minimal 6 karakter']);
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Username hanya boleh huruf, angka, dan underscore']);
        }

        $exists = Users::where('username', $username)
            ->when($userId, fn($q) => $q->where('id', '!=', $userId))
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Username sudah digunakan' : 'Username tersedia',
        ]);
    }

    public function checkEmail(Request $request)
    {
        $email  = $request->query('email');
        $userId = $request->query('user_id');

        if (!$email) {
            return response()->json(['available' => false, 'message' => 'Email tidak boleh kosong']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['available' => false, 'message' => 'Format email tidak valid']);
        }

        $exists = Users::where('email', $email)
            ->when($userId, fn($q) => $q->where('id', '!=', $userId))
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Email sudah terdaftar' : 'Email tersedia',
        ]);
    }

    public function getCities(Request $request)
    {
        try {
            if (!$request->input('province_code')) {
                return response()->json(['error' => 'Province code required'], 400);
            }
            return response()->json([
                'cities' => City::where('province_code', $request->input('province_code'))
                    ->orderBy('name')->get(['code', 'name']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cities'], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        try {
            if (!$request->input('city_code')) {
                return response()->json(['error' => 'City code required'], 400);
            }
            return response()->json([
                'districts' => District::where('city_code', $request->input('city_code'))
                    ->orderBy('name')->get(['code', 'name']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    public function getVillages(Request $request)
    {
        try {
            if (!$request->input('district_code')) {
                return response()->json(['error' => 'District code required'], 400);
            }
            return response()->json([
                'villages' => Village::where('district_code', $request->input('district_code'))
                    ->orderBy('name')->get(['code', 'name', 'meta']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load villages'], 500);
        }
    }

    public function getPostalCode(Request $request)
    {
        try {
            if (!$request->input('village_code')) {
                return response()->json(['error' => 'Village code required'], 400);
            }

            $village = Village::where('code', $request->input('village_code'))->first();

            if (!$village) {
                return response()->json(['error' => 'Village not found'], 404);
            }

            return response()->json([
                'postal_code' => is_array($village->meta) ? ($village->meta['postal_code'] ?? null) : null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load postal code'], 500);
        }
    }
}