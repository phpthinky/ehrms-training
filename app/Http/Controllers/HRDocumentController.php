<?php

namespace App\Http\Controllers;

use App\Models\HRDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HRDocumentController extends Controller
{
    /**
     * Display a listing of HR documents
     */
    public function index()
    {
        $documents = HRDocument::orderBy('created_at', 'desc')->paginate(20);
        
        // Get statistics
        $stats = [
            'total' => HRDocument::count(),
            'policies' => HRDocument::where('category', 'policy')->count(),
            'memos' => HRDocument::where('category', 'memo')->count(),
            'forms' => HRDocument::where('category', 'form')->count(),
            'others' => HRDocument::where('category', 'other')->count(),
        ];
        
        return view('hr-documents.index', compact('documents', 'stats'));
    }

    /**
     * Show the form for creating a new document
     */
    public function create()
    {
        return view('hr-documents.create');
    }

    /**
     * Store a newly created document
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:policy,memo,form,guideline,manual,template,report,letter,other',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480', // 20MB max
            'is_confidential' => 'boolean',
            'effective_date' => 'nullable|date',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename with timestamp
            $timestamp = now()->format('Ymd_His');
            $filename = $timestamp . '_' . str_replace(' ', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Store in hr_documents directory
            $file->move(public_path('uploads/hr_documents'), $filename);
            $filePath = 'hr_documents/' . $filename;
            
            // Get file size in KB
            $fileSize = round($file->getSize() / 1024, 2);
            
            // Create document record
            $document = HRDocument::create([
                'title' => $validated['title'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'file_name' => $originalName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_type' => $extension,
                'is_confidential' => $request->has('is_confidential'),
                'effective_date' => $validated['effective_date'] ?? null,
                'uploaded_by' => auth()->id(),
            ]);
            
            return redirect()->route('hr-documents.index')
                ->with('success', 'Document uploaded successfully!');
        }
        
        return back()->with('error', 'File upload failed. Please try again.');
    }

    /**
     * Display the specified document
     */
    public function show(HRDocument $hrDocument)
    {
        // Load the uploader relationship
        $hrDocument->load('uploader');
        
        return view('hr-documents.show', compact('hrDocument'));
    }

    /**
     * Show the form for editing the specified document
     */
    public function edit(HRDocument $hrDocument)
    {
        return view('hr-documents.edit', compact('hrDocument'));
    }

    /**
     * Update the specified document
     */
    public function update(Request $request, HRDocument $hrDocument)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:policy,memo,form,guideline,manual,template,report,letter,other',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:20480',
            'is_confidential' => 'boolean',
            'effective_date' => 'nullable|date',
        ]);

        // Handle file replacement if new file uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            $oldFilePath = public_path('uploads/' . $hrDocument->file_path);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
            
            // Upload new file
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            $timestamp = now()->format('Ymd_His');
            $filename = $timestamp . '_' . str_replace(' ', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            $file->move(public_path('uploads/hr_documents'), $filename);
            $filePath = 'hr_documents/' . $filename;
            $fileSize = round($file->getSize() / 1024, 2);
            
            // Update file info
            $validated['file_name'] = $originalName;
            $validated['file_path'] = $filePath;
            $validated['file_size'] = $fileSize;
            $validated['file_type'] = $extension;
        }
        
        $validated['is_confidential'] = $request->has('is_confidential');
        
        $hrDocument->update($validated);
        
        return redirect()->route('hr-documents.index')
            ->with('success', 'Document updated successfully!');
    }

    /**
     * Remove the specified document
     */
    public function destroy(HRDocument $hrDocument)
    {
        // Delete file from storage
        $filePath = public_path('uploads/' . $hrDocument->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $hrDocument->delete();
        
        return redirect()->route('hr-documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    /**
     * Download the specified document
     */
    public function download(HRDocument $hrDocument)
    {
        $filePath = public_path('uploads/' . $hrDocument->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File not found.');
        }
        
        return response()->download($filePath, $hrDocument->file_name);
    }
}
