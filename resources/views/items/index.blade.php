@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Manajemen Item</h1>

    <button id="openModalButton" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Barang</button>

    <div class="flex justify-between mb-4">
        <input type="text" id="search" placeholder="Cari Barang..." class="border p-2 rounded">
        <select id="sort" class="border p-2 rounded">
            <option value="nama_barang">Sort by Nama Barang</option>
            <option value="stok">Sort by Stok</option>
        </select>
    </div>

    <!-- <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div> -->
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nama Barang</th>
                <th class="border p-2">Jenis Barang</th>
                <th class="border p-2">Stok</th>
                <th class="border p-2">Action</th>
            </tr>
        </thead>
        <tbody id="items-table"></tbody>
    </table>
    <div id="pagination" class="flex space-x-2 mt-4"></div>
</div>

<div id="itemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden">
    <div class="relative p-4 w-full max-w-md mx-auto my-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold mb-2">Tambah Item</h2>
            <form id="itemForm">
                @csrf
                <input type="hidden" name="item_id" id="item_id">
                <input type="text" name="nama_barang" id="nama_barang" placeholder="Nama Barang" class="border p-2 rounded w-full mb-2">
                <input type="number" name="stok" id="stok" placeholder="Stok" class="border p-2 rounded w-full mb-2">
                <input type="text" name="jenis_barang" id="jenis_barang" placeholder="Jenis Barang" class="border p-2 rounded w-full mb-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
                <button type="button" id="closeModalButton" class="bg-gray-300 px-4 py-2 rounded ml-2">Tutup</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        loadItems();

        $("#search, #sort").on("input change", function() {
            loadItems();
        });

        $("#openModalButton").click(function() {
            $('#itemModal h2').html('Tambah Item');
            $('#itemModal button[type=submit]').html('Tambah');
            $("#item_id").val("");
            $("#nama_barang").val("");
            $("#stok").val("");
            $("#jenis_barang").val("");
            $("#itemModal").removeClass("hidden");
        });

        $("#closeModalButton").click(function() {
            $("#itemModal").addClass("hidden");
        });

        $("#itemForm").on("submit", function(e) {
            e.preventDefault();
            let tipe = $('#itemModal button[type=submit]').html();
            if(tipe == 'Tambah'){
                $.post("/api/items", $(this).serialize(), function() {
                    alert("Barang berhasil ditambahkan!");
                    $("#itemModal").addClass("hidden");
                    loadItems();
                });
            } else {
                let id = $('#item_id').val();
                $.ajax({
                    url: `/api/items/${id}`,
                    type: "PUT",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $("#itemModal").addClass("hidden");
                        loadItems();
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
                url: `/api/items/${id}`,
                type: 'DELETE',
                success: function(response){
                    alert(response.message);
                    loadItems();
                },
                error: function(xhr, status, error){
                    alert(xhr.responseJSON.message);
                }
            })
        });

        $(document).on('click', '.btn-edit', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            $.get(`/api/items/${id}`, function(response){
                $('#itemModal h2').html('Edit Barang');
                $('#itemModal button[type=submit]').html('Update');
                $('#item_id').val(response.id);
                $("#nama_barang").val(response.nama_barang);
                $("#stok").val(response.stok);
                $("#jenis_barang").val(response.jenis_barang);
            })
            $("#itemModal").removeClass("hidden");
        })

        function loadItems(page = 1) {
            let search = $("#search").val();
            let sortBy = $("#sort").val();
            let url = `/api/items?page=${page}&`;

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
                    $("#items-table")
                        .html("<tr class='text-center'><td colspan='4'>Tidak ada data</td></tr>");
                } else {
                    $("#items-table")
                        .html(response.data.map(
                            t => `
                                <tr>
                                    <td class="border">${t.nama_barang}</td>
                                    <td class="border">${t.jenis_barang}</td>
                                    <td class="border">${t.stok}</td>
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
                loadItems(page);
            });
        }
    });
</script>
@endsection
