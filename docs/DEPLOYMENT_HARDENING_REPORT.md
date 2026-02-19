# Deployment Hardening and Structural Improvement Report

## Scope
This document details all implemented improvements, mapped to previous audit tasks, with before/after behavior and code-level comparison.

---

## 1) Configuration and bootstrap hardening

### Problem
- DB credentials and app URL were hardcoded in source files.
- Timezone/config had no centralized bootstrap.

### Changes made
- Added `components/config/app.php`:
  - lightweight `.env` reader
  - centralized config object via `appConfig()`
- Updated `components/processes/db_connection.php` to consume `appConfig()` and fail safely.
- Updated `components/functions/checkLogin.php` to use environment-driven `APP_BASE_URL`.
- Added `.env.example` with required deployment variables.

### Before vs After
- **Before:** Config was scattered and hardcoded.
- **After:** Config is centralized and environment-driven, safer for production promotion.

---

## 2) Password security and migration strategy

### Problem
- Passwords were compared as plaintext.
- New accounts/admin resets wrote plaintext passwords.

### Changes made
- Updated `components/classes/userSession.php` login flow:
  - Uses `password_verify()` for hashed passwords.
  - Supports legacy plaintext records for backward compatibility.
  - Automatically re-hashes legacy plaintext passwords on successful login.
- Updated `components/processes/registrationProcess.php`:
  - New generated passwords are hashed before insert.
- Updated `hrd/components/manageAccountsResetPassword.php`:
  - Reset password now stored as hash.
- Updated `hrd/components/manageAccountsAddAdmin.php`:
  - Default password for new admin/payment users now stored as hash.
- Updated `components/processes/changePassword.php`:
  - Validates old password with hash-aware flow and stores new hash.
- Updated `components/processes/checkPassword.php`:
  - Hash-aware check with legacy fallback.

### Before vs After
- **Before:** plaintext comparison and plaintext DB storage.
- **After:** secure hash verification and hash storage across account lifecycle, with migration compatibility.

---

## 3) SQL injection mitigation for dynamic update column

### Problem
- `hrd/components/changeStatus.php` interpolated untrusted `type` into SQL column expression.

### Changes made
- Added strict allowlist for mutable columns.
- Rejects invalid column requests.
- Keeps value and ID as prepared parameters.
- Added admin-role check and POST method enforcement.

### Before vs After
- **Before:** attacker-controlled column expression possible.
- **After:** only predefined columns can be updated.

---

## 4) Upload security improvements

### Problem
- Upload checks relied on client MIME (`$_FILES['type']`) and deterministic filename.
- Storage path was inside existing asset tree and less strict.

### Changes made
- Updated `hrd/components/uploadScannedAttendanceSheet.php`:
  - role + method checks
  - strict numeric validation for `trainingID`
  - size limit sourced from `UPLOAD_MAX_BYTES`
  - server-side MIME validation with `finfo`
  - randomized output filename suffix
  - dedicated storage path `hrd/storage/scanned_attendance_sheets/`
  - safer directory mode (`0750`)

### Before vs After
- **Before:** easier MIME spoofing / predictable naming.
- **After:** stronger validation and more secure file placement.

---

## 5) XSS output encoding

### Problem
- Notice title/body in `index.php` were rendered without escaping.

### Changes made
- Added reusable escape helper `components/functions/html.php`.
- Updated `index.php` to escape notice title/body before rendering.

### Before vs After
- **Before:** stored XSS vector if malicious DB content appears.
- **After:** encoded output reduces browser script injection risk.

---

## 6) Session + request guard foundation

### Problem
- No consistent helper for endpoint guards and response patterns.

### Changes made
- Added `components/functions/security.php` with:
  - request method guards
  - session auth/role checks
  - JSON helper response function
  - CSRF token generation/verification primitives
- Applied guard usage in high-risk modified endpoints.

### Notes
- CSRF primitives are in place and login flow already uses token issuance + validation.
- Additional full-AJAX token wiring can be rolled out per page progressively.

---

## 7) Deployment hygiene and repo structure

### Problem
- Missing root ignore rules and deployment guide.

### Changes made
- Added `.gitignore` to avoid committing:
  - `.env`
  - `vendor/`
  - runtime-generated uploads/artifacts
- Added `README.md` with deployment configuration and runtime expectations.

### Before vs After
- **Before:** weak deployment guidance and higher risk of sensitive/runtime data commits.
- **After:** clearer deploy path and cleaner production repo behavior.

---

## 8) Compatibility considerations

To minimize breakage with existing AJAX consumers:
- Kept legacy success response strings such as `ok` on updated admin endpoints.
- Preserved endpoint URLs and core control flow.

At the same time, backend hardening is now in place for:
- password storage/verification
- upload controls
- role-guarding
- dynamic SQL safety

---

## Recommended next rollout (phase 2)
1. Enforce CSRF checks on all AJAX state-changing requests after adding shared token injection in all pages.
2. Expand role/ownership checks to every `hrd/components` mutating endpoint.
3. Move all mutable runtime files outside web root and serve via controlled download handlers.
4. Add DB migration script to proactively hash any remaining plaintext passwords.
5. Add CI checks (`php -l`, coding standard, static analysis) and production healthcheck endpoint.

---

## Files changed summary
- `.env.example`
- `.gitignore`
- `README.md`
- `docs/DEPLOYMENT_HARDENING_REPORT.md`
- `components/config/app.php`
- `components/functions/security.php`
- `components/functions/html.php`
- `components/processes/db_connection.php`
- `components/functions/checkLogin.php`
- `login.php`
- `components/processes/loginProcess.php`
- `components/classes/userSession.php`
- `components/processes/changePassword.php`
- `components/processes/checkPassword.php`
- `components/processes/registrationProcess.php`
- `hrd/components/manageAccountsResetPassword.php`
- `hrd/components/manageAccountsAddAdmin.php`
- `hrd/components/changeStatus.php`
- `hrd/components/uploadScannedAttendanceSheet.php`
- `index.php`
