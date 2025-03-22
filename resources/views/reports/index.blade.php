@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Perbandingan Jenis Barang</h1>

        <div class="flex items-center gap-4 mb-4">
            <label>Rentang Waktu:</label>
            <input type="date" id="start_date" class="border p-2 rounded">
            <input type="date" id="end_date" class="border p-2 rounded">

            <label>Urutkan:</label>
            <select id="orderBy" class="border p-2 rounded">
                <option value="desc">Terbesar</option>
                <option value="asc">Terkecil</option>
            </select>

            <button id="compareBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Bandingkan</button>
        </div>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Jenis Barang</th>
                    <th class="border p-2">Total Terjual</th>
                </tr>
            </thead>
            <tbody id="compareTable"></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            loadComparison();

            $("#compareBtn").click(function() {
                loadComparison();
            });

            function loadComparison(){
                let startDate = $("#start_date").val();
                let endDate = $("#end_date").val();
                let orderBy = $("#orderBy").val();
                let url = `/api/compare?orderBy=${orderBy}`;

                if (startDate && endDate) {
                    url += `&start_date=${startDate}&end_date=${endDate}`;
                }
                
                $.get(url, function(response){
                    let rows = '';
                    response.forEach((transaction) => {
                        rows += `
                            <tr>
                                <td class="border p-2">${transaction.jenis_barang}</td>
                                <td class="border p-2">${transaction.total_terjual}</td>
                            </tr>`;
                    });
                    $("#compareTable").html(rows);
                })
            }
        })
    </script>
@endsection