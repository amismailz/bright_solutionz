<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
class AttachmentController extends Controller
{
    public function downloadPrivateFile($filename)
    {
        $fullPath = storage_path('app/private/' . $filename);
        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        }
        return response()->file($fullPath);
    }
    
}
