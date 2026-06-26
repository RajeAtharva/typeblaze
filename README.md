# TypeBlaze

Typing test web app built with PHP, MySQL, jQuery, HTML, and CSS.

## Run Locally

1. Move the project to your PHP web root.
   XAMPP: `C:\xampp\htdocs\typeblaze`
   WAMP: `C:\wamp64\www\typeblaze`
2. Start Apache and MySQL.
3. Import `database.sql` into MySQL or phpMyAdmin.
4. Open `includes/config.php` and confirm these values:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'atharva@07raje');
define('DB_NAME', 'typeblaze');
define('SITE_URL', 'http://localhost/typeblaze');
```

5. Visit `http://localhost/typeblaze/`

## Default Accounts

- Admin: `admin@typeblaze.io` / `admin123`
- Demo user: `demo@typeblaze.io` / `admin123`

## Main Features

- User signup and login
- Typing test with sample, quote, code, and custom modes
- Saved results for logged-in users
- Dashboard with recent history and stats
- Global leaderboard
- Admin panel for users, results, and typing texts
