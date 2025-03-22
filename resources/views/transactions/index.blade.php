@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

    <button id="openModalButton" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Transaksi</button>

    <div class="flex justify-between mb-4">
        <input type="text" id="search" placeholder="Cari Barang..." class="border p-2 rounded">
        <select id="sort" class="border p-2 rounded">
            <option value="nama_barang">Sort by Nama Barang</option>
            <option value="tanggal_transaksi">Sort by Tanggal</option>
        </select>
    </div>

    <!-- <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div> -->
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nama Barang</th>
                <th class="border p-2">Terjual</th>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody id="transactions-table"></tbody>
    </table>
    <div id="pagination" class="flex space-x-2 mt-4"></div>
</div>

<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden">
    <div class="relative p-4 w-full max-w-md mx-auto my-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-2">Tambah Transaksi</h2>
            <form id="TransactionForm">
                @csrf
                <input type="hidden" id="trans_id" name="trans_id">
                <select id="item_id" name="item_id" class="border p-2 rounded w-full mb-2"></select>
                <input type="number" name="jumlah_terjual" id="jumlah_terjual" placeholder="Jumlah Terjual" class="border p-2 rounded w-full mb-2">
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="border p-2 rounded w-full mb-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
                <button type="button" id="closeModalButton" class="bg-gray-300 px-4 py-2 rounded ml-2">Tutup</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadItems();
        loadTransactions();

        $("#search, #sort").on("input change", function() {
            loadTransactions();
        });

        $("#openModalButton").click(function() {
            $('#transactionModal h2').html('Tambah Transaksi');
            $('#transactionModal button[type=submit]').html('Tambah');
            $('#trans_id').val("");
            $("#item_id").val(1);
            $("#jumlah_terjual").val("");
            $("#tanggal_transaksi").val();
            $("#transactionModal").removeClass("hidden");
        });

        $("#closeModalButton").click(function() {
            $("#transactionModal").addClass("hidden");
        });

        $("#TransactionForm").on("submit", function(e) {
            e.preventDefault();
            let tipe = $('#transactionModal button[type=submit]').html();
            if(tipe == "Tambah"){
                $.post("/api/transactions", $(this).serialize(), function() {
                    alert("Transaksi berhasil ditambahkan!");
                    $("#transactionModal").addClass("hidden");
                    loadTransactions();
                });
            } else {
                let id = $('#trans_id').val();
                $.ajax({
                    url: `/api/transactions/${id}`,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $("#transactionModal").addClass("hidden");
                        loadTransactions();
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseJSON.message);
                    }
                })
            }
        });

        $(document).on('click', '.btn-delete', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            
            $.ajax({
                url: `/api/transactions/${id}`,
                type: 'DELETE',
                success: function(response){
                    alert(response.message);
                    loadTransactions();
                },
                error: function(xhr, status, error){
                    alert(xhr.responseJSON.message);
                }
            })
        });

        $(document).on('click', '.btn-edit', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $.get(`/api/transactions/${id}`, function(response){
                $('#transactionModal h2').html('Edit Transaksi');
                $('#transactionModal button[type=submit]').html('Update');
                $('#trans_id').val(id);
                $("#item_id").val(response.item_id);
                $("#jumlah_terjual").val(response.jumlah_terjual);
                $("#tanggal_transaksi").val(response.tanggal_transaksi);
            })
            $("#transactionModal").removeClass("hidden");
        })

        function loadItems() {
            $.get("/api/select-items", function(response) {
                $("#item_id").html(response.data.map(item => `<option value="${item.id}">${item.nama_barang}</option>`));
            });
        }

        function loadTransactions(page = 1) {
            let search = $("#search").val();
            let sortBy = $("#sort").val();
            let url = `/api/transactions?page=${page}&`;

            if (search || sortBy) {
                let params = [];
                if (search) {
                    params.push(`search=${encodeURIComponent(search)}`);
                }
                if (sortBy) {
                    params.push(`sortBy=${encodeURIComponent(sortBy)}`);
                }
                url += params.join("&");
            }
            //$("#loading-indicator").show();
            $.get(url, function(response) {
                //$("#loading-indicator").hide();
                if(response.data.length == 0) {
                    $("#transactions-table")
                        .html("<tr class='text-center'><td colspan='4'>Tidak ada data</td></tr>");
                } else {
                    $("#transactions-table")
                        .html(response.data.map(
                            t => `
                                <tr>
                                    <td class="border">${t.item.nama_barang}</td>
                                    <td class="border">${t.jumlah_terjual}</td>
                                    <td class="border">${t.tanggal_transaksi}</td>
                                    <td class="border"><button class="bg-yellow-500 text-white px-2 py-1 mx-1 rounded btn-edit" data-id="${t.id}">Edit</button><button class="bg-red-500 text-white px-2 py-1 rounded btn-delete" data-id="${t.id}">Delete</button></td>
                                </tr>
                            `
                        ));
                    createPagination(response.links);
                }
            });
        }

        function createPagination(links){
            let paginationHtml = '';
            let firstPage = links[0]?.url ? links[0].url.split('page=')[1] : 1;
            let lastPage = links[links.length - 1]?.url ? links[links.length - 1].url.split('page=')[1] : 1;
            let currentPage = links.find(link => link.active)?.url?.split('page=')[1] || firstPage;
            
            links.forEach(link => {
                let pageNumber = link.url ? link.url.split('page=')[1] : "";
                paginationHtml += `
                    <button 
                        data-page="${pageNumber}" 
                        class="px-4 py-2 rounded-lg border border-gray-300 transition 
                            ${link.active || !link.url ? 'cursor-not-allowed' : ""}
                            ${(link.active) ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100 text-gray-700'}"
                        ${(link.active || !link.url) ? 'disabled' : ''}
                    >
                        ${link.label}
                    </button>
                `;
            });

            $("#pagination").html(paginationHtml);

            // Tambahkan event listener untuk tombol paginasi
            $("#pagination button").click(function() {
                let page = $(this).data('page');
                loadTransactions(page);
            });
        }
    });
</script>
@endsection
