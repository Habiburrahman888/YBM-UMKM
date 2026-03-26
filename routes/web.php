<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Umkm\SettingUmkmController;
use App\Http\Controllers\Umkm\ProdukController;
use App\Http\Controllers\Unit\UmkmController;
use App\Http\Controllers\Admin\ReportUnitDanUmkmController;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\Report\LaporanUmkmAllController;

Route::get('/', [GuestController::class, 'beranda'])->name('root');

Route::prefix('/')->name('guest.')->group(function () {
    Route::get('/', [GuestController::class, 'beranda'])->name('beranda');
    Route::get('/katalog', [GuestController::class, 'katalog'])->name('katalog');
    Route::get('/katalog/{uuid}', [GuestController::class, 'detailProduk'])->name('detail-produk');
    Route::get('/mitra', [GuestController::class, 'umkm'])->name('umkm');
    Route::get('/mitra/{uuid}', [GuestController::class, 'detailUmkm'])->name('detail-umkm');
    Route::get('/tentang', [GuestController::class, 'tentang'])->name('tentang');
    Route::get('/checkout/{uuid}', [GuestController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{uuid}', [GuestController::class, 'storeCheckout'])->name('store-checkout');

    Route::get('/cek-pesanan', [GuestController::class, 'cekPesanan'])->name('cek-pesanan');
    Route::post('/cek-pesanan', [GuestController::class, 'searchPesanan'])->name('search-pesanan');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify-otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset-sent', [AuthController::class, 'showResetSent'])->name('password.reset-sent');
Route::get('/password/reset/{uuid}/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset/{uuid}', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/password/resend-reset-link', [AuthController::class, 'resendResetLink'])->name('password.resend');

Route::get('/complete-profile/{token}', [AuthController::class, 'showCompleteProfile'])->name('complete-profile');
Route::post('/complete-profile/{token}', [AuthController::class, 'storeCompleteProfile'])->name('complete-profile.store');

Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
Route::get('/check-username', [AuthController::class, 'checkUsername'])->name('check-username');
Route::get('/get-cities', [AuthController::class, 'getCities'])->name('get-cities');
Route::get('/get-districts', [AuthController::class, 'getDistricts'])->name('get-districts');
Route::get('/get-villages', [AuthController::class, 'getVillages'])->name('get-villages');
Route::get('/get-postal-code', [AuthController::class, 'getPostalCode'])->name('get-postal-code');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete-photo', [ProfileController::class, 'deletePhoto'])->name('delete-photo');

        Route::get('/password/edit', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/password/verify/{token}', [ProfileController::class, 'verifyPasswordChange'])->name('password.verify');

        Route::get('/email/edit', [ProfileController::class, 'editEmail'])->name('email.edit');
        Route::put('/email', [ProfileController::class, 'updateEmail'])->name('email.update');
        Route::get('/email/verify/{token}', [ProfileController::class, 'verifyEmailChange'])->name('email.verify');
    });

    Route::middleware(['role:admin'])->group(function () {

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [UserController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{uuid}/verify-email', [UserController::class, 'verifyEmail'])->name('verify-email');
            Route::post('/{uuid}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        Route::prefix('unit')->name('unit.')->group(function () {
            Route::get('/', [UnitController::class, 'index'])->name('index');
            Route::get('/create', [UnitController::class, 'create'])->name('create');
            Route::post('/', [UnitController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [UnitController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [UnitController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [UnitController::class, 'destroy'])->name('destroy');
            Route::post('/{uuid}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/api/cities/{provinceCode}', [UnitController::class, 'getCities'])->name('api.cities');
            Route::get('/api/districts/{cityCode}', [UnitController::class, 'getDistricts'])->name('api.districts');
            Route::get('/api/villages/{districtCode}', [UnitController::class, 'getVillages'])->name('api.villages');
        });

        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('/', [KategoriController::class, 'index'])->name('index');
            Route::get('/create', [KategoriController::class, 'create'])->name('create');
            Route::post('/', [KategoriController::class, 'store'])->name('store');
            Route::get('/{uuid}/edit', [KategoriController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [KategoriController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [KategoriController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'show'])->name('show');
            Route::get('/edit', [SettingController::class, 'edit'])->name('edit');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::post('/test-mail', [SettingController::class, 'testMail'])->name('test-mail');
        });

        Route::patch('/umkm/{umkm}/approve', [UmkmController::class, 'approve'])->name('umkm.approve');
        Route::patch('/umkm/{umkm}/reject', [UmkmController::class, 'reject'])->name('umkm.reject');
        Route::patch('/umkm/{umkm}/toggle-status', [UmkmController::class, 'toggleStatus'])->name('umkm.toggleStatus');
        Route::patch('/umkm/{umkm}/change-status', [UmkmController::class, 'changeStatus'])->name('umkm.changeStatus');
        Route::post('/umkm/{umkm}/create-account', [UmkmController::class, 'createAccount'])->name('umkm.createAccount');
    });

    Route::middleware(['auth', 'role:admin,unit,umkm'])->prefix('umkm')->name('umkm.')->group(function () {

        Route::get('/ajax/cities', [UmkmController::class, 'getCities'])->name('ajax.cities');
        Route::get('/ajax/districts', [UmkmController::class, 'getDistricts'])->name('ajax.districts');
        Route::get('/ajax/villages', [UmkmController::class, 'getVillages'])->name('ajax.villages');

        Route::get('/get-cities', [UmkmController::class, 'getCities'])->name('getCities');
        Route::get('/get-districts', [UmkmController::class, 'getDistricts'])->name('getDistricts');
        Route::get('/get-villages', [UmkmController::class, 'getVillages'])->name('getVillages');

        Route::get('/', [UmkmController::class, 'index'])->name('index');
        Route::get('/create', [UmkmController::class, 'create'])->name('create');
        Route::post('/', [UmkmController::class, 'store'])->name('store');

        Route::get('/report', [LaporanUmkmAllController::class, 'downloadAll'])->name('report.all');
        Route::get('/report-preview', [ReportUnitDanUmkmController::class, 'preview'])->name('report.preview');
        Route::get('/report/unit/{unitId}/{slug?}', [LaporanUmkmAllController::class, 'downloadByUnit'])->name('report.unit');
        Route::get('/{umkm}/report', [LaporanUmkmAllController::class, 'downloadSingle'])->name('report.single');

        Route::get('/{umkm}', [UmkmController::class, 'show'])->name('show');
        Route::get('/{umkm}/edit', [UmkmController::class, 'edit'])->name('edit');
        Route::put('/{umkm}', [UmkmController::class, 'update'])->name('update');
        Route::delete('/{umkm}', [UmkmController::class, 'destroy'])->name('destroy');

        Route::post('/{umkm}/verify', [UmkmController::class, 'verify'])->name('verify');
        Route::post('/{umkm}/reject', [UmkmController::class, 'reject'])->name('reject');
        Route::post('/{umkm}/create-account', [UmkmController::class, 'createAccount'])->name('create-account');

        Route::post('/{umkm}/modal', [UmkmController::class, 'storeModal'])->name('modal.store');
        Route::put('/{umkm}/modal/{modal}', [UmkmController::class, 'updateModal'])->name('modal.update');
        Route::delete('/{umkm}/modal/{modal}', [UmkmController::class, 'destroyModal'])->name('modal.destroy');
    });

    Route::middleware(['role:admin,unit'])->group(function () {
        Route::post('/umkm/{umkm:uuid}/verify', [UmkmController::class, 'verify'])->name('umkm.verify');
        Route::post('/umkm/{umkm:uuid}/reject', [UmkmController::class, 'reject'])->name('umkm.reject');
    });

    Route::middleware(['role:unit'])->prefix('unit')->name('unit.')->group(function () {
        Route::get('/laporan-transaksi', [\App\Http\Controllers\Unit\LaporanTransaksiController::class, 'index'])->name('laporan-transaksi.index');
        Route::get('/laporan-transaksi/export-pdf', [\App\Http\Controllers\Unit\LaporanTransaksiController::class, 'exportPdf'])->name('laporan-transaksi.export-pdf');
    });

    Route::middleware(['role:umkm'])->group(function () {

        Route::prefix('produk')->name('umkm.produk.')->group(function () {
            Route::get('/', [ProdukController::class, 'index'])->name('index');
            Route::get('/create', [ProdukController::class, 'create'])->name('create');
            Route::post('/', [ProdukController::class, 'store'])->name('store');
            Route::get('/{uuid}', [ProdukController::class, 'show'])->name('show');
            Route::get('/{uuid}/edit', [ProdukController::class, 'edit'])->name('edit');
            Route::put('/{uuid}', [ProdukController::class, 'update'])->name('update');
            Route::delete('/{uuid}', [ProdukController::class, 'destroy'])->name('destroy');
            Route::delete('/{uuid}/foto', [ProdukController::class, 'destroyFoto'])->name('foto.destroy');
        });

        Route::prefix('pesanan')->name('umkm.pesanan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Umkm\PesananController::class, 'index'])->name('index');
            Route::get('/export-pdf', [\App\Http\Controllers\Umkm\PesananController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/{uuid}', [\App\Http\Controllers\Umkm\PesananController::class, 'show'])->name('show');
            Route::put('/{uuid}/status', [\App\Http\Controllers\Umkm\PesananController::class, 'updateStatus'])->name('update-status');
        });

        Route::prefix('settings-umkm')->name('umkm.settings.')->group(function () {
            Route::get('/', [SettingUmkmController::class, 'show'])->name('show');
            Route::get('/edit', [SettingUmkmController::class, 'edit'])->name('edit');
            Route::put('/', [SettingUmkmController::class, 'update'])->name('update');
            Route::delete('/logo', [SettingUmkmController::class, 'deleteLogo'])->name('logo.delete');
            Route::delete('/qris', [SettingUmkmController::class, 'deleteQris'])->name('qris.delete');
        });
    });
});
