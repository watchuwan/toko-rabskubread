<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/test-preview', function () {
    return view('filament.modals.diskon-preview', [
        'produk_id' => 1, // ganti dengan ID produk yang ada
        'tipe' => 'persentase',
        'nilai' => 10,
        'label' => 'Test Diskon',
    ]);
});

