# Quick Reference - Database Changes

## Tables Summary

| Table | Changes | Key New Features |
|-------|---------|------------------|
| `users` | nim, no_telp, jenis_kelamin → nullable | Simple sign-up, profile_complete tracking |
| `laboratorium` | +status, +photo_lab | Availability status, lab photos |
| `alat` | +lab_id, kategori→enum, -lokasi, jumlah→status_peminjaman | Lab-scoped tools, better inventory |
| `jadwal` | NEW TABLE | Schedule tracking per user/lab |
| `documents` | NEW TABLE | SOP, panduan, laporan kerusakan |
| `peminjaman` | +durasi_jam, +kondisi_pengembalian, +catatan_kerusakan | Detailed borrow records |

## Key Enums

```
laboratorium.status: tersedia | tidak_tersedia
alat.kategori: Komputer | Printer | Router | Switch | Server | Networking | Perangkat Lunak | Perangkat Keras | Lainnya
alat.status_peminjaman: tersedia | tidak_tersedia
jadwal.status: terjadwal | sedang berlangsung | selesai | dibatalkan
documents.tipe_dokumen: SOP | Panduan Peminjaman | Laporan Kerusakan
```

## Relasi Baru

```
Laboratorium ──┬── Alat (1:M)
               ├── Peminjaman (1:M)
               ├── Jadwal (1:M)
               └── Document (1:M)

User ──┬── Peminjaman (1:M)
       ├── Jadwal (1:M)
       └── Document (1:M, as uploader)

Alat ──┬── Peminjaman (1:M)
       └── Laboratorium (M:1)
```

## Migration Commands

```bash
# Run all new migrations
php artisan migrate

# Run specific migration
php artisan migrate --path=database/migrations/2025_11_20_030000_modify_users_table_for_simple_signup.php
php artisan migrate --path=database/migrations/2025_11_20_031000_add_photo_and_status_to_laboratorium_table.php
php artisan migrate --path=database/migrations/2025_11_20_032000_modify_alat_table.php
php artisan migrate --path=database/migrations/2025_11_20_033000_create_jadwal_table.php
php artisan migrate --path=database/migrations/2025_11_20_034000_create_documents_table.php
php artisan migrate --path=database/migrations/2025_11_20_035000_modify_peminjaman_table.php

# Rollback specific migration
php artisan migrate:rollback --path=database/migrations/2025_11_20_030000_modify_users_table_for_simple_signup.php
```

## Files Modified/Created

### Migrations

- ✅ `2025_11_20_030000_modify_users_table_for_simple_signup.php`
- ✅ `2025_11_20_031000_add_photo_and_status_to_laboratorium_table.php`
- ✅ `2025_11_20_032000_modify_alat_table.php`
- ✅ `2025_11_20_033000_create_jadwal_table.php`
- ✅ `2025_11_20_034000_create_documents_table.php`
- ✅ `2025_11_20_035000_modify_peminjaman_table.php`

### Models

- ✅ `app/Models/User.php` - Updated
- ✅ `app/Models/Laboratorium.php` - Updated
- ✅ `app/Models/Alat.php` - Updated
- ✅ `app/Models/Jadwal.php` - Created
- ✅ `app/Models/Document.php` - Created

### Documentation

- ✅ `DATABASE_RECONSTRUCTION.md` - Detailed documentation
- ✅ `CHANGES_QUICK_REFERENCE.md` - This file
