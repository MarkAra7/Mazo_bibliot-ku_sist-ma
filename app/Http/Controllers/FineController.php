<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FineController extends Controller
{
    public function index(Request $request): View
    {
        $query = Fine::with(['borrowing.book', 'reader']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('reader', fn($r) => $r->where('name', 'like', "%{$search}%"))
                  ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        if ($paid = $request->get('paid')) {
            if ($paid === 'paid') {
                $query->whereNotNull('paid_at');
            } elseif ($paid === 'unpaid') {
                $query->whereNull('paid_at');
            }
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['amount', 'paid_at', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        $totalUnpaid = Fine::whereNull('paid_at')->sum('amount');

        return view('fines.index', [
            'fines' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
            'paid' => $request->get('paid'),
            'totalUnpaid' => $totalUnpaid,
        ]);
    }

    public function pay(Fine $fine): RedirectResponse
    {
        if ($fine->paid_at !== null) {
            return back()->withErrors(['error' => 'Šis sods jau ir apmaksāts.']);
        }

        $fine->update(['paid_at' => now()]);

        return redirect()->route('fines.index')->with('success', 'Sods atzīmēts kā apmaksāts!');
    }
}
