<?php

namespace App\Http\Controllers;

use App\Services\SvgFillerService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // Register a new user
    public function index(Request $request)
    {
        $filler = new SvgFillerService();
        return $filler->makePrint();
    }
}
