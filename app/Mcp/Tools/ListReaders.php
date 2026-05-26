<?php

namespace App\Mcp\Tools;

use App\Models\Reader;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Uzskaita visus lasītājus.')]
class ListReaders extends Tool
{
    public function handle(Request $request): Response
    {
        $readers = Reader::all()->map(fn ($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'email' => $r->email,
        ]);

        return Response::text($readers->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function schema(\Illuminate\Contracts\JsonSchema\JsonSchema $schema): array
    {
        return [];
    }
}
