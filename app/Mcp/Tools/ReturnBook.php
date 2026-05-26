<?php

namespace App\Mcp\Tools;

use App\Models\Borrowing;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Reģistrē grāmatas atgriešanu.')]
class ReturnBook extends Tool
{
    public function handle(Request $request): Response
    {
        $borrowingId = (int) $request->input('borrowing_id');

        try {
            DB::transaction(function () use ($borrowingId) {
                $borrowing = Borrowing::lockForUpdate()->findOrFail($borrowingId);
                if ($borrowing->returned_at !== null) {
                    throw new \RuntimeException('Grāmata jau ir atdota.');
                }
                $borrowing->update(['returned_at' => now()->toDateString()]);
            });
        } catch (\RuntimeException $e) {
            return Response::text('Kļūda: ' . $e->getMessage());
        }

        return Response::text('Grāmata veiksmīgi atgriezta.');
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'borrowing_id' => $schema->integer(),
        ];
    }
}
