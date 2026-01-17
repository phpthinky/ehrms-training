<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HRDocument extends Model
{
    use HasFactory;

    protected $table = 'hr_documents';

    protected $fillable = [
        'title',
        'category',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'is_confidential',
        'effective_date',
        'uploaded_by',
    ];

    protected $casts = [
        'is_confidential' => 'boolean',
        'effective_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who uploaded this document
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        if ($this->file_size < 1024) {
            return number_format($this->file_size, 2) . ' KB';
        }
        return number_format($this->file_size / 1024, 2) . ' MB';
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'policy' => 'Policy',
            'memo' => 'Memorandum',
            'form' => 'Form',
            'guideline' => 'Guideline',
            'manual' => 'Manual',
            'template' => 'Template',
            'report' => 'Report',
            'letter' => 'Letter',
            'other' => 'Other',
        ];

        return $categories[$this->category] ?? 'Unknown';
    }

    /**
     * Get category badge color
     */
    public function getCategoryColorAttribute()
    {
        $colors = [
            'policy' => 'primary',
            'memo' => 'info',
            'form' => 'success',
            'guideline' => 'warning',
            'manual' => 'secondary',
            'template' => 'dark',
            'report' => 'danger',
            'letter' => 'light',
            'other' => 'secondary',
        ];

        return $colors[$this->category] ?? 'secondary';
    }

    /**
     * Get file icon based on extension
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'bi-file-pdf-fill text-danger',
            'doc' => 'bi-file-word-fill text-primary',
            'docx' => 'bi-file-word-fill text-primary',
            'xls' => 'bi-file-excel-fill text-success',
            'xlsx' => 'bi-file-excel-fill text-success',
            'jpg' => 'bi-file-image-fill text-warning',
            'jpeg' => 'bi-file-image-fill text-warning',
            'png' => 'bi-file-image-fill text-warning',
        ];

        return $icons[$this->file_type] ?? 'bi-file-earmark-fill text-secondary';
    }
}
