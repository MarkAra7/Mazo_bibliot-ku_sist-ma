<?php

namespace App\Http\Controllers;

use App\Models\Reader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReaderController extends Controller
{
    public function index(): View
    {
        return view('readers.index', ['readers' => Reader::paginate(10)]);
    }

    public function create(): View
    {
        return view('readers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:readers',
        ]);

        Reader::create($validated);

        return redirect()->route('readers.index')->with('success', 'Lasītājs pievienots!');
    }

    public function edit(Reader $reader): View
    {
        return view('readers.edit', ['reader' => $reader]);
    }

    public function update(Request $request, Reader $reader): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:readers,email,' . $reader->id,
        ]);

        $reader->update($validated);

        return redirect()->route('readers.index')->with('success', 'Lasītājs atjaunināts!');
    }

    public function destroy(Reader $reader): RedirectResponse
    {
        $reader->delete();

        return redirect()->route('readers.index')->with('success', 'Lasītājs dzēsts!');
    }
}
