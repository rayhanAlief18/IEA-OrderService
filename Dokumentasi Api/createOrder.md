Request Create order :

Membuat order baru setelah validasi input dan pengecekan user serta kendaraan dari service eksternal.

📍 Endpoint

POST /api/order

✅ Request

Method: POST
URL: http://localhost:8003/api/order
Content-Type: application/json

📥 Request Body

{
  "id_user": 1,
  "id_kendaraan": 2,
  "pelayanan": "Cuci Salju",
  "biaya": 25000,
  "durasi_pengerjaan": "30 menit",
  "no_antrian": "A12"
}

📋 Validasi & Pesan Kesalahan

| Field               | Validasi                  | Pesan Kesalahan                                                                 |
| ------------------- | ------------------------- | ------------------------------------------------------------------------------- |
| `id_user`           | required, integer         | "ID user wajib diisi.", "ID user harus berupa angka."                           |
| `id_kendaraan`      | required, integer         | "ID kendaraan wajib diisi.", "ID kendaraan harus berupa angka."                 |
| `pelayanan`         | required, string, max:255 | "Jenis pelayanan wajib diisi.", "Pelayanan harus berupa teks."                  |
| `biaya`             | required, numeric, min:0  | "Biaya wajib diisi.", "Biaya harus berupa angka.", "Biaya tidak boleh negatif." |
| `durasi_pengerjaan` | required, string, max:100 | "Durasi pengerjaan wajib diisi.", "Durasi pengerjaan harus berupa teks."        |
| `no_antrian`        | required, string, max:50  | "Nomor antrean wajib diisi.", "Nomor antrean harus berupa teks."                |


✅ Response Sukses (201 Created)

{
  "message": "Berhasil menambahkan data kendaraan",
  "data": {
    "id": 10,
    "id_user": 1,
    "id_kendaraan": 2,
    "pelayanan": "Cuci Salju",
    "biaya": 25000,
    "durasi_pengerjaan": "30 menit",
    "no_antrian": "A12",
    "created_at": "2025-05-03T10:00:00.000000Z",
    "updated_at": "2025-05-03T10:00:00.000000Z"
  }
}

❌ Response Error (422 Unprocessable Entity)

{
  "errors": {
    "pelayanan": [
      "Jenis pelayanan wajib diisi."
    ]
  }
}

❌ Response Error (404 Not Found)

{
  "message": "User tidak ditemukan, Silahkan buat ulang"
}

atau 

{
  "message": "Kendaraan tidak ditemukan, Silahkan buat ulang"
}


❌ Response Error (500 Internal Server Error)

{
  "message": "Gagal menambahkan data kendaraan",
  "error": "Exception message here"
}


