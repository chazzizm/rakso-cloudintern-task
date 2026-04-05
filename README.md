# KeebMods - VAPT Training Environment

Welcome to KeebMods, an intentionally vulnerable e-commerce web application built for Vulnerability Assessment and Penetration Testing (VAPT) exercises. 

## 🛠️ Setup Instructions
1. Clone or download this repository.
2. Move the `rakso-cloudintern-task` folder into your XAMPP `htdocs` directory.
3. Open XAMPP and start **Apache** and **MySQL**.
4. Navigate to `http://localhost/phpmyadmin`.
5. Create a new database named `keebmods_db`.
6. Import the included `keebmods_db.sql` file into the new database.
7. Access the site at `http://localhost`.

## 🎯 VAPT Scope (Level 2)
This application contains several realistic web vulnerabilities.

**Target Areas:**
*   **Authentication & Database:** Test for SQL Injection (Note: Error reporting has been disabled. You may need to rely on Blind SQLi techniques).
*   **Session & Access Control:** Attempt to access privileged administrative functions without proper authorization (Broken Access Control).
*   **Input Validation:** Test for Cross-Site Scripting (XSS). Note: Basic filters are in place, requiring payload evasion techniques.
*   **Data Exposure:** Look for Insecure Direct Object Reference (IDOR) vulnerabilities. Pay attention to how user data is passed in URLs.
*   **Business Logic:** Attempt to manipulate e-commerce functions (e.g., checkout totals).

## 🔐 Default Test Accounts

**Admin Account**
*   **Username:** `keeb_admin`
*   **Password:** `SuperSecretAdmin123!`

**Standard Customer Account**
*   **Username:** `test_user`
*   **Password:** `password123`

---
*Disclaimer: This project is strictly for educational and authorized testing purposes within a closed local environment.*