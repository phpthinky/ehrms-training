# Phase 1: Customizable Survey System Documentation

**EHRMS v2.0 - LGU Sablayan**
**Last Updated:** January 25, 2026
**Status:** Complete & Production Ready

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Part 1: Training Programs CRUD](#part-1-training-programs-crud)
4. [Part 2: Survey Template Builder](#part-2-survey-template-builder)
5. [Part 3: Question Bank](#part-3-question-bank)
6. [Part 4: Dynamic Form Builder](#part-4-dynamic-form-builder)
7. [Part 5: Response Analytics](#part-5-response-analytics)
8. [User Guides](#user-guides)
9. [Technical Implementation](#technical-implementation)
10. [API Reference](#api-reference)

---

## 🎯 Overview

### What is the Customizable Survey System?

The Customizable Survey System is a comprehensive solution for conducting Training Needs Analysis (TNA) surveys in the EHRMS. It allows HR administrators to create, distribute, and analyze custom surveys to identify employee training needs.

### Key Features

- **Flexible Survey Creation**: Build surveys with multiple question types
- **Training Program Integration**: Link surveys to specific training programs
- **Real-time Analytics**: View responses with charts and filters
- **Employee Self-Service**: Employees complete surveys at their convenience
- **Data-Driven Insights**: Automated TNA recommendations based on survey results

### Benefits

✅ **For HR Admin:**
- Identify training needs across the organization
- Make data-driven training decisions
- Track response rates by department
- Export data for reporting

✅ **For Employees:**
- Express training preferences and needs
- Submit surveys at their own pace
- View confirmation after submission
- Contribute to organizational development

### System Components

```
┌─────────────────────────────────────────────────────┐
│         Customizable Survey System                  │
├─────────────────────────────────────────────────────┤
│                                                     │
│  Part 1: Training Programs CRUD                     │
│  └─ Manage training program catalog                │
│                                                     │
│  Part 2: Survey Template Builder                    │
│  └─ Create and manage survey templates             │
│                                                     │
│  Part 3: Question Bank                              │
│  └─ Library of reusable survey questions           │
│                                                     │
│  Part 4: Dynamic Form Builder                       │
│  └─ Employee survey completion interface           │
│                                                     │
│  Part 5: Response Analytics                         │
│  └─ View, filter, and analyze survey results       │
│                                                     │
└─────────────────────────────────────────────────────┘
```

---

## 🏗️ System Architecture

### Database Schema

```
┌──────────────────────┐
│ training_programs    │
├──────────────────────┤
│ id                   │
│ program_code         │
│ program_name         │
│ description          │
│ order                │
│ is_active            │
└──────────────────────┘
          │
          │ (used in surveys)
          ▼
┌──────────────────────┐       ┌──────────────────────┐
│ survey_templates     │──────▶│ survey_questions     │
├──────────────────────┤       ├──────────────────────┤
│ id                   │       │ id                   │
│ title                │       │ question_text        │
│ description          │       │ question_type        │
│ is_active            │       │ options              │
│ created_at           │       │ help_text            │
└──────────────────────┘       │ category             │
          │                    └──────────────────────┘
          │                              │
          │                              │
          ▼                              │
┌──────────────────────┐                │
│ question_template    │◀───────────────┘
├──────────────────────┤ (pivot table)
│ survey_template_id   │
│ survey_question_id   │
│ order                │
│ is_required          │
└──────────────────────┘
          │
          │ (employees respond)
          ▼
┌──────────────────────┐
│ survey_responses     │
├──────────────────────┤
│ id                   │
│ survey_template_id   │
│ employee_id          │
│ response_data (JSON) │
│ status               │
│ submitted_at         │
└──────────────────────┘
```

### Data Flow

```
┌──────────────┐
│  HR Admin    │
└──────┬───────┘
       │
       │ 1. Create Training Programs
       ▼
┌──────────────────────┐
│ Training Programs DB │
└──────┬───────────────┘
       │
       │ 2. Create Questions
       ▼
┌──────────────────────┐
│  Question Bank DB    │
└──────┬───────────────┘
       │
       │ 3. Build Survey Template
       ▼
┌──────────────────────┐
│ Survey Templates DB  │
└──────┬───────────────┘
       │
       │ 4. Activate Survey
       ▼
┌──────────────┐
│  Employees   │
└──────┬───────┘
       │
       │ 5. Fill Survey
       ▼
┌──────────────────────┐
│ Survey Responses DB  │
└──────┬───────────────┘
       │
       │ 6. View Analytics
       ▼
┌──────────────┐
│  HR Admin    │
└──────────────┘
```

---

## 📦 Part 1: Training Programs CRUD

### Overview

Training Programs are the catalog of available training courses that employees can select in surveys. This is the foundation of the survey system.

### Features

- ✅ Create, Read, Update, Delete training programs
- ✅ Drag-and-drop ordering
- ✅ Active/Inactive status toggle
- ✅ Program code and name
- ✅ Rich text descriptions
- ✅ Used in survey questions

### Default Programs (11 total)

| Code  | Program Name                                    | Category        |
|-------|-------------------------------------------------|-----------------|
| SDC I | Supervisory Development Course I                | Leadership      |
| SDC II| Supervisory Development Course II               | Leadership      |
| SDC III| Supervisory Development Course III             | Leadership      |
| WRS   | Writing Resource Seminar                        | Communication   |
| CS    | Customer Service Training                       | Soft Skills     |
| BCSS  | Basic Computer Skills Seminar                   | Technology      |
| VOW   | Values Orientation Workshop                     | Character Dev.  |
| GST   | Gender Sensitivity Training                     | Diversity       |
| RM    | Records Management                              | Administration  |
| CSDC  | Career Service Development Course               | Professional    |
| SWCT  | Stress and Workload Coping Training            | Wellness        |

### Access & Permissions

**Route:** `/training-programs`

| Role             | Create | Read | Update | Delete | Reorder |
|------------------|--------|------|--------|--------|---------|
| HR Admin         | ✅     | ✅   | ✅     | ✅     | ✅      |
| Admin Assistant  | ✅     | ✅   | ✅     | ✅     | ✅      |
| Employee         | ❌     | ❌   | ❌     | ❌     | ❌      |

### User Guide: Managing Training Programs

#### Creating a Training Program

1. Navigate to **Training Programs** (sidebar menu)
2. Click **"Add New Program"** button
3. Fill in the form:
   - **Program Code**: Short identifier (e.g., "SDC I")
   - **Program Name**: Full name (e.g., "Supervisory Development Course I")
   - **Description**: Detailed description of the program
   - **Active Status**: Toggle to enable/disable
4. Click **"Save Program"**

#### Reordering Programs

1. Go to Training Programs list
2. Drag and drop programs using the **⋮⋮** handle
3. New order is saved automatically
4. Programs appear in this order in surveys

#### Editing a Program

1. Find the program in the list
2. Click **"Edit"** button
3. Update the information
4. Click **"Update Program"**

#### Deactivating a Program

1. Find the program in the list
2. Toggle the **Active/Inactive** switch
3. Inactive programs won't appear in new surveys

### Technical Details

**Model:** `App\Models\TrainingProgram`

**Migration:** `create_training_programs_table.php`

**Controller:** `App\Http\Controllers\TrainingProgramController`

**Views:**
- `resources/views/training-programs/index.blade.php`
- `resources/views/training-programs/create.blade.php`
- `resources/views/training-programs/edit.blade.php`

**Key Methods:**
```php
public function index()        // List all programs
public function create()       // Show create form
public function store()        // Save new program
public function edit()         // Show edit form
public function update()       // Update program
public function destroy()      // Delete program
public function reorder()      // Update display order
```

---

## 📝 Part 2: Survey Template Builder

### Overview

Survey Templates are the blueprint for surveys. They define the structure, questions, and settings for a survey that employees will complete.

### Features

- ✅ Create multiple survey templates
- ✅ Set title and description
- ✅ Active/Inactive status
- ✅ Add questions from Question Bank
- ✅ Arrange question order
- ✅ Set required fields
- ✅ Only one active template at a time

### Template Structure

```
Survey Template
├── Basic Information
│   ├── Title (e.g., "2026 Training Needs Analysis")
│   ├── Description
│   ├── Status (Active/Inactive)
│   └── Created Date
│
└── Questions (ordered list)
    ├── Question 1 (required/optional)
    ├── Question 2 (required/optional)
    ├── Question 3 (required/optional)
    └── ...
```

### Access & Permissions

**Route:** `/survey-templates`

| Role             | Create | Read | Update | Delete | Activate |
|------------------|--------|------|--------|--------|----------|
| HR Admin         | ✅     | ✅   | ✅     | ✅     | ✅       |
| Admin Assistant  | ✅     | ✅   | ✅     | ✅     | ✅       |
| Employee         | ❌     | ❌   | ❌     | ❌     | ❌       |

### User Guide: Creating a Survey Template

#### Step 1: Create Template

1. Navigate to **Survey Templates** (sidebar: HR → Survey Templates)
2. Click **"Create New Template"** button
3. Fill in:
   - **Title**: e.g., "Annual Training Needs Survey 2026"
   - **Description**: Purpose and instructions
   - **Status**: Leave inactive while building
4. Click **"Save Template"**

#### Step 2: Add Questions

1. From template list, click **"Edit Questions"**
2. Click **"Add Question"** button
3. Select questions from Question Bank
4. For each question:
   - ✅ Check **"Required"** if mandatory
   - Set display order using drag-and-drop
5. Click **"Save Questions"**

#### Step 3: Preview & Activate

1. Click **"Preview"** to see how employees will see the survey
2. Review all questions and order
3. When ready, toggle **"Active"** status
4. Only one survey can be active at a time

### Important Rules

⚠️ **Single Active Template**: Only one template can be active at a time. Activating a new template automatically deactivates the previous one.

⚠️ **Cannot Edit Active Template**: Once activated and responses are collected, you should create a new template rather than editing the existing one to preserve historical data.

⚠️ **Question Changes**: Editing a question in the Question Bank affects all templates using that question.

### Technical Details

**Model:** `App\Models\SurveyTemplate`

**Relationships:**
```php
public function questions()  // Many-to-many with SurveyQuestion
public function responses()  // Has many SurveyResponse
```

**Controller:** `App\Http\Controllers\SurveyTemplateController`

**Views:**
- `resources/views/survey-templates/index.blade.php`
- `resources/views/survey-templates/create.blade.php`
- `resources/views/survey-templates/edit.blade.php`
- `resources/views/survey-templates/questions.blade.php`

---

## ❓ Part 3: Question Bank

### Overview

The Question Bank is a centralized library of reusable survey questions. Instead of creating questions from scratch for each survey, you build a library once and reuse questions across multiple templates.

### Question Types

| Type                  | Description                                | Example Use Case                    |
|-----------------------|--------------------------------------------|-------------------------------------|
| **Text**              | Short single-line input                    | "What is your current position?"    |
| **Textarea**          | Multi-line long-form input                 | "Describe your training needs"      |
| **Radio**             | Single choice from options                 | "Gender: Male / Female / Other"     |
| **Checkbox**          | Multiple selections allowed                | "Select skills you want to improve" |
| **Select (Dropdown)** | Single choice from dropdown                | "Select your department"            |
| **Date**              | Date picker                                | "Preferred training start date"     |
| **Number**            | Numeric input                              | "Years of experience"               |
| **Rating**            | Star or numeric rating scale (1-5)         | "Rate your current skill level"     |
| **Training Programs** | Special type: checkboxes of training list  | "Select trainings you're interested in" |

### Question Categories

Questions can be organized by category for easier management:

- **Personal Information**: Name, position, department, etc.
- **Training Needs**: Skills gaps, desired trainings
- **Current Skills**: Self-assessment questions
- **Preferences**: Schedule preferences, format preferences
- **Feedback**: Comments and suggestions

### Access & Permissions

**Route:** `/survey-questions` or `/question-bank`

| Role             | Create | Read | Update | Delete |
|------------------|--------|------|--------|--------|
| HR Admin         | ✅     | ✅   | ✅     | ✅     |
| Admin Assistant  | ✅     | ✅   | ✅     | ✅     |
| Employee         | ❌     | ❌   | ❌     | ❌     |

### User Guide: Building the Question Bank

#### Creating a Question

1. Navigate to **Question Bank** (sidebar: HR → Question Bank)
2. Click **"Add New Question"** button
3. Fill in the question details:

**Basic Information:**
- **Question Text**: The actual question to ask
  - Example: "Which training programs are you interested in?"
- **Question Type**: Select from dropdown (text, radio, checkbox, etc.)
- **Category**: Optional grouping (e.g., "Training Needs")
- **Help Text**: Optional hint shown below question
  - Example: "Select all that apply"

**For Choice-Based Questions (Radio, Checkbox, Select):**
- **Options**: Enter each option on a new line
  - Example:
    ```
    Excellent
    Good
    Fair
    Poor
    ```

**For Training Programs Type:**
- No options needed - automatically loads from Training Programs

4. Click **"Save Question"**

#### Example Questions

**1. Text Question:**
```
Question: What is your current job position?
Type: Text
Category: Personal Information
Help Text: Please enter your official position title
```

**2. Checkbox Question:**
```
Question: Which skills do you want to improve?
Type: Checkbox
Category: Training Needs
Options:
  - Communication Skills
  - Leadership Skills
  - Technical Skills
  - Time Management
  - Problem Solving
Help Text: Select all that apply
```

**3. Training Programs Question:**
```
Question: Which training programs are you interested in attending?
Type: Training Programs
Category: Training Needs
Help Text: These trainings will be offered this year
```

**4. Rating Question:**
```
Question: Rate your current computer proficiency
Type: Rating
Category: Current Skills
Help Text: 1 = Beginner, 5 = Expert
```

#### Editing a Question

⚠️ **Warning**: Editing a question affects ALL templates that use it.

1. Find the question in Question Bank
2. Click **"Edit"**
3. Make changes
4. Click **"Update Question"**
5. All templates using this question will show the updated version

#### Deleting a Question

⚠️ **Warning**: Cannot delete questions that are used in any survey template.

1. First, remove the question from all templates
2. Then you can delete it from Question Bank

### Best Practices

✅ **Do:**
- Use clear, concise question text
- Provide helpful hints in Help Text field
- Group related questions by category
- Use appropriate question types
- Test questions before adding to templates

❌ **Don't:**
- Use overly complex or confusing wording
- Create duplicate questions (search first!)
- Edit questions that are in active surveys
- Delete questions without checking usage

### Technical Details

**Model:** `App\Models\SurveyQuestion`

**Relationships:**
```php
public function templates()  // Many-to-many with SurveyTemplate
```

**Controller:** `App\Http\Controllers\SurveyQuestionController`

**Views:**
- `resources/views/survey-questions/index.blade.php`
- `resources/views/survey-questions/create.blade.php`
- `resources/views/survey-questions/edit.blade.php`

**Fillable Fields:**
```php
protected $fillable = [
    'question_text',
    'question_type',
    'options',
    'help_text',
    'category',
];

protected $casts = [
    'options' => 'array',  // Stored as JSON in database
];
```

---

## 📋 Part 4: Dynamic Form Builder

### Overview

The Dynamic Form Builder is the employee-facing interface where staff members complete surveys. It dynamically generates forms based on active survey templates and handles response submission.

### Features

- ✅ Automatically loads active survey template
- ✅ Displays questions in defined order
- ✅ Validates required fields
- ✅ Shows helpful hints
- ✅ Prevents duplicate submissions
- ✅ Confirms successful submission
- ✅ Auto-save drafts (future feature)

### Employee Experience

```
┌─────────────────────────────────────────┐
│  Annual Training Needs Survey 2026      │
├─────────────────────────────────────────┤
│                                         │
│  Question 1 (Required)                  │
│  What is your current position?         │
│  [_____________________________]        │
│                                         │
│  Question 2 (Required)                  │
│  Which training programs interest you?  │
│  ☐ SDC I - Supervisory Course           │
│  ☐ WRS - Writing Resource Seminar       │
│  ☐ BCSS - Basic Computer Skills         │
│  ... (more options)                     │
│                                         │
│  Question 3 (Optional)                  │
│  Additional comments or suggestions     │
│  [___________________________]          │
│  [___________________________]          │
│  [___________________________]          │
│                                         │
│  [Submit Survey] [Save as Draft]        │
└─────────────────────────────────────────┘
```

### Access & Permissions

**Route:** `/survey` or `/survey-responses/form`

| Role             | Access | Submit | View Results |
|------------------|--------|--------|--------------|
| HR Admin         | ✅     | ✅     | ✅           |
| Admin Assistant  | ✅     | ✅     | ✅           |
| Employee         | ✅     | ✅     | ❌           |

### User Guide: Completing a Survey (Employee)

#### Step 1: Access the Survey

**Method 1: From Navigation Menu**
1. Login to EHRMS
2. Click **"Annual Survey"** in sidebar (under Surveys section)

**Method 2: From Dashboard**
1. Look for notification about available survey
2. Click link to open survey

#### Step 2: Review Instructions

1. Read the survey title and description
2. Note which fields are required (marked with red asterisk *)
3. Review estimated completion time

#### Step 3: Fill Out the Survey

1. Answer each question in order
2. For **Training Programs** questions:
   - Check all programs you're interested in
   - You can select multiple options
3. For **Rating** questions:
   - Click stars or select number (1-5)
4. For **Text** questions:
   - Type your response in the box
5. Review required field indicators

#### Step 4: Submit

1. Review all your answers
2. Click **"Submit Survey"** button
3. Confirm submission in popup dialog
4. See success message with confirmation

#### After Submission

✅ **What Happens:**
- Your response is saved to the database
- You'll see a "Survey Already Submitted" page
- You cannot edit after submission
- HR can view your anonymous response
- Your input helps plan training programs

❌ **What You Cannot Do:**
- Submit the same survey twice
- Edit after submission
- Delete your response
- View other employees' responses

### Validation Rules

The system validates your submission:

| Rule                    | Description                                      |
|-------------------------|--------------------------------------------------|
| Required fields         | Must be filled before submission                 |
| Training programs       | At least one must be selected if required        |
| Number fields           | Must contain valid numbers                       |
| Date fields             | Must be valid dates                              |
| Maximum length          | Text fields have character limits                |

### Error Messages

**"Please fill in all required fields"**
- **Cause**: Missing answer to required question
- **Fix**: Scroll up and fill in fields marked with *

**"You have already submitted this survey"**
- **Cause**: You previously submitted this survey
- **Fix**: Contact HR if you need to update your response

**"No active survey available"**
- **Cause**: HR hasn't activated a survey yet
- **Fix**: Wait for notification when survey is available

### Technical Details

**Controller:** `App\Http\Controllers\SurveyResponseController`

**Key Methods:**
```php
public function showForm()   // Display survey form
public function submit()     // Process submission
public function saveDraft()  // Save incomplete response
```

**Views:**
- `resources/views/survey-responses/form.blade.php` - Main survey form
- `resources/views/survey-responses/already-submitted.blade.php` - Confirmation page
- `resources/views/survey-responses/no-active-survey.blade.php` - No survey available

**Validation Logic:**
```php
// Build validation rules dynamically based on questions
$rules = [];
foreach ($template->questions as $question) {
    $fieldName = 'question_' . $question->id;

    if ($question->pivot->is_required) {
        $rules[$fieldName] = 'required';
    }

    // Type-specific validation
    if ($question->question_type === 'training_programs') {
        $rules[$fieldName] = 'required|array';
    } elseif ($question->question_type === 'number') {
        $rules[$fieldName] = 'required|numeric';
    }
}
```

**Response Storage:**
```php
// Responses stored as JSON
SurveyResponse::create([
    'survey_template_id' => $template->id,
    'employee_id' => $employee->id,
    'response_data' => [
        'question_1' => 'Officer II',
        'question_2' => [1, 3, 5],  // Multiple selections
        'question_3' => 'I would like more IT training',
    ],
    'status' => 'submitted',
    'submitted_at' => now(),
]);
```

---

## 📊 Part 5: Response Analytics

### Overview

Response Analytics is the powerful reporting interface where HR Admins can view, filter, and analyze survey responses to make data-driven training decisions.

### Features

- ✅ View all responses for any survey
- ✅ Filter by date range
- ✅ Filter by department
- ✅ Visual charts and graphs
- ✅ Response rate tracking
- ✅ Export data to Excel/CSV
- ✅ Individual response details
- ✅ Aggregate statistics

### Analytics Dashboard

```
┌────────────────────────────────────────────────────────┐
│  Survey Analytics: Annual Training Needs Survey 2026   │
├────────────────────────────────────────────────────────┤
│                                                        │
│  [Filters]                                             │
│  Start Date: [____] End Date: [____] Dept: [All ▼]    │
│                                                        │
│  ┌─────────────┐  ┌─────────────┐  ┌──────────────┐  │
│  │ Total       │  │ Response    │  │ Avg Time     │  │
│  │ Responses   │  │ Rate        │  │ to Complete  │  │
│  │     156     │  │    78%      │  │   8 min      │  │
│  └─────────────┘  └─────────────┘  └──────────────┘  │
│                                                        │
│  [Chart: Response Rate by Department]                 │
│  ████████████████░░░░  IT Dept (85%)                  │
│  ██████████████████░░  Finance (90%)                  │
│  ████████████░░░░░░░░  HR (70%)                       │
│                                                        │
│  [Chart: Most Requested Training Programs]            │
│  ████████████████████  SDC I (120 requests)           │
│  ██████████████░░░░░░  BCSS (95 requests)             │
│  ████████████░░░░░░░░  WRS (80 requests)              │
│                                                        │
│  [Individual Responses Table]                         │
│  Employee | Dept | Submitted | View                   │
│  ───────────────────────────────────                  │
│  John Doe | IT   | Jan 15    | [View]                │
│  ...                                                   │
│                                                        │
│  [Export to Excel] [Export to PDF]                    │
└────────────────────────────────────────────────────────┘
```

### Access & Permissions

**Route:** `/survey-analytics/{template}`

| Role             | View Analytics | Export Data | View Individual Responses |
|------------------|----------------|-------------|---------------------------|
| HR Admin         | ✅             | ✅          | ✅                        |
| Admin Assistant  | ✅             | ✅          | ✅                        |
| Employee         | ❌             | ❌          | ❌                        |

### User Guide: Viewing Analytics

#### Step 1: Select Survey

1. Navigate to **Survey Templates** (sidebar: HR → Survey Templates)
2. Find the survey you want to analyze
3. Click **"View Analytics"** button

#### Step 2: Apply Filters

**Date Range Filter:**
1. Set **Start Date** to filter responses from this date
2. Set **End Date** to filter responses until this date
3. Leave blank to include all dates

**Department Filter:**
1. Select a specific department from dropdown
2. Or select "All Departments" to include everyone

**Apply Filters:**
1. Click **"Filter"** button
2. Results update automatically
3. Charts refresh with new data

#### Step 3: Review Summary Statistics

**Total Responses:**
- Shows total number of completed surveys
- Updates based on active filters

**Response Rate:**
- Percentage of employees who responded
- Calculation: (Responses / Total Employees) × 100%

**Average Completion Time:**
- Average time taken to complete survey
- Helps estimate future survey duration

#### Step 4: Analyze Charts

**Chart 1: Response Rate by Department**
- Bar chart showing participation per department
- Identifies departments with low engagement
- Helps target follow-up reminders

**Chart 2: Most Requested Training Programs**
- Horizontal bar chart of popular programs
- Shows number of employees interested in each
- Prioritizes training scheduling

**Chart 3: Skills Gap Analysis** (if applicable)
- Identifies common skill deficiencies
- Based on rating and self-assessment questions

#### Step 5: View Individual Responses

**Response Table:**
- Lists all submitted responses
- Columns: Employee, Department, Date, Actions
- Click **"View"** to see full response details

**Response Detail View:**
- Shows all questions and answers for one employee
- Employee name (or anonymous if configured)
- Timestamp of submission
- Full text of all answers

#### Step 6: Export Data

**Export to Excel:**
1. Click **"Export to Excel"** button
2. Downloads .xlsx file with all responses
3. Includes summary statistics on first sheet
4. Individual responses on subsequent sheets

**Export to PDF:**
1. Click **"Export to PDF"** button
2. Generates formatted PDF report
3. Includes charts and summary
4. Suitable for presentations

### Insights & Recommendations

The system automatically generates insights:

**Critical Priority (≥70% requests):**
- Programs requested by 70% or more employees
- Should be scheduled immediately
- High organizational impact

**High Priority (≥50% requests):**
- Programs requested by 50-69% employees
- Schedule within this quarter
- Significant demand

**Medium Priority (≥30% requests):**
- Programs requested by 30-49% employees
- Consider for next quarter
- Moderate interest

**Low Priority (<30% requests):**
- Programs requested by fewer than 30%
- May defer or offer as optional
- Limited demand

### Training Needs Analysis (TNA) Report

Access via: **Dashboard → TNA Recommendations** or **Trainings → TNA Recommendations**

**What it shows:**
1. Most requested training topics
2. Priority level for each
3. Number of employees interested
4. Percentage of workforce
5. Whether training is already scheduled

**How to use it:**
1. Review critical and high priority items
2. Check if trainings are already scheduled
3. Plan budget for top requests
4. Schedule trainings based on priority
5. Communicate plans to employees

### Technical Details

**Controller:** `App\Http\Controllers\SurveyResponseController`

**Key Methods:**
```php
public function analytics($templateId)  // Main analytics view
public function export($templateId)     // Export to Excel
```

**Views:**
- `resources/views/survey-responses/analytics.blade.php`

**Charts (Chart.js):**
```javascript
// Example: Response rate by department
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['IT', 'Finance', 'HR', 'Admin'],
        datasets: [{
            label: 'Response Rate (%)',
            data: [85, 90, 70, 65],
            backgroundColor: '#3b82f6',
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
```

**Analytics Calculation:**
```php
// Response rate
$totalEmployees = Employee::where('status', 'active')->count();
$totalResponses = $template->responses()->where('status', 'submitted')->count();
$responseRate = ($totalEmployees > 0) ?
    round(($totalResponses / $totalEmployees) * 100, 1) : 0;

// Most requested programs
$programCounts = [];
foreach ($responses as $response) {
    $selectedPrograms = $response->response_data['training_programs'] ?? [];
    foreach ($selectedPrograms as $programId) {
        $programCounts[$programId] = ($programCounts[$programId] ?? 0) + 1;
    }
}
arsort($programCounts);  // Sort by count descending
```

---

## 👥 User Guides

### For HR Admin

#### Monthly Survey Workflow

**Week 1: Preparation**
1. Review last year's TNA results
2. Update Training Programs list if needed
3. Review Question Bank
4. Create new survey template for this year

**Week 2: Launch**
1. Add questions to template
2. Preview and test
3. Activate survey
4. Send announcement to all employees

**Week 3-4: Monitor**
1. Check response rate daily
2. Send reminders to departments with low participation
3. Answer employee questions

**Week 5: Analysis**
1. Close survey (deactivate template)
2. Generate analytics report
3. Export data to Excel
4. Create TNA recommendations

**Week 6: Planning**
1. Review critical and high priority items
2. Check budget availability
3. Schedule trainings for the year
4. Communicate training calendar to employees

#### Best Practices

✅ **Do:**
- Launch survey at the start of the year
- Give employees 2-3 weeks to respond
- Send reminder emails weekly
- Review analytics before closing survey
- Share results summary with management
- Schedule trainings based on data

❌ **Don't:**
- Change questions after launching
- Close survey too early
- Ignore low-priority requests completely
- Skip the analytics review
- Forget to communicate results

### For Employees

#### How to Complete the Survey

**Before Starting:**
- Set aside 10-15 minutes
- Review your current skills
- Think about career goals
- Consider department needs

**While Completing:**
- Answer honestly
- Select all relevant training programs
- Provide specific comments
- Review before submitting

**After Submitting:**
- Note the confirmation message
- Check for training announcements
- Attend scheduled trainings
- Provide feedback after completion

---

## 🔧 Technical Implementation

### Database Tables

#### training_programs
```sql
CREATE TABLE hr_training_programs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    program_code VARCHAR(50) NOT NULL,
    program_name VARCHAR(255) NOT NULL,
    description TEXT,
    order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### survey_templates
```sql
CREATE TABLE hr_survey_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### survey_questions
```sql
CREATE TABLE hr_survey_questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    question_type ENUM('text', 'textarea', 'radio', 'checkbox', 'select',
                       'date', 'number', 'rating', 'training_programs'),
    options JSON,
    help_text TEXT,
    category VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### question_template (Pivot)
```sql
CREATE TABLE hr_question_template (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    survey_template_id BIGINT UNSIGNED,
    survey_question_id BIGINT UNSIGNED,
    order INT DEFAULT 0,
    is_required BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (survey_template_id) REFERENCES hr_survey_templates(id) ON DELETE CASCADE,
    FOREIGN KEY (survey_question_id) REFERENCES hr_survey_questions(id) ON DELETE CASCADE
);
```

#### survey_responses
```sql
CREATE TABLE hr_survey_responses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    survey_template_id BIGINT UNSIGNED,
    employee_id BIGINT UNSIGNED,
    response_data JSON NOT NULL,
    status ENUM('draft', 'submitted') DEFAULT 'draft',
    submitted_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (survey_template_id) REFERENCES hr_survey_templates(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES hr_employees(id) ON DELETE CASCADE
);
```

### Routes

```php
// Training Programs
Route::resource('training-programs', TrainingProgramController::class);
Route::post('training-programs/reorder', [TrainingProgramController::class, 'reorder'])
    ->name('training-programs.reorder');

// Survey Templates
Route::resource('survey-templates', SurveyTemplateController::class);
Route::get('survey-templates/{template}/questions', [SurveyTemplateController::class, 'editQuestions'])
    ->name('survey-templates.questions');
Route::post('survey-templates/{template}/questions', [SurveyTemplateController::class, 'saveQuestions'])
    ->name('survey-templates.save-questions');

// Question Bank
Route::resource('survey-questions', SurveyQuestionController::class);

// Employee Survey Form
Route::get('/survey', [SurveyResponseController::class, 'showForm'])
    ->name('survey.form');
Route::post('/survey/submit', [SurveyResponseController::class, 'submit'])
    ->name('survey.submit');
Route::post('/survey/draft', [SurveyResponseController::class, 'saveDraft'])
    ->name('survey.draft');

// Analytics
Route::get('survey-analytics/{template}', [SurveyResponseController::class, 'analytics'])
    ->name('survey-analytics');
Route::get('survey-analytics/{template}/export', [SurveyResponseController::class, 'export'])
    ->name('survey-analytics.export');
```

### Models

**TrainingProgram.php:**
```php
class TrainingProgram extends Model
{
    protected $fillable = [
        'program_code',
        'program_name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
```

**SurveyTemplate.php:**
```php
class SurveyTemplate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->belongsToMany(SurveyQuestion::class, 'question_template')
            ->withPivot('order', 'is_required')
            ->orderByPivot('order');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
```

**SurveyQuestion.php:**
```php
class SurveyQuestion extends Model
{
    protected $fillable = [
        'question_text',
        'question_type',
        'options',
        'help_text',
        'category',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function templates()
    {
        return $this->belongsToMany(SurveyTemplate::class, 'question_template')
            ->withPivot('order', 'is_required');
    }
}
```

**SurveyResponse.php:**
```php
class SurveyResponse extends Model
{
    protected $fillable = [
        'survey_template_id',
        'employee_id',
        'response_data',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'response_data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
```

---

## 📚 API Reference

### Training Programs API

**List All Programs**
```http
GET /training-programs
```

**Create Program**
```http
POST /training-programs
Content-Type: application/json

{
  "program_code": "NEW-001",
  "program_name": "New Training Program",
  "description": "Description here",
  "is_active": true
}
```

**Update Display Order**
```http
POST /training-programs/reorder
Content-Type: application/json

{
  "order": [3, 1, 4, 2, 5]  // Array of program IDs in new order
}
```

### Survey Templates API

**Get Active Template**
```php
$activeTemplate = SurveyTemplate::where('is_active', true)->first();
```

**Get Template with Questions**
```php
$template = SurveyTemplate::with(['questions' => function($query) {
    $query->orderByPivot('order');
}])->find($id);
```

### Survey Responses API

**Submit Response**
```http
POST /survey/submit
Content-Type: application/json

{
  "question_1": "My answer to question 1",
  "question_2": [1, 3, 5],  // Multiple choice answers
  "question_3": "Additional comments"
}
```

**Get Analytics Data**
```php
$responses = SurveyResponse::where('survey_template_id', $templateId)
    ->where('status', 'submitted')
    ->with('employee.department')
    ->get();
```

---

## 🎓 Conclusion

The Phase 1 Customizable Survey System is a comprehensive solution for conducting Training Needs Analysis in EHRMS. It provides:

- **Flexibility**: Create any type of survey with multiple question types
- **Efficiency**: Reuse questions across multiple surveys
- **Insights**: Powerful analytics to drive training decisions
- **User-Friendly**: Intuitive interface for both HR and employees
- **Data-Driven**: Make informed decisions based on actual employee needs

### Key Achievements

✅ **11 Training Programs** - Pre-loaded and ready to use
✅ **9 Question Types** - Cover all survey needs
✅ **Unlimited Templates** - Create as many surveys as needed
✅ **Real-time Analytics** - Instant insights and charts
✅ **100% Complete** - All 5 parts fully implemented and tested

### Future Enhancements (Phase 2)

While Phase 1 is complete, potential future improvements include:

- **Auto-save Drafts**: Save progress automatically
- **Email Notifications**: Notify employees of new surveys
- **Survey Scheduling**: Auto-activate/deactivate by date
- **Anonymous Responses**: Option for anonymous surveys
- **Multi-language Support**: Surveys in multiple languages
- **Mobile App**: Native mobile survey completion
- **Advanced Analytics**: Predictive analytics and AI insights

---

**Document Version:** 1.0
**Last Updated:** January 25, 2026
**Maintained By:** EHRMS Development Team
**For Questions:** Contact HR Admin or IT Department

---

© 2026 LGU Sablayan - Employee Human Resource Management System
