<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::withCount('books');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $allowed = ['name', 'books_count', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('categories.index', [
            'categories' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:categories',
            'description' => 'nullable|max:5000',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Kategorija pievienota!');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'nullable|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|max:5000',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Kategorija atjaunināta!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategorija dzēsta!');
    }
}
