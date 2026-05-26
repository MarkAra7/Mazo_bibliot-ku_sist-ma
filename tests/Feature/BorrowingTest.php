<?php

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index displays borrowings', function () {
    $book = Book::factory()->create();
    $reader = Reader::factory()->create();
    Borrowing::factory()->count(3)->create([
        'book_id' => $book->id,
        'reader_id' => $reader->id,
    ]);

    $this->get(route('borrowings.index'))
        ->assertStatus(200)
        ->assertViewHas('borrowings');
});

test('create displays form', function () {
    Book::factory()->count(2)->create();
    Reader::factory()->count(2)->create();

    $this->get(route('borrowings.create'))->assertStatus(200);
});

test('store creates borrowing', function () {
    $book = Book::factory()->create(['available_copies' => 5]);
    $reader = Reader::factory()->create();

    $this->post(route('borrowings.store'), [
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'borrowed_at' => '2026-05-26',
    ])->assertRedirect(route('borrowings.index'));

    $this->assertDatabaseHas('borrowings', [
        'book_id' => $book->id,
        'reader_id' => $reader->id,
    ]);
});

test('store rejects borrowing when no copies', function () {
    $book = Book::factory()->create(['available_copies' => 0]);
    $reader = Reader::factory()->create();

    $this->post(route('borrowings.store'), [
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'borrowed_at' => '2026-05-26',
    ])->assertSessionHasErrors();
});

test('store validates required fields', function () {
    $this->post(route('borrowings.store'), [])
        ->assertSessionHasErrors(['book_id', 'reader_id', 'borrowed_at']);
});

test('return updates borrowing', function () {
    $book = Book::factory()->create(['available_copies' => 1]);
    $reader = Reader::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'returned_at' => null,
    ]);

    $this->patch(route('borrowings.return', $borrowing))
        ->assertRedirect(route('borrowings.index'));

    expect($borrowing->fresh()->returned_at)->not->toBeNull();
});

test('cannot return already returned book', function () {
    $book = Book::factory()->create(['available_copies' => 5]);
    $reader = Reader::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'returned_at' => '2026-05-20',
    ]);

    $this->patch(route('borrowings.return', $borrowing))
        ->assertSessionHasErrors();
});

test('destroy deletes borrowing', function () {
    $book = Book::factory()->create(['available_copies' => 5]);
    $reader = Reader::factory()->create();
    $borrowing = Borrowing::factory()->create([
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'returned_at' => null,
    ]);

    $this->delete(route('borrowings.destroy', $borrowing))
        ->assertRedirect(route('borrowings.index'));

    $this->assertDatabaseMissing('borrowings', ['id' => $borrowing->id]);
});
