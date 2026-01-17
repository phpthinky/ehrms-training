# 🏛️ EHRMS - Employee Human Resource Management System

A comprehensive Laravel-based Human Resource Management System designed for government offices, specifically developed for LGU Sablayan, Occidental Mindoro.

![Laravel](https://img.shields.io/badge/Laravel-11.45.1-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![License](https://img.shields.io/badge/License-MIT-green)
![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen)

---

## 📋 Table of Contents

- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Usage](#-usage)
- [User Roles](#-user-roles)
- [Modules](#-modules)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

---

## ✨ Features

### Core Modules

#### 👥 Employee Management
- Complete employee records (1,000+ capacity)
- Department organization
- Rank level classification (Higher/Normal)
- Status tracking (Active/Inactive)
- Position and contact management
- User account linking

#### 🎓 Training Management
- Internal & external training tracking
- Rank-level restrictions
- Training topics & categories
- Venue and date management
- Training status monitoring
- Certificate management

#### 📊 Training Attendance System
- Employee enrollment with eligibility filters
- Attendance tracking (Attended/Absent/Pending)
- Certificate upload & download
- Bulk operations (mark all attended)
- Participant notifications
- Attendance statistics & exports

#### 📁 Employee 201 Files Management
- 11 document categories:
  - Personal Data Sheet (PDS)
  - Service Record
  - Transcript of Records (TOR)
  - Diploma
  - Certificates (Training/Seminar)
  - NBI Clearance
  - Medical Certificate
  - Tax Identification (TIN)
  - Birth Certificate
  - Marriage Certificate
  - Other Documents
- Organized by employee number
- Upload/download/delete operations
- Completion tracking
- Role-based access control

#### 📝 Training Needs Survey System
- Annual employee assessment
- Select 1-5 training topics
- Preferred schedule & format
- Additional suggestions
- HR analytics dashboard
- Topic popularity rankings
- Response rate tracking
- Duplicate prevention

#### 👤 Employee Self-Service Portal
- My Profile (personal & employment info)
- My Training History
- My 201 Files
- Certificate downloads
- Training survey submission
- Quick statistics dashboard

#### 🔐 Security & Access Control
- Role-based permissions (HR Admin, Admin Assistant, Employee)
- Authentication system
- Session management
- File access restrictions
- Secure document handling

#### 📱 User Interface
- Professional Bootstrap 5.3 design
- Responsive layout (mobile-friendly)
- Expandable sidebar navigation
- Color-coded status badges
- Interactive dashboards
- Real-time statistics

---

## 💻 System Requirements

### Server Requirements
- PHP >= 8.2
- MySQL >= 8.0 or MariaDB >= 10.3
- Apache/Nginx web server
- Composer >= 2.0

### PHP Extensions Required
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- cURL
- GD (for image processing)
- ZIP
```

### Recommended Server Specs
- **RAM:** 2GB minimum, 4GB recommended
- **Storage:** 10GB minimum
- **CPU:** 2 cores minimum

### Shared Hosting Compatibility
✅ **Hostinger Compatible** - Optimized for single shared hosting
- Uses `public/uploads/` directory structure
- `.htaccess` security measures
- No symlink requirements
- Authentication-based access control

---

## 🚀 Installation

### Step 1: Clone Repository

```bash
git clone https://github.com/yourusername/ehrms.git
cd ehrms
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 5: Run Migrations

```bash
# Run all migrations
php artisan migrate

# Seed database with sample data (optional)
php artisan db:seed
```

### Step 6: Create Storage Directories

```bash
# Create upload directories
mkdir -p public/uploads/employee_files
mkdir -p public/uploads/training_certificates
mkdir -p public/uploads/hr_documents

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache public/uploads
```

### Step 7: Configure .htaccess Security

Create `public/uploads/.htaccess`:

```apache
# Prevent PHP execution
<FilesMatch "\.php$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Prevent directory listing
Options -Indexes

# Force download for certain file types
<FilesMatch "\.(pdf|doc|docx|jpg|jpeg|png)$">
    Header set Content-Disposition attachment
</FilesMatch>
```

### Step 8: Create Admin User

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'username' => 'admin',
    'email' => 'admin@lgu.gov.ph',
    'password' => Hash::make('password'),
    'role' => 'hr_admin',
    'status' => 'active'
]);
```

---

## ⚙️ Configuration

### Application Settings

Edit `config/app.php`:

```php
'name' => 'EHRMS - LGU Sablayan',
'timezone' => 'Asia/Manila',
'locale' => 'en',
```

### File Upload Settings

Edit `config/filesystems.php` or `.env`:

```env
# Maximum file sizes
UPLOAD_MAX_FILESIZE=10M
POST_MAX_SIZE=10M
```

### Mail Configuration (Optional)

For email notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lgu.gov.ph
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🗄️ Database Setup

### Database Tables (19 Total)

#### Core Tables
- `users` - User accounts
- `hr_employees` - Employee records
- `hr_departments` - Department list

#### Training Tables
- `hr_trainings` - Training programs
- `hr_training_topics` - Training categories
- `hr_training_attendance` - Attendance records

#### File Management Tables
- `hr_employee_files` - 201 files
- `hr_documents` - HR secure documents

#### Survey Tables
- `hr_training_surveys` - Annual needs assessment

#### Communication Tables
- `hr_messages` - Internal messaging
- `hr_notifications` - System notifications

### Running Migrations

```bash
# Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Roll back last migration
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

### Database Seeders

Available seeders:
- `DepartmentSeeder` - Sample departments
- `EmployeeSeeder` - Sample employees
- `TrainingTopicSeeder` - Training categories
- `UserSeeder` - Sample users

Run specific seeder:
```bash
php artisan db:seed --class=DepartmentSeeder
```

---

## 📖 Usage

### Accessing the System

**Local Development:**
```
http://localhost/hr-training/public
```

**Production (Hostinger):**
```
https://democodes.online/hr-training/public
```

### Default Login Credentials

After seeding:

**HR Admin:**
- Username: `admin`
- Password: `password`

**Employee:**
- Username: `employee`
- Password: `password`

**⚠️ IMPORTANT:** Change default passwords immediately in production!

---

## 👥 User Roles

### HR Admin (`hr_admin`)

**Full system access:**
- ✅ Manage employees
- ✅ Manage departments
- ✅ Create/edit trainings
- ✅ Manage attendance
- ✅ Upload/delete all files
- ✅ View survey results
- ✅ Access all reports
- ✅ Manage HR documents

### Admin Assistant (`admin_assistant`)

**Same as HR Admin**
- Full administrative capabilities

### Employee (`employee`)

**Self-service access:**
- ✅ View own profile
- ✅ View training history
- ✅ Download own certificates
- ✅ Access own 201 files
- ✅ Submit training survey
- ✅ View notifications
- ❌ Cannot access other employees' data
- ❌ Cannot upload/delete files

---

## 📦 Modules

### 1. Employee Management

**Location:** `Employees` menu

**Features:**
- Add new employee
- Edit employee details
- View employee profile
- Manage departments
- Track employment status

**Routes:**
```
GET  /employees          - List all employees
GET  /employees/create   - Create form
POST /employees          - Store new employee
GET  /employees/{id}     - View employee
GET  /employees/{id}/edit - Edit form
PUT  /employees/{id}     - Update employee
DELETE /employees/{id}   - Delete employee
```

### 2. Training Management

**Location:** `Trainings` menu

**Features:**
- Create training programs
- Set rank-level restrictions
- Assign training topics
- Manage venues & schedules
- Track training status

**Routes:**
```
GET  /trainings          - List trainings
GET  /trainings/create   - Create form
POST /trainings          - Store training
GET  /trainings/{id}     - View details
GET  /trainings/{id}/edit - Edit form
PUT  /trainings/{id}     - Update training
DELETE /trainings/{id}   - Delete training
```

### 3. Training Attendance

**Location:** Training details → Attendance tab

**Features:**
- Add participants
- Mark attendance status
- Upload certificates
- Bulk operations
- Send notifications
- Export reports

**Key Routes:**
```
POST /trainings/{id}/attendance                     - Add participant
PUT  /trainings/attendance/{id}/status              - Update status
POST /trainings/attendance/{id}/certificate         - Upload certificate
GET  /trainings/attendance/{id}/certificate/download - Download certificate
POST /trainings/{id}/attendance/mark-all-attended   - Bulk mark attended
POST /trainings/{id}/attendance/notify              - Send notifications
```

### 4. Employee 201 Files

**Location:** 
- HR: Employees → 201 Files
- Employee: My 201 Files

**Features:**
- Upload documents
- Categorize files (11 categories)
- Download files
- Track completion
- Delete files (HR only)

**Routes:**
```
GET  /employees/{id}/files        - View files
GET  /employees/{id}/files/create - Upload form
POST /employees/{id}/files        - Store file
GET  /files/{id}/download         - Download file
DELETE /files/{id}                - Delete file (HR only)
GET  /my-files                    - Employee redirect
```

### 5. Training Survey

**Location:** Training Survey menu

**Features:**
- Annual employee survey
- Select 1-5 topics
- Suggest additional topics
- Set preferences
- View results (HR only)
- Analytics dashboard

**Routes:**
```
GET  /training-survey           - Survey form
POST /training-survey           - Submit survey
GET  /training-surveys          - Results dashboard (HR)
GET  /training-surveys/{id}     - View individual survey (HR)
```

### 6. Self-Service Portal

**Location:** Employee menu

**Features:**
- My Profile
- My Trainings
- My 201 Files
- Quick statistics
- Certificate downloads

**Routes:**
```
GET /my-profile     - View own profile
GET /my-trainings   - View training history
GET /my-files       - Redirect to 201 files
```

---

## 🚀 Deployment

### Hostinger Shared Hosting Setup

#### 1. Upload Files

Via FTP (FileZilla):
```
Local → Remote
/ehrms → /public_html/hr-training
```

#### 2. Update .env

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://democodes.online/hr-training/public

DB_HOST=localhost
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

#### 3. Set Permissions

```bash
chmod 755 public/uploads
chmod 644 .env
```

#### 4. Optimize Laravel

```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches if needed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 5. Secure .env File

Create `/hr-training/.htaccess`:
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

#### 6. Setup Cron Jobs (Optional)

For scheduled tasks:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. 403 Forbidden on Employee Files

**Problem:** Employees can't access their 201 files

**Solution:** Check routes are in correct middleware section

```php
// ✅ Correct - General authenticated section
Route::middleware(['auth'])->group(function () {
    Route::get('employees/{employee}/files', ...);
    Route::get('files/{file}/download', ...);
});
```

#### 2. SQL Column Not Found Error

**Problem:** `Column 'name' not found` or `Column 'topic_name' not found`

**Solution:** Use correct column name `title`

```php
// ✅ Correct
$topics = TrainingTopic::orderBy('title')->get();

// In views
{{ $topic->title }}
```

#### 3. Route Not Defined Error

**Problem:** `Route [training-survey] not defined`

**Solution:** Update route references to `training-survey.form`

```blade
<!-- ✅ Correct -->
<a href="{{ route('training-survey.form') }}">Training Survey</a>
```

#### 4. Upload Directory Not Found

**Problem:** Files won't upload

**Solution:** Create directories and set permissions

```bash
mkdir -p public/uploads/employee_files
chmod -R 775 public/uploads
```

#### 5. Duplicate Import Error

**Problem:** `Cannot use EmployeeFileController because name is already in use`

**Solution:** Remove duplicate import in `routes/web.php`

```php
// ❌ Wrong - Duplicate
use App\Http\Controllers\EmployeeFileController;
use App\Http\Controllers\EmployeeFileController; // Remove this

// ✅ Correct - Single import
use App\Http\Controllers\EmployeeFileController;
```

### Debug Mode

**Enable for development only:**

```env
APP_DEBUG=true
APP_ENV=local
```

**Always disable in production:**

```env
APP_DEBUG=false
APP_ENV=production
```

### Clear All Caches

```bash
php artisan optimize:clear
# This runs:
# - config:clear
# - route:clear
# - view:clear
# - cache:clear
# - compiled:clear
```

### Permission Issues

```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Or
chmod -R 777 storage bootstrap/cache
```

---

## 📚 API Documentation

### Authentication

All API requests require authentication token:

```bash
POST /api/login
{
    "username": "admin",
    "password": "password"
}

# Response
{
    "token": "your-api-token",
    "user": {...}
}
```

### Endpoints (Future Implementation)

```
GET  /api/employees          - List employees
GET  /api/employees/{id}     - Get employee
POST /api/employees          - Create employee

GET  /api/trainings          - List trainings
GET  /api/trainings/{id}     - Get training

GET  /api/attendance         - List attendance
POST /api/attendance         - Mark attendance
```

---

## 🤝 Contributing

### Development Setup

1. Fork the repository
2. Create feature branch
   ```bash
   git checkout -b feature/new-feature
   ```
3. Make changes
4. Run tests (when available)
   ```bash
   php artisan test
   ```
5. Commit changes
   ```bash
   git commit -m "Add new feature"
   ```
6. Push to branch
   ```bash
   git push origin feature/new-feature
   ```
7. Create Pull Request

### Coding Standards

- Follow PSR-12 coding style
- Use meaningful variable names
- Comment complex logic
- Write descriptive commit messages
- Keep functions small and focused

### Git Commit Convention

```
feat: Add new feature
fix: Bug fix
docs: Documentation update
style: Code formatting
refactor: Code refactoring
test: Add tests
chore: Maintenance tasks
```

---

## 📄 License

This project is licensed under the MIT License.

```
MIT License

Copyright (c) 2025 LGU Sablayan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 💬 Support

### Documentation

Complete guides available in `/docs`:
- `INSTALLATION_GUIDE.md`
- `DEPLOYMENT_GUIDE.md`
- `USER_MANUAL.md`
- `TROUBLESHOOTING.md`

### Issue Reporting

Found a bug? [Create an issue](https://github.com/yourusername/ehrms/issues)

Include:
- Laravel version
- PHP version
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)

### Contact

- **Email:** support@lgu-sablayan.gov.ph
- **GitHub:** [@yourusername](https://github.com/yourusername)

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap 5
- Bootstrap Icons
- LGU Sablayan, Occidental Mindoro

---

## 📊 Project Statistics

- **Controllers:** 10
- **Models:** 11
- **Views:** 30+
- **Routes:** 70+
- **Database Tables:** 19
- **Features:** 50+
- **Lines of Code:** 10,000+

---

## 🗺️ Roadmap

### Version 1.0 (Current)
- ✅ Employee Management
- ✅ Training Management
- ✅ Attendance Tracking
- ✅ 201 Files Management
- ✅ Training Survey
- ✅ Self-Service Portal

### Version 1.1 (Planned)
- ⏳ Excel/PDF Report Generation
- ⏳ Email Notifications
- ⏳ Advanced Analytics
- ⏳ Mobile App
- ⏳ Biometric Integration

### Version 2.0 (Future)
- ⏳ Leave Management
- ⏳ Payroll Integration
- ⏳ Performance Evaluation
- ⏳ Recruitment Module

---

## 📸 Screenshots

### Dashboard
![Dashboard](docs/screenshots/dashboard.png)

### Employee Management
![Employees](docs/screenshots/employees.png)

### Training Attendance
![Attendance](docs/screenshots/attendance.png)

### Employee 201 Files
![Files](docs/screenshots/files.png)

---

## 🎯 Key Features Summary

| Feature | Status | Users |
|---------|--------|-------|
| Employee CRUD | ✅ Complete | HR Admin |
| Training Management | ✅ Complete | HR Admin |
| Attendance Tracking | ✅ Complete | HR Admin |
| Certificate Upload | ✅ Complete | HR Admin |
| 201 Files | ✅ Complete | HR + Employees |
| Training Survey | ✅ Complete | All Users |
| Self-Service Portal | ✅ Complete | Employees |
| Analytics Dashboard | ✅ Complete | HR Admin |
| Email Notifications | ⏳ Planned | All Users |
| Report Generation | ⏳ Planned | HR Admin |

---

**Made with ❤️ for LGU Sablayan, Occidental Mindoro**

**Status:** Production Ready ✅  
**Version:** 1.0.0  
**Last Updated:** January 2026