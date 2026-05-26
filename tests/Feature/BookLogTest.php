<?php

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('trigger logs when book copies updated via eloquent', function () {
    $book = Book::factory()->create(['available_copies' => 5]);

    $book->update(['available_copies' => 3]);

    $this->assertDatabaseHas('book_log', [
        'book_id' => $book->id,
        'old_copies' => 5,
        'new_copies' => 3,
        'operation' => 'UPDATE',
    ]);
});

test('trigger logs when book copies updated via raw sql', function () {
    $book = Book::factory()->create(['available_copies' => 5]);

    DB::statement('UPDATE books SET available_copies = 1 WHERE id = ?', [$book->id]);

    $this->assertDatabaseHas('book_log', [
        'book_id' => $book->id,
        'old_copies' => 5,
        'new_copies' => 1,
        'operation' => 'UPDATE',
    ]);
});

test('trigger logs borrowing decrement', function () {
    $book = Book::factory()->create(['available_copies' => 3]);
    $reader = Reader::factory()->create();

    $this->post(route('borrowings.store'), [
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'borrowed_at' => '2026-05-26',
    ]);

    $this->assertDatabaseHas('book_log', [
        'book_id' => $book->id,
        'old_copies' => 3,
        'new_copies' => 2,
        'operation' => 'UPDATE',
    ]);
});

test('trigger logs borrowing return increment', function () {
    $book = Book::factory()->create(['available_copies' => 3]);
    $reader = Reader::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'borrowed_at' => '2026-05-01',
        'returned_at' => null,
    ]);

    $book->update(['available_copies' => 2]);

    $this->assertDatabaseHas('book_log', [
        'book_id' => $book->id,
        'old_copies' => 3,
        'new_copies' => 2,
        'operation' => 'UPDATE',
    ]);
});

test('multiple changes create multiple log entries', function () {
    $book = Book::factory()->create(['available_copies' => 10]);

    $book->update(['available_copies' => 7]);
    $book->update(['available_copies' => 5]);
    $book->update(['available_copies' => 3]);

    $logs = DB::table('book_log')->where('book_id', $book->id)->get();

    expect($logs)->toHaveCount(3);
    expect($logs[0]->old_copies)->toBe(10);
    expect($logs[0]->new_copies)->toBe(7);
    expect($logs[1]->old_copies)->toBe(7);
    expect($logs[1]->new_copies)->toBe(5);
    expect($logs[2]->old_copies)->toBe(5);
    expect($logs[2]->new_copies)->toBe(3);
});

test('trigger does not log when copies unchanged', function () {
    $book = Book::factory()->create(['available_copies' => 5]);

    $book->update(['title' => 'Jauns nosaukums']);

    $this->assertDatabaseMissing('book_log', ['book_id' => $book->id]);
});
