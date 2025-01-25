<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAccessResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Register a new user
    public function getAccess(Request $request)
    {
        return new UserAccessResource(Auth::user());
    }

}
