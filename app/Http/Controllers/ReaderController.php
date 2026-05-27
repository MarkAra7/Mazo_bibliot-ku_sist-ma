<?php

namespace App\Http\Controllers;

use App\Models\Reader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReaderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Reader::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['name', 'email', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('readers.index', [
            'readers' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function show(Reader $reader): View
    {
        $reader->load(['borrowings.book', 'fines.borrowing.book']);

        $unpaidFines = $reader->fines->whereNull('paid_at');
        $totalUnpaid = $unpaidFines->sum('amount');

        return view('readers.show', [
            'reader' => $reader,
            'unpaidFines' => $unpaidFines,
            'totalUnpaid' => $totalUnpaid,
        ]);
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
