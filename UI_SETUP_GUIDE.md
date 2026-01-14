# 🎨 EHRMS UI SETUP GUIDE
## Bootstrap 5.3 Design Implementation

---

## ✅ WHAT'S INCLUDED

1. **Main App Layout** (`layouts/app.blade.php`)
   - Expandable/collapsible sidebar
   - Professional header with user profile
   - Role-based navigation menu
   - Notification badges
   - Responsive design

2. **Welcome Page** (`welcome.blade.php`)
   - Modern landing page
   - Feature showcase
   - Statistics display
   - Professional animations

3. **Login Page** (`auth/login.blade.php`)
   - Split-screen design
   - Password visibility toggle
   - Remember me option
   - Error handling

4. **Dashboard** (`dashboard.blade.php`)
   - Role-based content (HR Staff vs Employee)
   - Statistics cards
   - Upcoming trainings list
   - Quick action buttons

---

## 📂 FILE PLACEMENT

Copy these files to your Laravel project:

```
resources/views/
├── layouts/
│   └── app.blade.php          # Main layout with sidebar
├── auth/
│   └── login.blade.php        # Login page
├── welcome.blade.php          # Landing page
└── dashboard.blade.php        # Dashboard
```

Controllers:
```
app/Http/Controllers/
├── Auth/
│   └── LoginController.php
└── DashboardController.php
```

Routes:
```
routes/
└── web.php
```

---

## 🚀 SETUP STEPS

### 1. Copy All View Files

```bash
# Copy views
cp resources/views/layouts/app.blade.php [YOUR_PROJECT]/resources/views/layouts/
cp resources/views/auth/login.blade.php [YOUR_PROJECT]/resources/views/auth/
cp resources/views/welcome.blade.php [YOUR_PROJECT]/resources/views/
cp resources/views/dashboard.blade.php [YOUR_PROJECT]/resources/views/
```

### 2. Copy Controllers

```bash
cp app/Http/Controllers/Auth/LoginController.php [YOUR_PROJECT]/app/Http/Controllers/Auth/
cp app/Http/Controllers/DashboardController.php [YOUR_PROJECT]/app/Http/Controllers/
```

### 3. Copy Routes

```bash
cp routes/web.php [YOUR_PROJECT]/routes/
```

### 4. Register Middleware

Add to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'status' => \App\Http\Middleware\CheckStatus::class,
    ]);
})
```

### 5. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎯 TESTING THE UI

### 1. Start Server
```bash
php artisan serve
```

### 2. Visit Pages

- **Welcome Page**: http://localhost:8000
- **Login Page**: http://localhost:8000/login
- **Dashboard**: http://localhost:8000/dashboard (after login)

### 3. Test Accounts

Login with these seeded accounts:

| Email | Password | Role |
|-------|----------|------|
| hradmin@sablayan.gov.ph | password | HR Admin |
| assistant@sablayan.gov.ph | password | Admin Assistant |
| johndoe@sablayan.gov.ph | password | Employee |

---

## 🎨 DESIGN FEATURES

### Color Scheme
- **Primary Blue**: #1e40af (Professional government blue)
- **Dark Blue**: #1e3a8a (Accent)
- **Green**: #059669 (Success/Active states)
- **Amber**: #f59e0b (Warnings)

### Typography
- **Display Font**: Outfit (Headers, titles)
- **Body Font**: Work Sans (Content, paragraphs)

### Sidebar Features
- ✅ Smooth expand/collapse animation
- ✅ Remembers state in localStorage
- ✅ Icon-only mode when collapsed
- ✅ Active link highlighting
- ✅ Badge notifications
- ✅ Mobile responsive

### Dashboard Features
- ✅ Role-based content
- ✅ Statistics cards with icons
- ✅ Upcoming trainings list
- ✅ Quick action buttons
- ✅ Department overview
- ✅ Animated elements

---

## 📱 RESPONSIVE DESIGN

The UI is fully responsive:

- **Desktop** (>768px): Full sidebar with text
- **Tablet** (768px): Collapsible sidebar
- **Mobile** (<768px): Overlay sidebar menu

---

## 🔧 CUSTOMIZATION

### Change Primary Color

Edit the CSS variables in `layouts/app.blade.php`:

```css
:root {
    --primary-blue: #1e40af;    /* Change this */
    --primary-dark: #1e3a8a;    /* And this */
    --accent-green: #059669;     /* Optional */
}
```

### Add New Sidebar Links

Edit `layouts/app.blade.php`, find the `<nav class="sidebar-nav">` section:

```blade
<a href="{{ route('your.route') }}" class="nav-link">
    <i class="bi bi-your-icon"></i>
    <span class="nav-link-text">Your Link</span>
</a>
```

### Modify Stats Cards

Edit `dashboard.blade.php` stats section to show different metrics.

---

## 🎭 AVAILABLE BOOTSTRAP ICONS

The UI uses Bootstrap Icons. Browse all icons at:
https://icons.getbootstrap.com/

Common icons already used:
- `bi-people` (Employees)
- `bi-journal-bookmark` (Trainings)
- `bi-envelope` (Messages)
- `bi-bell` (Notifications)
- `bi-file-earmark-text` (Files)
- `bi-speedometer2` (Dashboard)

---

## 🚨 TROUBLESHOOTING

### Sidebar not showing
- Check if you're logged in
- Clear browser cache
- Check route names match

### Styles broken
- CDN links may be blocked
- Check internet connection
- Try local Bootstrap installation

### Routes not working
```bash
php artisan route:list  # Check all routes
php artisan route:clear # Clear route cache
```

### Session issues
```bash
php artisan session:clear
```

---

## 📚 NEXT STEPS

Now that the UI is ready, you can:

1. **Create Employee Management Pages**
   - List view with search/filter
   - Create/Edit forms
   - Profile view

2. **Build Training Management**
   - Training list
   - Attendance tracking
   - Approval workflow

3. **Implement File Management**
   - Upload interface
   - File listing
   - Preview/download

4. **Add Messaging System**
   - Inbox/Compose
   - Message threads
   - Read receipts

---

## 💡 TIPS

- The sidebar state is saved in localStorage
- All pages inherit from `layouts/app.blade.php`
- Use `@section('page-title')` to set page titles
- Use `@push('styles')` and `@push('scripts')` for page-specific assets

---

## 📝 NOTES

- **No JavaScript frameworks required** - Pure vanilla JS
- **CDN-based** - No npm build needed (optional)
- **Production-ready** - Professional government aesthetic
- **Accessible** - Semantic HTML and proper ARIA labels

---

**UI Version**: 1.0.0  
**Last Updated**: January 2026  
**Framework**: Laravel 11 + Bootstrap 5.3
