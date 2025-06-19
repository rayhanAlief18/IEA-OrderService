<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\service;
use Illuminate\Support\Facades\Http;


class ServiceController extends Controller
{
    // Tampilkan semua data service
    public function index()
    {
        try {
            $data = service::all();
            return response()->json([
                'message' => 'Berhasil menampilkan data service',
                'data' => $data,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menampilkan data service !',
                'error' => $e->getMessage(),
            ], 500);
        }
        ;
    }

    // Simpan data baru
    public function store(Request $request)
    {

        try {

            $request->validate([
                'id_kendaraan' => 'required',
                'pelayanan' => 'required|string',
                'biaya' => 'required|integer',
                'durasi' => 'required|integer',
            ]);
            
            $service = Service::create($request->all());

            return response()->json([
                'message' => 'Service berhasil ditambahkan.',
                'data' => $service
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan service.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Tampilkan satu data berdasarkan ID
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    // Update data berdasarkan ID
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kendaraan' => 'required|string',
            'pelayanan' => 'required|string',
            'biaya' => 'required|integer',
            'durasi' => 'required|integer',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return response()->json([
            'message' => 'Service berhasil diperbarui.',
            'data' => $service
        ]);
    }

    // Hapus data berdasarkan ID
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service berhasil dihapus.']);
    }
}
