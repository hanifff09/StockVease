<?php

namespace App\Http\Controllers;

use App\Http\Requests\KondisiRequest;
use App\Models\Kondisi;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class KondisiController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Kondisi::all()
        ]);
    }
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Kondisi::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('full_name', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function destroy($uuid)
    {
        $kondisi = Kondisi::findByUuid($uuid);
        if ($kondisi) {
            $kondisi->delete();
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

    public function store (KondisiRequest $request)
    {
        $req = $request->validated();

        $req['kondisi'] = $request->kondisi . '%';

        $add = Kondisi::create($req);

        return response()->json([
            'success' => true,
            'data' => $add
        ]);
    }

    public function edit ($uuid){
        $kondisi = Kondisi::findByUuid($uuid);
        return response()->json([
            'success' => true,
            'data' => $kondisi
        ]);
    }


    public function update(Request $request, $uuid)
    {
        $kondisi = Kondisi::findByUuid($uuid);
        if ($kondisi) {
            $kondisi->update($request->all());
            return response()->json([
                'status' => 'true',
                'message' => 'data berhasil diubah'
            ]);
        } else {
            return response([
                'message' => 'gagal mengubah'
            ]);
        }
    }

    
    public function show($uuid)
    {
        $kondisi = Kondisi::findByUuid($uuid);
        return response()->json([
            'status' => true,
            'pasien' => $kondisi,
        ]);
    }
}
