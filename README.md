# LocaLetter

> Previously Newsletter Pro

Localetter is a self-hosted mailing list manager that runs on your local XAMPP stack.
No subscriptions, no cloud, no data leaving your hands.

[Download LocaLetter](https://localetter.vercel.app/)

(https://localetter.vercel.app/)

---

## Features

- Bulk email sending
- Mailing list CRUD (insert, read, update, delete)
- Gmail SMTP integration via PHPMailer
- Session-protected dashboard
- Single-admin registration and login flow

---

## Current Stack

- PHP
- MySQL
- HTML, CSS, JavaScript
- PHPMailer (manual file-based integration, no Composer)

---

## App Flow

1. Open index.html
2. Click Let's Go
3. Backend/initialize.php creates database and required tables
4. Register/Login using credentials.
5. On auth success, redirect to Frontend/app.html
6. Dashboard allows list management and bulk mail sending

---

## Database

Current database name: localetter
>Make sure in phpMyAdmin no database exists with name 'localetter'.

Tables created by Backend/initialize.php:

- admin
- list

If you need to change the database name, update these files:

- Backend/initialize.php
- Backend/db.php
- Backend/auth/login.php
- Backend/auth/register.php

---

## Email Configuration

Localetter sends mail from Backend/mail/send.php using Gmail SMTP.

Required setup:

1. Copy secret.example.php to secret.php in the project root.
2. Fill these values in secret.php:
   - MAIL_FROM
   - MAIL_PASSWORD (Gmail App Password)
   - MAIL_NAME
3. Keep secret.php out of Git.

This repository already ignores:

- secret.php
- .vscode/

---

## Local Setup

1. Clone the repository into your XAMPP htdocs folder.
2. Start Apache and MySQL in XAMPP.
3. Open the project in browser (for example /Localetter/index.html).
4. Click Let's Go to initialize DB and follow register/login flow.
5. Add recipients in dashboard and use Send Mail.

---

## Project Structure

- Backend/auth: auth + session guard + logout + session check
- Backend/crud: mailing list CRUD endpoints
- Backend/mail: PHPMailer files + send endpoint
- Frontend: pages, scripts, styles
- secret.example.php: mail credentials template
- secret.php: local credentials file (not tracked)

---

## Important Notes

- Intended for local/self-hosted usage.
- Not production hardened yet.
- Admin password is currently stored as plain text in DB.

---

## License

MIT 2025.
