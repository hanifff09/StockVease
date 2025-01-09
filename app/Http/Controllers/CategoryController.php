<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Category::all()
        ]);
    }
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Category::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('full_name', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    public function destroy($uuid)
    {
        $category = Category::findByUuid($uuid);
        if ($category) {
            $category->delete();
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

    public function store (CategoryRequest $request)
    {
        $req = $request->validated();

        if($request->hasFile('image')){
            $req['image'] = $request->file('image')->store('Category', 'public');
        }

        $add = Category::create($req);

        return response()->json([
            'success' => true,
            'data' => $add
        ]);
    }

    public function edit ($uuid){
        $category = Category::findByUuid($uuid);
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }


    public function update(Request $request, $uuid)
    {
        $category = Category::findByUuid($uuid);
        if ($category) {
            $category->update($request->all());
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
        $category = Category::findByUuid($uuid);
        return response()->json([
            'status' => true,
            'pasien' => $category,
        ]);
    }
}
