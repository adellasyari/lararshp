# TODO: Buat Tampilan Index untuk Resepsionis - Temu Dokter

## Step 1: Buat Migration untuk Temu Dokter
- Buat migration file untuk tabel temu_dokter dengan field: id, tanggal_jadwal, waktu_jadwal, status, idpet, idpemilik, idrekam_medis, created_at, updated_at

## Step 2: Buat Model TemuDokter
- Buat file `app/Models/TemuDokter.php` dengan relasi ke Pet, Pemilik, dan RekamMedis

## Step 3: Buat Controller TemuDokter untuk Resepsionis
- Buat file `app/Http/Controllers/Resepsionis/TemuDokterController.php`
- Implementasikan method index() untuk menampilkan data temu dokter dengan relasi

## Step 4: Buat View Index Temu Dokter untuk Resepsionis
- Buat file `resources/views/resepsionis/temu-dokter/index.blade.php`
- Buat tabel untuk menampilkan data temu dokter (Tanggal, Waktu, Status, Pet, Pemilik, dll.)

## Step 5: Update Routes
- Tambahkan route untuk resepsionis temu-dokter di `routes/web.php` dalam middleware isResepsionis

## Step 6: Update Dashboard Resepsionis
- Tambahkan menu Temu Dokter di dashboard resepsionis
