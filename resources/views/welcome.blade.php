@extends('layouts.app')

@section('content')
<div class="container mx-auto text-center py-10">
    <h1 class="text-4xl font-bold text-blue-600 mb-4">Selamat Datang di Aplikasi Penjualan!</h1>

    <p class="text-lg text-gray-700 mb-8">
        Aplikasi ini dirancang untuk memudahkan pengelolaan data barang, transaksi, dan laporan penjualan Anda.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Data Barang</h2>
            <p class="text-gray-600">
                Kelola informasi barang Anda dengan mudah. Tambah, edit, atau hapus barang sesuai kebutuhan.
            </p>
            <a href="{{ route('items.index') }}" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-full inline-block">Lihat Data Barang</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Data Transaksi</h2>
            <p class="text-gray-600">
                Catat setiap transaksi penjualan dengan cepat dan akurat. Pantau riwayat transaksi Anda secara efisien.
            </p>
            <a href="{{ route('transactions.index') }}" class="mt-4 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full inline-block">Lihat Data Transaksi</a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-4">Laporan Penjualan</h2>
            <p class="text-gray-600">
                Dapatkan wawasan berharga dari laporan penjualan. Analisis data untuk pengambilan keputusan yang lebih baik.
            </p>
            <a href="{{ route('reports.index') }}" class="mt-4 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-full inline-block">Lihat Laporan</a>
        </div>
    </div>
</div>
@endsection