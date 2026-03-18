<?php

namespace App\Mail;

use App\Models\Umkm;
use App\Models\SettingAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UmkmRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $umkm;
    public $username;
    public $password;
    public $nama_expo;
    public $logo_expo;
    public $logo_umkm;

    public function __construct(Umkm $umkm, $username = null, $password = null)
    {
        $this->umkm     = $umkm;
        $this->username = $username;
        $this->password = $password;

        $settings        = SettingAdmin::first();
        $this->nama_expo = $settings?->nama_expo ?? config('app.name', 'UMKM Expo');

        // Simpan PATH logo (bukan base64) agar blade bisa pakai $message->embed()
        $this->logo_expo = $this->getLogoPath($settings);

        $this->logo_umkm = $umkm->logo_umkm
            ? storage_path('app/public/' . $umkm->logo_umkm)
            : null;
    }

    public function build()
    {
        $this->umkm->relationLoaded('kategori') ?: $this->umkm->load('kategori');

        return $this->subject('Registrasi UMKM Berhasil - ' . $this->nama_expo)
            ->view('emails.umkm-registration');
    }

    private function getLogoPath($setting): ?string
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

}
