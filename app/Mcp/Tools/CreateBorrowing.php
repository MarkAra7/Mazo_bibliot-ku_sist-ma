<?php

namespace App\Mcp\Tools;

use App\Models\Book;
use App\Models\Reader;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Reģistrē jaunu aizņēmumu (grāmatas izsniegšanu lasītājam).')]
class CreateBorrowing extends Tool
{
    public function handle(Request $request): Response
    {
        $bookId = (int) $request->input('book_id');
        $readerId = (int) $request->input('reader_id');
        $borrowedAt = $request->input('borrowed_at', now()->toDateString());

        try {
            DB::transaction(function () use ($bookId, $readerId, $borrowedAt) {
                $book = Book::lockForUpdate()->findOrFail($bookId);
                if ($book->available_copies <= 0) {
                    throw new \RuntimeException('Nav pieejamu eksemplāru.');
                }

                \App\Models\Borrowing::create([
                    'book_id' => $bookId,
                    'reader_id' => $readerId,
                    'borrowed_at' => $borrowedAt,
                ]);
            });
        } catch (\RuntimeException $e) {
            return Response::text('Kļūda: ' . $e->getMessage());
        }

        return Response::text('Aizņēmums veiksmīgi reģistrēts.');
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'book_id' => $schema->integer(),
            'reader_id' => $schema->integer(),
            'borrowed_at' => $schema->string(),
        ];
    }
}
