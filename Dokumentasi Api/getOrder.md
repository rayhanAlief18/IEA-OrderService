Request Get Order:

Mengambil seluruh data order dari database.

📍 Endpoint

GET /api/order

✅ Request

Method: GET
URL: http://localhost:8003/api/order

✅ Response Sukses (201 Created)

{
	"message": "Berhasil menampilkan data order",
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
			"id": 2,
			"id_user": 7,
			"id_kendaraan": 2,
			"pelayanan": "Cuci Menyeluruh",
			"biaya": 30000,
			"durasi_pengerjaan": 20,
			"no_antrian": 2,
			"status": "Menunggu",
			"created_at": "2025-04-30T12:09:47.000000Z",
			"updated_at": "2025-04-30T12:09:47.000000Z"
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

❌ Response Error (500 Internal Server Error)

{
  "message": "Gagal menampilkan data order !",
  "error": "Exception message here"
}


🔄 Contoh CURL Request

curl -X GET http://localhost:8003/api/order


📌 Catatan

Pastikan koneksi ke database aktif.
Status code 201 biasanya untuk "created", sebaiknya diganti dengan 200 OK untuk response GET.