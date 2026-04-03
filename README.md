# KeebMods - VAPT Training Environment

KeebMods is a deliberately vulnerable e-commerce web application built using PHP and MySQL. It is designed specifically for Vulnerability Assessment and Penetration Testing (VAPT) exercises. 

It features a modern, responsive UI to simulate a realistic target environment while intentionally containing critical security flaws for educational purposes.

⚠️ **WARNING:** This application is highly vulnerable. **Do not** host this on a public web server. It should only be run locally or in an isolated testing environment.

## 🎯 Included Vulnerabilities
This application contains the following OWASP Top 10 vulnerabilities:
*   **SQL Injection (SQLi):** Present in the login portal, search bar, and user profile parameters.
*   **Cross-Site Scripting (XSS):** 
    *   *Reflected XSS:* Search functionality.
    *   *Stored XSS:* Product reviews and comment section.
*   **Broken Access Control:** Hidden administrative dashboard accessible without proper role verification.
*   **Insecure Direct Object Reference (IDOR):** Unrestricted access to private user order histories via URL parameter manipulation.
*   **Business Logic Flaw:** Client-side trust issue allowing price manipulation during the checkout process.

## ⚙️ Setup Instructions

To run this environment locally, you will need a local web server environment like [XAMPP](https://www.apachefriends.org/index.html).

1. **Clone the Repository**
   Clone or download this repository into your local web server directory (e.g., `C:\xampp\htdocs\rakso-cloudintern-task`).

2. **Start the Server**
   Open the XAMPP Control Panel and start both the **Apache** and **MySQL** modules.

3. **Database Configuration**
   * Navigate to `http://localhost/phpmyadmin`.
   * Create a new database named exactly `keebmods_db`.
   * Click on the new database, go to the **Import** tab, and upload the `keebmods_db.sql` file included in this repository.
   * Click **Import** at the bottom to build the necessary tables and populate the default data.

4. **Launch the Application**
   Open your browser and navigate to: `http://localhost/rakso-cloudintern-task/index.php`

## 🧪 Default Test Accounts
*   **User:** `test_user` / **Password:** `password123`
*   **Admin:** `admin` / **Password:** `admin123` *(Note: Try to find the admin panel without using this account!)*