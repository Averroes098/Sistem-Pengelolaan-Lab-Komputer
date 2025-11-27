# Staff Features Refactoring

This document summarizes the refactoring of staff-related features to improve code organization and maintainability.

## Summary of Changes

1.  **Centralized Staff Logic in `StafController`**:
    *   The following methods have been moved from `PeminjamanController` and `DashboardController` to `StafController`:
        *   `stafDashboard`
        *   `validasi`
        *   `approve`
        *   `reject`
        *   `pengembalian`
        *   `konfirmasiPengembalian`
        *   `laporanPeminjaman`
    *   This consolidation ensures that all staff-related logic is located in a single, dedicated controller.

2.  **Updated Routes**:
    *   The staff-related routes in `routes/web.php` have been updated to point to the corresponding methods in `StafController`.
    *   This change ensures that requests are correctly routed to the new controller.

3.  **Cleaned Up Controllers**:
    *   The moved methods have been removed from `PeminjamanController` and `DashboardController`.
    *   This cleanup results in a more organized and maintainable codebase, with a clear separation of concerns.

This refactoring improves the overall structure of the application, making it easier to understand, maintain, and extend in the future.