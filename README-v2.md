# LGU Sablayan EHRMS v2.0

**Employee Human Resource Management System**  
Municipality of Sablayan, Occidental Mindoro  

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)

---

## 📖 About

LGU Sablayan EHRMS is a comprehensive Human Resource Management System designed for local government units. Manages employee records, training programs, 201 files, and customizable training needs surveys.

**Version:** 2.0  
**Release Date:** January 2026  
**Framework:** Laravel 11.x  
**Database Prefix:** `hr_` (all tables)

---

## ✨ Features

### Core Modules (11)
1. Dashboard - Overview & statistics with TNA widgets
2. Employee Management - Complete 201 files
3. Training Topics - Categorized programs
4. Training Management - Scheduling & tracking
5. Training Attendance - Participation monitoring
6. Training Surveys - Annual needs analysis
7. Survey System - Customizable templates & analytics
8. HR Documents - Policy repository
9. Messaging - Internal communication
10. Notifications - Real-time system alerts
11. Help & Guide - Comprehensive user documentation

### Phase 1: Customizable Survey System ✅ COMPLETE
- **Training Programs CRUD** ✅ Complete
- **Survey Template Builder** ✅ Complete
- **Question Bank** ✅ Complete
- **Dynamic Forms** ✅ Complete
- **Response Analytics** ✅ Complete

---

## 💻 System Requirements

**Server:**
- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.3+
- Apache 2.4+ / Nginx 1.18+
- Composer 2.5+
- Node.js 18+

**PHP Extensions:**
BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, PDO_MySQL, Tokenizer, XML

---

## 📥 Quick Install

```bash
# 1. Clone
git clone https://github.com/your-org/lgu-sablayan-ehrms.git
cd lgu-sablayan-ehrms

# 2. Dependencies
composer install
npm install && npm run build

# 3. Configure
cp .env.example .env
php artisan key:generate

# 4. Database (.env)
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_PREFIX=hr_

# 5. Migrate & Seed
php artisan migrate
php artisan db:seed --class=UsersSeeder

# 6. Storage
php artisan storage:link

# 7. Run
php artisan serve
```

---

## 🔑 Default Credentials

```
HR Admin:
Email: hradmin@sablayan.gov.ph
Password: password

Admin Assistant:
Email: assistant@sablayan.gov.ph
Password: password

Employees:
Email: johndoe@sablayan.gov.ph / janesmith@sablayan.gov.ph
Password: password
```

**⚠️ Change passwords in production!**

---

## 🗄️ Database Configuration

### Important: Database Prefix

All tables use `hr_` prefix:
```
hr_users, hr_employees, hr_departments, hr_trainings, etc.
```

**Configuration** (config/database.php):
```php
'prefix' => env('DB_PREFIX', 'hr_'),
```

**Models - DO NOT include prefix:**
```php
// ✅ CORRECT
protected $table = 'employees';

// ❌ WRONG
protected $table = 'hr_employees';
```

Laravel adds prefix automatically.

---

## 🆕 Phase 1: Customizable Survey System ✅ COMPLETE

### Part 1: Training Programs CRUD ✅

**Features:**
- Add/Edit/Delete programs
- Drag-and-drop reordering
- Active/inactive toggle
- Program codes

**11 Default Programs:**
SDC I/II/III, WRS, CS, BCSS, VOW, GST, RM, CSDC, SWCT

**Access:** `/training-programs`

### Part 2: Survey Template Builder ✅

**Features:**
- Create/Edit/Delete survey templates
- Template versioning
- Active/inactive status
- Template duplication

**Access:** `/survey-templates`

### Part 3: Question Bank ✅

**Features:**
- Question library with multiple types (text, textarea, radio, checkbox, select, date, number, rating)
- Category-based organization
- Required field validation
- Option management for choice-based questions

**Access:** `/question-bank`

### Part 4: Dynamic Form Builder ✅

**Features:**
- Drag-and-drop question ordering
- Real-time form preview
- Employee survey responses
- Auto-save functionality

**Access:** `/survey-responses/create/{template}`

### Part 5: Response Analytics ✅

**Features:**
- Advanced filtering (date range, department)
- Visual analytics with Chart.js
- Response rate tracking
- Export capabilities
- Statistical summaries

**Access:** `/survey-analytics/{template}`

---

## 🐛 Recent Fixes & Enhancements (v2.0)

**Training Bugs:**
- ✅ Start/Complete training buttons fixed
- ✅ Date validation before starting
- ✅ Employee dashboard red alerts
- ✅ Attendance reminders (3-day window)

**Database Issues:**
- ✅ All models updated for `hr_` prefix
- ✅ Foreign keys fixed (hr_users)
- ✅ Column names aligned with migrations
- ✅ UsersSeeder fixed
- ✅ Double prefix bugs fixed (hr_hr_ → hr_)
- ✅ Dashboard SQL query prefix issues resolved

**New Enhancements:**
- ✅ Dashboard widgets (Top Training Department, TNA Recommendations)
- ✅ Reports page with 4 Chart.js visualizations
- ✅ Notification system with real-time UI
- ✅ Model observers for automated notifications
- ✅ Training recommendations page based on TNA
- ✅ Manual filters for survey analytics
- ✅ Welcome email for new employees (with credentials)

---

## 📦 Project Structure

```
app/
├── Http/Controllers/
│   ├── TrainingProgramController.php ⭐ NEW
│   └── ...
├── Models/
│   ├── TrainingProgram.php ⭐ NEW
│   ├── SurveyTemplate.php ⭐ NEW
│   └── ...

database/
├── migrations/
│   ├── 2025_01_18_000001_create_training_programs_table.php ⭐
│   └── ...
├── seeders/
│   ├── UsersSeeder.php (FIXED)
│   ├── TrainingProgramSeeder.php ⭐ NEW
│   └── ...

resources/views/
├── training-programs/ ⭐ NEW
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── ...
```

---

## 🛠️ Development

**Local Setup:**
```bash
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=UsersSeeder
php artisan serve
```

**Clear Caches:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 🚀 Production Deployment

**Checklist:**
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] Change default passwords
- [ ] Configure SSL (HTTPS)
- [ ] Set proper permissions (755/644)
- [ ] Run `npm run build`
- [ ] Cache config: `php artisan config:cache`
- [ ] Enable backups

---

## 📊 Database Backup

**Manual Backup:**
```bash
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
```

**Restore:**
```bash
mysql -u user -p database < backup_20260119.sql
```

---

## 🔒 Security

- Laravel Breeze authentication
- Role-based access control
- Bcrypt password hashing
- CSRF protection
- SQL injection protection (Eloquent)
- File upload validation

**Roles:**
- HR Admin: Full access
- Admin Assistant: Limited admin
- Employee: Personal data
- Guest: Read-only

---

## 📞 Support

**Technical Issues:**  
Email: it@sablayan.gov.ph  
Phone: (043) 123-4567

**Documentation:**  
- [Laravel Docs](https://laravel.com/docs)
- [Bootstrap Docs](https://getbootstrap.com/docs)

---

## 🔄 Version History

### v2.0 (January 2026)
- ✨ Phase 1: Complete Customizable Survey System
  - Training Programs CRUD
  - Survey Template Builder
  - Question Bank Management
  - Dynamic Form Builder
  - Response Analytics with filters
- ✨ Enhanced Dashboard
  - Top Training Department widget
  - TNA Recommendations widget
- ✨ Notification System
  - Real-time notifications UI
  - Model observers (auto-notify)
  - Training recommendations page
- ✨ Reports & Analytics
  - 4 Chart.js visualizations
  - Manual filters (date, department)
- 🐛 Fixed database prefix issues (hr_hr_ → hr_)
- 🐛 Fixed training buttons
- 🐛 Fixed dashboard SQL queries
- 🔧 Updated all seeders
- 📧 Welcome email for new employees
- 📚 Comprehensive documentation

### v1.0 (January 2025)
- ✨ Initial release
- ✨ 9 core modules
- ✨ Basic training survey

---

## 👥 Credits

**Developer:** Harold  
**Client:** LGU Sablayan, Occidental Mindoro  
**Framework:** Laravel 11.x  

---

## 📝 Important Notes

### Using Database Prefix

**Query Builder (Recommended):**
```php
DB::table('employees')->where('status', 'active')->get();
```

**Raw SQL (Include prefix):**
```php
DB::select('SELECT * FROM hr_employees WHERE status = ?', ['active']);
```

### Seeder Commands

```bash
# All seeders
php artisan db:seed

# Specific seeder
php artisan db:seed --class=UsersSeeder

# Fresh migrate + seed
php artisan migrate:fresh --seed
```

---

## 📜 License

Proprietary - LGU Sablayan  
© 2025-2026 Municipality of Sablayan  
All rights reserved

---

**Last Updated:** January 24, 2026
**Version:** 2.0
**Status:** Production Ready (Phase 1 Complete)
