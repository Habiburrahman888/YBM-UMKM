<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Users;
use App\Models\SettingAdmin;

class ProfileController extends Controller
{
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function getLogoPath($setting)
    {
        if ($setting && $setting->logo_expo) {
            $path = storage_path('app/public/' . $setting->logo_expo);
            if (file_exists($path)) {
                return $path;
            }
        }
        
        $defaultPath = public_path('images/default-logo.png');
        if (file_exists($defaultPath)) {
            return $defaultPath;
        }
        
        return null;
    }

    public function show()
    {
        $user = Auth::user();

        $breadcrumbs = [
            ['name' => 'Profil', 'url' => route('profile.show')]
        ];

        return view('profile.show', compact('user', 'breadcrumbs'));
    }

    public function edit()
    {
        $user = Auth::user();

        $breadcrumbs = [
            ['name' => 'Profil', 'url' => route('profile.show')],
            ['name' => 'Edit Profil', 'url' => route('profile.edit')]
        ];

        return view('profile.edit', compact('user', 'breadcrumbs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validationRules = [
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'foto_profil' => 'nullable|image|mimes:png,jpeg,jpg,gif|max:2048',
        ];

        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal 255 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
            'username.unique' => 'Username sudah digunakan.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto harus PNG, JPG, JPEG, atau GIF.',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), $validationRules, $messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'username' => $request->username,
            ];

            if ($request->hasFile('foto_profil')) {
                $file = $request->file('foto_profil');

                if ($file->isValid()) {
                    $this->deleteImageIfExists($user->foto_profil);

                    $filename = 'profile_' . $user->uuid . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('profile_photos', $filename, 'public');
                    $updateData['foto_profil'] = $path;
                }
            }

            $user->update($updateData);

            DB::commit();

            return redirect()->route('profile.show')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editPassword()
    {
        $breadcrumbs = [
            ['name' => 'Profil', 'url' => route('profile.show')],
            ['name' => 'Ubah Password', 'url' => route('profile.password.edit')]
        ];

        return view('profile.edit-password', compact('breadcrumbs'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->with('error', 'Password saat ini tidak sesuai.')
                ->withInput();
        }

        try {
            $token = Str::random(64);
            $cacheKey = 'password_verify_' . $user->id;

            Cache::forget($cacheKey);

            Cache::put($cacheKey, [
                'token' => $token,
                'user_id' => $user->id,
                'new_password' => Hash::make($request->password),
                'created_at' => now()->toDateTimeString(),
            ], now()->addMinutes(30));

            $verifyUrl = route('profile.password.verify', ['token' => $token]);

            $setting = DB::table('setting_admin')->first();
            $nama_expo = $setting ? $setting->nama_expo : 'YBM UMKM';
            $logo_expo = $this->getLogoPath($setting);

            Mail::send('emails.verify-password-change', [
                'user' => $user,
                'verifyUrl' => $verifyUrl,
                'expiresInMinutes' => 30,
                'nama_expo' => $nama_expo,
                'logo_expo' => $logo_expo,
            ], function ($message) use ($user, $nama_expo) {
                $message->to($user->email)
                    ->subject('Verifikasi Perubahan Password - ' . $nama_expo);
            });

            return redirect()->route('profile.show')
                ->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek email untuk menyelesaikan perubahan password.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function verifyPasswordChange($token)
    {
        $user = Auth::user();
        $cacheKey = 'password_verify_' . $user->id;

        $data = Cache::get($cacheKey);

        if (!$data || $data['token'] !== $token) {
            return redirect()->route('profile.show')
                ->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        try {
            DB::beginTransaction();

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => $data['new_password'],
                    'updated_at' => now()
                ]);

            Cache::forget($cacheKey);

            DB::commit();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Password berhasil diperbarui! Silakan login dengan password baru Anda.');
                
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('profile.show')
                ->with('error', 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage());
        }
    }

    public function editEmail()
    {
        $user = Auth::user();

        $breadcrumbs = [
            ['name' => 'Profil', 'url' => route('profile.show')],
            ['name' => 'Ubah Email', 'url' => route('profile.email.edit')]
        ];

        return view('profile.edit-email', compact('user', 'breadcrumbs'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi untuk konfirmasi.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->with('error', 'Password tidak sesuai.')
                ->withInput();
        }

        try {
            $setting = DB::table('setting_admin')->first();
            
            $nama_expo = $setting ? $setting->nama_expo : 'YBM UMKM';
            
            $logo_expo = $this->getLogoPath($setting);

            $newEmailToken = Str::random(64);
            $cacheKey = 'email_verify_' . $user->id;

            Cache::forget($cacheKey);

            Cache::put($cacheKey, [
                'token' => $newEmailToken,
                'user_id' => $user->id,
                'old_email' => $user->email,
                'new_email' => $request->email,
                'created_at' => now()->toDateTimeString(),
            ], now()->addMinutes(30));

            $verifyNewUrl = route('profile.email.verify', ['token' => $newEmailToken]);

            Mail::send('emails.verify-email-change', [
                'user' => $user,
                'verifyUrl' => $verifyNewUrl,
                'newEmail' => $request->email,
                'oldEmail' => $user->email,
                'expiresInMinutes' => 30,
                'nama_expo' => $nama_expo,
                'logo_expo' => $logo_expo,
            ], function ($message) use ($request, $nama_expo) {
                $message->to($request->email)
                    ->subject('Verifikasi Email Baru - ' . $nama_expo);
            });

            return redirect()->route('profile.show')
                ->with('success', 'Link verifikasi telah dikirim ke email baru Anda. Silakan cek email untuk menyelesaikan perubahan.');
        } catch (\Exception $e) {
            Cache::forget($cacheKey);

            return back()
                ->with('error', 'Terjadi kesalahan saat memproses permintaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function verifyEmailChange($token)
    {
        $user = Auth::user();
        $cacheKey = 'email_verify_' . $user->id;

        $data = Cache::get($cacheKey);

        if (!$data || $data['token'] !== $token) {
            return redirect()->route('profile.show')
                ->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        try {
            DB::beginTransaction();

            $user->update([
                'email' => $data['new_email'],
                'email_verified_at' => now(),
            ]);

            Cache::forget($cacheKey);

            DB::commit();

            return redirect()->route('profile.show')
                ->with('success', 'Email berhasil diperbarui dan terverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('profile.show')
                ->with('error', 'Terjadi kesalahan saat memperbarui email: ' . $e->getMessage());
        }
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $this->deleteImageIfExists($user->foto_profil);

            $user->update(['foto_profil' => null]);

            DB::commit();

            return back()->with('success', 'Foto profil berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage());
        }
    }

    public function toggleStatus()
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user->update([
                'is_active' => !$user->is_active
            ]);

            DB::commit();

            $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return back()->with('success', "Akun berhasil {$status}!");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}