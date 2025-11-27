# Peminjaman User Feature Enhancement

This document summarizes the enhancements made to the user-side lab borrowing feature. The main goal was to provide users with more flexibility by allowing them to specify a return date.

## Summary of Changes

1.  **Added 'Tanggal Kembali' (Return Date) Field**:
    *   A new date input field for `tanggal_kembali` was added to the `resources/views/user/peminjaman/create.blade.php` form.
    *   This allows users to select a return date for the lab they are borrowing.

2.  **Updated Controller Logic**:
    *   The `storeUser` method in `app/Http/Controllers/PeminjamanController.php` was updated to handle the new `tanggal_kembali` field.
    *   Validation rules were added to ensure that `tanggal_kembali` is a required field and that it is on or after the `tanggal_pinjam` (loan date).
    *   The `Peminjaman` model creation logic was updated to use the `tanggal_kembali` value from the request.

3.  **Improved User Experience with JavaScript**:
    *   A JavaScript snippet was added to `resources/views/user/peminjaman/create.blade.php` to dynamically set the minimum selectable date for `tanggal_kembali` to the value of `tanggal_pinjam`. This prevents users from selecting an invalid date range.
    *   The `jam_selesai` (end time) custom validity is now reset when `jam_mulai` (start time) changes, ensuring that the validation is re-evaluated correctly.

These changes improve the functionality and usability of the lab borrowing feature for users, making the application more flexible and user-friendly.