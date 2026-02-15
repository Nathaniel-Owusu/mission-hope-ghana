# Mission Hope SDA Church Website

Welcome to the Mission Hope SDA Church website project.

## Project Structure

This is a PHP/MySQL based website designed to be hosted on shared hosting platforms like Hostinger.

- **Frontend**: HTML5, TailwindCSS (CDN), Vanilla JavaScript.
- **Backend**: PHP 7.4+.
- **Database**: MySQL.

## Local Development Setup (XAMPP)

1.  Clone this repository into your `htdocs` folder (e.g., `C:\xampp\htdocs\mission-hope`).
2.  Start Apache and MySQL in XAMPP Control Panel.
3.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
4.  Create a new database named `missionhope`.
5.  Import the `database.sql` file located in the root of this project into the new database.
6.  Open `admin/db.php` and ensure the local database credentials are correct:
    ```php
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "missionhope";
    ```
7.  Visit `http://localhost/mission-hope` in your browser.

## Deployment to Hostinger (or Shared Hosting)

To deploy this site to a live server:

### 1. Database Setup

1.  Log in to your hosting control panel (cPanel/hPanel).
2.  Go to **MySQL Databases** and create a new database.
3.  Create a strict database user and password, and assign the user to the database with full privileges.
4.  Go to **phpMyAdmin**, select your new database, and click **Import**.
5.  Upload the `database.sql` file.

### 2. File Upload

1.  Use an FTP client (FileZilla) or the hosting File Manager.
2.  Upload the contents of this folder to `public_html`.
3.  **Exclude** the following files if possible (not critical, but cleaner):
    - `.git/` folder
    - `.gitignore`
    - `README.md`
    - `*.bak` files

### 3. Configuration

1.  On the live server, edit the file `admin/db.php`.
2.  Uncomment the "Live Server" section and update the credentials:
    ```php
    // Live Server Configuration
    $server = "localhost";
    $username = "your_hosting_db_user";
    $password = "your_hosting_db_password";
    $dbname = "your_hosting_database_name";
    ```
3.  Save the changes.

## Admin Access

- The admin dashboard is located at `/admin`.
- Default credentials (change after first login!):
  - **Username**: `admin`
  - **Password**: `missionhope2024`

## Technologies Used

- **Tailwind CSS**: Used via CDN for styling.
- **Ionicons**: For icons.
- **PHP**: Server-side logic.
- **MySQL**: Database.

---

&copy; 2026 Mission Hope SDA Church
