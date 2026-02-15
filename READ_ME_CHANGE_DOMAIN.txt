HOW TO CHANGE YOUR DOMAIN NAME IN HOSTINGER
===========================================

OPTION 1: YOU WANT TO REPLACE THE MAIN DOMAIN
1. Log in to Hostinger hPanel.
2. Click on "Hosting" in the top menu.
3. Click "Manage" next to your hosting plan.
4. Look for the "Change Main Domain" option (usually under the "Account" or "Hosting" section sidebar).
   - Note: If you don't see this, you might need to use Option 2.
5. Select your new domain from the dropdown (you must have bought it already).
6. Click "Confirm".
   - Hostinger will automatically update the directory structure. Your files will be moved to the new domain folder.

OPTION 2: ADDING A NEW DOMAIN (AND MOVING FILES)
1. Go to "Websites" in the top menu.
2. Click "Add Website".
3. Select "Skip, I will start from scratch" (since you already have the code).
4. Enter your NEW domain name.
5. Once created, go to "Files" > "File Manager".
6. You will see two folders: `domains/OLD_DOMAIN/public_html` and `domains/NEW_DOMAIN/public_html`.
7. Go to `domains/OLD_DOMAIN/public_html`.
8. Select ALL files -> Right Click -> "Move".
9. Choose the destination: `domains/NEW_DOMAIN/public_html`.
10. Click "Move".

CHECK CONFIGURATION (IMPORTANT)
 After changing the domain, check your `admin/db.php` file.
 - Hostinger database details usually stay the same, but the "localhost" part is correct.
 - If you created a NEW database for the new domain, update the username/password in `admin/db.php`.

FINAL STEP
 - Visit your new domain URL.
 - If it doesn't load immediately, wait 15-30 minutes for DNS propagation.
