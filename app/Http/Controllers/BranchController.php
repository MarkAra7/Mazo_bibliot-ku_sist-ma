<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Branch::withCount('books');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'name');
        $sortDir = $request->get('dir', 'asc');
        $allowed = ['name', 'books_count', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('branches.index', [
            'branches' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'nullable|max:50',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Filiāle pievienota!');
    }

    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'required|max:500',
            'phone' => 'nullable|max:50',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Filiāle atjaunināta!');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Filiāle dzēsta!');
    }
}
