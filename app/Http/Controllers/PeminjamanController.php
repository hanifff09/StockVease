<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\VerificationSession;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
        })
            ->whereYear('tanggal_peminjaman', $request->tahun)
            ->whereMonth('tanggal_peminjaman', $request->bulan)
            ->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->map(function ($peminjaman) {
            if ($peminjaman->status == 0) {
                $peminjaman->text_status = 'Menunggu Dikonfirmasi';
            } elseif ($peminjaman->status == 1) {
                $peminjaman->text_status = 'Pending';
            } elseif ($peminjaman->status == 2) {
                $peminjaman->text_status = 'Dipinjam';
            } elseif ($peminjaman->status == 3) {
                $peminjaman->text_status = 'Terlambat';
            } elseif ($peminjaman->status == 4) {
                $peminjaman->text_status = 'Selesai';
            } elseif ($peminjaman->status == 5) {
                $peminjaman->text_status = 'Ditolak';
            }
            return $peminjaman;
        });

        return response()->json($data);
    }

    public function add(Request $request)
    {
        try {
            // Validate the verification session
            $session = VerificationSession::where('session_token', $request->session_token)
                ->where('is_verified', true)
                ->where('expires_at', '>', now())
                ->first();

            if (!$session) {
                return response()->json([
                    'status' => false,
                    'message' => 'Silakan verifikasi email Anda terlebih dahulu'
                ], 403);
            }

            // Validate the form data
            $validated = $request->validate([
                'nama' => 'required|string',
                'nip' => 'required|string',
                'alasan_pinjam' => 'required|string',
                'item' => 'required|string',
                'tanggal_peminjaman' => 'required|date',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            ]);

            // Create peminjaman with verified email and name from session
            $peminjaman = Peminjaman::create([
                'uuid' => Str::uuid(),
                ...$validated,
                'email' => $session->email,
                'nama' => $session->name
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data peminjaman berhasil disimpan.',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating peminjaman:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addmin(Request $request)
    {
        try {
            // Validate the form data
            $validated = $request->validate([
                'nama' => 'required|string',
                'nip' => 'required|string',
                'email' => 'required|email',
                'alasan_pinjam' => 'required|string',
                'item' => 'required|string',
                'tanggal_peminjaman' => 'required|date',
                'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_peminjaman',
            ]);

            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'uuid' => Str::uuid(),
                ...$validated
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data peminjaman berhasil disimpan.',
                'data' => $peminjaman
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating peminjaman:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }


    public function edit($uuid)
    {
        $peminjaman = Peminjaman::findByUuid($uuid);

        return response()->json([
            'data' => $peminjaman,
        ], 200);
    }

    public function get()
    {
        return response()->json(['data' => Peminjaman::all()]);
    }

    public function update($uuid, Request $request)
    {
        $peminjaman = Peminjaman::findOrFail($uuid);

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
    public function apdet($uuid, Request $request)
    {
        $peminjaman = Peminjaman::where('uuid', $uuid)->firstOrFail();

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

    public function updateStock($uuid, Request $request)
    {
        try {
            DB::beginTransaction();

            $item = Item::where('uuid', $uuid)->firstOrFail();

            // Check if stock is available
            if ($item->stok <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stok item tidak tersedia'
                ], 400);
            }

            // Update stock
            $item->stok = $item->stok - 1;
            $item->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil diperbarui',
                'data' => $item
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating stock:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui stok: ' . $e->getMessage()
            ], 500);
        }
    }

    // In PeminjamanController.php

    public function getMonthlyStats()
    {
        $monthlyStats = Peminjaman::selectRaw('
        DATE_FORMAT(created_at, "%Y-%m") as month,
        COUNT(*) as total_loans
    ')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($stat) {
                return [
                    'month' => date('M Y', strtotime($stat->month)),
                    'total' => $stat->total_loans
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $monthlyStats
        ]);
    }

    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2',
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Generate OTP code (6 digits)
            $otpCode = sprintf("%06d", mt_rand(1, 999999));

            // Generate session token
            $sessionToken = Str::random(64);

            // Create verification session first
            $session = VerificationSession::create([
                'uuid' => Str::uuid(),
                'name' => $request->name,
                'email' => $request->email,
                'verification_code' => $otpCode,
                'session_token' => $sessionToken,
                'expires_at' => now()->addMinutes(15),
                'item_uuid' => $request->item_uuid
            ]);

            // Send email using Brevo/Sendinblue
            $apiKey = env('SENDINBLUE_API_KEY');
            if (!$apiKey) {
                Log::error('Sendinblue API key not configured');
                throw new \Exception('Email service configuration missing');
            }

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('SENDINBLUE_SENDER_NAME'),
                    'email' => env('SENDINBLUE_SENDER_EMAIL')
                ],
                'to' => [
                    ['email' => $request->email, 'name' => $request->name]
                ],
                'subject' => 'Kode OTP Verifikasi Email',
                'htmlContent' => view('emails.otp', [
                    'name' => $request->name,
                    'otpCode' => $otpCode
                ])->render()
            ]);

            if (!$response->successful()) {
                Log::error('Sendinblue API error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                throw new \Exception('Failed to send email: ' . $response->json()['message'] ?? 'Unknown error');
            }

            return response()->json([
                'status' => true,
                'message' => 'Kode OTP telah dikirim ke email Anda',
                'session_token' => $sessionToken
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending OTP:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengirim kode OTP: ' . $e->getMessage()
            ], 500);
        }
    }


    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_token' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $session = VerificationSession::where('session_token', $request->session_token)
            ->where('verification_code', $request->otp)
            ->where('expires_at', '>', now())
            ->where('is_verified', false)
            ->first();

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ], 400);
        }

        $session->is_verified = true;
        $session->save();

        return response()->json([
            'status' => true,
            'message' => 'Verifikasi OTP berhasil',
            'data' => [
                'name' => $session->name,
                'email' => $session->email,
                'item_uuid' => $session->item_uuid
            ]
        ]);
    }

    public function downloadPDF(Request $request)
    {
        try {
            // Get data with filters
            $peminjaman = Peminjaman::when($request->search, function (Builder $query, string $search) {
                $query->where('nama', 'like', "%$search%")
                    ->orWhere('nip', 'like', "%$search%")
                    ->orWhere('item', 'like', "%$search%")
                    ->orWhere('alasan_pinjam', 'like', "%$search%");
            })
                ->when($request->tahun, function ($query) use ($request) {
                    $query->whereYear('tanggal_peminjaman', $request->tahun);
                })
                ->when($request->bulan, function ($query) use ($request) {
                    $query->whereMonth('tanggal_peminjaman', $request->bulan);
                })
                ->latest()
                ->get();    

            // Map status text
            $peminjaman->map(function ($item) {
                if ($item->status == 0) {
                    $item->text_status = 'Menunggu Dikonfirmasi';
                } elseif ($item->status == 1) {
                    $item->text_status = 'Pending';
                } elseif ($item->status == 2) {
                    $item->text_status = 'Dipinjam';
                } elseif ($item->status == 3) {
                    $item->text_status = 'Terlambat';
                } elseif ($item->status == 4) {
                    $item->text_status = 'Selesai';
                } elseif ($item->status == 5) {
                    $item->text_status = 'Ditolak';
                }
                return $item;
            });

            $pdf = PDF::loadView('pdf.peminjaman', [
                'peminjaman' => $peminjaman,
                'bulan' => $request->bulan ? date('F', mktime(0, 0, 0, $request->bulan, 1)) : 'Semua Bulan',
                'tahun' => $request->tahun ?? date('Y')
            ]);

            return $pdf->download('rekap-peminjaman.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengunduh PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
