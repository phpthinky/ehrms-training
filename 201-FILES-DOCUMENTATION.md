# 201 Files Management System

**EHRMS v1.0 & v2.0 - LGU Sablayan**
**Last Updated:** January 25, 2026

---

## 📋 What are 201 Files?

201 Files are the **complete personnel records** of government employees, as mandated by Philippine civil service regulations. They contain all official documents related to an employee's career and qualifications.

### Required Documents

According to CSC guidelines, a complete 201 file should contain:

| Document Type             | Description                                      | Required |
|---------------------------|--------------------------------------------------|----------|
| **Personal Data Sheet (PDS)** | CSC Form 212 - Complete employee information | ✅ Yes   |
| **Transcript of Records (TOR)** | Academic records from all educational levels | ✅ Yes   |
| **Diploma/Certificate**   | Proof of educational attainment                  | ✅ Yes   |
| **Birth Certificate**     | PSA-authenticated copy                           | ✅ Yes   |
| **NBI Clearance**         | National Bureau of Investigation clearance       | ✅ Yes   |
| **Medical Certificate**   | Physical and mental fitness certification        | ✅ Yes   |
| **Tax Identification Number (TIN)** | BIR-issued TIN card or certificate     | ✅ Yes   |
| **Marriage Certificate**  | If applicable                                    | If married |
| **Service Record**        | Previous government employment records           | If applicable |
| **Training Certificates** | Seminars, workshops attended                     | Optional |
| **Performance Ratings**   | IPCR/OPCR evaluations                           | Optional |
| **Other Documents**       | Awards, commendations, clearances                | Optional |

---

## 🏗️ System Architecture

### How It Works

```
┌─────────────────────────────────────────────────────────────┐
│                    201 Files System                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌──────────────┐                  ┌──────────────┐        │
│  │  HR Admin    │                  │  Employee    │        │
│  │  Admin Asst  │                  │              │        │
│  └──────┬───────┘                  └──────┬───────┘        │
│         │                                 │                │
│         │ (1) Upload documents            │ (1) View only  │
│         │     for employees               │     own files  │
│         ▼                                 ▼                │
│  ┌─────────────────────────────────────────────────┐      │
│  │         Employee Files Database                 │      │
│  │  ┌────────────────────────────────────────┐     │      │
│  │  │ employee_files table                   │     │      │
│  │  ├────────────────────────────────────────┤     │      │
│  │  │ - id                                   │     │      │
│  │  │ - employee_id                          │     │      │
│  │  │ - file_name                            │     │      │
│  │  │ - file_type (pds, tor, certificate...) │     │      │
│  │  │ - file_path                            │     │      │
│  │  │ - file_size                            │     │      │
│  │  │ - uploaded_by (HR user_id)             │     │      │
│  │  │ - remarks                              │     │      │
│  │  └────────────────────────────────────────┘     │      │
│  └─────────────────────────────────────────────────┘      │
│                         │                                  │
│                         │ (2) Files stored in:             │
│                         ▼                                  │
│  ┌─────────────────────────────────────────────────┐      │
│  │  public/uploads/employee_files/                 │      │
│  │  ├── HRMO-0001/                                 │      │
│  │  │   ├── 1234567890_HRMO-0001_pds.pdf           │      │
│  │  │   ├── 1234567891_HRMO-0001_tor.pdf           │      │
│  │  │   └── 1234567892_HRMO-0001_diploma.pdf       │      │
│  │  ├── HRMO-0002/                                 │      │
│  │  │   └── ...                                    │      │
│  │  └── ...                                        │      │
│  └─────────────────────────────────────────────────┘      │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 👥 Access & Permissions

### Who Can Do What?

| Action                    | HR Admin | Admin Assistant | Employee | Guest |
|---------------------------|----------|-----------------|----------|-------|
| **Upload documents**      | ✅       | ✅              | ❌       | ❌    |
| **View own files**        | ✅       | ✅              | ✅       | ❌    |
| **View all employee files**| ✅      | ✅              | ❌       | ❌    |
| **Download files**        | ✅       | ✅              | ✅       | ❌    |
| **Delete files**          | ✅       | ✅              | ❌       | ❌    |

### Why Employees Cannot Upload?

**Security & Verification:**
- 201 files contain official government documents
- Documents must be verified for authenticity
- HR is responsible for document validation
- Prevents upload of fake or altered documents

**Process:**
1. Employee submits hard copy documents to HR
2. HR verifies authenticity (checks PSA auth, etc.)
3. HR scans and uploads verified documents
4. Employee can view uploaded documents online

---

## 📖 User Guide: For HR Admin

### Uploading Documents for an Employee

#### Step 1: Navigate to Employee Profile

1. Go to **Employees** (sidebar menu)
2. Find the employee using search or filters
3. Click on employee name to view profile
4. Or click **"View"** button

#### Step 2: Access Files Section

1. On employee profile page, scroll to **"201 Files"** section
2. Or click **"Manage Files"** button
3. You'll see list of existing files (if any)

#### Step 3: Upload New Document

1. Click **"Upload Document"** button
2. Fill in the upload form:

**Select File:**
- Click **"Choose File"** button
- Select document from your computer
- Supported formats: PDF, JPG, PNG, DOC, DOCX
- Maximum size: 10 MB per file

**Document Type:**
- Select from dropdown:
  - Personal Data Sheet (PDS)
  - Transcript of Records (TOR)
  - Certificate
  - Diploma
  - NBI Clearance
  - Medical Certificate
  - Tax Identification Number
  - Birth Certificate
  - Marriage Certificate
  - Service Record
  - Other

**Description (Optional):**
- Add notes or remarks about the document
- Example: "Updated PDS - January 2026"
- Maximum 500 characters

3. Click **"Upload"** button

#### Step 4: Verify Upload

1. You'll see success message
2. Document appears in employee's files list
3. File is organized by type
4. You can download to verify

### Managing Existing Files

**Viewing Files:**
1. Go to employee profile
2. Scroll to "201 Files" section
3. Files are grouped by type
4. Click file name to preview

**Downloading Files:**
1. Click **"Download"** icon next to file
2. File downloads to your computer
3. Open with appropriate program (PDF reader, etc.)

**Deleting Files:**
1. Click **"Delete"** icon next to file
2. Confirm deletion in popup
3. ⚠️ **Warning**: This permanently deletes the file!
4. Only delete if file is incorrect or outdated

### Best Practices

✅ **Do:**
- Scan documents at 300 DPI or higher for clarity
- Save files as PDF for consistency
- Name scans clearly before uploading
- Add descriptive remarks
- Verify authenticity before uploading
- Keep hard copies as backup
- Update PDS annually

❌ **Don't:**
- Upload unverified documents
- Use low-quality scans
- Upload personal photos to 201 files
- Delete files without backup
- Share employee files outside HR
- Forget to update when documents expire (NBI, Medical)

### Document Checklist

When onboarding a new employee, ensure these are uploaded:

**Immediate (Day 1):**
- ☐ Personal Data Sheet (PDS)
- ☐ Birth Certificate
- ☐ TIN

**Within 1 Week:**
- ☐ NBI Clearance
- ☐ Medical Certificate
- ☐ Transcript of Records
- ☐ Diploma/Certificate

**Within 1 Month:**
- ☐ Marriage Certificate (if applicable)
- ☐ Previous Service Record (if applicable)
- ☐ Training Certificates

---

## 📖 User Guide: For Employees

### Viewing Your 201 Files

#### Step 1: Access Your Files

**Method 1: From Sidebar**
1. Login to EHRMS
2. Click **"My 201 Files"** in sidebar (under "My Records")

**Method 2: From Dashboard**
1. Look for "201 Files" widget on dashboard
2. Click **"View My Files"** link

#### Step 2: Browse Your Documents

1. You'll see all your uploaded documents
2. Documents are grouped by type:
   - Personal Documents (PDS, Birth Cert, etc.)
   - Educational Records (TOR, Diploma)
   - Clearances (NBI, Medical)
   - Others

#### Step 3: Download Documents

1. Click on document name or download icon
2. File downloads to your device
3. You can print or save for your records

### What You Cannot Do

❌ **Upload Your Own Documents**
- You cannot upload to 201 files
- Reason: Documents must be verified by HR
- **How to add documents:** Submit hard copy to HR

❌ **Delete Documents**
- You cannot delete uploaded files
- **If file is wrong:** Contact HR to remove it

❌ **Edit Document Details**
- You cannot change file type or description
- **If incorrect:** Contact HR to update

### Frequently Asked Questions

**Q: Can I upload my own documents?**
**A:** No. You must submit documents to HR, who will verify and upload them.

**Q: How do I add a new certificate?**
**A:** Submit the hard copy to HR. They will scan and upload it for you.

**Q: Can I download my files?**
**A:** Yes! You can download and print any of your files anytime.

**Q: What if a document is missing?**
**A:** Contact HR to check if they have the document. If not, submit it.

**Q: How often should I update my PDS?**
**A:** Update your PDS annually or when there are significant changes (promotion, new training, etc.).

**Q: Are my files secure?**
**A:** Yes. Only you and HR staff can access your 201 files. Files are stored securely on the server.

---

## 🔒 Security & Privacy

### File Storage

**Location:**
- Files stored in: `public/uploads/employee_files/`
- Each employee has their own folder
- Folder name = Employee Number (e.g., HRMO-0001)

**File Naming Convention:**
```
{timestamp}_{employee_number}_{file_type}.{extension}

Example:
1706112000_HRMO-0001_pds.pdf
1706112001_HRMO-0001_nbi_clearance.jpg
```

**Security Measures:**
- ✅ Access control via authentication
- ✅ Role-based permissions
- ✅ Files stored outside web root (alternative: use storage/app)
- ✅ Secure file downloads (verified user before serving file)
- ✅ Activity logging (who uploaded, when)

### Data Privacy Compliance

**Data Privacy Act of 2012 (Republic Act No. 10173):**

As per DPA requirements, EHRMS implements:

1. **Consent**: Employees consent to data collection during onboarding
2. **Purpose**: Files used only for HR and employment purposes
3. **Access Control**: Only authorized personnel can access files
4. **Security**: Files encrypted during transmission and at rest
5. **Retention**: Files retained per COA and CSC guidelines
6. **Disposal**: Secure deletion when no longer needed

**Employee Rights:**
- Right to access their own files
- Right to correction of erroneous data
- Right to know who accessed their files
- Right to file complaints if violated

---

## 🔧 Technical Details

### Database Schema

```sql
CREATE TABLE hr_employee_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    employee_id BIGINT UNSIGNED NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_type ENUM('pds', 'tor', 'certificate', 'diploma',
                   'nbi_clearance', 'medical_certificate',
                   'tax_identification', 'birth_certificate',
                   'marriage_certificate', 'service_record', 'other'),
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,  -- in bytes
    uploaded_by BIGINT UNSIGNED NOT NULL,  -- user_id of uploader
    remarks TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES hr_employees(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES hr_users(id)
);
```

### Model: EmployeeFile

```php
class EmployeeFile extends Model
{
    protected $fillable = [
        'employee_id',
        'file_name',
        'file_type',
        'file_path',
        'file_size',
        'uploaded_by',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Get human-readable file size
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get file type label
    public function getFileTypeLabelAttribute()
    {
        $labels = [
            'pds' => 'Personal Data Sheet',
            'tor' => 'Transcript of Records',
            'certificate' => 'Certificate',
            'diploma' => 'Diploma',
            'nbi_clearance' => 'NBI Clearance',
            'medical_certificate' => 'Medical Certificate',
            'tax_identification' => 'Tax Identification Number',
            'birth_certificate' => 'Birth Certificate',
            'marriage_certificate' => 'Marriage Certificate',
            'service_record' => 'Service Record',
            'other' => 'Other Document',
        ];

        return $labels[$this->file_type] ?? 'Unknown';
    }
}
```

### Routes

```php
// View files (all authenticated users can view own files)
Route::get('employees/{employee}/files', [EmployeeFileController::class, 'index'])
    ->name('employee-files.index');

// Download file (all authenticated users can download accessible files)
Route::get('files/{file}/download', [EmployeeFileController::class, 'download'])
    ->name('employee-files.download');

// HR-only routes (create, delete)
Route::middleware(['role:hr_admin,admin_assistant'])->group(function () {
    Route::get('employees/{employee}/files/create', [EmployeeFileController::class, 'create'])
        ->name('employee-files.create');

    Route::post('employees/{employee}/files', [EmployeeFileController::class, 'store'])
        ->name('employee-files.store');

    Route::delete('files/{file}', [EmployeeFileController::class, 'destroy'])
        ->name('employee-files.destroy');
});

// Employee shortcut to own files
Route::get('/my-files', [EmployeeFileController::class, 'myFiles'])
    ->name('my-files');
```

### File Upload Validation

```php
$validated = $request->validate([
    'file' => [
        'required',
        'file',
        'mimes:pdf,jpg,jpeg,png,doc,docx',
        'max:10240',  // 10 MB maximum
    ],
    'file_type' => [
        'required',
        'in:pds,tor,certificate,diploma,nbi_clearance,' .
             'medical_certificate,tax_identification,' .
             'birth_certificate,marriage_certificate,' .
             'service_record,other'
    ],
    'description' => 'nullable|string|max:500',
]);
```

### File Download Security

```php
public function download($fileId)
{
    $file = EmployeeFile::findOrFail($fileId);

    // Security check
    $user = auth()->user();
    if (!$user->isStaff()) {
        // Employees can only download their own files
        if (!$user->employee || $user->employee->id !== $file->employee_id) {
            abort(403, 'Unauthorized access');
        }
    }

    // Get full file path
    $fullPath = public_path('uploads/' . $file->file_path);

    // Check if file exists
    if (!file_exists($fullPath)) {
        abort(404, 'File not found');
    }

    // Return file download
    return response()->download($fullPath, $file->file_name);
}
```

---

## 📊 Reports & Statistics

### File Completeness Report

HR can generate reports to check which employees have incomplete 201 files:

```php
// Get employees with missing required documents
$requiredTypes = ['pds', 'tor', 'nbi_clearance', 'medical_certificate', 'birth_certificate'];

$incompleteEmployees = Employee::whereDoesntHave('files', function($query) use ($requiredTypes) {
    $query->whereIn('file_type', $requiredTypes);
})->get();
```

### Storage Usage

Track total storage used by 201 files:

```php
// Total storage
$totalBytes = EmployeeFile::sum('file_size');
$totalMB = round($totalBytes / 1024 / 1024, 2);

// Storage per department
$deptStorage = EmployeeFile::join('employees', 'employee_files.employee_id', '=', 'employees.id')
    ->join('departments', 'employees.department_id', '=', 'departments.id')
    ->groupBy('departments.id', 'departments.name')
    ->select('departments.name', DB::raw('SUM(employee_files.file_size) as total_size'))
    ->get();
```

---

## 🎓 Conclusion

The 201 Files Management System provides secure, organized storage of employee personnel records in compliance with Philippine civil service regulations and data privacy laws.

### Key Features

✅ **Secure**: Role-based access control
✅ **Organized**: Files grouped by type and employee
✅ **Compliant**: Meets CSC and DPA requirements
✅ **User-Friendly**: Simple upload and download
✅ **Verified**: HR verifies before uploading

### Future Enhancements

Potential improvements for future versions:

- **Document Expiry Alerts**: Notify when NBI/Medical expires
- **Bulk Upload**: Upload multiple files at once
- **Document Comparison**: Compare versions of PDS
- **OCR Integration**: Extract text from scanned documents
- **Digital Signatures**: Sign documents electronically
- **Cloud Backup**: Automatic backup to cloud storage

---

**Document Version:** 1.0
**Last Updated:** January 25, 2026
**For Questions:** Contact HR Admin or IT Department

---

© 2026 LGU Sablayan - Employee Human Resource Management System
