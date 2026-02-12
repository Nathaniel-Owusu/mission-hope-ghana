HOSTING INSTRUCTIONS FOR MISSION HOPE WEBSITE
===============================================

1. PREPARE YOUR DATABASE
   - Log in to your hosting control panel (e.g., cPanel, hPanel).
   - Go to "MySQL Databases" and create a new database (e.g., `u123_missionhope`).
   - Create a new database user and password.
   - Add the user to the database with ALL PRIVILEGES.
   - Go to "phpMyAdmin", select your new database, and click "Import".
   - Upload the `database.sql` file included in this folder.

2. CONFIGURE CONNECTION
   - Open the file `admin/db.php`.
   - You will see a section commented out for "Live Server".
   - Uncomment those lines (remove /* and */) and enter your new database details:
     $server = "localhost"; 
     $username = "YOUR_DB_USER"; 
     $password = "YOUR_DB_PASSWORD"; 
     $dbname = "YOUR_DB_NAME";

3. UPLOAD FILES
   - Use an FTP client (like FileZilla) or your hosting's File Manager.
   - Upload all files and folders from `mission hope` to `public_html`.
   - EXCLUDE the following files for security/cleanliness:
     - database.sql (You already imported it)
     - debug_db.php
     - fix_db.php
     - index.html.bak
     - any .git folders

4. PERMISSIONS
   - Ensure the `gallery_uploads` folder has write permissions so you can upload images from the admin panel.
   - Usually, permissions should be 755. If uploads fail, try 777 temporarily.

5. ADMIN LOGIN
   - Go to `yourdomain.com/admin/login.php`
   - Default Credentials:
     Username: admin
     Password: missionhope2024
   - IMPORTANT: Edit `admin/login.php` to change this password immediately after uploading!

6. PHP VERSION
   - Ensure your hosting is running PHP 7.4 or higher (PHP 8.0+ recommended).

Need Help?
Contact your developer or hosting support if you encounter "500 Internal Server Errors" or "Database Connection Failed".
