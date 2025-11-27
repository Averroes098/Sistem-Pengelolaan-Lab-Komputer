# Authentication Feature Enhancement

This document summarizes the enhancements made to the authentication system to improve security and user experience.

## Summary of Changes

1.  **Improved Password Security**:
    *   The minimum password length has been increased to 8 characters in both the registration and login forms.
    *   Passwords are now securely hashed using `Hash::make()` before being stored in the database, which is a significant security improvement.

2.  **Enhanced Data Integrity**:
    *   A `unique` validation rule has been added to the `nim` field in the registration form to prevent duplicate entries and ensure data consistency.

3.  **Added "Remember Me" Functionality**:
    *   A "Remember Me" checkbox has been added to the login form, allowing users to stay logged in even after closing the browser.
    *   The `authenticate` method in `AuthController` has been updated to handle the "Remember Me" functionality.

These changes improve the security, data integrity, and user experience of the authentication system, making the application more robust and user-friendly.
