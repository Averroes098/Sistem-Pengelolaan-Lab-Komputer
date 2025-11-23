# QUICK REFERENCE - Bug Fixes Testing Guide

## 🎯 Three Issues Fixed

### Issue #1: Admin Laboratorium Route ✅

**What was broken:** Admin → Edit Lab → Error "Route not defined"  
**What was fixed:** Changed redirect to use correct route name `admin.laboratorium.index`  
**Files changed:** `LaboratoriumController.php` (3 methods)

**Quick Test:**

```
1. Login as admin
2. Click Dashboard
3. Edit any laboratorium
4. Click Submit
5. Should redirect to list (no error) ✓
```

---

### Issue #2A: Staff SOP Upload ✅

**What was broken:** Upload SOP tidak berjalan (no implementation)  
**What was fixed:** Implemented full uploadSOP() method with file storage + database record  
**Files changed:** `StafController.php`

**Quick Test:**

```
1. Login as staf
2. Go to Dashboard
3. Click "Upload SOP"
4. Select lab + PDF file
5. Click Submit
6. Check storage/app/public/documents/sop/ for file ✓
7. Check database for document record ✓
```

**What happens:**

- File validated (pdf|doc|docx|txt, max 5MB)
- File stored to `storage/app/public/documents/sop/`
- Database record created with tipe_dokumen='SOP'

---

### Issue #2B: Staff Damage Tracking ✅

**What was broken:** Catat kerusakan tidak bertambah (no implementation)  
**What was fixed:** Implemented full inputKerusakan() method with alat update + damage record  
**Files changed:** `StafController.php`

**Quick Test:**

```
1. Login as staf
2. Go to Dashboard
3. Click "Catat Kerusakan"
4. Select equipment + enter description
5. Click Submit
6. Check database alats table: kondisi='Rusak' ✓
7. Check database documents table for damage record ✓
```

**What happens:**

- Equipment validated (must exist)
- Alat.kondisi updated to 'Rusak'
- Damage report document record created with tipe_dokumen='Laporan Kerusakan'

---

### Issue #3: User Profile Completion ✅

**What was broken:** Lengkapi data profil tidak berjalan (no form, no workflow)  
**What was fixed:** Added complete profile workflow with form, middleware, and conditional logic  
**Files changed:**

- `ProfileController.php` (2 methods)
- `CheckProfileComplete.php` (middleware)
- `routes/web.php`
- `Kernel.php`
- `profile/complete.blade.php` (new view)

**Quick Test:**

```
1. Create test user: test@example.com / password123
2. Set is_profile_complete=0 in database manually
3. Login as test user
4. Auto-redirected to /profile/complete ✓
5. See form: NIM, no_telp, jenis_kelamin (required), plus optional fields
6. Fill form and submit
7. Check database: is_profile_complete=1 ✓
8. Redirected to dashboard ✓
9. Logout and login again
10. Should NOT see profile completion page ✓
11. Click "Edit Profile" → normal update flow ✓
```

**What happens:**

- New users with incomplete profiles auto-redirect to completion form
- Middleware enforces completion before accessing user features
- Form validates required fields: NIM, nomor telepon, jenis kelamin
- After submission, profile marked complete and user gets full access

---

## 📂 Files to Review

**Controllers:**

- `app/Http/Controllers/LaboratoriumController.php` - Check lines 36, 58, 66
- `app/Http/Controllers/StafController.php` - Check uploadSOP() and inputKerusakan() methods
- `app/Http/Controllers/ProfileController.php` - Check completeProfile() and update()

**Middleware:**

- `app/Http/Middleware/CheckProfileComplete.php` - Check route() calls

**Routes:**

- `routes/web.php` - Check profile routes (around lines 63-75)

**Kernel:**

- `app/Http/Kernel.php` - Check $routeMiddleware has 'profile-complete'

**Views:**

- `resources/views/profile/complete.blade.php` - NEW file, check form action

---

## 🔧 Commands to Know

```bash
# Create storage symlink (already done)
php artisan storage:link

# Check storage is linked
ls -la public/storage  # Should show symlink

# Run tests
php artisan test

# Clear cache if needed
php artisan cache:clear
php artisan config:clear
```

---

## 📊 Database Checks

**After SOP Upload:**

```sql
SELECT * FROM documents WHERE tipe_dokumen='SOP' ORDER BY created_at DESC;
```

Should see your uploaded SOP record.

**After Damage Tracking:**

```sql
SELECT * FROM alats WHERE kondisi='Rusak';
SELECT * FROM documents WHERE tipe_dokumen='Laporan Kerusakan' ORDER BY created_at DESC;
```

Should see updated equipment status and damage record.

**After Profile Completion:**

```sql
SELECT id, email, is_profile_complete FROM users WHERE email='test@example.com';
```

Should see is_profile_complete = 1 (true).

---

## 🚨 Troubleshooting

**Admin route still shows error:**

- Clear config cache: `php artisan config:clear`
- Check LaboratoriumController lines 36, 58, 66
- Verify route name in error matches expected format

**SOP file not showing up:**

- Check storage/app/public/documents/sop/ directory exists
- Run `php artisan storage:link` again
- Verify file permissions on storage/ directory

**Profile not redirecting to completion:**

- Check middleware is registered in Kernel.php
- Check user.is_profile_complete=0 in database
- Check middleware is applied to user route group

**Form validation errors not showing:**

- Check $errors variable in blade template
- Verify @csrf and @method('PATCH') in form
- Check form action route name matches

---

## ✅ Pre-Deployment Checklist

- [ ] Issue #1 test passed (admin laboratorium CRUD)
- [ ] Issue #2A test passed (SOP upload)
- [ ] Issue #2B test passed (damage tracking)
- [ ] Issue #3 test passed (profile completion)
- [ ] Storage symlink working (check public/storage exists)
- [ ] Database records created correctly
- [ ] No Laravel errors in logs
- [ ] All redirects working correctly
- [ ] File permissions correct on storage/

---

## 📞 Quick Support

**Problem**: Route not found  
**Solution**: Check route name format (admin.laboratorium.index, not laboratorium.index)

**Problem**: File not saving  
**Solution**: Check storage/ directory exists and is writable, run storage:link

**Problem**: Profile completion not showing  
**Solution**: Check is_profile_complete field = 0, verify middleware registered

**Problem**: Form not submitting  
**Solution**: Check form has @csrf and @method('PATCH'), action route name correct

---

## 🎓 What Each Bug Fix Does

**Issue #1 (Admin):**

- Ensures admin can edit laboratories without route errors
- Makes CRUD operations work correctly with proper redirects

**Issue #2A (SOP Upload):**

- Allows staff to upload and store SOP documents
- Creates audit trail in database
- Makes files accessible via public/storage symlink

**Issue #2B (Damage Tracking):**

- Allows staff to report equipment damage
- Updates equipment status to "Rusak" (broken)
- Creates damage report for documentation

**Issue #3 (Profile Completion):**

- Enforces new users to complete profiles before using system
- Middleware blocks feature access until profile complete
- Provides user-friendly form for data entry

---

**All fixes ready for production testing!** ✅
