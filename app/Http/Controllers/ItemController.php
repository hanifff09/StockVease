<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Item::with('category', 'kondisi')->get()
        ]);
    }
    public function index(Request $request)
{
    $per = $request->per ?? 10;
    $page = $request->page ? $request->page - 1 : 0;

    DB::statement('set @no=0+' . $page * $per);
    $data = Item::with('category', 'kondisi')
        ->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhereHas('category', function (Builder $query) use ($search) {
                    $query->where('nama', 'like', "%$search%");
                })
                ->orWhereHas('kondisi', function (Builder $query) use ($search) {
                    $query->where('kondisi', 'like', "%$search%");
                });
        })
        ->latest()
        ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

    return response()->json($data);
}

    
    public function destroy($uuid)
    {
        $item = Item::findByUuid($uuid);
        if ($item) {
            $item->delete();
            return response()->json([
                'message' => "Data successfully deleted",
                'code' => 200
            ]);
        } else {
            return response([
                'message' => "Failed delete data $uuid / data doesn't exists"
            ]);
        }
    }

    public function store (ItemRequest $request)
    {
        $req = $request->validated();

        if($request->hasFile('image')){
            $req['image'] = $request->file('image')->store('Item', 'public');
        }

        $add = Item::create($req);

        return response()->json([
            'success' => true,
            'data' => $add
        ]);
    }

    public function edit ($uuid){
        $item = Item::findByUuid($uuid);
        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }


    public function update(Request $request, $uuid)
    {
        $item = Item::findByUuid($uuid);
    
        if ($item) {
            // Handle image jika ada file yang diunggah
            if ($request->hasFile('image')) {
                // Hapus image lama jika ada
                if ($item->image) {
                    Storage::delete(str_replace('public/', '', $item->image));
                }
    
                // Simpan file image baru
                $imagePath = $request->file('image')->store('public/items');
                $item->image = str_replace('public/', '', $imagePath);
            }
    
            // Perbarui data item lainnya
            $item->update($request->except('image'));
    
            return response()->json([
                'status' => 'true',
                'message' => 'Data berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Gagal mengubah'
            ]);
        }
    }
    

    
    public function show($uuid)
    {
        $item = Item::findByUuid($uuid);
        return response()->json([
            'status' => true,
            'pasien' => $item,
        ]);
    }
}

