# Aplikasi Penjualan

Aplikasi Penjualan adalah aplikasi web yang dirancang untuk membantu Anda mengelola data barang, transaksi, dan laporan penjualan dengan mudah.

## Fitur

* **Manajemen Barang:** Tambah, edit, dan hapus data barang.
* **Manajemen Transaksi:** Catat transaksi penjualan dengan cepat dan akurat.
* **Laporan Penjualan:** Dapatkan laporan penjualan untuk analisis dan pengambilan keputusan.

## Teknologi yang Digunakan

* Laravel (PHP Framework)
* Tailwind CSS (CSS Framework)
* JavaScript (dengan jQuery)

## Instalasi

1.  Kloning repositori:

    ```bash
    git clone [https://github.com/MuhammadAmmar314/sales-app.git](https://github.com/MuhammadAmmar314/sales-app.git)
    ```

2.  Masuk ke direktori proyek:

    ```bash
    cd sales-app
    ```

3.  Instal dependensi Composer:

    ```bash
    composer install
    ```

4.  Salin file `.env.example` ke `.env` dan konfigurasi database:

    ```bash
    cp .env.example .env
    ```

5.  Generate key aplikasi:

    ```bash
    php artisan key:generate
    ```

6.  Jalankan migrasi database:

    ```bash
    php artisan migrate
    ```

7.  Jalankan seeder (jika ada):

    ```bash
    php artisan db:seed
    ```

8.  Jalankan server pengembangan:

    ```bash
    php artisan serve
    ```

9.  Buka aplikasi di browser: `http://localhost:8000`