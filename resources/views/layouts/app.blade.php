<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
    <title>@yield('title', 'Aplikasi Penjualan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <nav class="bg-blue-500 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-lg font-bold">Aplikasi Penjualan</a>

            <div class="hidden md:flex space-x-4">
                <a href="{{ route('items.index') }}" class="px-3 {{ request()->routeIs('items.index') ? 'font-bold underline' : '' }}">Data Barang</a>
                <a href="{{ route('transactions.index') }}" class="px-3 {{ request()->routeIs('transactions.index') ? 'font-bold underline' : '' }}">Data Transaksi</a>
                <a href="{{ route('reports.index') }}" class="px-3 {{ request()->routeIs('reports.index') ? 'font-bold underline' : '' }}">Laporan Transaksi</a>
            </div>

            <div class="md:hidden">
                <button id="collapseButton" class="focus:outline-none">
                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                        <path id="collapseIcon" fill-rule="evenodd" clip-rule="evenodd" d="M4 6H20V8H4V6ZM4 12H20V14H4V12ZM4 18H20V20H4V18Z"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <div id="collapseMenu" class="hidden md:hidden w-full mt-2 bg-blue-600 rounded-md shadow-md">
        <div class="flex flex-col space-y-2 p-4">
            <a href="{{ route('items.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 {{ request()->routeIs('items.index') ? 'font-bold underline bg-blue-700' : '' }}">Data Barang</a>
            <a href="{{ route('transactions.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 {{ request()->routeIs('transactions.index') ? 'font-bold underline bg-blue-700' : '' }}">Data Transaksi</a>
            <a href="{{ route('reports.index') }}" class="px-3 py-2 rounded-md hover:bg-blue-700 {{ request()->routeIs('reports.index') ? 'font-bold underline bg-blue-700' : '' }}">Laporan Transaksi</a>
        </div>
    </div>

    <div class="container mx-auto mt-6 p-4 bg-white shadow-md rounded">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const collapseButton = document.getElementById('collapseButton');
            const collapseMenu = document.getElementById('collapseMenu');
            const collapseIcon = document.getElementById('collapseIcon');

            collapseButton.addEventListener('click', function() {
                collapseMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
