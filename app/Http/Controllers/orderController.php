<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

// Qr-Code
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class orderController extends Controller
{
    public function index()
    {
        try {
            $data = order::all();
            return response()->json([
                'message' => 'Berhasil menampilkan data order',
                'data' => $data,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menampilkan data order !',
                'error' => $e->getMessage(),
            ], 500);
        }
        ;
    }

    public function createOrder(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id_user' => 'required|integer',
                'id_kendaraan' => 'required|integer',
                'pelayanan' => 'required|string|max:255',
                'biaya' => 'required|numeric|min:0',
                'durasi_pengerjaan' => 'required|string|max:100',
                'no_antrian' => 'required|string|max:50',
            ], [
                'id_user.required' => 'ID user wajib diisi.',
                'id_user.integer' => 'ID user harus berupa angka.',

                'id_kendaraan.required' => 'ID kendaraan wajib diisi.',
                'id_kendaraan.integer' => 'ID kendaraan harus berupa angka.',

                'pelayanan.required' => 'Jenis pelayanan wajib diisi.',
                'pelayanan.string' => 'Pelayanan harus berupa teks.',

                'biaya.required' => 'Biaya wajib diisi.',
                'biaya.numeric' => 'Biaya harus berupa angka.',
                'biaya.min' => 'Biaya tidak boleh negatif.',

                'durasi_pengerjaan.required' => 'Durasi pengerjaan wajib diisi.',
                'durasi_pengerjaan.string' => 'Durasi pengerjaan harus berupa teks.',

                'no_antrian.required' => 'Nomor antrean wajib diisi.',
                'no_antrian.string' => 'Nomor antrean harus berupa teks.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            // Cek ke user-service
            $userResponse = Http::get('http://localhost:8000/api/getUser/' . $request->id_user);
            if (!$userResponse->successful()) {
                return response()->json(['message' => 'User tidak ditemukan, Silahkan buat ulang'], 404);
            }

            // Cek ke vehicle-service
            $vehicleResponse = Http::get('http://localhost:8002/api/getVehicle/' . $request->id_kendaraan);
            if (!$vehicleResponse->successful()) {
                return response()->json(['message' => 'Kendaraan tidak ditemukan, Silahkan buat ulang'], 404);
            }

            // QrCode Progress
            // Generate unique invoice_number
            do {
                $invoice_number = 'INV-' . strtoupper(Str::random(10));
            } while (order::where('invoice_number', $invoice_number)->exists());

            // Generate QR code PNG (pakai GD default)
            $qrImage = QrCode::format('png')
                ->size(300)
                ->generate($invoice_number);

            $fileName = $invoice_number . '.png';
            Storage::disk('public')->put('qrcodes/' . $fileName, $qrImage);

            $qrImageUrl = asset('storage/qrcodes/' . $fileName);

            // dd($request->all(), $qrImageUrl,$invoice_number);

            $data = order::create([
                'id_user' => $request->id_user,
                'id_kendaraan' => $request->id_kendaraan,
                'pelayanan' => $request->pelayanan,
                'biaya' => $request->biaya,
                'durasi_pengerjaan' => $request->durasi_pengerjaan,
                'no_antrian' => $request->no_antrian,
                'qr_code' => $qrImageUrl,
                'invoice_number' => $invoice_number,
            ]);

            return response()->json([
                'message' => 'Berhasil menambahkan data order',
                'data' => $data,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrderByUser($id)
    {
        try {
            $orders = order::where('id_user', $id)->get();
            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Tidak ada order untuk karyawan ini'], 404);
            }
            return response()->json([
                'message' => 'Berhasil megambil data history order',
                'data' => $orders
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal megambil data history order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrderByVehicle($id)
    {
        try {
            $vehicle = order::where('id_kendaraan', $id)->get();
            if ($vehicle->isEmpty()) {
                return response()->json(['message' => 'Tidak ada order untuk kendaraan ini'], 404);
            }
            return response()->json([
                'message' => 'Berhasil megambil data history order berdasarkan kendaraan',
                'data' => $vehicle
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal megambil data history order berdasarkan kendraan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrderByDate($date)
    {
        try {
            $orders = order::whereDate('created_at', $date)->get();
            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Tidak ada order pada tanggal ini'], 404);
            }
            return response()->json([
                'message' => 'Berhasil megambil data history order berdasarkan tanggal',
                'data' => $orders
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal megambil data history order berdasarkan tanggal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
