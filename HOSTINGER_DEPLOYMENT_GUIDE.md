# 🚀 EHRMS HOSTINGER DEPLOYMENT GUIDE
## Single Shared Hosting with hr_ Prefixed Tables

---

## 📋 WHAT'S DIFFERENT

All EHRMS tables now use the `hr_` prefix to organize your shared database:

```
✅ users (default Laravel table - modified)
✅ hr_departments
✅ hr_employees
✅ hr_employee_files
✅ hr_training_topics
✅ hr_trainings
✅ hr_training_attendance
✅ hr_training_surveys
✅ hr_messages
✅ hr_notifications
✅ hr_public_files
```

This keeps your EHRMS data separate from other applications in the same database.

---

## 🎯 HOSTINGER DEPLOYMENT STEPS

### STEP 1: Prepare Your Files

1. **Upload Laravel Project**
   - Connect via FTP/SFTP or File Manager
   - Upload to `public_html` or subdirectory
   - Make sure `.env` file is uploaded

2. **Set Correct Permissions**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

### STEP 2: Database Setup

1. **Create Database via Hostinger Panel**
   - Go to `Databases` → `MySQL Databases`
   - Create new database (e.g., `u123456_ehrms`)
   - Create database user
   - Assign user to database

2. **Update .env File**
   ```env
   APP_NAME="EHRMS LGU Sablayan"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u123456_ehrms
   DB_USERNAME=u123456_ehrms_user
   DB_PASSWORD=your_secure_password
   
   SESSION_DRIVER=database
   QUEUE_CONNECTION=database
   ```

### STEP 3: Run Migrations

**Option A: Via SSH (if available)**
```bash
cd public_html
php artisan migrate --force
php artisan db:seed --force
```

**Option B: Via Hostinger Terminal (File Manager)**
- Open File Manager
- Right-click on project folder → Terminal
- Run commands above

**Option C: Via Remote Script (if SSH not available)**
Create `install.php` in your public folder:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Run migrations
Artisan::call('migrate', ['--force' => true]);
echo "Migrations: " . Artisan::output();

// Run seeder
Artisan::call('db:seed', ['--force' => true]);
echo "Seeder: " . Artisan::output();

echo "✅ Installation complete!";
// Delete this file after running
unlink(__FILE__);
?>
```

Visit: `https://yourdomain.com/install.php`

⚠️ **DELETE install.php immediately after running!**

### STEP 4: Configure Web Server

**For Apache (.htaccess)**

Create/update `public/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**For Hostinger's hPanel:**
1. Go to `Advanced` → `PHP Configuration`
2. Set PHP version to 8.2 or higher
3. Enable required extensions:
   - mbstring
   - openssl
   - pdo_mysql
   - tokenizer
   - xml
   - ctype
   - json

### STEP 5: Set Up Cron Jobs (Optional)

In Hostinger panel → Advanced → Cron Jobs:
```bash
* * * * * cd /home/u123456/public_html && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 TROUBLESHOOTING

### Error: "500 Internal Server Error"

**Solution 1: Check Permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

**Solution 2: Generate App Key**
```bash
php artisan key:generate
```

**Solution 3: Clear All Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Error: "Database Connection Failed"

**Check:**
1. Database name, username, password in `.env`
2. Database user has permissions
3. `DB_HOST=localhost` (not 127.0.0.1)

### Error: "Class not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "SQLSTATE[42S02]: Base table or view not found"

**Run migrations:**
```bash
php artisan migrate --force
```

### Error: "Storage link not found"

```bash
php artisan storage:link
```

---

## 📊 VERIFY INSTALLATION

### Check Database Tables

Login to phpMyAdmin and verify these tables exist:
```
✓ users
✓ hr_departments
✓ hr_employees
✓ hr_employee_files
✓ hr_training_topics
✓ hr_trainings
✓ hr_training_attendance
✓ hr_training_surveys
✓ hr_messages
✓ hr_notifications
✓ hr_public_files
```

### Test Login

Visit your website and login with:
```
Email: hradmin@sablayan.gov.ph
Password: password
```

---

## 🔐 SECURITY CHECKLIST

### Before Going Live:

1. **Change Default Passwords**
   ```sql
   -- Via phpMyAdmin, run:
   UPDATE users SET password = '$2y$12$NEW_HASH_HERE' WHERE email = 'hradmin@sablayan.gov.ph';
   ```

2. **Update .env Security**
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

3. **Hide .env File**
   Add to `.htaccess`:
   ```apache
   <Files .env>
       Order allow,deny
       Deny from all
   </Files>
   ```

4. **Disable Directory Listing**
   ```apache
   Options -Indexes
   ```

5. **Set Up HTTPS**
   - Enable SSL in Hostinger panel
   - Force HTTPS in `.htaccess`

---

## 🗄️ DATABASE MANAGEMENT

### Backup Database (Manual)

Via phpMyAdmin:
1. Select your database
2. Click `Export`
3. Choose `Quick` or `Custom`
4. Download SQL file

### Restore Database

1. Drop all hr_ tables (or entire database)
2. Import SQL backup file via phpMyAdmin

### Automated Backups

Add to cron jobs:
```bash
0 2 * * * mysqldump -u username -ppassword database_name > /home/u123456/backups/backup_$(date +\%Y\%m\%d).sql
```

---

## 📈 PERFORMANCE OPTIMIZATION

### Enable OPcache

In `php.ini` (via hPanel):
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### Cache Routes and Config

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

⚠️ **Run these only in production!**

### Database Indexing

The migrations already include proper indexes, but verify:
```sql
SHOW INDEX FROM hr_employees;
SHOW INDEX FROM hr_trainings;
```

---

## 🚀 POST-DEPLOYMENT TASKS

### 1. Test All Features
- [ ] Login with all role types
- [ ] Create test employee
- [ ] Upload test file
- [ ] Send test message
- [ ] Create test training

### 2. Configure Email (Optional)
Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Set Up File Storage

Create directories:
```bash
mkdir -p storage/app/employee_files
mkdir -p storage/app/training_certificates
mkdir -p storage/app/public_files
chmod -R 775 storage/app
```

### 4. Update Admin Details

Login and update default accounts:
- Change passwords
- Update email addresses
- Add real employee records

---

## 📱 MOBILE OPTIMIZATION

The UI is already mobile-responsive, but verify:
1. Test on mobile devices
2. Check sidebar menu on small screens
3. Test file uploads on mobile
4. Verify forms work with touch

---

## 🔄 UPDATE PROCEDURE

When updating the system:

1. **Backup Everything**
   ```bash
   # Backup database
   mysqldump -u user -p database > backup.sql
   
   # Backup files
   tar -czf backup_files.tar.gz public_html/
   ```

2. **Upload New Files**
   - Via FTP/SFTP
   - Don't overwrite `.env`

3. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

4. **Clear Caches**
   ```bash
   php artisan optimize:clear
   ```

5. **Test Everything**

---

## 📞 COMMON HOSTINGER PATHS

```
Root Directory:       /home/u123456/
Public HTML:          /home/u123456/public_html/
Logs:                 /home/u123456/logs/
PHP ini:              /home/u123456/.php/
```

---

## ✅ CHECKLIST FOR GOING LIVE

- [ ] Database created and configured
- [ ] Migrations run successfully
- [ ] Seeder run (test accounts created)
- [ ] Storage directories created and permissions set
- [ ] .env configured correctly
- [ ] APP_DEBUG=false
- [ ] Default passwords changed
- [ ] SSL certificate installed
- [ ] HTTPS forced
- [ ] Cron jobs set up
- [ ] Backup system configured
- [ ] All roles tested
- [ ] File uploads working
- [ ] Routes optimized (cached)

---

## 🆘 SUPPORT

If you encounter issues:

1. Check `/storage/logs/laravel.log`
2. Check Hostinger error logs in hPanel
3. Verify `.env` configuration
4. Clear all caches
5. Check file permissions

---

## 📚 QUICK COMMANDS REFERENCE

```bash
# Clear everything
php artisan optimize:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reset migrations
php artisan migrate:fresh --seed --force

# Check routes
php artisan route:list

# Generate new app key
php artisan key:generate
```

---

**Deployment Version**: 2.0 (with hr_ prefix)  
**Last Updated**: January 2026  
**Tested On**: Hostinger Single Shared Hosting  
**PHP Version**: 8.2+  
**MySQL Version**: 5.7+
