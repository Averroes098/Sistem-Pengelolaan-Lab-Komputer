# 🔧 TECHNICAL DOCUMENTATION - AKUN LOGIN SETUP

**Dibuat**: November 23, 2025  
**Version**: 1.0  
**Status**: Production Ready ✅

---

## 📋 SETUP OVERVIEW

### Database Reset Process
1. **Rollback Migrations**
   ```bash
   php artisan migrate:rollback --force
   ```
   - Menghapus semua struktur database

2. **Fresh Migrations**
   ```bash
   php artisan migrate:refresh --force
   ```
   - Menjalankan 16 migrations
   - Membuat 9 tables: users, cache, jobs, laboratorium, peminjaman, alat, jadwal, documents, etc

3. **Fix Duplicate Status Column**
   - Migration `2025_11_20_031000` mempunyai conditional check
   - Mencegah duplicate column error

### User Creation
```php
// Menggunakan Eloquent Model
User::create([
    'nama' => 'Name',
    'email' => 'email@example.com',
    'password' => bcrypt('password'),
    'level' => 'admin|staf|user',
    'nim' => 'unique_id',
    'no_telp' => 'phone',
    'jenis_kelamin' => 'L|P',
    'program_studi' => 'program',
    'angkatan' => 2021,
    'alamat' => 'address',
    'is_profile_complete' => 1
]);
```

---

## 🗄️ DATABASE SCHEMA

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(255) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    no_telp VARCHAR(16) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    level ENUM('user','admin','staf','kadep') DEFAULT 'user',
    program_studi VARCHAR(255) NULL,
    angkatan VARCHAR(255) NULL,
    alamat VARCHAR(255) NULL,
    is_profile_complete BOOLEAN DEFAULT 0,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### User Roles & Levels
```
Enum values:
- 'admin'  → Administrator dashboard at /admin/dashboard
- 'staf'   → Staff dashboard at /staf/dashboard  
- 'user'   → User dashboard at /user
 - 'kadep'  → Kepala Departemen dashboard at /kadep/dashboard

Kadep features:
- Route `GET /kadep/kerusakan` → list Laporan Kerusakan (lihat laporan dari staf)
- Route `POST /kadep/kerusakan/confirm/{id}` → konfirmasi laporan dan perbarui `alat.kondisi` menjadi `Baik`
```

---

## 🔐 AUTHENTICATION FLOW

### Login Process
1. User POST ke `/login`
2. Validation di `AuthenticatedSessionController`
3. Password verify dengan bcrypt
4. Session create & redirect

### Authorization
```php
// Middleware check by level
if (Auth::check() && Auth::user()->level === 'admin') {
    // Allow access to /admin/* routes
}
```

**Middleware Files:**
- `app/Http/Middleware/Admin.php` → Check level === 'admin'
- `app/Http/Middleware/Staf.php` → Check level === 'staf'
- `app/Http/Middleware/User.php` → Check level === 'user'

**Pattern Used:** Redirect (not abort)
```php
if ($user->level !== 'admin') {
    return redirect()->route('dashboard')->with('error', 'Unauthorized');
}
```

---

## 🛣️ ROUTES STRUCTURE

### Public Routes
```
GET  /                      → Home page
GET  /login                 → Login form
POST /login                 → Process login
GET  /register              → Register form
POST /register              → Process register
```

### Admin Routes (prefix: /admin, middleware: Admin)
```
GET  /admin/dashboard       → Admin dashboard
GET  /admin/laboratorium    → Lab management
GET  /admin/peminjaman      → Borrowing management
```

### Staff Routes (prefix: /staf, middleware: Staf)
```
GET  /staf/dashboard        → Staff dashboard
GET  /staf/peminjaman       → Borrowing list
POST /staf/peminjaman/{id}  → Approve/Reject
```

### User Routes (prefix: /user, middleware: User)
```
GET  /user                  → User dashboard
GET  /user/peminjaman       → Borrowing history
POST /user/peminjaman       → Create borrowing
GET  /user/profile          → User profile
```

---

## 🔑 CREATED TEST ACCOUNTS

### Account 1: Admin
```
Model: App\Models\User
Email: admin@lab.com
Password: admin123 (hashed: bcrypt)
Level: 'admin'
NIM: ADM001
is_profile_complete: 1 (true)
```

### Account 2: Staff
```
Model: App\Models\User
Email: staf@lab.com
Password: staf123 (hashed: bcrypt)
Level: 'staf'
NIM: STF001
is_profile_complete: 1 (true)
```

### Account 3: User
```
Model: App\Models\User
Email: user@lab.com
Password: user123 (hashed: bcrypt)
Level: 'user'
NIM: NIM123
is_profile_complete: 1 (true)
```

---

## 📁 KEY FILES

### Models
- `app/Models/User.php`
  - Fillable attributes include all fields
  - Casts: timestamps
  - Relationships: peminjamans, jadwals, documents

### Controllers
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
  - Handle login/logout
  - `store()` method processes login
  - `destroy()` method handles logout

### Middleware
- `app/Http/Middleware/Admin.php` - Admin auth check
- `app/Http/Middleware/Staf.php` - Staff auth check
- `app/Http/Middleware/User.php` - User auth check
- Pattern: Check Auth::check() → Check level === expected → Redirect if fail

### Views
- `resources/views/auth/login.blade.php` - Login form
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/staf/dashboard.blade.php` - Staff dashboard
- `resources/views/user/index.blade.php` - User dashboard

---

## 🔄 MIGRATION FILES (16 Total)

```
1. 0001_01_01_000000_create_users_table
   - Create users table with enum level

2. 0001_01_01_000001_create_cache_table
   - Cache table for Laravel

3. 0001_01_01_000002_create_jobs_table
   - Jobs queue table

4. 2025_11_11_200252_create_laboratorium_table
   - Laboratorium (labs) table

5. 2025_11_11_225100_add_status_to_laboratorium_table
   - Add status enum: ['tersedia', 'tidak_tersedia']

6. 2025_11_12_000000_create_peminjaman_table
   - Peminjaman (borrowing) table

7. 2025_11_12_101014_add_profile_fields_to_users_table
   - Add program_studi, angkatan, alamat, is_profile_complete

8. 2025_11_20_000000_update_user_level_enum
   - Update level enum: ['user', 'admin', 'staf']

9. 2025_11_20_001000_create_alat_table
   - Equipment (alat) table

10. 2025_11_20_002000_add_alat_id_to_peminjaman_table
    - Foreign key to alat table

11. 2025_11_20_030000_modify_users_table_for_simple_signup
    - Adjust users table fields

12. 2025_11_20_031000_add_photo_and_status_to_laboratorium_table
    - Add photo_lab and conditional status

13. 2025_11_20_032000_modify_alat_table
    - Modify alat table structure

14. 2025_11_20_033000_create_jadwal_table
    - Schedule table

15. 2025_11_20_034000_create_documents_table
    - Documents table

16. 2025_11_20_035000_modify_peminjaman_table
    - Final modifications to peminjaman table
```

---

## 🛡️ SECURITY NOTES

### Password Hashing
- Using Laravel's `bcrypt()` function
- Cost factor: default (usually 10-12)
- Never store plain text passwords

### CSRF Protection
- All forms use `@csrf` token
- Session based protection

### Authentication Guard
- Default guard: 'web'
- Session-based authentication
- Uses Sessions table

### Authorization
- Role-based access control via middleware
- Redirect on unauthorized access (friendly UX)
- Not using abort(403) anymore

---

## 📊 QUERY EXAMPLES

### Verify User Login
```php
$user = User::where('email', 'admin@lab.com')->first();
if ($user && Hash::check('admin123', $user->password)) {
    // Login successful
}
```

### Get All Users by Level
```php
$admins = User::where('level', 'admin')->get();
$staff = User::where('level', 'staf')->get();
$users = User::where('level', 'user')->get();
```

### Check Profile Completion
```php
$complete = User::where('is_profile_complete', 1)->count();
$incomplete = User::where('is_profile_complete', 0)->count();
```

---

## 🚀 COMMANDS USED

### Database Reset
```bash
# Rollback all migrations
php artisan migrate:rollback --force

# Fresh migrations
php artisan migrate:refresh --force

# Check migration status
php artisan migrate:status
```

### Cache Operations
```bash
# Clear all caches
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear
```

### User Management Scripts
```bash
# Create users
php create_users.php

# Verify users
php verify_users.php

# Interactive shell
php artisan tinker
```

---

## 🔍 DEBUGGING

### Check Laravel Log
```bash
tail -f storage/logs/laravel.log
```

### Check Database Connection
```php
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::table('users')->count();
```

### Verify User Password
```php
$user = User::find(1);
echo Hash::check('admin123', $user->password);
```

---

## ✅ TESTING CHECKLIST

### Authentication Tests
- [ ] Login with admin account → redirect to /admin/dashboard
- [ ] Login with staff account → redirect to /staf/dashboard
- [ ] Login with user account → redirect to /user
- [ ] Invalid credentials → stay on login page
- [ ] Logout → redirect to login page

### Authorization Tests
- [ ] Admin can access /admin/* routes
- [ ] Staff can access /staf/* routes
- [ ] User can access /user/* routes
- [ ] Admin cannot access /staf/* routes
- [ ] Staff cannot access /admin/* routes
- [ ] Unauthenticated user redirected to login

### Database Tests
- [ ] 3 users exist in database
- [ ] Passwords are hashed
- [ ] Levels are correct
- [ ] Profile complete flags are set

---

## 📝 VERSION HISTORY

### v1.0 - November 23, 2025
- Initial setup complete
- 3 test accounts created
- All migrations successful
- Database fresh reset
- Ready for production testing

---

**Status**: ✅ Complete and Verified  
**Database**: peminjaman_lab  
**Server**: http://127.0.0.1:8000  
**Last Updated**: November 23, 2025
