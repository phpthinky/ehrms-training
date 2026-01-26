<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ExternalTrainingRequest;
use App\Models\Notification;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExternalTrainingRequestController extends Controller
{
    /**
     * Display listing of external training requests (HR view)
     */
    public function index(Request $request)
    {
        $query = ExternalTrainingRequest::with(['employee.department', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('training_title', 'like', "%{$search}%")
                    ->orWhere('training_provider', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($eq) use ($search) {
                        $eq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => ExternalTrainingRequest::count(),
            'pending' => ExternalTrainingRequest::pending()->count(),
            'approved' => ExternalTrainingRequest::approved()->count(),
            'rejected' => ExternalTrainingRequest::rejected()->count(),
            'completed' => ExternalTrainingRequest::completed()->count(),
        ];

        $departments = \App\Models\Department::orderBy('name')->get();

        return view('external-training-requests.index', compact('requests', 'stats', 'departments'));
    }

    /**
     * Show form for employee to create new request
     */
    public function create()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        return view('external-training-requests.create', compact('employee'));
    }

    /**
     * Store a new request
     */
    public function store(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        $validated = $request->validate([
            'training_title' => 'required|string|max:255',
            'training_description' => 'nullable|string|max:1000',
            'training_provider' => 'nullable|string|max:255',
            'training_venue' => 'nullable|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'purpose' => 'nullable|string|max:1000',
            'request_form' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'department_head_letter' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'requesting_party_document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'action' => 'required|in:draft,submit',
        ]);

        DB::beginTransaction();

        try {
            // Create upload folder
            $uploadFolder = 'external_training_requests/' . $employee->employee_number;
            $fullPath = public_path('uploads/' . $uploadFolder);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Upload request form
            $requestForm = $request->file('request_form');
            $requestFormName = time() . '_request_form.' . $requestForm->getClientOriginalExtension();
            $requestForm->move($fullPath, $requestFormName);

            // Upload department head letter
            $deptLetter = $request->file('department_head_letter');
            $deptLetterName = time() . '_dept_head_letter.' . $deptLetter->getClientOriginalExtension();
            $deptLetter->move($fullPath, $deptLetterName);

            // Upload requesting party document
            $partyDoc = $request->file('requesting_party_document');
            $partyDocName = time() . '_requesting_party.' . $partyDoc->getClientOriginalExtension();
            $partyDoc->move($fullPath, $partyDocName);

            // Create the request
            $trainingRequest = ExternalTrainingRequest::create([
                'employee_id' => $employee->id,
                'training_title' => $validated['training_title'],
                'training_description' => $validated['training_description'],
                'training_provider' => $validated['training_provider'],
                'training_venue' => $validated['training_venue'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'estimated_cost' => $validated['estimated_cost'],
                'purpose' => $validated['purpose'],
                'request_form_path' => $uploadFolder . '/' . $requestFormName,
                'request_form_name' => $requestForm->getClientOriginalName(),
                'department_head_letter_path' => $uploadFolder . '/' . $deptLetterName,
                'department_head_letter_name' => $deptLetter->getClientOriginalName(),
                'requesting_party_document_path' => $uploadFolder . '/' . $partyDocName,
                'requesting_party_document_name' => $partyDoc->getClientOriginalName(),
                'status' => $validated['action'] === 'submit' ? 'pending' : 'draft',
            ]);

            // Create notification for HR if submitted
            if ($validated['action'] === 'submit') {
                $this->notifyHR($trainingRequest);
            }

            DB::commit();

            $message = $validated['action'] === 'submit'
                ? 'External training request submitted successfully. HR will review your request.'
                : 'External training request saved as draft.';

            return redirect()->route('my-external-training-requests')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    /**
     * Display a specific request
     */
    public function show(ExternalTrainingRequest $externalTrainingRequest)
    {
        $user = auth()->user();

        // Check authorization
        if (!$user->isStaff()) {
            if (!$user->employee || $user->employee->id != $externalTrainingRequest->employee_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $externalTrainingRequest->load(['employee.department', 'reviewer', 'training']);

        return view('external-training-requests.show', compact('externalTrainingRequest'));
    }

    /**
     * Show form to edit a draft/rejected request
     */
    public function edit(ExternalTrainingRequest $externalTrainingRequest)
    {
        $user = auth()->user();

        // Only owner can edit
        if (!$user->employee || $user->employee->id != $externalTrainingRequest->employee_id) {
            abort(403, 'Unauthorized access');
        }

        // Can only edit draft or rejected
        if (!$externalTrainingRequest->canBeEdited()) {
            return back()->with('error', 'This request cannot be edited.');
        }

        $employee = $user->employee;

        return view('external-training-requests.edit', compact('externalTrainingRequest', 'employee'));
    }

    /**
     * Update a draft/rejected request
     */
    public function update(Request $request, ExternalTrainingRequest $externalTrainingRequest)
    {
        $user = auth()->user();

        // Only owner can update
        if (!$user->employee || $user->employee->id != $externalTrainingRequest->employee_id) {
            abort(403, 'Unauthorized access');
        }

        if (!$externalTrainingRequest->canBeEdited()) {
            return back()->with('error', 'This request cannot be edited.');
        }

        $validated = $request->validate([
            'training_title' => 'required|string|max:255',
            'training_description' => 'nullable|string|max:1000',
            'training_provider' => 'nullable|string|max:255',
            'training_venue' => 'nullable|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'purpose' => 'nullable|string|max:1000',
            'request_form' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'department_head_letter' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'requesting_party_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'action' => 'required|in:draft,submit',
        ]);

        DB::beginTransaction();

        try {
            $employee = $user->employee;
            $uploadFolder = 'external_training_requests/' . $employee->employee_number;
            $fullPath = public_path('uploads/' . $uploadFolder);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            $updateData = [
                'training_title' => $validated['training_title'],
                'training_description' => $validated['training_description'],
                'training_provider' => $validated['training_provider'],
                'training_venue' => $validated['training_venue'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'estimated_cost' => $validated['estimated_cost'],
                'purpose' => $validated['purpose'],
                'status' => $validated['action'] === 'submit' ? 'pending' : 'draft',
            ];

            // If resubmitting a rejected request, clear the rejection reason
            if ($externalTrainingRequest->status === 'rejected' && $validated['action'] === 'submit') {
                $updateData['rejection_reason'] = null;
            }

            // Handle file uploads if new files provided
            if ($request->hasFile('request_form')) {
                // Delete old file
                $this->deleteFile($externalTrainingRequest->request_form_path);

                $file = $request->file('request_form');
                $fileName = time() . '_request_form.' . $file->getClientOriginalExtension();
                $file->move($fullPath, $fileName);
                $updateData['request_form_path'] = $uploadFolder . '/' . $fileName;
                $updateData['request_form_name'] = $file->getClientOriginalName();
            }

            if ($request->hasFile('department_head_letter')) {
                $this->deleteFile($externalTrainingRequest->department_head_letter_path);

                $file = $request->file('department_head_letter');
                $fileName = time() . '_dept_head_letter.' . $file->getClientOriginalExtension();
                $file->move($fullPath, $fileName);
                $updateData['department_head_letter_path'] = $uploadFolder . '/' . $fileName;
                $updateData['department_head_letter_name'] = $file->getClientOriginalName();
            }

            if ($request->hasFile('requesting_party_document')) {
                $this->deleteFile($externalTrainingRequest->requesting_party_document_path);

                $file = $request->file('requesting_party_document');
                $fileName = time() . '_requesting_party.' . $file->getClientOriginalExtension();
                $file->move($fullPath, $fileName);
                $updateData['requesting_party_document_path'] = $uploadFolder . '/' . $fileName;
                $updateData['requesting_party_document_name'] = $file->getClientOriginalName();
            }

            $externalTrainingRequest->update($updateData);

            // Notify HR if submitted
            if ($validated['action'] === 'submit') {
                $this->notifyHR($externalTrainingRequest);
            }

            DB::commit();

            $message = $validated['action'] === 'submit'
                ? 'External training request resubmitted successfully.'
                : 'External training request updated.';

            return redirect()->route('my-external-training-requests')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update request: ' . $e->getMessage());
        }
    }

    /**
     * Delete a draft request
     */
    public function destroy(ExternalTrainingRequest $externalTrainingRequest)
    {
        $user = auth()->user();

        // Only owner can delete, and only drafts
        if (!$user->employee || $user->employee->id != $externalTrainingRequest->employee_id) {
            abort(403, 'Unauthorized access');
        }

        if ($externalTrainingRequest->status !== 'draft') {
            return back()->with('error', 'Only draft requests can be deleted.');
        }

        // Delete files
        $this->deleteFile($externalTrainingRequest->request_form_path);
        $this->deleteFile($externalTrainingRequest->department_head_letter_path);
        $this->deleteFile($externalTrainingRequest->requesting_party_document_path);

        $externalTrainingRequest->delete();

        return redirect()->route('my-external-training-requests')
            ->with('success', 'Request deleted successfully.');
    }

    /**
     * Employee's own requests
     */
    public function myRequests()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        $requests = ExternalTrainingRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('external-training-requests.my-requests', compact('requests'));
    }

    /**
     * HR review form
     */
    public function review(ExternalTrainingRequest $externalTrainingRequest)
    {
        if (!$externalTrainingRequest->canBeReviewed()) {
            return back()->with('error', 'This request cannot be reviewed.');
        }

        $externalTrainingRequest->load(['employee.department']);

        return view('external-training-requests.review', compact('externalTrainingRequest'));
    }

    /**
     * Process HR approval/rejection
     */
    public function processReview(Request $request, ExternalTrainingRequest $externalTrainingRequest)
    {
        if (!$externalTrainingRequest->canBeReviewed()) {
            return back()->with('error', 'This request cannot be reviewed.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:1000',
            'hr_remarks' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            if ($validated['action'] === 'approve') {
                // Create a training record
                $training = Training::create([
                    'training_topic_id' => null,
                    'created_by' => auth()->id(),
                    'title' => $externalTrainingRequest->training_title,
                    'description' => $externalTrainingRequest->training_description,
                    'type' => 'external',
                    'rank_level' => 'all',
                    'venue' => $externalTrainingRequest->training_venue,
                    'facilitator' => $externalTrainingRequest->training_provider,
                    'start_date' => $externalTrainingRequest->start_date,
                    'end_date' => $externalTrainingRequest->end_date,
                    'requested_by' => $externalTrainingRequest->employee->user_id,
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'status' => 'scheduled',
                    'notes' => 'Created from external training request #' . $externalTrainingRequest->id,
                ]);

                $externalTrainingRequest->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'hr_remarks' => $validated['hr_remarks'],
                    'training_id' => $training->id,
                ]);

                // Notify employee of approval
                $this->notifyEmployee($externalTrainingRequest, 'approved');

                $message = 'Request approved. Training record created.';
            } else {
                $externalTrainingRequest->update([
                    'status' => 'rejected',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'],
                    'hr_remarks' => $validated['hr_remarks'],
                ]);

                // Notify employee of rejection
                $this->notifyEmployee($externalTrainingRequest, 'rejected');

                $message = 'Request rejected. Employee has been notified.';
            }

            DB::commit();

            return redirect()->route('external-training-requests.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process review: ' . $e->getMessage());
        }
    }

    /**
     * Mark request as completed
     */
    public function markCompleted(ExternalTrainingRequest $externalTrainingRequest)
    {
        if ($externalTrainingRequest->status !== 'approved') {
            return back()->with('error', 'Only approved requests can be marked as completed.');
        }

        $externalTrainingRequest->update(['status' => 'completed']);

        // Also update the training status if exists
        if ($externalTrainingRequest->training) {
            $externalTrainingRequest->training->update(['status' => 'completed']);
        }

        return back()->with('success', 'Request marked as completed.');
    }

    /**
     * Download a document
     */
    public function downloadDocument(ExternalTrainingRequest $externalTrainingRequest, string $type)
    {
        $user = auth()->user();

        // Check authorization
        if (!$user->isStaff()) {
            if (!$user->employee || $user->employee->id != $externalTrainingRequest->employee_id) {
                abort(403, 'Unauthorized access');
            }
        }

        $pathField = match ($type) {
            'request_form' => 'request_form_path',
            'department_head_letter' => 'department_head_letter_path',
            'requesting_party_document' => 'requesting_party_document_path',
            default => null,
        };

        $nameField = match ($type) {
            'request_form' => 'request_form_name',
            'department_head_letter' => 'department_head_letter_name',
            'requesting_party_document' => 'requesting_party_document_name',
            default => null,
        };

        if (!$pathField || !$externalTrainingRequest->$pathField) {
            abort(404, 'Document not found.');
        }

        $filePath = public_path('uploads/' . $externalTrainingRequest->$pathField);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }

        return response()->download($filePath, $externalTrainingRequest->$nameField);
    }

    /**
     * Delete a file
     */
    private function deleteFile(?string $path): void
    {
        if ($path) {
            $fullPath = public_path('uploads/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    /**
     * Notify HR staff of new request
     */
    private function notifyHR(ExternalTrainingRequest $request): void
    {
        $hrUsers = \App\Models\User::whereIn('role', ['hr_admin', 'admin_assistant'])->get();

        foreach ($hrUsers as $hrUser) {
            Notification::create([
                'user_id' => $hrUser->id,
                'type' => 'external_training_request',
                'title' => 'New External Training Request',
                'message' => $request->employee->full_name . ' submitted an external training request: ' . $request->training_title,
                'data' => json_encode(['request_id' => $request->id]),
            ]);
        }
    }

    /**
     * Notify employee of review result
     */
    private function notifyEmployee(ExternalTrainingRequest $request, string $status): void
    {
        $title = $status === 'approved'
            ? 'External Training Request Approved'
            : 'External Training Request Rejected';

        $message = $status === 'approved'
            ? 'Your external training request "' . $request->training_title . '" has been approved.'
            : 'Your external training request "' . $request->training_title . '" has been rejected. Reason: ' . $request->rejection_reason;

        Notification::create([
            'user_id' => $request->employee->user_id,
            'type' => 'external_training_' . $status,
            'title' => $title,
            'message' => $message,
            'data' => json_encode(['request_id' => $request->id]),
        ]);
    }
}
