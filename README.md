---

# 🌟 Community Sign-Up Portal

A modern, lightweight, single-file PHP & MySQL application for user registration and member directory listing. Features responsive styling, server-side validation, secure PDO prepared statements, and XSS protection.

---

## ✨ Features

- **Modern & Responsive UI**: Clean card-based design with gradients, smooth transitions, and mobile responsiveness using the Inter font.
- **Server-Side Validation**:
  - `Name` (required)
  - `City` (required)
  - `Age` (optional; validates as a non-negative integer)
- **Secure Database Operations**:
  - Uses **PHP Data Objects (PDO)** with parameterized prepared statements to prevent SQL Injection.
  - Safe output escaping with `htmlspecialchars` to prevent Cross-Site Scripting (XSS).
- **Interactive Feedback**:
  - Success banner greeting new members by name and city.
  - Error alert list when input validation fails.
- **Dynamic Community Table**:
  - Displays registered members in reverse chronological order.
  - Real-time member count badge.
  - Empty state message when no members have signed up yet.

---

## 📋 Requirements

- **PHP**: Version 7.4 or higher (with `pdo_mysql` extension enabled)
- **MySQL / MariaDB**: Version 5.7+ or MariaDB 10.3+
- **Web Server**: Apache, Nginx, or PHP's built-in development server

---

## 🗄️ Database Setup

1. Open your MySQL client (e.g., MySQL Workbench, phpMyAdmin, or command-line client).
2. Run the following SQL script to create the database and table:

```sql
CREATE DATABASE IF NOT EXISTS myapp 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE myapp;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NULL,
    city VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## ⚙️ Configuration

If your MySQL credentials differ from the defaults (`root` with no password), update the PDO connection block near the top of the PHP file:

```php
$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';
$username = 'root';      // Your MySQL username
$password = '';          // Your MySQL password

$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);
```

---

## 🚀 Getting Started

### Option 1: PHP Built-in Server (Quickest)

1. Save the file as `index.php` in a project folder.
2. Open a terminal in that folder and run:
   ```bash
   php -S localhost:8000
   ```
3. Visit `http://localhost:8000` in your web browser.

### Option 2: Using Local Stacks (XAMPP / WampServer / Laragon)

1. Move your file (e.g., `index.php`) into your web root:
   - **XAMPP**: `C:\xampp\htdocs\myapp\index.php`
   - **WampServer**: `C:\wamp64\www\myapp\index.php`
   - **Laragon**: `C:\laragon\www\myapp\index.php`
2. Ensure both **Apache** and **MySQL** services are started.
3. Open your browser and navigate to `http://localhost/myapp/`.

---

## 📁 File Structure

```text
.
├── index.php         # Application logic, processing, and HTML/CSS view
├── schema.sql        # Database creation script (optional)
└── README.md         # Project documentation
```

---

## 🔒 Security Best Practices Implemented

- **SQL Injection Prevention**: Uses `$pdo->prepare()` and bound execution parameters.
- **XSS Protection**: User-supplied values are escaped via `e()` (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) before rendering into the DOM.
- **Strict Error Handling**: PDO error mode configured to throw exceptions (`PDO::ERRMODE_EXCEPTION`).

---

## 💡 Recommended Future Enhancements

- [ ] Add **CSRF (Cross-Site Request Forgery)** token verification for form submissions.
- [ ] Implement **pagination** for the community members table for large datasets.
- [ ] Extract database credentials into a separate `.env` or configuration file.
- [ ] Add update and delete (CRUD) capabilities with authentication.
```
