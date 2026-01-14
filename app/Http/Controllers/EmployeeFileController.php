<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeFileController extends Controller
{
    public function myFiles()
    {
        // TODO: Implement
        return view('files.my-files');
    }

    public function upload(Request $request)
    {
        // TODO: Implement file upload
        return back()->with('info', 'File upload feature coming soon.');
    }
}
