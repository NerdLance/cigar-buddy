<?php

namespace App\Http\Controllers;

use App\Models\Cigar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CigarController extends Controller
{
    public function index(Request $request)
    {  
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => "Unauthorized."], 401);
        }

        $cigars = Cigar::where('user_id', $user->id)->latest()->get();

        return response()->json($cigars, 200);
    }

    public function show(Request $request, int $cigar_id)
    {
        $user = $request->user();

        $cigar = Cigar::where('user_id', $user->id)->find($cigar_id);

        if (!$cigar) {
            return response()->json(['message' => 'Cigar not found.'], 404);
        }

        return response()->json($cigar, 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => "Unauthorized"], 401);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ring' => ['nullable', 'integer', 'max:200'],
            'length' => ['nullable', 'integer', 'max:200'],
            'notes' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'rating' => ['nullable', 'min:0', 'max:100'],
        ]);

        $new_cigar = new Cigar;
        $new_cigar->user_id = $user->id;
        $new_cigar->name = $request->name;
        $new_cigar->ring = $request->ring;
        $new_cigar->length = $request->length;
        $new_cigar->notes = $request->notes;
        $new_cigar->summary = $request->summary;
        $new_cigar->rating = $request->rating;
        $new_cigar->save();

        return response()->json($new_cigar, 200);
    }

    public function update(Request $request, int $cigar_id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ring' => ['nullable', 'integer', 'max:200'],
            'length' => ['nullable', 'integer', 'max:200'],
            'notes' => ['nullable', 'string'],
            'summary' => ['nullable', 'string'],
            'rating' => ['nullable', 'min:0', 'max:100'],
        ]);

        $user = $request->user();

        $cigar = Cigar::where('user_id', $user->id)->find($cigar_id);

        if (!$cigar) {
            return response()->json(['message' => 'Cigar not found.'], 404);
        }

        $cigar->name = $request->name;
        $cigar->ring = $request->ring;
        $cigar->length = $request->length;
        $cigar->notes = $request->notes;
        $cigar->summary = $request->summary;
        $cigar->rating = $request->rating;
        $cigar->save();

        return response()->json($cigar, 200);
    }

    public function delete(Request $request, int $cigar_id)
    {
        $user = $request->user();

        $cigar = Cigar::where('user_id', $user->id)->find($cigar_id);

        if (!$cigar) {
            return response()->json(['message' => 'Cigar not found.'], 404);
        }

        $cigar->delete();

        return response()->json($cigar, 200);
    }

}
