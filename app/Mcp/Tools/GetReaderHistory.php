<?php

namespace App\Mcp\Tools;

use App\Models\Borrowing;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Parāda lasītāja aizņēmumu vēsturi.')]
class GetReaderHistory extends Tool
{
    public function handle(Request $request): Response
    {
        $readerId = (int) $request->input('reader_id');

        $history = Borrowing::with('book')
            ->where('reader_id', $readerId)
            ->orderBy('borrowed_at', 'desc')
            ->get()
            ->map(fn ($b) => [
                'book' => $b->book->title,
                'borrowed_at' => $b->borrowed_at,
                'returned_at' => $b->returned_at ?? 'Nav atdots',
            ]);

        return Response::text($history->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'reader_id' => $schema->integer(),
        ];
    }
}
