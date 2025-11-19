# Dokumentasi Rekonstruksi Database - Sistem Pengelolaan Lab Komputer

## Ringkasan Perubahan

Rekonstruksi database dilakukan berdasarkan feedback dan requirement dari stakeholder untuk meningkatkan sistem pengelolaan lab komputer dengan fitur-fitur berikut:

1. **Sign-up yang lebih simple**
2. **Profile completion workflow**
3. **Lab availability status dan foto**
4. **Tool inventory management yang lebih baik**
5. **Scheduling dan document management**
6. **MSI Dashboard untuk supervisory insights**

---

## Detail Perubahan per Tabel

### 1. Tabel `users`

**Perubahan:**

- Field `nim`, `no_telp`, `jenis_kelamin` menjadi **NULLABLE** (opsional saat sign-up)
- Field wajib saat sign-up: `nama`, `email`, `password`
- Tambahan field untuk tracking profil:
  - `program_studi` (nullable)
  - `angkatan` (nullable)
  - `alamat` (nullable)
  - `is_profile_complete` (boolean, default: false)

**Alasan:** Memudahkan user untuk sign-up dengan data minimal, namun memaksa melengkapi data sebelum mengakses menu utama.

**Relasi:**

- `hasMany(Peminjaman)`
- `hasMany(Jadwal)`
- `hasMany(Document)` - sebagai uploader

---

### 2. Tabel `laboratorium`

**Perubahan:**

- ✅ Tambah kolom `status` (enum: 'tersedia', 'tidak_tersedia')
  - Default: 'tersedia'
  - Memudahkan penandaan lab yang sedang dalam maintenance/perbaikan
- ✅ Tambah kolom `photo_lab` (string, nullable)
  - Untuk menyimpan path foto laboratorium
  - User bisa melihat kondisi lab sebelum meminjam

**Relasi:**

- `hasMany(Peminjaman)`
- `hasMany(Jadwal)`
- `hasMany(Alat)`
- `hasMany(Document)`

---

### 3. Tabel `alat` (Equipment/Tools)

**Perubahan:**

- ✅ Tambah `lab_id` (FK ke `laboratorium`)
  - Setiap alat terikat pada lab spesifik
  - Menghapus relasi dari `ruangan` (jika ada)
  
- ✅ Ubah `kategori` menjadi **ENUM** dengan pilihan:
  - Komputer
  - Printer
  - Router
  - Switch
  - Server
  - Networking
  - Perangkat Lunak
  - Perangkat Keras
  - Lainnya

- ✅ **HAPUS** kolom `lokasi` (redundan dengan lab_id)

- ✅ Ganti `jumlah` dengan `status_peminjaman` (enum: 'tersedia', 'tidak_tersedia')
  - Alasan: Fokus pada availability state, bukan quantity

- ✅ Pertahankan `kondisi` (enum: 'Baik', 'Rusak', 'Perbaikan')

**Relasi:**

- `belongsTo(Laboratorium)` - via lab_id
- `hasMany(Peminjaman)` - via alat_id

---

### 4. Tabel `peminjaman` (Loan Records)

**Perubahan:**

- ✅ Relasi ke `lab_id` (sudah ada)
- ✅ Relasi ke `user_id` (sudah ada)
- ✅ Relasi ke `alat_id` (dipastikan ada)

- ✅ Tambah `durasi_jam` (integer, default: 1)
  - Tracking durasi peminjaman dalam jam

- ✅ Tambah `kondisi_pengembalian` (enum: 'Baik', 'Rusak', 'Hilang')
  - Kondisi alat saat dikembalikan

- ✅ Tambah `catatan_kerusakan` (text, nullable)
  - Detail kerusakan yang terjadi

**Relasi:**

- `belongsTo(User)` - peminjam
- `belongsTo(Laboratorium)` - lab yang dipinjam
- `belongsTo(Alat)` - peralatan yang dipinjam

---

### 5. Tabel `jadwal` (NEW - Scheduling)

**Struktur:**

```
id: bigint (PK)
user_id: FK ke users
lab_id: FK ke laboratorium
tgl_jadwal: date
waktu_mulai: time
waktu_selesai: time
status: enum ('terjadwal', 'sedang berlangsung', 'selesai', 'dibatalkan')
keterangan: text (nullable)
timestamps: created_at, updated_at
```

**Alasan:**

- Tracking jadwal penggunaan lab per user
- Mencegah double booking
- Useful untuk MSI dashboard

**Relasi:**

- `belongsTo(User)` - penjadwal
- `belongsTo(Laboratorium)` - lab yang dijadwalkan

---

### 6. Tabel `documents` (NEW - Document Management)

**Struktur:**

```
id: bigint (PK)
lab_id: FK ke laboratorium
tipe_dokumen: enum ('SOP', 'Panduan Peminjaman', 'Laporan Kerusakan')
judul: string
deskripsi: text (nullable)
file_path: string (path ke file upload)
uploaded_by: FK ke users (admin/supervisor yang upload)
timestamps: created_at, updated_at
```

**Alasan:**

- Menyimpan SOP (Standard Operating Procedure) per lab
- Menyimpan panduan peminjaman
- Menyimpan laporan kerusakan peralatan
- Semua terdokumentasi dan terstruktur

**Relasi:**

- `belongsTo(Laboratorium)` - lab yang terkait
- `belongsTo(User, 'uploaded_by')` - user yang upload

---

## Workflow Fitur

### 1. Sign-Up Flow

```
User Registration
├── Step 1: Masukkan Nama, Email, Password (WAJIB)
├── Step 2: Redirect ke Profile Completion
├── Step 3: Masukkan NIM, No Telp, Jenis Kelamin (WAJIB)
├── Step 4: Set is_profile_complete = true
└── Step 5: Akses ke menu utama
```

### 2. Lab Borrowing Flow

```
Browse Labs
├── Filter berdasarkan status (tersedia/tidak_tersedia)
├── Lihat foto lab
├── Submit jadwal peminjaman
└── Admin approve/reject
```

### 3. Equipment Management Flow

```
Admin Dashboard
├── View semua alat per lab
├── Update status (tersedia/tidak_tersedia)
├── Track kondisi alat (Baik/Rusak/Perbaikan)
├── Log kerusakan saat pengembalian
└── Generate report untuk MSI
```

---

## Enum Values Reference

### users.level

- `user` - Regular user (mahasiswa)
- `admin` - Administrator (lab staff)

### laboratorium.status

- `tersedia` - Lab available untuk dipinjam
- `tidak_tersedia` - Lab in maintenance/tidak bisa dipinjam

### alat.kategori

- `Komputer`
- `Printer`
- `Router`
- `Switch`
- `Server`
- `Networking`
- `Perangkat Lunak`
- `Perangkat Keras`
- `Lainnya`

### alat.kondisi

- `Baik` - Dalam kondisi baik
- `Rusak` - Rusak/tidak bisa digunakan
- `Perbaikan` - Sedang diperbaiki

### alat.status_peminjaman

- `tersedia` - Bisa dipinjam
- `tidak_tersedia` - Tidak bisa dipinjam

### jadwal.status

- `terjadwal` - Sudah dijadwalkan
- `sedang berlangsung` - Sedang digunakan
- `selesai` - Sudah selesai
- `dibatalkan` - Dibatalkan

### documents.tipe_dokumen

- `SOP` - Standard Operating Procedure
- `Panduan Peminjaman` - Panduan peminjaman lab
- `Laporan Kerusakan` - Laporan kerusakan equipment

---

## MSI Dashboard Insights

Berdasarkan data yang terkumpul, MSI (Management Information System) Dashboard dapat menampilkan:

### 1. Statistik Peminjaman Lab

- Chart: Lab mana yang paling sering dipinjam
- Insight: Identifikasi lab yang memerlukan perawatan lebih
- Actionable: Perencanaan maintenance dan resource allocation

### 2. Equipment Analysis

- Chart: Alat mana yang sering dipinjam
- Chart: Alat yang sering rusak
- Insight: Trend kerusakan peralatan
- Actionable: Perencanaan pengadaan replacement equipment

### 3. Usage Trend

- Daily/Weekly/Monthly trend peminjaman
- Peak usage hours
- Seasonal patterns
- Actionable: Scheduling dan resource planning

### Data Tracking

- Semua peminjaman tercatat via `peminjaman` table
- Kondisi peralatan tercatat via `kondisi_pengembalian`
- Kerusakan terdokumentasi via `documents` table (tipe: Laporan Kerusakan)
- Timeline transparan untuk decision making

---

## Migration Files Created

1. `2025_11_20_030000_modify_users_table_for_simple_signup.php`
   - Ubah nim, no_telp, jenis_kelamin menjadi nullable

2. `2025_11_20_031000_add_photo_and_status_to_laboratorium_table.php`
   - Tambah status enum dan photo_lab

3. `2025_11_20_032000_modify_alat_table.php`
   - Tambah lab_id, ubah kategori jadi enum, hapus lokasi, ubah jumlah jadi status_peminjaman

4. `2025_11_20_033000_create_jadwal_table.php`
   - Tabel baru untuk scheduling

5. `2025_11_20_034000_create_documents_table.php`
   - Tabel baru untuk SOP, panduan, dan laporan

6. `2025_11_20_035000_modify_peminjaman_table.php`
   - Tambah durasi_jam, kondisi_pengembalian, catatan_kerusakan

---

## Model Classes Created/Updated

### Models Created

- `Jadwal.php` - Model untuk jadwal
- `Document.php` - Model untuk dokumen

### Models Updated

- `User.php` - Tambah fillable fields dan relasi jadwal & documents
- `Laboratorium.php` - Tambah relasi jadwal, alat, documents
- `Alat.php` - Tambah relasi laboratorium
- `Peminjaman.php` - (Sudah memiliki relasi yang tepat)

---

## How to Run Migrations

```bash
cd c:\xampp\htdocs\peminjaman-lab-main

# Run semua migrations
php artisan migrate

# Atau specific migration
php artisan migrate --path=database/migrations/2025_11_20_030000_modify_users_table_for_simple_signup.php
```

---

## Testing Checklist

- [ ] Sign-up dengan data minimal (nama, email, password)
- [ ] Redirect ke profile completion
- [ ] User tidak bisa akses menu sebelum profil lengkap
- [ ] Admin bisa update status lab (tersedia/tidak_tersedia)
- [ ] Foto lab bisa di-upload dan ditampilkan
- [ ] Alat per lab terbaca dengan benar
- [ ] Status peminjaman alat working
- [ ] Jadwal peminjaman tercatat dengan benar
- [ ] Documents (SOP, panduan, laporan) bisa di-upload
- [ ] MSI dashboard bisa pull data untuk reporting

---

Generated: November 20, 2025
