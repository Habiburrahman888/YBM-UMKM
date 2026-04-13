<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingAdmin;
use App\Models\Sosmed;
use App\Models\RecaptchaConfig;
use App\Models\GoogleConfig;
use App\Models\MailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function show()
    {
        $setting = SettingAdmin::first();
        $recaptchaConfig = RecaptchaConfig::first();
        $googleConfig = GoogleConfig::first();
        $mailConfig = MailConfig::first();
        $sosmed = Sosmed::first();

        $breadcrumbs = [
            ['name' => 'Settings', 'url' => route('admin.settings.show')]
        ];

        return view('admin.settings.show', compact(
            'setting',
            'recaptchaConfig',
            'googleConfig',
            'mailConfig',
            'sosmed',
            'breadcrumbs'
        ));
    }

    public function edit()
    {
        $setting = SettingAdmin::first();
        $recaptchaConfig = RecaptchaConfig::first();
        $googleConfig = GoogleConfig::first();
        $mailConfig = MailConfig::first();
        $sosmed = Sosmed::first();

        $breadcrumbs = [
            ['name' => 'Settings', 'url' => route('admin.settings.show')],
            ['name' => 'Edit Settings', 'url' => route('admin.settings.edit')]
        ];

        return view('admin.settings.edit', compact(
            'setting',
            'recaptchaConfig',
            'googleConfig',
            'mailConfig',
            'sosmed',
            'breadcrumbs'
        ));
    }

    public function update(Request $request)
    {
        $setting = SettingAdmin::first();
        
        $validator = Validator::make($request->all(), [
            'nama_expo' => 'required|string|max:255',
            'logo_expo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'tentang' => 'required|string',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'RECAPTCHA_SITE_KEY' => 'nullable|string|max:255',
            'RECAPTCHA_SECRET_KEY' => 'nullable|string|max:255',
            'GOOGLE_CLIENT_ID' => 'nullable|string|max:255',
            'GOOGLE_CLIENT_SECRET' => 'nullable|string',
            'GOOGLE_REDIRECT_URI' => 'nullable|url|max:255',
            'GOOGLE_CONNECT_URL' => 'nullable|url|max:255',
            'MAIL_MAILER' => 'nullable|string|max:50',
            'MAIL_HOST' => 'nullable|string|max:255',
            'MAIL_PORT' => 'nullable|string|max:10',
            'MAIL_USERNAME' => 'nullable|string|max:255',
            'MAIL_PASSWORD' => 'nullable|string|max:255',
            'MAIL_ENCRYPTION' => 'nullable|string|in:tls,ssl,null',
            'MAIL_FROM_ADDRESS' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update atau Create Sosmed
            $sosmedData = [
                'facebook' => $request->filled('facebook') ? $request->facebook : '',
                'instagram' => $request->filled('instagram') ? $request->instagram : '',
                'youtube' => $request->filled('youtube') ? $request->youtube : '',
            ];

            $sosmed = Sosmed::first();
            if ($sosmed) {
                $sosmed->update($sosmedData);
            } else {
                Sosmed::create($sosmedData);
            }

            // Update Setting Expo
            $settingData = [
                'nama_expo' => $request->nama_expo,
                'tentang' => $request->tentang,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'phone' => $request->phone,
            ];

            if ($request->hasFile('logo_expo')) {
                if ($setting && $setting->logo_expo) {
                    Storage::disk('public')->delete($setting->logo_expo);
                }
                $settingData['logo_expo'] = $request->file('logo_expo')
                    ->store('settings/logos', 'public');
            }

            if ($setting) {
                $setting->update($settingData);
            } else {
                if (!isset($settingData['logo_expo'])) {
                    $settingData['logo_expo'] = 'settings/logos/default.png';
                }
                SettingAdmin::create($settingData);
            }

            // Update reCAPTCHA Config
            $recaptchaConfig = RecaptchaConfig::first();
            if ($recaptchaConfig) {
                $recaptchaConfig->update([
                    'RECAPTCHA_SITE_KEY' => $request->RECAPTCHA_SITE_KEY,
                    'RECAPTCHA_SECRET_KEY' => $request->RECAPTCHA_SECRET_KEY,
                ]);
            } else {
                RecaptchaConfig::create([
                    'RECAPTCHA_SITE_KEY' => $request->RECAPTCHA_SITE_KEY,
                    'RECAPTCHA_SECRET_KEY' => $request->RECAPTCHA_SECRET_KEY,
                ]);
            }

            // Update Google Config
            $googleConfig = GoogleConfig::first();
            if ($googleConfig) {
                $googleConfig->update([
                    'GOOGLE_CLIENT_ID' => $request->GOOGLE_CLIENT_ID,
                    'GOOGLE_CLIENT_SECRET' => $request->GOOGLE_CLIENT_SECRET,
                    'GOOGLE_REDIRECT_URI' => $request->GOOGLE_REDIRECT_URI,
                    'GOOGLE_CONNECT_URL' => $request->GOOGLE_CONNECT_URL,
                ]);
            } else {
                GoogleConfig::create([
                    'GOOGLE_CLIENT_ID' => $request->GOOGLE_CLIENT_ID,
                    'GOOGLE_CLIENT_SECRET' => $request->GOOGLE_CLIENT_SECRET,
                    'GOOGLE_REDIRECT_URI' => $request->GOOGLE_REDIRECT_URI,
                    'GOOGLE_CONNECT_URL' => $request->GOOGLE_CONNECT_URL,
                ]);
            }

            // Update Mail Config
            $mailConfigData = [
                'MAIL_MAILER' => $request->MAIL_MAILER ?? 'smtp',
                'MAIL_HOST' => $request->MAIL_HOST,
                'MAIL_PORT' => $request->MAIL_PORT ?? '587',
                'MAIL_USERNAME' => $request->MAIL_USERNAME,
                'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION,
                'MAIL_FROM_ADDRESS' => $request->MAIL_FROM_ADDRESS,
            ];

            if ($request->filled('MAIL_PASSWORD')) {
                $mailConfigData['MAIL_PASSWORD'] = $request->MAIL_PASSWORD;
            }

            $mailConfig = MailConfig::first();
            if ($mailConfig) {
                $mailConfig->update($mailConfigData);
            } else {
                if (!$request->filled('MAIL_PASSWORD')) {
                    $mailConfigData['MAIL_PASSWORD'] = null;
                }
                MailConfig::create($mailConfigData);
            }

            DB::commit();

            return redirect()->route('admin.settings.show')
                ->with('success', 'Setting berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Test mail configuration
     */
    public function testMail(Request $request)
    {
        try {
            $mailConfig = MailConfig::first();
            
            if (!$mailConfig || !$mailConfig->MAIL_HOST || !$mailConfig->MAIL_FROM_ADDRESS) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi mail belum lengkap'
                ], 400);
            }

            config([
                'mail.mailers.smtp.host' => $mailConfig->MAIL_HOST,
                'mail.mailers.smtp.port' => $mailConfig->MAIL_PORT,
                'mail.mailers.smtp.username' => $mailConfig->MAIL_USERNAME,
                'mail.mailers.smtp.password' => $mailConfig->MAIL_PASSWORD,
                'mail.mailers.smtp.encryption' => $mailConfig->MAIL_ENCRYPTION,
                'mail.from.address' => $mailConfig->MAIL_FROM_ADDRESS,
            ]);

            \Illuminate\Support\Facades\Mail::raw('Test email dari sistem.', function ($message) use ($request, $mailConfig) {
                $message->to($request->test_email ?? $mailConfig->MAIL_FROM_ADDRESS)
                    ->subject('Test Email Configuration');
            });

            return response()->json([
                'success' => true,
                'message' => 'Email test berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
}