<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    // Register a new user
    public function getByKey(Request $request, Reference $reference)
    {
        $data = [];
        foreach ($request->keys as $refKey) {
            $data[$refKey] = $reference->getRefItems($refKey);
        }
        return response()->json($data);
    }
}
