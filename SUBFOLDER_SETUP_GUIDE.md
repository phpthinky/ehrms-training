# 🚀 EHRMS SUBFOLDER SETUP GUIDE
## Running at democodes.online/hr-training

---

## 📂 FOLDER STRUCTURE

Your server should look like this:

```
public_html/
├── .htaccess                    ← Root htaccess (updated)
├── public/                      ← Your main site
│   └── index.php
├── app/                         ← Your main site files
├── hr-training/                 ← EHRMS Laravel app
│   ├── .htaccess               ← Laravel root htaccess
│   ├── .env                    ← Laravel config
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/                 ← Laravel public folder
│   │   ├── .htaccess          ← Laravel public htaccess
│   │   ├── index.php
│   │   └── (assets)
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   └── vendor/
└── (other folders)
```

---

## 🔧 STEP-BY-STEP SETUP

### Step 1: Update Root .htaccess

Replace your root `public_html/.htaccess` with this:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect hr-training to its public folder
    RewriteCond %{REQUEST_URI} ^/hr-training
    RewriteCond %{REQUEST_URI} !^/hr-training/public
    RewriteRule ^hr-training/(.*)$ /hr-training/public/$1 [L]

    # Handle root URL (https://democodes.online/)
    RewriteRule ^$ public/index.php [L]

    # Handle all other URLs (but not hr-training)
    RewriteCond %{REQUEST_URI} !^/hr-training
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php?url=$1 [L,QSA]
</IfModule>

# Security
Options -Indexes

# Block sensitive folders
RewriteRule ^app/ - [F,L]
RewriteRule ^database/ - [F,L]
RewriteRule ^hr-training/app/ - [F,L]
RewriteRule ^hr-training/database/ - [F,L]
RewriteRule ^hr-training/storage/ - [F,L]
RewriteRule ^hr-training/bootstrap/ - [F,L]

# Block sensitive files
<FilesMatch "\.(sql|db|log|ini|conf|bak|env)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Allow .htaccess files
<Files ".htaccess">
    Order allow,deny
    Allow from all
</Files>
```

### Step 2: Create .htaccess in hr-training/

Create `public_html/hr-training/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 3: Create .htaccess in hr-training/public/

Create `public_html/hr-training/public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 4: Update Laravel .env

Edit `public_html/hr-training/.env`:

```env
APP_NAME="EHRMS LGU Sablayan"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://democodes.online/hr-training

# Database settings
DB_PREFIX=hr_
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Step 5: Update config/app.php

Add this to `hr-training/config/app.php`:

```php
// Add after 'url' => env('APP_URL', 'http://localhost'),
'asset_url' => env('ASSET_URL', '/hr-training'),
```

### Step 6: Set Permissions

```bash
chmod -R 755 hr-training/storage
chmod -R 755 hr-training/bootstrap/cache
```

### Step 7: Create Storage Link

```bash
cd public_html/hr-training
php artisan storage:link
```

### Step 8: Clear Caches

```bash
cd public_html/hr-training
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

---

## 🧪 TEST YOUR SETUP

### Test URLs:

1. **Main Site**: https://democodes.online
   - Should work normally

2. **EHRMS Welcome**: https://democodes.online/hr-training
   - Should show Laravel welcome page or redirect to login

3. **EHRMS Login**: https://democodes.online/hr-training/login
   - Should show login page

4. **EHRMS Dashboard**: https://democodes.online/hr-training/dashboard
   - Should redirect to login if not authenticated
   - Should show dashboard if logged in

---

## 🔍 TROUBLESHOOTING

### Problem: 404 Not Found

**Solution 1: Check .htaccess files**
```bash
# Make sure these files exist:
ls public_html/.htaccess
ls public_html/hr-training/.htaccess
ls public_html/hr-training/public/.htaccess
```

**Solution 2: Check mod_rewrite**
Make sure Apache has `mod_rewrite` enabled.

**Solution 3: Check APP_URL**
In `.env`, make sure:
```env
APP_URL=https://democodes.online/hr-training
```

### Problem: Assets Not Loading (CSS/JS)

**Solution: Update asset URLs in layouts**

In `resources/views/layouts/app.blade.php`, use:
```blade
<!-- Instead of absolute paths -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<!-- Which becomes -->
https://democodes.online/hr-training/css/app.css
```

Laravel's `asset()` helper automatically adds the subfolder.

### Problem: Routes Not Working

**Clear route cache:**
```bash
php artisan route:clear
php artisan config:cache
```

### Problem: 500 Internal Server Error

**Check logs:**
```bash
tail -f hr-training/storage/logs/laravel.log
```

**Check permissions:**
```bash
chmod -R 755 hr-training/storage
chmod -R 755 hr-training/bootstrap/cache
```

---

## 📝 IMPORTANT NOTES

### 1. URLs in Views

Always use Laravel helpers for URLs:

```blade
✅ CORRECT:
{{ route('dashboard') }}
{{ url('dashboard') }}
{{ asset('css/app.css') }}

❌ WRONG:
/dashboard
/hr-training/dashboard
/css/app.css
```

### 2. Redirects in Controllers

Always use route names:

```php
✅ CORRECT:
return redirect()->route('dashboard');
return redirect()->route('login');

❌ WRONG:
return redirect('/dashboard');
return redirect('/hr-training/login');
```

### 3. Forms

Always use route names:

```blade
✅ CORRECT:
<form action="{{ route('login') }}" method="POST">

❌ WRONG:
<form action="/login" method="POST">
<form action="/hr-training/login" method="POST">
```

---

## 🔐 SECURITY CHECKLIST

After setup:

- [ ] `.env` file not accessible via browser
- [ ] `storage/` folder not accessible
- [ ] `database/` folder not accessible
- [ ] `.htaccess` files properly configured
- [ ] `APP_DEBUG=false` in production
- [ ] Strong database password
- [ ] Change default user passwords

---

## 🎯 FINAL FILE LOCATIONS

```
public_html/
├── .htaccess                           ← Root (updated with hr-training rules)
│
├── hr-training/
│   ├── .htaccess                      ← Laravel root
│   ├── .env                           ← APP_URL with /hr-training
│   │
│   └── public/
│       ├── .htaccess                  ← Laravel public
│       ├── index.php
│       └── (CSS, JS, images)
```

---

## ✅ EXPECTED RESULTS

After proper setup:

| URL | Result |
|-----|--------|
| democodes.online | Your main site |
| democodes.online/hr-training | EHRMS welcome/login |
| democodes.online/hr-training/login | Login page |
| democodes.online/hr-training/dashboard | Dashboard (after login) |
| democodes.online/hr-training/employees | Employee list (HR only) |

---

## 🚀 QUICK DEPLOYMENT CHECKLIST

```bash
# 1. Upload Laravel files to hr-training folder
# 2. Update root .htaccess
# 3. Create hr-training/.htaccess
# 4. Create hr-training/public/.htaccess
# 5. Configure .env with correct APP_URL
# 6. Set permissions
chmod -R 755 hr-training/storage
chmod -R 755 hr-training/bootstrap/cache

# 7. Run migrations
cd hr-training
php artisan migrate --force
php artisan db:seed --force

# 8. Create storage link
php artisan storage:link

# 9. Cache configs
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 10. Test
# Visit: https://democodes.online/hr-training
```

---

## 📞 STILL NOT WORKING?

### Check Apache Error Logs
```bash
tail -f /var/log/apache2/error.log
```

### Check Laravel Logs
```bash
tail -f hr-training/storage/logs/laravel.log
```

### Test .htaccess is working
Create a test file:
```bash
echo "<?php phpinfo();" > hr-training/public/test.php
```

Visit: https://democodes.online/hr-training/test.php

If you see PHP info, .htaccess is working!

**Don't forget to delete test.php after testing!**

---

**Version**: Subfolder Setup v1.0  
**Date**: January 14, 2026  
**Subfolder**: /hr-training  
**Domain**: democodes.online
