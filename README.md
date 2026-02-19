# HRD Hub

## Deployment-ready baseline

This repository now includes:

- Environment-driven configuration via `.env` values (`components/config/app.php`)
- Hardened DB bootstrap (`components/processes/db_connection.php`)
- Password hashing (`password_hash` / `password_verify`) with legacy plaintext migration support
- Admin endpoint hardening (role checks + stricter validation)
- Improved upload validation and safer storage path for scanned attendance PDFs
- Output escaping helper usage on notice rendering

## Environment variables

Copy `.env.example` to `.env` and set values:

- `APP_BASE_URL`
- `APP_TIMEZONE`
- `DB_HOST`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`
- `UPLOAD_MAX_BYTES`

## Runtime notes

- Ensure PHP extensions include: `mysqli`, `fileinfo`
- Uploaded scanned attendance files are stored under: `hrd/storage/scanned_attendance_sheets/`
- Do not use default passwords in production; rotate all seeded/reset credentials.
