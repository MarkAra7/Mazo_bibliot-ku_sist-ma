<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with(['authors', 'categories', 'branch']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['title', 'isbn', 'available_copies', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('books.index', [
            'books' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('books.create', [
            'authors' => Author::all(),
            'categories' => Category::all(),
            'branches' => Branch::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books',
            'available_copies' => 'required|integer|min:0',
            'branch_id' => 'nullable|exists:branches,id',
            'author_ids' => 'array',
            'author_ids.*' => 'exists:authors,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $book = Book::create($validated);

        if ($request->has('author_ids')) {
            $book->authors()->sync($validated['author_ids']);
        }

        if ($request->has('category_ids')) {
            $book->categories()->sync($validated['category_ids']);
        }

        return redirect()->route('books.index')->with('success', 'Grāmata pievienota!');
    }

    public function show(Book $book): View
    {
        $book->load(['authors', 'categories', 'branch', 'borrowings.reader']);

        return view('books.show', ['book' => $book]);
    }

    public function edit(Book $book): View
    {
        return view('books.edit', [
            'book' => $book,
            'authors' => Author::all(),
            'categories' => Category::all(),
            'branches' => Branch::all(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'available_copies' => 'required|integer|min:0',
            'branch_id' => 'nullable|exists:branches,id',
            'author_ids' => 'array',
            'author_ids.*' => 'exists:authors,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $book->update($validated);

        if ($request->has('author_ids')) {
            $book->authors()->sync($validated['author_ids']);
        }

        if ($request->has('category_ids')) {
            $book->categories()->sync($validated['category_ids']);
        }

        return redirect()->route('books.index')->with('success', 'Grāmata atjaunināta!');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Grāmata dzēsta!');
    }
}
