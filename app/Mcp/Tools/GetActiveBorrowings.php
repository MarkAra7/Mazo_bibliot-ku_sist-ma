<?php

namespace App\Mcp\Tools;

use App\Models\Borrowing;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Parāda visus aktīvos aizņēmumus (grāmatas, kas nav atdotas).')]
class GetActiveBorrowings extends Tool
{
    public function handle(Request $request): Response
    {
        $borrowings = Borrowing::with(['book', 'reader'])
            ->whereNull('returned_at')
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'book' => $b->book->title,
                'reader' => $b->reader->name,
                'borrowed_at' => $b->borrowed_at,
            ]);

        return Response::text($borrowings->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [];
    }
}
