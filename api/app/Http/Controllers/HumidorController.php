<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Humidor;

class HumidorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $humidors = Humidor::where('user_id', $user->id)->get();

        return response()->json($humidors, 200);
    }

    public function show(Request $request, int $humidor_id)
    {
        $user = $request->user();
        
        $humidor = Humidor::where('user_id', $user->id)->find($humidor_id);

        if (!$humidor) {
            return response()->json(['message' => 'Humidor not found.'], 404);
        }

        return response()->json($humidor, 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'humidity' => ['nullable', 'integer'],
        ]);

        $humidor = new Humidor;
        $humidor->user_id = $user->id;
        $humidor->name = $request->name;
        $humidor->description = $request->description;
        $humidor->humidity = $request->humidity;
        $humidor->save();

        return response()->json($humidor, 200);
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
