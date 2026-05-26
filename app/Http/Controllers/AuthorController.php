<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Author::withCount('books');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $allowed = ['name', 'books_count', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('authors.index', [
            'authors' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('authors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'bio' => 'nullable|max:5000',
        ]);

        Author::create($validated);

        return redirect()->route('authors.index')->with('success', 'Autors pievienots!');
    }

    public function edit(Author $author): View
    {
        return view('authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'bio' => 'nullable|max:5000',
        ]);

        $author->update($validated);

        return redirect()->route('authors.index')->with('success', 'Autors atjaunināts!');
    }

    public function destroy(Author $author): RedirectResponse
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Autors dzēsts!');
    }
}
