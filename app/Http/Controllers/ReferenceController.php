<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\Tag;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{
    // Register a new user
    public function getByKey(Request $request, Reference $reference, Tag $tag)
    {
        $data = [];
        foreach ($request->keys as $refKey) {
            match (true) {
                in_array($refKey, $reference->getAvailableKeys()) => $data[$refKey] = $reference->getRefItems($refKey),
                $refKey == 'tag' => $data[$refKey] = $tag->getTags(),
            };
        }
        return response()->json($data);
    }
}
