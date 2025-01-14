<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function add(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'alasan_pinjam' => 'required|string',
            'item' => 'required|string',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
        ]);

        $peminjaman = Peminjaman::create($validated);
    
        return response()->json([
            'status' => true,
            'message' => 'Data peminjaman berhasil disimpan.',
            'data' => $peminjaman,
        ]);
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        return response()->json([
            'data' => $peminjaman,
        ], 200);
    }

    public function get()
    {
        return response()->json(['data' => Peminjaman::all()]);
    }

    public function update($id, Request $request)
    {
        $peminjaman = Peminjaman::findOrFail($id);
    
        $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'alasan_pinjam' => 'required|string',
            'item' => 'required|string',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
        ]);
    
        $peminjaman->update($request->all());
    
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diubah'
        ]);
    }
    
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $peminjaman->delete();
        
        return response()->json([
            'message' => "Data telah dihapus",
            'code' => 200
        ]);
    }
}