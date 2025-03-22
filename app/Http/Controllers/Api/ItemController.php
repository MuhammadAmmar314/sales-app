<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query();
        
        if ($request->has('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sortBy')) {
            $query->orderBy($request->sortBy, 'asc');
        }

        $items = $query->orderBy('id','asc')->paginate(10);

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'stok' => 'required',
            'jenis_barang' => 'required'
        ]);

        $item = new Item();
        $item->nama_barang = $request->nama_barang;
        $item->stok = $request->stok;
        $item->jenis_barang = $request->jenis_barang;
        $item->save();

        return response()->json(['message' => 'Item berhasil ditambahkan!', 'data' => $item], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);

        $request->validate([
            'nama_barang' => 'required',
            'stok' => 'required',
            'jenis_barang' => 'required'
        ]);

        $item->nama_barang = $request->nama_barang;
        $item->stok = $request->stok;
        $item->jenis_barang = $request->jenis_barang;
        $item->save();

        return response()->json(['message' => 'Item berhasil diupdate!', 'data' => $item]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item berhasil dihapus!']);
    }

    public function selectItems()
    {
        return response()->json(['data' => Item::all()]);
    }
}
