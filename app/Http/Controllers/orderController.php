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
            $data->transform(function ($order) {
                // Ambil user dari user-service
                $userResponse = Http::get("http://nginx-user/api/getUser/{$order->id_user}");
                $order->user_data = $userResponse->successful() ? $userResponse->json() : null;

                // Ambil kendaraan dari vehicle-service
                $vehicleResponse = Http::get("http://nginx-vehicle/api/getVehicle/{$order->id_kendaraan}");
                $order->kendaraan_data = $vehicleResponse->successful() ? $vehicleResponse->json() : null;

                return $order;
            });

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
                'nama_pemesan' => 'required|string|max:255',
                'id_service' => 'required|integer',
            ], [
                'id_user.required' => 'ID user wajib diisi.',
                'id_user.integer' => 'ID user harus berupa angka.',

                'id_kendaraan.required' => 'ID kendaraan wajib diisi.',
                'id_kendaraan.integer' => 'ID kendaraan harus berupa angka.',

                'nama_pemesan.required' => 'Jenis pelayanan wajib diisi.',
                'nama_pemesan.string' => 'Pelayanan harus berupa teks.',
                'nama_pemesan.max' => 'Maksimal 255 karakter.',

                'id_service.required' => 'Nomor antrean wajib diisi.',
                'id_service.integer' => 'Nomor antrean harus berupa teks.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            // Cek ke user-service
            $userResponse = Http::get('http://nginx-user/api/getUser/' . $request->id_user);
            // if (!$userResponse->successful()) {
            //     return response()->json(['message' => 'User tidak ditemukan, Silahkan buat ulang'], 404);
            // }
            

            // Cek ke vehicle-service
            $vehicleResponse = Http::get('http://nginx-vehicle/api/getVehicle/' . $request->id_kendaraan);
            // if (!$vehicleResponse->successful()) {
            //     return response()->json(['message' => 'Kendaraan tidak ditemukan, Silahkan buat ulang'], 404);
            // }

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

            // antrian otomatis
            // Ambil tanggal hari ini (tanpa jam)
            $tanggalHariIni = now()->toDateString();
            // Hitung jumlah order yang dibuat pada hari ini
            $jumlahOrderHariIni = order::whereDate('created_at', $tanggalHariIni)->count();
            // Nomor antrean berikutnya
            $no_antrian = $jumlahOrderHariIni + 1;

            $data = order::create([
                'id_user' => $request->id_user,
                'id_kendaraan' => $request->id_kendaraan,
                'invoice_number' => $invoice_number,
                'qr_code' => $qrImageUrl,
                'nama_pemesan' => $request->nama_pemesan,
                'no_antrian' => $no_antrian,
                'status' => 'Menunggu',
                'id_service' => $request->id_service,
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
            $orders->transform(function ($order) {
                // Ambil user dari user-service
                $userResponse = Http::get("http://nginx-user/api/getUser/{$order->id_user}");
                $order->user_data = $userResponse->successful() ? $userResponse->json() : null;

                return $order;
            });

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
            $orders = Order::with('service') // pastikan relasi di-join
                ->whereDate('created_at', $date)
                ->get();
            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Tidak ada order pada tanggal ini'], 404);
            }
            // Loop dan tambahkan user_data dan kendaraan_data dari microservice
            $orders->transform(function ($order) {
                // Ambil user dari user-service
                $userResponse = Http::get("http://nginx-user/api/getUser/{$order->id_user}");
                $order->user_data = $userResponse->successful() ? $userResponse->json() : null;

                // Ambil kendaraan dari vehicle-service
                $vehicleResponse = Http::get("http://nginx-vehicle/api/getVehicle/{$order->id_kendaraan}");
                $order->kendaraan_data = $vehicleResponse->successful() ? $vehicleResponse->json() : null;

                return $order;
            });

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
