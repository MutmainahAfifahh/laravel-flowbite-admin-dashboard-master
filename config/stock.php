<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Batas Stok Minimum Global
    |--------------------------------------------------------------------------
    |
    | Nilai ini digunakan sebagai batas minimum qty yang boleh diinput oleh
    | Staff saat mencatat transaksi stok (Masuk/Keluar). Jika Staff memasukkan
    | qty di bawah nilai ini, transaksi tidak dapat disimpan.
    |
    | Admin dapat mengubah nilai ini melalui menu "Pengaturan Stok Minimum".
    | Perubahan akan langsung berlaku untuk semua Staff secara real-time.
    |
    */

    'minimum_stock' => 10,

];
