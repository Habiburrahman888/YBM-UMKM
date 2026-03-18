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
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Users;
use App\Models\Unit;
use App\Models\GoogleConfig;
use App\Models\MailConfig;
use App\Models\RecaptchaConfig;
use App\Models\SettingAdmin;
use Carbon\Carbon;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class AuthController extends Controller
{
    const OTP_EXPIRY_MINUTES = 15;
    const RESEND_COOLDOWN_SECONDS = 900;
    const PASSWORD_RESET_EXPIRY_MINUTES = 15;
    const MAX_LOGO_UNIT = 1;

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

    private function loadMailConfig()
    {
        try {
            $mailConfig = MailConfig::first();

            if (!$mailConfig) {
                return false;
            }

            config([
                'mail.mailers.smtp.host'       => $mailConfig->MAIL_HOST,
                'mail.mailers.smtp.port'       => $mailConfig->MAIL_PORT ?? 587,
                'mail.mailers.smtp.encryption' => $mailConfig->MAIL_ENCRYPTION ?? 'tls',
                'mail.mailers.smtp.username'   => $mailConfig->MAIL_USERNAME,
                'mail.mailers.smtp.password'   => $mailConfig->MAIL_PASSWORD,
                'mail.from.address'            => $mailConfig->MAIL_FROM_ADDRESS ?? $mailConfig->MAIL_USERNAME,
                'mail.from.name'               => $mailConfig->MAIL_FROM_NAME ?? 'YBM UMKM',
            ]);

            \Illuminate\Support\Facades\Mail::purge('smtp');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function maskEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        $parts    = explode('@', $email);
        $username = $parts[0];
        $domain   = $parts[1];

        if (strlen($username) > 1) {
            $maskedUsername = $username[0] . str_repeat('*', strlen($username) - 1);
        } else {
            $maskedUsername = $username;
        }

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

            $score = $result['score'] ?? 0;
            if ($score < 0.5) {
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    // =========================================================
    // LOGIN
    // =========================================================

    public function showLogin()
    {
        $recaptchaSiteKey = null;

        try {
            $recaptchaConfig  = RecaptchaConfig::first();
            $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;
        } catch (\Exception $e) {
            // silent
        }

        if (!$recaptchaSiteKey) {
            $recaptchaSiteKey = config('services.recaptcha.site_key');
        }

        return view('auth.login', compact('recaptchaSiteKey'));
    }

    public function login(Request $request)
    {
        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

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
                ->withInput($request->only('login', 'remember'))
                ->with('recaptchaSiteKey', $recaptchaSiteKey);
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'login')) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->with('recaptchaSiteKey', $recaptchaSiteKey)
                ->withErrors(['login' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $loginType   = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        $user = Users::where($loginType, $request->login)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->with('recaptchaSiteKey', $recaptchaSiteKey)
                ->withErrors(['login' => 'Akun tidak terdaftar dalam sistem.']);
        }

        if (!$user->email_verified_at) {
            return redirect()->route('verify-otp')
                ->with('email', $user->email)
                ->with('recaptchaSiteKey', $recaptchaSiteKey)
                ->with('warning', 'Email Anda belum diverifikasi. Silakan cek email Anda untuk kode OTP.');
        }

        if (!$user->is_active) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->with('recaptchaSiteKey', $recaptchaSiteKey)
                ->withErrors(['login' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . ($user->username ?? $this->maskEmail($user->email)) . '!');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withInput($request->only('login', 'remember'))
                ->with('recaptchaSiteKey', $recaptchaSiteKey)
                ->withErrors(['password' => 'Password yang Anda masukkan salah.']);
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . ($user->username ?? $this->maskEmail($user->email)) . '!');
    }

    // =========================================================
    // REGISTER
    // =========================================================

    public function showRegister()
    {
        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.register', compact('recaptchaSiteKey'));
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'register')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        try {
            $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            $cacheKey = 'otp_registration_' . $normalizedEmail;
            $otpData  = [
                'email'      => $normalizedEmail,
                'otp'        => $otp,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ];

            Cache::put($cacheKey, $otpData, self::OTP_EXPIRY_MINUTES * 60);

            $cooldownKey = 'otp_cooldown_' . $normalizedEmail;
            Cache::put($cooldownKey, Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS), self::RESEND_COOLDOWN_SECONDS);

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
                    $message->to($normalizedEmail)
                        ->subject('Kode OTP Verifikasi - ' . $expo['nama_expo']);
                });

                return redirect()->route('verify-otp', ['email' => $normalizedEmail])
                    ->with('email', $normalizedEmail)
                    ->with('success', 'Kode OTP telah dikirim ke email Anda.');
            } catch (\Exception $mailException) {
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
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
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
            return redirect()->route('register')
                ->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $normalizedEmail = strtolower(trim($email));

        $cacheKey = 'otp_registration_' . $normalizedEmail;
        $otpData  = Cache::get($cacheKey);

        if (!$otpData) {
            return redirect()->route('register')
                ->with('error', 'OTP telah kedaluwarsa. Silakan daftar ulang.');
        }

        try {
            $expiresAt = $otpData['expires_at'];
            if (is_string($expiresAt)) {
                $expiresAt = Carbon::parse($expiresAt);
            } elseif (!($expiresAt instanceof Carbon)) {
                $expiresAt = Carbon::parse($expiresAt);
            }

            if (Carbon::now()->isAfter($expiresAt)) {
                Cache::forget($cacheKey);
                return redirect()->route('register')
                    ->with('error', 'OTP telah kedaluwarsa. Silakan daftar ulang.');
            }
        } catch (\Exception $e) {
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan. Silakan daftar ulang.');
        }

        $cooldownKey = 'otp_cooldown_' . $normalizedEmail;
        $canResendAt = Cache::get($cooldownKey);

        $request->session()->put('otp_email', $normalizedEmail);
        $request->session()->put('email', $normalizedEmail);

        $maskedEmail = $this->maskEmail($email);

        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? config('services.recaptcha.site_key');

        return view('auth.verify-otp', [
            'email'            => $email,
            'maskedEmail'      => $maskedEmail,
            'expiresAt'        => $expiresAt->toIso8601String(),
            'canResendAt'      => $canResendAt,
            'recaptchaSiteKey' => $recaptchaSiteKey,
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
            return redirect()->back()
                ->withErrors($validator)
                ->with('email', $request->email);
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
            } else {
                if (!$user->email_verified_at) {
                    $user->update(['email_verified_at' => now()]);
                }
            }

            Cache::forget($cacheKey);
            Cache::forget('otp_cooldown_' . $request->email);

            $profileToken = (string) Str::uuid();
            $cacheData    = [
                'email'      => $request->email,
                'user_id'    => $user->id,
                'token'      => $profileToken,
                'created_at' => now()->toDateTimeString(),
            ];

            Cache::put('complete_profile_' . $request->email, $cacheData, 3600);
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
                $remainingSeconds = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remainingSeconds} detik sebelum mengirim ulang OTP.",
                ], 400);
            }

            $otp       = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES);

            $cacheKey = 'otp_registration_' . $email;
            Cache::put($cacheKey, [
                'email'      => $email,
                'otp'        => $otp,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::OTP_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(self::RESEND_COOLDOWN_SECONDS), self::RESEND_COOLDOWN_SECONDS);

            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                $expo = $this->getExpoSettings();

                Mail::send('emails.otp-verification', array_merge($expo, [
                    'otp'              => $otp,
                    'email'            => $email,
                    'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
                ]), function ($message) use ($email, $expo) {
                    $message->to($email)
                        ->subject('Kode OTP Baru - ' . $expo['nama_expo']);
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

    public function showForgotPasswordForm()
    {
        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.forgot-password', compact('recaptchaSiteKey'));
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'forgot_password')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        try {
            $email = $request->email;
            $user  = Users::where('email', $email)->first();

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'Email tidak terdaftar.')
                    ->withInput();
            }

            $cooldownKey    = 'password_reset_cooldown_' . $email;
            $cooldownExpiry = Cache::get($cooldownKey);

            if ($cooldownExpiry && Carbon::now()->isBefore($cooldownExpiry)) {
                $remainingSeconds = Carbon::now()->diffInSeconds($cooldownExpiry);
                return redirect()->back()
                    ->with('error', "Mohon tunggu {$remainingSeconds} detik sebelum meminta link reset password lagi")
                    ->withInput();
            }

            $cacheKey     = 'password_reset_' . $user->uuid;
            $existingData = Cache::get($cacheKey);

            if ($existingData) {
                $expiresAt = Carbon::parse($existingData['expires_at']);
                $token     = Carbon::now()->lt($expiresAt) ? $existingData['token'] : Str::random(64);
            } else {
                $token = Str::random(64);
            }

            $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

            Cache::put($cacheKey, [
                'email'      => $email,
                'token'      => $token,
                'user_id'    => $user->id,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                $resetUrl = route('password.reset', [
                    'uuid'  => $user->uuid,
                    'token' => $token,
                ]);

                $expo = $this->getExpoSettings();

                Mail::send('emails.password-reset', array_merge($expo, [
                    'token'            => $token,
                    'email'            => $email,
                    'nama'             => $user->username ?? $this->maskEmail($user->email),
                    'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                    'resetUrl'         => $resetUrl,
                ]), function ($message) use ($email, $expo) {
                    $message->to($email)
                        ->subject('Reset Password - ' . $expo['nama_expo']);
                });
            }

            $request->session()->put('email', $email);
            $request->session()->flash('email', $email);

            return redirect()->route('password.reset-sent')
                ->with('email', $email)
                ->with('success', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
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
            return redirect()->route('password.request')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        $cacheKey  = 'password_reset_' . $user->uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa. Silakan minta ulang.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);

        $cooldownKey = 'password_reset_cooldown_' . $email;
        $canResendAt = Cache::get($cooldownKey);

        $canResendIn = 0;
        if ($canResendAt) {
            $canResendIn = max(0, Carbon::now()->diffInSeconds($canResendAt, false));
        }

        $request->session()->put('email', $email);
        $request->session()->flash('email', $email);

        $maskedEmail      = $this->maskEmail($email);
        $countdownSeconds = max(0, Carbon::now()->diffInSeconds($expiresAt));
        if ($countdownSeconds > 480) {
            $countdownSeconds = 480;
        }

        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.reset-password-sent', [
            'email'            => $email,
            'maskedEmail'      => $maskedEmail,
            'expiresAt'        => $expiresAt,
            'canResendAt'      => $canResendAt,
            'canResendIn'      => $canResendIn,
            'countdownSeconds' => $countdownSeconds,
            'user'             => $user,
            'recaptchaSiteKey' => $recaptchaSiteKey,
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
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);
        if (Carbon::now()->gt($expiresAt)) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        $email       = $resetData['email'];
        $maskedEmail = $this->maskEmail($email);

        $recaptchaConfig  = RecaptchaConfig::first();
        $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

        return view('auth.reset-password', compact(
            'email',
            'maskedEmail',
            'token',
            'uuid',
            'recaptchaSiteKey'
        ));
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
                $remainingSeconds = Carbon::now()->diffInSeconds($canResendAt);
                return response()->json([
                    'success' => false,
                    'message' => "Tunggu {$remainingSeconds} detik sebelum mengirim ulang link reset password.",
                ], 400);
            }

            $user = Users::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
            }

            $cacheKey  = 'password_reset_' . $user->uuid;
            $token     = Str::random(64);
            $expiresAt = Carbon::now()->addMinutes(self::PASSWORD_RESET_EXPIRY_MINUTES);

            Cache::put($cacheKey, [
                'email'      => $email,
                'token'      => $token,
                'user_id'    => $user->id,
                'expires_at' => $expiresAt->toDateTimeString(),
                'created_at' => now()->toDateTimeString(),
            ], self::PASSWORD_RESET_EXPIRY_MINUTES * 60);

            Cache::put($cooldownKey, Carbon::now()->addSeconds(60), 60);

            $mailConfigLoaded = $this->loadMailConfig();

            if ($mailConfigLoaded) {
                $resetUrl = route('password.reset', [
                    'uuid'  => $user->uuid,
                    'token' => $token,
                ]);

                $expo = $this->getExpoSettings();

                try {
                    Mail::send('emails.password-reset', array_merge($expo, [
                        'token'            => $token,
                        'email'            => $email,
                        'nama'             => $user->username ?? $this->maskEmail($user->email),
                        'expiresInMinutes' => self::PASSWORD_RESET_EXPIRY_MINUTES,
                        'resetUrl'         => $resetUrl,
                    ]), function ($message) use ($email, $expo) {
                        $message->to($email)
                            ->subject('Reset Password - ' . $expo['nama_expo']);
                    });
                } catch (\Exception $mailEx) {
                    return response()->json(['success' => false, 'message' => 'Gagal mengirim email. Silakan coba lagi.'], 500);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Konfigurasi email belum lengkap.'], 500);
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Link reset password telah dikirim ulang ke email Anda.',
                'canResendIn' => 60,
            ]);
        } catch (\Exception $e) {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$this->verifyRecaptcha($request->recaptcha_token, 'reset_password')) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['password' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.']);
        }

        $cacheKey  = 'password_reset_' . $uuid;
        $resetData = Cache::get($cacheKey);

        if (!$resetData) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        if (!hash_equals($resetData['token'], $request->token)) {
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid.');
        }

        if ($resetData['email'] !== $request->email) {
            return redirect()->route('password.request')
                ->with('error', 'Email tidak sesuai.');
        }

        $expiresAt = Carbon::parse($resetData['expires_at']);
        if (Carbon::now()->gt($expiresAt)) {
            return redirect()->route('password.request')
                ->with('error', 'Link reset password telah kadaluarsa.');
        }

        DB::beginTransaction();

        try {
            $user = Users::find($resetData['user_id']);

            if (!$user) {
                throw new \Exception('Pengguna tidak ditemukan');
            }

            $user->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

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
                    $message->to($user->email)
                        ->subject('Password Berhasil Diubah - ' . $expo['nama_expo']);
                });
            } catch (\Exception $mailEx) {
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
            return redirect()->route('register')
                ->with('error', 'Token tidak valid. Silakan daftar ulang.');
        }

        $tokenMapKey = 'token_map_' . $token;
        $email       = Cache::get($tokenMapKey);

        if (!$email) {
            $inactiveUsers = Users::where('is_active', false)
                ->whereNotNull('email_verified_at')
                ->get();

            foreach ($inactiveUsers as $user) {
                $possibleKeys = [
                    'complete_profile_' . $user->email,
                    'google_complete_' . $user->email,
                ];

                foreach ($possibleKeys as $cacheKey) {
                    $cacheData = Cache::get($cacheKey);

                    if ($cacheData && isset($cacheData['token']) && $cacheData['token'] === $token) {
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

        $cacheKey  = 'complete_profile_' . $email;
        $cacheData = Cache::get($cacheKey);

        if (!$cacheData) {
            $cacheKey  = 'google_complete_' . $email;
            $cacheData = Cache::get($cacheKey);
        }

        if (!$cacheData || !isset($cacheData['user_id'])) {
            return redirect()->route('register')
                ->with('error', 'Sesi telah berakhir. Silakan daftar ulang.');
        }

        $user = Users::find($cacheData['user_id']);

        if (!$user) {
            return redirect()->route('register')
                ->with('error', 'Pengguna tidak ditemukan. Silakan daftar ulang.');
        }

        try {
            $provinces    = Province::orderBy('name', 'asc')->get();
            $isGoogleUser = !empty($user->google_id);
            $maskedEmail  = $this->maskEmail($user->email);

            $recaptchaConfig  = RecaptchaConfig::first();
            $recaptchaSiteKey = $recaptchaConfig?->RECAPTCHA_SITE_KEY ?? null;

            return view('auth.complete-profile', compact(
                'user',
                'maskedEmail',
                'isGoogleUser',
                'provinces',
                'token',
                'recaptchaSiteKey'
            ));
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
                'required',
                'string',
                'min:6',
                'max:50',
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

            if ($errors->has('username') || $errors->has('password') || $errors->has('password_confirmation')) {
                $errorStep = 1;
            } elseif ($errors->has('admin_nama') || $errors->has('admin_telepon') || $errors->has('admin_email') || $errors->has('admin_foto')) {
                $errorStep = 2;
            } elseif (
                $errors->has('nama_unit') || $errors->has('deskripsi') || $errors->has('logo') ||
                $errors->has('alamat') ||
                $errors->has('provinsi_kode') || $errors->has('kota_kode') ||
                $errors->has('kecamatan_kode') || $errors->has('kelurahan_kode') ||
                $errors->has('kode_pos') || $errors->has('telepon') || $errors->has('email_unit')
            ) {
                $errorStep = 3;
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_step', $errorStep);
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

            $adminFotoPath = null;
            if ($request->hasFile('admin_foto')) {
                $adminFotoPath = $request->file('admin_foto')->store('admin-fotos', 'public');
            }

            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('unit-logos', 'public');
            }

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

            $kodePos = $request->kode_pos;
            if (!$kodePos && $kelurahan->meta && is_array($kelurahan->meta)) {
                $kodePos = $kelurahan->meta['postal_code'] ?? null;
            }

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
                    $message->to($user->email)
                        ->subject('Registrasi Berhasil - ' . $expo['nama_expo']);
                });
            } catch (\Exception $mailEx) {
                // silent fail
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
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

            if ($action === 'register') {
                return $this->handleGoogleRegister($googleUser);
            }

            return $this->handleGoogleLogin($googleUser, $request);
        } catch (\Exception $e) {
            $redirectRoute = session('oauth_action', 'login') === 'register' ? 'register' : 'login';
            session()->forget('oauth_action');

            return redirect()->route($redirectRoute)
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    private function handleGoogleRegister($googleUser)
    {
        $existingGoogleUser = Users::where('google_id', $googleUser->id)->first();
        if ($existingGoogleUser) {
            return redirect()->route('register')
                ->with('error', 'Akun Google sudah terdaftar. Silakan login.');
        }

        $existingEmail = Users::where('email', $googleUser->email)->first();
        if ($existingEmail) {
            return redirect()->route('register')
                ->with('error', 'Email sudah terdaftar dengan metode lain. Silakan login.');
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

            $cacheData = [
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
        $user = Users::where('google_id', $googleUser->id)->first();

        if (!$user) {
            $user = Users::where('email', $googleUser->email)->first();
        }

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Akun tidak ditemukan. Silakan daftar terlebih dahulu.');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('login')
                ->with('error', 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.');
        }

        if (!$user->is_active) {
            $profileToken = (string) Str::uuid();

            $cacheData = [
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
            $provinceCode = $request->input('province_code');

            if (!$provinceCode) {
                return response()->json(['error' => 'Province code required'], 400);
            }

            $cities = City::where('province_code', $provinceCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name']);

            return response()->json(['cities' => $cities]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load cities'], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        try {
            $cityCode = $request->input('city_code');

            if (!$cityCode) {
                return response()->json(['error' => 'City code required'], 400);
            }

            $districts = District::where('city_code', $cityCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name']);

            return response()->json(['districts' => $districts]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    public function getVillages(Request $request)
    {
        try {
            $districtCode = $request->input('district_code');

            if (!$districtCode) {
                return response()->json(['error' => 'District code required'], 400);
            }

            $villages = Village::where('district_code', $districtCode)
                ->orderBy('name', 'asc')
                ->get(['code', 'name', 'meta']);

            return response()->json(['villages' => $villages]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load villages'], 500);
        }
    }

    public function getPostalCode(Request $request)
    {
        try {
            $villageCode = $request->input('village_code');

            if (!$villageCode) {
                return response()->json(['error' => 'Village code required'], 400);
            }

            $village = Village::where('code', $villageCode)->first();

            if (!$village) {
                return response()->json(['error' => 'Village not found'], 404);
            }

            $postalCode = null;
            if ($village->meta && is_array($village->meta)) {
                $postalCode = $village->meta['postal_code'] ?? null;
            }

            return response()->json(['postal_code' => $postalCode]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load postal code'], 500);
        }
    }

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function cleanupRegistrationCache(string $email, string $token)
    {
        $keysToForget = [
            'complete_profile_' . $email,
            'google_complete_' . $email,
            'otp_registration_' . $email,
            'otp_cooldown_' . $email,
            'token_map_' . $token,
        ];

        foreach ($keysToForget as $key) {
            Cache::forget($key);
        }
    }

    private function generateUniqueUsername(string $email, ?int $excludeUserId = null): string
    {
        $emailParts   = explode('@', $email);
        $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', $emailParts[0]);
        $baseUsername = strtolower($baseUsername);

        $username = $baseUsername;
        $counter  = 1;

        while (Users::where('username', $username)
            ->when($excludeUserId, fn($q) => $q->where('id', '!=', $excludeUserId))
            ->exists()
        ) {
            $username = $baseUsername . $counter;
            $counter++;
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
                $number++;
                $kodeUnit = $prefix . $year . str_pad($number, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            if ($attempts >= 10) {
                throw new \Exception('Failed to generate unique kode unit after 10 attempts');
            }

            return $kodeUnit;
        });
    }
}