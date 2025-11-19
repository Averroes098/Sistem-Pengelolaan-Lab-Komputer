# 🚀 Migration Execution Guide

## Pre-Migration Checklist

- [ ] Backup database lama: `mysqldump -u root peminjaman_lab > backup_2025_11_20.sql`
- [ ] Backup `.env` file
- [ ] Close all database connections
- [ ] Inform team about migration window
- [ ] Test migrations in local/dev environment first

---

## Recommended Migration Strategy

### Option 1: Fresh Migration (Recommended untuk dev/staging)

✅ Paling clean dan simple
❌ Semua data lama akan dihapus

```bash
cd c:\xampp\htdocs\peminjaman-lab-main

# Fresh start - hapus semua tabel dan migrate dari awal
php artisan migrate:fresh --seed

# Atau tanpa seed
php artisan migrate:fresh
```

### Option 2: Incremental Migration (Recommended untuk production)

✅ Keep data lama
⚠️ Perlu manual data mapping

```bash
cd c:\xampp\htdocs\peminjaman-lab-main

# Run hanya migration baru yang belum dijalankan
php artisan migrate
```

---

## Step-by-Step Migration (Option 2 - Production)

### Step 1: Backup

```bash
# Backup database
mysqldump -u root peminjaman_lab > backup_2025_11_20.sql

# Backup environment
copy .env .env.backup
```

### Step 2: Run Individual Migrations (in order)

```bash
cd c:\xampp\htdocs\peminjaman-lab-main

# 1. Modify users table untuk simple signup
php artisan migrate --path=database/migrations/2025_11_20_030000_modify_users_table_for_simple_signup.php

# 2. Add photo dan status ke laboratorium
php artisan migrate --path=database/migrations/2025_11_20_031000_add_photo_and_status_to_laboratorium_table.php

# 3. Modify alat table
php artisan migrate --path=database/migrations/2025_11_20_032000_modify_alat_table.php

# 4. Create jadwal table
php artisan migrate --path=database/migrations/2025_11_20_033000_create_jadwal_table.php

# 5. Create documents table
php artisan migrate --path=database/migrations/2025_11_20_034000_create_documents_table.php

# 6. Modify peminjaman table
php artisan migrate --path=database/migrations/2025_11_20_035000_modify_peminjaman_table.php
```

### Step 3: Verify Migration

```bash
# Check migration status
php artisan migrate:status

# Output should show:
# | Batch | Migration | Batch Run Date |
# | 1 | 2025_11_20_030000_modify_users_table_for_simple_signup | 2025-11-20 xx:xx:xx |
# | 1 | 2025_11_20_031000_add_photo_and_status_to_laboratorium_table | 2025-11-20 xx:xx:xx |
# | etc...
```

### Step 4: Seed Test Data (Optional)

```bash
php artisan db:seed --class=DatabaseSeeder

# Atau seed all
php artisan db:seed
```

---

## Rollback Guide

### Rollback All Migrations

```bash
php artisan migrate:rollback

# Rollback multiple batches
php artisan migrate:rollback --step=6
```

### Rollback Specific Migration

```bash
# Rollback hingga sebelum batch tertentu
php artisan migrate:rollback --path=database/migrations/2025_11_20_030000_modify_users_table_for_simple_signup.php
```

### Restore from Backup

```bash
# If migration failed, restore from backup
mysql -u root peminjaman_lab < backup_2025_11_20.sql
```

---

## Verification Queries

Setelah migration selesai, jalankan queries ini untuk verify:

### Check Users Table

```sql
-- Verify users table structure
DESC users;

-- Expected columns: id, nim, nama, no_telp, jenis_kelamin, email, password, level, program_studi, angkatan, alamat, is_profile_complete, created_at, updated_at

-- Check nullable fields
SELECT COLUMN_NAME, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'users' AND TABLE_SCHEMA = 'peminjaman_lab';
```

### Check Laboratorium Table

```sql
DESC laboratorium;

-- Expected new columns: status, photo_lab

-- Check enum values
SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'laboratorium' AND COLUMN_NAME = 'status';
```

### Check Alat Table

```sql
DESC alat;

-- Expected changes:
-- - lab_id (FK)
-- - kategori as ENUM
-- - NO lokasi column
-- - NO jumlah column (replaced by status_peminjaman)

-- Verify FK
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'alat' AND COLUMN_NAME = 'lab_id';
```

### Check New Tables

```sql
-- Check jadwal table
DESC jadwal;

-- Check documents table
DESC documents;

-- Verify FKs
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME IN ('jadwal', 'documents');
```

### Check Peminjaman Table

```sql
DESC peminjaman;

-- Expected new columns:
-- - durasi_jam
-- - kondisi_pengembalian
-- - catatan_kerusakan

-- Verify structure
SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'peminjaman' ORDER BY ORDINAL_POSITION;
```

---

## Data Migration & Mapping (if needed)

Jika production environment sudah punya data, perlu mapping:

### Map Old Alat to New Structure

```sql
-- Backup old alat data
CREATE TABLE alat_backup AS SELECT * FROM alat;

-- Map existing alat ke lab_id
-- Misal: semua alat existing dipindahkan ke lab pertama
UPDATE alat SET lab_id = 1 WHERE lab_id IS NULL;

-- Set kategori default untuk existing data
UPDATE alat SET kategori = 'Lainnya' WHERE kategori IS NULL;

-- Set status_peminjaman dari jumlah (optional logic)
ALTER TABLE alat ADD COLUMN status_peminjaman_temp ENUM('tersedia', 'tidak_tersedia');
UPDATE alat SET status_peminjaman_temp = IF(jumlah > 0, 'tersedia', 'tidak_tersedia');
UPDATE alat SET status_peminjaman = status_peminjaman_temp;
ALTER TABLE alat DROP COLUMN status_peminjaman_temp;

-- Verify mapping
SELECT id, kode_alat, nama_alat, lab_id, kategori, status_peminjaman FROM alat LIMIT 10;
```

### Map Old Users to New Structure

```sql
-- Backup old users data
CREATE TABLE users_backup AS SELECT * FROM users;

-- Set profile complete untuk existing users (karena sudah ada data)
UPDATE users SET is_profile_complete = true WHERE id > 0;

-- Verify mapping
SELECT id, nim, nama, no_telp, jenis_kelamin, is_profile_complete FROM users LIMIT 10;
```

---

## Health Check After Migration

```bash
# Clear cache
php artisan cache:clear

# Clear config
php artisan config:clear

# Clear routes
php artisan route:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache

# Test artisan
php artisan tinker
>>> User::count()  // Should return user count
>>> Laboratorium::count()  // Should return lab count
>>> Alat::count()  // Should return alat count
>>> exit
```

---

## Troubleshooting

### Error: "Column doesn't exist"

```
Cause: Migration failed partway through
Solution: 
1. Check migrate:status to see which batch failed
2. Rollback: php artisan migrate:rollback --step=1
3. Fix issue (usually foreign key constraint)
4. Re-run migration
```

### Error: "FOREIGN KEY constraint fails"

```
Cause: Table reference doesn't exist yet
Solution:
1. Ensure migrations run in correct order
2. Check if referenced table exists
3. Rollback and re-run migrations in correct order
```

### Error: "Duplicate entry"

```
Cause: Unique constraint violation
Solution:
1. Check existing data for duplicates
2. Backup and clean duplicate data
3. Re-run migration
```

### Laravel Cache Issues

```
Clear all caches:
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

Rebuild caches:
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Post-Migration Tasks

- [ ] Verify all tables created correctly
- [ ] Test sign-up with new validation
- [ ] Test profile completion flow
- [ ] Test lab browsing with status filter
- [ ] Verify relationships in tinker/code
- [ ] Run existing tests
- [ ] Update documentation if needed
- [ ] Deploy to staging/production
- [ ] Monitor for errors in logs

---

## Rollback Procedure (if critical issue)

```bash
# 1. Stop application
# 2. Restore from backup
mysql -u root peminjaman_lab < backup_2025_11_20.sql

# 3. Or rollback migrations
php artisan migrate:rollback --step=6

# 4. Verify
php artisan migrate:status

# 5. Clear cache
php artisan cache:clear

# 6. Restart application
```

---

## Timeline Estimate

- Pre-migration: 5 minutes
- Fresh migration (Option 1): 2-5 minutes
- Incremental migration (Option 2): 10-15 minutes
- Verification: 10 minutes
- Total: ~25-30 minutes downtime (for Option 2)

---

**Generated**: November 20, 2025  
**Status**: Ready for Execution  
**Test Environment**: ✅ Ready  
**Production Environment**: ⏳ Awaiting execution approval
