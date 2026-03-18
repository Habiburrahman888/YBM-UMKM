<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test email dari YBM UMKM - Konfigurasi Gmail berhasil! Waktu: ' . now(), function ($m) {
        $m->to('habiburrahmanelsyirazy88@gmail.com')
          ->subject('✅ Test Email YBM UMKM - Berhasil!');
    });
    echo "✅ EMAIL BERHASIL DIKIRIM!\n";
    echo "Silakan cek inbox Gmail Anda.\n";
} catch (\Exception $e) {
    echo "❌ GAGAL: " . $e->getMessage() . "\n";
}
