<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\CreateBorrowing;
use App\Mcp\Tools\GetActiveBorrowings;
use App\Mcp\Tools\GetMostBorrowedBooks;
use App\Mcp\Tools\GetReaderHistory;
use App\Mcp\Tools\ListBooks;
use App\Mcp\Tools\ListReaders;
use App\Mcp\Tools\ReturnBook;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Mazo bibliotēku sistēma')]
#[Version('1.0.0')]
#[Instructions('Bibliotēkas pārvaldības sistēma. Ļauj apskatīt grāmatas, lasītājus, reģistrēt aizņēmumus un atgriešanas.')]
class LibraryServer extends Server
{
    protected array $tools = [
        ListBooks::class,
        ListReaders::class,
        CreateBorrowing::class,
        ReturnBook::class,
        GetReaderHistory::class,
        GetActiveBorrowings::class,
        GetMostBorrowedBooks::class,
    ];

    protected array $resources = [];

    protected array $prompts = [];
}
