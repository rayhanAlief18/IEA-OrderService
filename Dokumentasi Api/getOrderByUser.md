Request Get history order by user:

Mengambil riwayat order berdasarkan ID pengguna (karyawan).

📍 Endpoint

GET /api/order/by-user/{id}
{id}: ID dari pengguna.

✅ Request

Method: GET
URL Contoh: http://localhost:8003/api/order/by-user/1

✅ Response Sukses (201 Created)
{
	"message": "Berhasil megambil data history order",
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
		},
		{
			"id": 3,
			"id_user": 6,
			"id_kendaraan": 2,
			"pelayanan": "Cuci Menyeluruh",
			"biaya": 30000,
			"durasi_pengerjaan": 20,
			"no_antrian": 3,
			"status": "Menunggu",
			"created_at": "2025-04-30T12:10:06.000000Z",
			"updated_at": "2025-04-30T12:10:06.000000Z"
		}
	]
}

❌ Response Error (404 Not Found)
{
  "message": "Tidak ada order untuk karyawan ini"
}

❌ Response Error (500 Internal Server Error)
{
  "message": "Gagal megambil data history order",
  "error": "Exception message here"
}



