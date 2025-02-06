<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataBaruController extends Controller
{
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::where('status', 0)->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 0) {
                $peminjaman->text_status = 'Menunggu Dikonfirmasi';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }
    public function update($uuid)
    {
        $peminjaman = Peminjaman::findByUuid($uuid);

        $peminjaman->update(['status' => 1]);

        return response()->json($peminjaman);
    }

    public function index1(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::where('status', 1)->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 1) {
                $peminjaman->text_status = 'Pending';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }

    public function update1($uuid)
    {
        $peminjaman = Peminjaman::findByUuid($uuid);

        $peminjaman->update(['status' => 2]);

        return response()->json($peminjaman);
    }

    public function index2(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::where('status', 2)->where('tanggal_pengembalian', '>=', Carbon::now()->format('Y-m-d') )->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 2) {
                $peminjaman->text_status = 'Dalam Peminjaman';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }
    public function update2($uuid)
    {
        $peminjaman = Peminjaman::findByUuid($uuid);

        $peminjaman->update(['status' => 4]);

        return response()->json($peminjaman);
    }

    public function index3(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::whereIn('status', [2, 3])->where('tanggal_pengembalian', '<=', Carbon::now()->format('Y-m-d'))->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 3) {
                $peminjaman->text_status = 'Terlambat';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }

    public function index4(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::where('status', 4)->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 4) {
                $peminjaman->text_status = 'Selesai';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }
}   