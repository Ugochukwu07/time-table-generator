<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index(){
        $venues = Venue::orderBy('name')->get();

        return view('venues.index', compact('venues'));
    }

    public function create(){
        return view('venues.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        Venue::create($request->only(['name', 'capacity', 'is_available', 'description']));

        return redirect()->route('venues.index')->with('success', 'Venue created successfully.');
    }

    public function edit(Venue $venue){
        return view('venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue){
        $request->validate([
            'name' => 'required|string|max:255|unique:venues,name,' . $venue->id,
            'capacity' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        $venue->update($request->only(['name', 'capacity', 'is_available', 'description']));

        return redirect()->route('venues.index')->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue){
        $venue->delete();

        return redirect()->route('venues.index')->with('success', 'Venue deleted successfully.');
    }
}
