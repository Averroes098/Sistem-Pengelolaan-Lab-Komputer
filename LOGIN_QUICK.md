# AKUN LOGIN PEMINJAMAN LAB

## ✅ Siap Pakai

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@lab.com | admin123 |
| Staff | staf@lab.com | staf123 |
| User | user@lab.com | user123 |

## 🚀 Buka Aplikasi

http://127.0.0.1:8000

## 📝 Langkah Testing

1. Buka aplikasi
2. Klik Login
3. Masukkan email & password dari tabel di atas
4. Sistem akan redirect ke dashboard sesuai role

## 🎯 Expected Behavior

- **admin@lab.com** → /admin/dashboard (Lihat statistik lab)
- **staf@lab.com** → /staf/dashboard (Lihat data peminjaman)
- **user@lab.com** → /user (Lihat lab & peminjaman)

## ✅ Status

- Database: ✅ Fresh Reset
- Migrations: ✅ 16/16 Selesai
- Accounts: ✅ 3 Akun Aktif
- Cache: ✅ Cleared
- Ready: ✅ YES

---

**Dibuat**: November 23, 2025  
**Database**: peminjaman_lab
