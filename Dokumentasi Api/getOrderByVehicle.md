Request Get Order By Vehicle:

Mengambil riwayat order berdasarkan ID kendaraan tertentu.

📍 Endpoint

GET /api/order/by-vehicle/{id}

✅ Request

Method: GET
URL Contoh: http://localhost:8003/api/order/by-vehicle/2

✅ Response Sukses (201 Created)

{
	"message": "Berhasil megambil data history order berdasarkan kendaraan",
	"data": [
		{
			"id": 1,
			"id_user": 6,
			"id_kendaraan": 3,
			"pelayanan": "Cuci Menyeluruh",
			"biaya": 75000,
			"durasi_pengerjaan": 45,
			"no_antrian": 1,
			"status": "Menunggu",
			"created_at": "2025-04-30T06:55:59.000000Z",
			"updated_at": "2025-04-30T06:55:59.000000Z"
		}
	]
}

❌ Response Error (404 Not Found)

{
  "message": "Tidak ada order untuk kendaraan ini"
}


❌ Response Error (500 Internal Server Error)

{
  "message": "Gagal megambil data history order berdasarkan kendraan",
  "error": "Exception message here"
}

