<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

    public function update5($uuid)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::findByUuid($uuid);

            // Cek apakah peminjaman sudah ditolak sebelumnya
            if ($peminjaman->status === 5) {
                return response()->json([
                    'status' => false,
                    'message' => 'Peminjaman sudah ditolak sebelumnya'
                ], 400);
            }

            // Temukan item terkait dan kembalikan stok
            $item = Item::where('nama', $peminjaman->item)->first();
            if ($item) {
                $item->stok = $item->stok + 1;
                $item->save();
            }

            // Update status peminjaman menjadi ditolak
            $peminjaman->update(['status' => 5]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Peminjaman berhasil ditolak dan stok telah dikembalikan',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error rejecting peminjaman:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal menolak peminjaman: ' . $e->getMessage()
            ], 500);
        }
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
        $data = Peminjaman::where('status', 2)->where('tanggal_pengembalian', '>=', Carbon::now()->format('Y-m-d'))->when($request->search, function (Builder $query, string $search) {
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
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::findByUuid($uuid);

            // Cek apakah peminjaman sudah selesai sebelumnya
            if ($peminjaman->status === 4) {
                return response()->json([
                    'status' => false,
                    'message' => 'Peminjaman sudah selesai sebelumnya'
                ], 400);
            }

            // Temukan item terkait dan kembalikan stok
            $item = Item::where('nama', $peminjaman->item)->first();
            if ($item) {
                $item->stok = $item->stok + 1;
                $item->save();
            }

            // Update status peminjaman menjadi selesai
            $peminjaman->update(['status' => 4]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Peminjaman selesai dan stok telah dikembalikan',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error completing peminjaman:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyelesaikan peminjaman: ' . $e->getMessage()
            ], 500);
        }
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

    public function update3($uuid)
    {
        try {
            DB::beginTransaction();

            $peminjaman = Peminjaman::findByUuid($uuid);

            // Cek apakah peminjaman sudah selesai sebelumnya
            if ($peminjaman->status === 4) {
                return response()->json([
                    'status' => false,
                    'message' => 'Peminjaman sudah selesai sebelumnya'
                ], 400);
            }

            // Temukan item terkait dan kembalikan stok
            $item = Item::where('nama', $peminjaman->item)->first();
            if ($item) {
                $item->stok = $item->stok + 1;
                $item->save();
            }

            // Update status peminjaman menjadi selesai
            $peminjaman->update(['status' => 4]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Peminjaman selesai dan stok telah dikembalikan',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error completing peminjaman:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyelesaikan peminjaman: ' . $e->getMessage()
            ], 500);
        }
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
        })
        ->whereYear('created_at', $request->tahun)
        ->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 4) {
                $peminjaman->text_status = 'Selesai';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }

    public function index5(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Peminjaman::where('status', 5)->when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%")
                ->orWhere('item', 'like', "%$search%")
                ->orWhere('alasan_pinjam', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 5) {
                $peminjaman->text_status = 'Ditolak';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }

    public function sendLateReturnEmail($uuid)
    {
        try {
            // Logging untuk debugging
            Log::info('Sending late email for UUID: ' . $uuid);

            $peminjaman = Peminjaman::where('uuid', $uuid)->first();

            if (!$peminjaman) {
                Log::error('Peminjaman not found for UUID: ' . $uuid);
                return response()->json([
                    'status' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ], 404);
            }

            // Log data peminjaman untuk verifikasi
            Log::info('Peminjaman data:', [
                'nama' => $peminjaman->nama,
                'email' => $peminjaman->email,
                'item' => $peminjaman->item
            ]);

            $apiKey = env('SENDINBLUE_API_KEY');
            if (!$apiKey) {
                Log::error('Sendinblue API key not configured');
                return response()->json([
                    'status' => false,
                    'message' => 'Email service configuration missing'
                ], 500);
            }

            // Pastikan email valid
            if (!filter_var($peminjaman->email, FILTER_VALIDATE_EMAIL)) {
                Log::error('Invalid email address: ' . $peminjaman->email);
                return response()->json([
                    'status' => false,
                    'message' => 'Email tidak valid'
                ], 400);
            }

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('SENDINBLUE_SENDER_NAME', 'System'),
                    'email' => env('SENDINBLUE_SENDER_EMAIL', 'noreply@yourapp.com')
                ],
                'to' => [
                    ['email' => $peminjaman->email, 'name' => $peminjaman->nama]
                ],
                'subject' => 'Peringatan Pengembalian Barang',
                'htmlContent' => view('emails.late-return', [
                    'name' => $peminjaman->nama,
                    'item' => $peminjaman->item,
                    'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman,
                    'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian
                ])->render()
            ]);

            // Log full response for debugging
            Log::info('Sendinblue API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengirim email: ' . $response->body()
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Email peringatan berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            // Extensive error logging
            Log::error('Complete Error in sendLateReturnEmail:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Sistem error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendRejectEmail($uuid)
    {
        try {
            Log::info('Sending rejection email for UUID: ' . $uuid);

            $peminjaman = Peminjaman::where('uuid', $uuid)->first();

            if (!$peminjaman) {
                Log::error('Peminjaman not found for UUID: ' . $uuid);
                return response()->json([
                    'status' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ], 404);
            }

            Log::info('Peminjaman data:', [
                'nama' => $peminjaman->nama,
                'email' => $peminjaman->email,
                'item' => $peminjaman->item
            ]);

            $apiKey = env('SENDINBLUE_API_KEY');
            if (!$apiKey) {
                Log::error('Sendinblue API key not configured');
                return response()->json([
                    'status' => false,
                    'message' => 'Email service configuration missing'
                ], 500);
            }

            if (!filter_var($peminjaman->email, FILTER_VALIDATE_EMAIL)) {
                Log::error('Invalid email address: ' . $peminjaman->email);
                return response()->json([
                    'status' => false,
                    'message' => 'Email tidak valid'
                ], 400);
            }

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('SENDINBLUE_SENDER_NAME', 'System'),
                    'email' => env('SENDINBLUE_SENDER_EMAIL', 'noreply@yourapp.com')
                ],
                'to' => [
                    ['email' => $peminjaman->email, 'name' => $peminjaman->nama]
                ],
                'subject' => 'Pemberitahuan Penolakan Peminjaman Barang',
                'htmlContent' => view('emails.reject-loan', [
                    'name' => $peminjaman->nama,
                    'item' => $peminjaman->item,
                    'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman,
                    'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian,
                    'alasan_pinjam' => $peminjaman->alasan_pinjam
                ])->render()
            ]);

            Log::info('Sendinblue API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengirim email: ' . $response->body()
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Email penolakan berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Complete Error in sendRejectEmail:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Sistem error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function sendConfirmEmail($uuid)
    {
        try {
            Log::info('Sending confirmation email for UUID: ' . $uuid);

            $peminjaman = Peminjaman::where('uuid', $uuid)->first();

            if (!$peminjaman) {
                Log::error('Peminjaman not found for UUID: ' . $uuid);
                return response()->json([
                    'status' => false,
                    'message' => 'Data peminjaman tidak ditemukan'
                ], 404);
            }

            Log::info('Peminjaman data:', [
                'nama' => $peminjaman->nama,
                'email' => $peminjaman->email,
                'item' => $peminjaman->item
            ]);

            $apiKey = env('SENDINBLUE_API_KEY');
            if (!$apiKey) {
                Log::error('Sendinblue API key not configured');
                return response()->json([
                    'status' => false,
                    'message' => 'Email service configuration missing'
                ], 500);
            }

            if (!filter_var($peminjaman->email, FILTER_VALIDATE_EMAIL)) {
                Log::error('Invalid email address: ' . $peminjaman->email);
                return response()->json([
                    'status' => false,
                    'message' => 'Email tidak valid'
                ], 400);
            }

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('SENDINBLUE_SENDER_NAME', 'System'),
                    'email' => env('SENDINBLUE_SENDER_EMAIL', 'noreply@yourapp.com')
                ],
                'to' => [
                    ['email' => $peminjaman->email, 'name' => $peminjaman->nama]
                ],
                'subject' => 'Konfirmasi Peminjaman Barang - Silakan Ambil Barang',
                'htmlContent' => view('emails.confirm-loan', [
                    'name' => $peminjaman->nama,
                    'item' => $peminjaman->item,
                    'tanggal_peminjaman' => $peminjaman->tanggal_peminjaman,
                    'tanggal_pengembalian' => $peminjaman->tanggal_pengembalian,
                    'alasan_pinjam' => $peminjaman->alasan_pinjam
                ])->render()
            ]);

            Log::info('Sendinblue API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengirim email: ' . $response->body()
                ], 500);
            }

            return response()->json([
                'status' => true,
                'message' => 'Email konfirmasi berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            Log::error('Complete Error in sendConfirmEmail:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Sistem error: ' . $e->getMessage()
            ], 500);
        }
    }
}
