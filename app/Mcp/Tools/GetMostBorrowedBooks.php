<?php

namespace App\Mcp\Tools;

use App\Models\Book;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Parāda populārākās grāmatas pēc aizņēmumu skaita.')]
class GetMostBorrowedBooks extends Tool
{
    public function handle(Request $request): Response
    {
        $limit = (int) $request->input('limit', 10);

        $books = Book::select([
            'books.id',
            'books.title',
            DB::raw('COUNT(borrowings.id) as borrow_count'),
        ])
            ->leftJoin('borrowings', 'books.id', '=', 'borrowings.book_id')
            ->groupBy('books.id', 'books.title')
            ->orderBy('borrow_count', 'desc')
            ->limit($limit)
            ->get();

        return Response::text($books->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'limit' => $schema->integer(),
        ];
    }
}
