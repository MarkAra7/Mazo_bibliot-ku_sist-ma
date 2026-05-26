<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Reader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function csv(string $resource): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $resource . '-' . now()->format('Y-m-d') . '.csv"',
        ];
        
        $callback = match ($resource) {
            'books' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Nosaukums', 'ISBN', 'Pieejamie eksemplāri', 'Izveidots']);
                Book::chunk(100, function ($books) use ($fh) {
                    foreach ($books as $book) {
                        fputcsv($fh, [$book->id, $book->title, $book->isbn, $book->available_copies, $book->created_at->format('d.m.Y')]);
                    }
                });
                fclose($fh);
            },
            'readers' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Vārds', 'E-pasts', 'Telefons', 'Izveidots']);
                Reader::chunk(100, function ($readers) use ($fh) {
                    foreach ($readers as $r) {
                        fputcsv($fh, [$r->id, $r->name, $r->email, $r->phone ?? '', $r->created_at->format('d.m.Y')]);
                    }
                });
                fclose($fh);
            },
            'borrowings' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Grāmata', 'Lasītājs', 'Aizņemts', 'Atgriezts', 'Statuss']);
                Borrowing::with(['book', 'reader'])->chunk(100, function ($borrowings) use ($fh) {
                    foreach ($borrowings as $b) {
                        fputcsv($fh, [
                            $b->id, $b->book->title, $b->reader->name,
                            $b->borrowed_at->format('d.m.Y'),
                            $b->returned_at ? $b->returned_at->format('d.m.Y') : '',
                            $b->returned_at ? 'Atgriezta' : 'Aktīvs',
                        ]);
                    }
                });
                fclose($fh);
            },
            'authors' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Vārds', 'Biogrāfija', 'Grāmatu skaits', 'Izveidots']);
                Author::withCount('books')->chunk(100, function ($authors) use ($fh) {
                    foreach ($authors as $a) {
                        fputcsv($fh, [$a->id, $a->name, $a->bio ?? '', $a->books_count, $a->created_at->format('d.m.Y')]);
                    }
                });
                fclose($fh);
            },
            'categories' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Nosaukums', 'Apraksts', 'Grāmatu skaits', 'Izveidots']);
                Category::withCount('books')->chunk(100, function ($categories) use ($fh) {
                    foreach ($categories as $c) {
                        fputcsv($fh, [$c->id, $c->name, $c->description ?? '', $c->books_count, $c->created_at->format('d.m.Y')]);
                    }
                });
                fclose($fh);
            },
            'fines' => function () {
                $fh = fopen('php://output', 'w');
                fputs($fh, "\xEF\xBB\xBF");
                fputcsv($fh, ['ID', 'Lasītājs', 'Summa', 'Iemesls', 'Apmaksāts']);
                Fine::with('reader')->chunk(100, function ($fines) use ($fh) {
                    foreach ($fines as $f) {
                        fputcsv($fh, [$f->id, $f->reader->name, $f->amount, $f->reason ?? '', $f->paid_at ? $f->paid_at->format('d.m.Y') : 'Nē']);
                    }
                });
                fclose($fh);
            },
            default => function () {
                abort(404);
            },
        };
        
        return new StreamedResponse($callback, 200, $headers);
    }
}
