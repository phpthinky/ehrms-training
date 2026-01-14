# 🚀 EHRMS - Electronic Human Resource Management System
## Laravel 11 - LGU Sablayan

A complete rebuild of the EHRMS system for Local Government Unit of Sablayan using Laravel 11 and Bootstrap 5.3.

---

## 📋 SYSTEM OVERVIEW

This system manages:
- ✅ Employee Records (201 Files)
- ✅ Training Management (Internal & External)
- ✅ Training Needs Analysis (Annual Survey)
- ✅ Document Management & Submission
- ✅ Internal Messaging
- ✅ In-System Notifications
- ✅ Department Management
- ✅ Role-Based Access Control

---

## 👥 USER ROLES

1. **HR Admin** (HRMO III) - Full system access
2. **Admin Assistant** - Assists in managing records and notifications
3. **Employee** - View own records, request training, upload documents
4. **Guest** - Limited access for external training attendees

---

## 🛠️ INSTALLATION STEPS

### Step 1: Configure Database

Edit your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ehrms_sablayan
DB_USERNAME=root
DB_PASSWORD=
```

### Step 2: Create Database

```bash
mysql -u root -p
CREATE DATABASE ehrms_sablayan;
EXIT;
```

### Step 3: Run Migrations

Copy all migration files from `/home/claude/database/migrations/` to your Laravel project's `database/migrations/` directory, then run:

```bash
php artisan migrate
```

### Step 4: Copy Models

Copy all model files from `/home/claude/app/Models/` to your `app/Models/` directory.

### Step 5: Copy Middleware

Copy middleware files from `/home/claude/app/Http/Middleware/` to your `app/Http/Middleware/` directory.

### Step 6: Register Middleware

Add to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'status' => \App\Http\Middleware\CheckStatus::class,
    ]);
})
```

### Step 7: Seed Database

Copy the seeder from `/home/claude/database/seeders/DatabaseSeeder.php` then run:

```bash
php artisan db:seed
```

### Step 8: Create Storage Symlink

```bash
php artisan storage:link
```

### Step 9: Set Permissions (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
```

---

## 🔐 DEFAULT LOGIN CREDENTIALS

| Role | Email | Password |
|------|-------|----------|
| HR Admin | hradmin@sablayan.gov.ph | password |
| Admin Assistant | assistant@sablayan.gov.ph | password |
| Employee 1 | johndoe@sablayan.gov.ph | password |
| Employee 2 | janesmith@sablayan.gov.ph | password |
| Guest | guest@sablayan.gov.ph | password |

---

## 📊 DATABASE STRUCTURE

### Core Tables

1. **users** - Authentication & basic user info
2. **departments** - Department list
3. **employees** - Full employee records (201 files base)
4. **employee_files** - Document storage (confidential)
5. **training_topics** - Training catalog
6. **trainings** - Internal & external training sessions
7. **training_attendance** - Who attended what
8. **training_surveys** - Annual training needs analysis
9. **messages** - Internal messaging
10. **notifications** - System notifications
11. **public_files** - HR announcements & public documents

---

## 🔄 NEXT STEPS - CONTROLLERS & VIEWS

After database setup, continue with:

### Module 2: Authentication Controllers
- Login with case-insensitive email
- Account approval workflow
- Role-based redirects after login

### Module 3: Employee Management
- CRUD operations
- Search & filter
- Profile view

### Module 4: 201 Files Management
- File upload (PDF, images)
- View/download with permissions
- Missing document tracking

### Module 5: Training Management
- Internal training CRUD
- External training request/approval
- Attendance tracking

### Module 6: Dashboard
- Role-based dashboards
- Live statistics
- Quick actions

### Module 7-11: Remaining Features
- Surveys
- Messaging
- Notifications
- Reports

---

## ⚠️ IMPORTANT NOTES

1. **File Storage**: Configure `config/filesystems.php` for employee_files and public_files
2. **Email**: Email notifications are DISABLED by default (in-system only)
3. **Validation**: Case-insensitive email login prevents duplicate accounts
4. **Security**: All routes will require authentication and role checking

---

## 🚫 EXCLUDED FEATURES (Future Work)

- QR Code Attendance
- Email Notifications
- SMS Notifications
- Online Payments
- Advanced Analytics/Charts
- Payroll Processing
- Leave Management
- Biometric Integration

---

## 📝 FILE STRUCTURE NOTES

```
storage/app/
├── employee_files/     # 201 files (confidential)
├── training_certificates/
├── public_files/       # HR announcements
└── temp/              # Temporary uploads
```

---

## 🔧 TROUBLESHOOTING

### Migration Issues
```bash
php artisan migrate:fresh --seed
```

### Permission Errors
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Class Not Found
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

## 📞 SUPPORT

For issues or questions, contact the development team.

**Built with:**
- Laravel 11
- Bootstrap 5.3
- MySQL 8.0
- PHP 8.2+

---

**Last Updated**: January 2026
**Version**: 1.0.0-MVP
