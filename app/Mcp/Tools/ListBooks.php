<?php

namespace App\Mcp\Tools;

use App\Models\Book;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Uzskaita visas grāmatas ar to ISBN un pieejamajiem eksemplāriem.')]
class ListBooks extends Tool
{
    public function handle(Request $request): Response
    {
        $books = Book::all()->map(fn ($b) => [
            'id' => $b->id,
            'title' => $b->title,
            'isbn' => $b->isbn,
            'available_copies' => $b->available_copies,
        ]);

        return Response::text($books->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [];
    }
}
