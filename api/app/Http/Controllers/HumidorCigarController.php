<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Humidor;
use App\Models\HumidorCigar;

class HumidorCigarController extends Controller
{
    public function index(Request $request, int $humidor_id)
    {
        $user = $request->user();

        $humidor_cigars = HumidorCigar::where('user_id', $user->id)->where('humidor_id', $humidor_id)->get();

        return response()->json($humidor_cigars, 200);
    }

    public function store(Request $request, int $humidor_id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ring' => ['nullable', 'numeric', 'max:200'],
            'length' => ['nullable', 'numeric', 'max:200'],
            'quantity' => ['required', 'integer'],
            'price' => ['nullable', 'numeric'],
        ]);

        $user = $request->user();

        $humidor = Humidor::where('user_id', $user->id)->find($humidor_id);

        if (!$humidor) {
            return response()->json(['message' => 'Humidor not found.'], 404);
        }

        $cigar = new HumidorCigar;
        $cigar->user_id = $user->id;
        $cigar->humidor_id = $humidor->id;
        $cigar->name = $request->name;
        $cigar->ring = $request->ring;
        $cigar->length = $request->length;
        $cigar->quantity = $request->quantity;
        $cigar->price_per_cigar = $request->price;
        $cigar->save();

        return response()->json($cigar, 200);
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
