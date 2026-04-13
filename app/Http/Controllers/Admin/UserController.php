<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Users::query();

        // Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'LIKE', '%' . $request->q . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        // Filter by verification
        if ($request->filled('verified')) {
            if ($request->verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $breadcrumbs = [
            ['name' => 'Kelola Pengguna', 'url' => route('admin.user.index')]
        ];

        return view('admin.user.index', compact('users', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['name' => 'Kelola Pengguna', 'url' => route('admin.user.index')],
            ['name' => 'Tambah Pengguna', 'url' => route('admin.user.create')]
        ];

        return view('admin.user.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|max:255|unique:users,username',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|in:admin,unit,umkm',
            'foto_profil'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data = [
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'is_active'     => $request->boolean('is_active', true),
        ];

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('users/profiles', 'public');
        }

        $user = Users::create($data);

        ActivityLogger::logCreate($user, "User baru '{$user->username}' berhasil dibuat", [
            'username' => $user->username,
            'email'    => $user->email,
            'role'     => $user->role,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($uuid)
    {
        $user = Users::where('uuid', $uuid)->first();

        if (!$user) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Data user tidak ditemukan!');
        }

        $breadcrumbs = [
            ['name' => 'Kelola Pengguna', 'url' => route('admin.user.index')],
            ['name' => 'Edit Pengguna', 'url' => route('admin.user.edit', $uuid)]
        ];

        return view('admin.user.edit', compact('user', 'breadcrumbs'));
    }

    public function update(Request $request, $uuid)
    {
        $user = Users::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'username'      => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => 'nullable|string|min:8|confirmed',
            'role'          => 'required|in:admin,unit,umkm',
            'foto_profil'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'     => 'nullable|boolean',
        ]);

        $data = [
            'username'      => $request->username,
            'email'         => $request->email,
            'role'          => $request->role,
            'is_active'     => $request->boolean('is_active', true),
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload foto profil baru
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('users/profiles', 'public');
        }

        // Hapus foto jika diminta
        if ($request->boolean('remove_foto') && $user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
            $data['foto_profil'] = null;
        }

        $old = ActivityLogger::safeAttributes($user);
        $user->update($data);
        $user->refresh();

        ActivityLogger::logUpdate($user, "User '{$user->username}' diupdate", $old, ActivityLogger::safeAttributes($user));

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($uuid)
    {
        $user = Users::where('uuid', $uuid)->firstOrFail();

        // Proteksi: tidak bisa hapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Hapus foto profil
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        ActivityLogger::logDelete(
            "User '{$user->username}' dihapus",
            get_class($user), $user->id, $user->username,
            ['email' => $user->email, 'role' => $user->role]
        );

        $user->delete();

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Verify user email manually
     */
    public function verifyEmail($uuid)
    {
        $user = Users::where('uuid', $uuid)->firstOrFail();

        if ($user->email_verified_at) {
            return redirect()
                ->route('admin.user.index')
                ->with('info', 'Email sudah terverifikasi sebelumnya');
        }

        $user->update([
            'email_verified_at' => now(),
            'verification_token' => null,
            'verification_token_expires_at' => null,
        ]);

        ActivityLogger::log('verify', "Email user '{$user->username}' diverifikasi manual", $user);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Email user berhasil diverifikasi');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($uuid)
    {
        $user = Users::where('uuid', $uuid)->firstOrFail();

        // Proteksi: tidak bisa nonaktifkan akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.user.index')
                ->with('error', 'Tidak dapat mengubah status akun sendiri');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log('toggle_status', "User '{$user->username}' {$status}", $user, [
            'is_active' => $user->is_active,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', "User berhasil {$status}");
    }
}