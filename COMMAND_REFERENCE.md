# 🎯 EHRMS - QUICK COMMAND REFERENCE

## 📦 INITIAL SETUP COMMANDS

```bash
# 1. Configure .env file first!
# Then run these in order:

# Create database
mysql -u root -p
CREATE DATABASE ehrms_sablayan;
EXIT;

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed

# Create storage link
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

---

## 🔄 RESET DATABASE (IF NEEDED)

```bash
# Fresh start with seed data
php artisan migrate:fresh --seed

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🚀 NEXT: CREATE CONTROLLERS

```bash
# Authentication
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/AccountRequestController

# Dashboard
php artisan make:controller DashboardController

# Employee Management
php artisan make:controller EmployeeController --resource
php artisan make:controller DepartmentController --resource

# 201 Files
php artisan make:controller EmployeeFileController --resource

# Training Management
php artisan make:controller TrainingController --resource
php artisan make:controller TrainingTopicController --resource
php artisan make:controller TrainingAttendanceController

# External Training Requests
php artisan make:controller ExternalTrainingController

# Training Survey
php artisan make:controller TrainingSurveyController

# Messaging
php artisan make:controller MessageController --resource

# Notifications
php artisan make:controller NotificationController

# Public Files
php artisan make:controller PublicFileController --resource

# Reports
php artisan make:controller ReportController
```

---

## 🔧 CREATE REQUESTS (VALIDATION)

```bash
# Employee
php artisan make:request StoreEmployeeRequest
php artisan make:request UpdateEmployeeRequest

# Training
php artisan make:request StoreTrainingRequest
php artisan make:request UpdateTrainingRequest

# File Upload
php artisan make:request UploadFileRequest

# Training Survey
php artisan make:request StoreSurveyRequest

# Message
php artisan make:request SendMessageRequest
```

---

## 📋 CREATE POLICIES (AUTHORIZATION)

```bash
php artisan make:policy EmployeePolicy --model=Employee
php artisan make:policy TrainingPolicy --model=Training
php artisan make:policy EmployeeFilePolicy --model=EmployeeFile
php artisan make:policy MessagePolicy --model=Message
```

---

## 🧪 TESTING COMMANDS

```bash
# Create test database
mysql -u root -p
CREATE DATABASE ehrms_test;
EXIT;

# Run tests
php artisan test

# Create feature tests
php artisan make:test EmployeeTest
php artisan make:test TrainingTest
php artisan make:test AuthenticationTest
```

---

## 🎨 FRONTEND COMPILATION

```bash
# Install dependencies
npm install

# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

---

## 🔍 DEBUGGING COMMANDS

```bash
# Check routes
php artisan route:list

# Check config
php artisan config:show

# Tinker (Laravel console)
php artisan tinker

# Check database connection
php artisan db:show

# View migrations status
php artisan migrate:status
```

---

## 🗃️ USEFUL DATABASE QUERIES (via Tinker)

```php
# Start tinker
php artisan tinker

# Count users by role
User::selectRaw('role, count(*) as count')->groupBy('role')->get();

# Count employees by department
Employee::selectRaw('department_id, count(*) as count')->groupBy('department_id')->get();

# Count trainings by type
Training::selectRaw('type, count(*) as count')->groupBy('type')->get();

# Get upcoming trainings
Training::where('status', 'scheduled')->where('start_date', '>=', now())->get();

# Count unread notifications per user
Notification::selectRaw('user_id, count(*) as count')->where('is_read', false)->groupBy('user_id')->get();
```

---

## 📊 MAINTENANCE COMMANDS

```bash
# Backup database
mysqldump -u root -p ehrms_sablayan > backup_$(date +%Y%m%d).sql

# Restore database
mysql -u root -p ehrms_sablayan < backup_20260113.sql

# Clear old logs
php artisan log:clear

# Optimize application
php artisan optimize
```

---

## 🔐 SECURITY COMMANDS

```bash
# Generate new app key
php artisan key:generate

# Create symbolic link safely
php artisan storage:link

# Clear sensitive caches
php artisan config:clear
php artisan cache:clear
```

---

## 🚀 DEPLOYMENT COMMANDS

```bash
# Production optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set to production mode in .env
APP_ENV=production
APP_DEBUG=false

# Run migrations in production
php artisan migrate --force
```

---

## ⚠️ EMERGENCY COMMANDS

```bash
# If site is broken - clear everything
php artisan optimize:clear

# Reset application
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload

# If still broken - check logs
tail -f storage/logs/laravel.log
```

---

## 📝 NOTES

- Always backup database before major changes
- Run `php artisan optimize:clear` if anything behaves strangely
- Use `php artisan tinker` for quick database testing
- Check `storage/logs/laravel.log` for errors

---

**Quick Start After Fresh Install:**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

Then visit: http://localhost:8000
Login: hradmin@sablayan.gov.ph / password
