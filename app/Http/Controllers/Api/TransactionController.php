<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with('item')
            ->join('items', 'transactions.item_id', '=', 'items.id');

        if ($request->has('search')) {
            $query->where('items.nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sortBy')) {
            $query->orderBy($request->sortBy, 'asc');
        }

        $query->select('transactions.*', 'items.nama_barang', 'items.stok', 'items.jenis_barang');

        $transactions = $query->orderBy('id','asc')->paginate(10);

        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah_terjual' => 'required|integer',
            'tanggal_transaksi' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::find($request->item_id);
            if ($item->stok < $request->jumlah_terjual) {
                return response()->json(['message' => 'Stok tidak mencukupi!'], 400);
            }
            
            $transaction = Transaction::create([
                'item_id' => $request->item_id,
                'jumlah_terjual' => $request->jumlah_terjual,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);
    
            $item->stok = $item->stok - $request->jumlah_terjual;
            $item->save();
            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil ditambahkan!', 'data' => $transaction], 201);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaksi gagal!'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::find($id);

        return response()->json($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah_terjual' => 'required|integer',
            'tanggal_transaksi' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
    
            $item = Item::findOrFail($transaction->item_id);
            $selisih = $request->jumlah_terjual - $transaction->jumlah_terjual;
            if ($selisih > 0) {
                if($item->stok > $selisih){
                    $item->stok = $item->stok - $selisih;
                } else {
                    return response()->json(['message' => 'Stok tidak mencukupi!'], 400);
                }
            } else {
                $item->stok = $item->stok + abs($selisih);
            }
            $item->save();
    
            $transaction->item_id = $request->item_id;
            $transaction->jumlah_terjual = $request->jumlah_terjual;
            $transaction->tanggal_transaksi = $request->tanggal_transaksi;
            $transaction->save();
            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil diupdate!']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Update transaksi gagal!'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $item = Item::findOrFail($transaction->item_id);
        $item->stok = $item->stok + $transaction->jumlah_terjual;
        $item->save();

        $transaction->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus!']);
    }

    public function compare(Request $request)
    {
        $data = Transaction::join('items', 'items.id', 'transactions.item_id')
            ->select('items.jenis_barang')
            ->selectRaw('SUM(transactions.jumlah_terjual) AS total_terjual')
            ->groupBy('items.jenis_barang');

        if($request->has('orderBy')){
            $data->orderBy('total_terjual', $request->orderBy);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $data->whereBetween('transactions.tanggal_transaksi', [$request->start_date, $request->end_date]);
        }

        return response()->json($data->get());
    }
}
