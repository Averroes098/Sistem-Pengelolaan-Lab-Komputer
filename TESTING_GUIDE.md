# 🧪 TESTING GUIDE - Perbaikan Error Login

**Tanggal:** 23 November 2025

Panduan lengkap untuk testing semua fitur role (Admin, Staf, User) setelah perbaikan error login.

---

## ✅ Pre-Testing Checklist

Sebelum testing, pastikan:

```bash
# 1. Database migrasi
php artisan migrate

# 2. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 3. Buat akun test (opsional, jika belum ada)
php artisan tinker
```

---

## 👤 Akun Test yang Diperlukan

Buat 3 akun dengan level berbeda:

### Admin
- **Email:** admin@lab.com
- **Password:** password
- **Level:** admin

### Staf
- **Email:** staf@lab.com
- **Password:** password
- **Level:** staf

### User
- **Email:** user@lab.com
- **Password:** password
- **Level:** user

---

## 🔄 Testing Flow

### 1️⃣ **TEST: Admin Login & Dashboard**

**Steps:**
```
1. Buka http://127.0.0.1:8000/
2. Login dengan:
   - Email: admin@lab.com
   - Password: password
3. Harusnya redirect ke /admin/dashboard atau /dashboard
```

**Ekspektasi:**
- ✅ Dashboard admin tampil tanpa error
- ✅ Tabel "Data Laboratorium" tampil
- ✅ Tabel "Peminjaman Terbaru" tampil (dengan data atau "Belum ada data peminjaman")
- ✅ Jika ada peminjaman dengan user null, tampil "User Tidak Dikenal"
- ✅ Dapat akses menu `/admin/laboratorium`
- ✅ Dapat akses menu `/admin/peminjaman`

**Troubleshooting:**
```
Jika error "Attempt to read property on null":
→ Berarti blade template belum diperbaiki dengan ?-> operator

Jika error "Class not found":
→ Jalankan: composer dump-autoload

Jika error 403 Unauthorized:
→ Cek di database apakah level = 'admin'
```

---

### 2️⃣ **TEST: Staf Login & Dashboard**

**Steps:**
```
1. Logout dari admin (klik Logout)
2. Login dengan:
   - Email: staf@lab.com
   - Password: password
3. Harusnya redirect ke /staf/dashboard atau /dashboard
```

**Ekspektasi:**
- ✅ Dashboard staf tampil tanpa error
- ✅ Menampilkan 3 card:
   - "Permintaan Menunggu" (angka)
   - "Alat Sedang Dipinjam" (angka)
   - "Alat Rusak" (angka)
- ✅ 4 tombol menu: Validasi, Pengembalian, Catat Kerusakan, Upload SOP
- ✅ Semua tombol berfungsi (bisa di-klik)

**Troubleshooting:**
```
Jika error "SQLSTATE table not found":
→ Berarti table peminjaman atau alat belum ada
→ Jalankan: php artisan migrate

Jika card menampilkan 0 tapi seharusnya ada data:
→ Cek query status_peminjaman di database (harusnya 'pending' atau 'disetujui')

Jika error di tombol:
→ Berarti route belum terdaftar, cek routes/web.php
```

---

### 3️⃣ **TEST: User Login & Dashboard**

**Steps:**
```
1. Logout dari staf
2. Login dengan:
   - Email: user@lab.com
   - Password: password
3. Harusnya redirect ke /user atau /dashboard
```

**Ekspektasi:**
- ✅ Dashboard user tampil dengan profile card
- ✅ Menampilkan nama, NIM, Prodi, Angkatan, Email user
- ✅ Grid labs tampil (atau "Belum ada laboratorium")
- ✅ Tabel "Riwayat Pengajuan Saya" tampil
  - Jika profile belum lengkap → tampil alert warning dengan progress bar
  - Jika ada peminjaman → tampil data peminjaman
  - Jika tidak ada → tampil "Belum ada riwayat peminjaman"

**Troubleshooting:**
```
Jika profile belum lengkap (tidak tampil alert):
→ Cek di database, field: program_studi, angkatan, alamat
→ Update manual atau lengkapi via form

Jika riwayat peminjaman error:
→ Pastikan Peminjaman model sudah di-eager load dengan laboratorium
→ Cek di Controller: with(['laboratorium', 'alat', 'user'])
```

---

### 4️⃣ **TEST: Unauthorized Access (CRITICAL)**

**Test 1: Admin akses halaman Staf**
```
1. Login sebagai admin
2. Ubah URL ke: http://127.0.0.1:8000/staf/dashboard
3. Harusnya REDIRECT ke /dashboard dengan pesan error
   "Anda tidak memiliki akses ke halaman staf."
```

**Test 2: User akses halaman Admin**
```
1. Login sebagai user
2. Ubah URL ke: http://127.0.0.1:8000/admin/laboratorium
3. Harusnya REDIRECT ke /dashboard dengan pesan error
   "Anda tidak memiliki akses ke halaman admin."
```

**Test 3: Non-login akses dashboard**
```
1. Logout terlebih dahulu
2. Buka URL: http://127.0.0.1:8000/dashboard
3. Harusnya REDIRECT ke /login
```

**Test 4: Direct URL ke protected route (No Login)**
```
1. Logout
2. Buka: http://127.0.0.1:8000/admin/peminjaman
3. Harusnya REDIRECT ke /login dengan session message
```

---

## 🐛 Debug Mode

Jika ada error, gunakan command berikut untuk debug:

### Cek Logs
```bash
# Tail latest logs
tail -f storage/logs/laravel.log

# Atau baca dari file
cat storage/logs/laravel.log | grep "ERROR"
```

### Cek Database
```bash
# Masuk ke tinker
php artisan tinker

# Cek user dengan level admin
>>> User::where('level', 'admin')->first()

# Cek ada relasi peminjaman?
>>> $user = User::with('peminjamans')->first()
>>> $user->peminjamans

# Cek peminjaman dengan user
>>> Peminjaman::with('user')->first()
```

### Test Query Langsung
```php
// Di tinker
>>> use App\Models\Peminjaman;
>>> Peminjaman::with(['user', 'laboratorium', 'alat'])->latest()->paginate(3)

// Jika error, ada relasi yang tidak ada/NULL
```

---

## ✨ Expected Success Indicators

Setelah semua test pass, Anda akan lihat:

| Role | Indikator Sukses |
|------|-----------------|
| 🟦 Admin | Dashboard tampil, bisa CRUD lab & peminjaman, akses hanya admin |
| 🟪 Staf | Dashboard dengan stats, bisa validasi & pengembalian, akses hanya staf |
| 🟩 User | Dashboard dengan lab catalog, riwayat peminjaman, akses hanya user |

---

## 📋 Testing Checklist

```markdown
## Pre-Testing
- [ ] Database sudah migrate
- [ ] Cache sudah clear
- [ ] Akun test sudah buat (3 role)

## Admin Testing
- [ ] Login berhasil
- [ ] Dashboard tampil tanpa error
- [ ] Data labs tampil
- [ ] Peminjaman terbaru tampil
- [ ] Bisa akses /admin/laboratorium
- [ ] Bisa akses /admin/peminjaman

## Staf Testing
- [ ] Login berhasil
- [ ] Dashboard staf tampil
- [ ] Stats tampil (menunggu, dipinjam, rusak)
- [ ] Semua tombol menu visible
- [ ] Bisa akses menu validasi & pengembalian

## User Testing
- [ ] Login berhasil
- [ ] Dashboard tampil dengan profile
- [ ] Riwayat peminjaman tampil
- [ ] Jika profile belum lengkap, ada notifikasi
- [ ] Bisa akses /user

## Security Testing
- [ ] Admin tidak bisa akses /staf
- [ ] User tidak bisa akses /admin
- [ ] Non-login tidak bisa akses /dashboard
- [ ] Redirect dengan pesan error yang jelas

## Browser Console
- [ ] Tidak ada JavaScript error
- [ ] Tidak ada warning (optional)
```

---

## 🎯 Final Verification

Setelah semua test pass, jalankan:

```bash
# Production ready check
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify no errors
php artisan tinker
>>> DB::connection()->getPdo()  // Cek DB connection
>>> exit
```

---

## 📞 Support

Jika menemukan issue:
1. Baca log file di `storage/logs/laravel.log`
2. Cek database dengan `php artisan tinker`
3. Verifikasi migration sudah jalan: `php artisan migrate:status`
4. Clear semua cache dan coba lagi

