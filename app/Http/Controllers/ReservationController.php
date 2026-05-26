<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Reservation::with(['book', 'reader']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('reader', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sortField = $request->get('sort', 'reserved_at');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['reserved_at', 'status', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('reservations.index', [
            'reservations' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
            'status' => $request->get('status'),
        ]);
    }

    public function create(): View
    {
        return view('reservations.create', [
            'books' => Book::all(),
            'readers' => Reader::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'reserved_at' => 'required|date',
        ]);

        Reservation::create($validated + ['status' => 'pending']);

        return redirect()->route('reservations.index')->with('success', 'Rezervācija reģistrēta!');
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->withErrors(['error' => 'Rezervāciju nevar atcelt — tā nav aktīva.']);
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now()->toDateString(),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Rezervācija atcelta!');
    }

    public function fulfill(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->withErrors(['error' => 'Rezervāciju nevar izpildīt — tā nav aktīva.']);
        }

        $book = $reservation->book;
        if ($book->available_copies <= 0) {
            return back()->withErrors(['error' => 'Nav pieejamu eksemplāru, lai izpildītu rezervāciju.']);
        }

        $borrowing = \App\Models\Borrowing::create([
            'book_id' => $reservation->book_id,
            'reader_id' => $reservation->reader_id,
            'borrowed_at' => now()->toDateString(),
        ]);

        $reservation->update([
            'status' => 'fulfilled',
            'fulfilled_by_borrowing_id' => $borrowing->id,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Rezervācija izpildīta — aizņēmums izveidots!');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Rezervācija dzēsta!');
    }
}
