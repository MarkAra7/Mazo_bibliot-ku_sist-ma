<?php

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index displays books', function () {
    Book::factory()->count(3)->create();

    $response = $this->get(route('books.index'));

    $response->assertStatus(200);
    $response->assertViewHas('books');
});

test('create displays form', function () {
    $this->get(route('books.create'))->assertStatus(200);
});

test('store creates book', function () {
    $this->post(route('books.store'), [
        'title' => 'Testa grāmata',
        'isbn' => '9789984380099',
        'available_copies' => 2,
    ])->assertRedirect(route('books.index'));

    $this->assertDatabaseHas('books', ['isbn' => '9789984380099']);
});

test('store validates required fields', function () {
    $this->post(route('books.store'), [])
        ->assertSessionHasErrors(['title', 'isbn', 'available_copies']);
});

test('show displays book', function () {
    $book = Book::factory()->create();

    $this->get(route('books.show', $book))
        ->assertStatus(200)
        ->assertSee($book->title);
});

test('edit displays form', function () {
    $book = Book::factory()->create();

    $this->get(route('books.edit', $book))->assertStatus(200);
});

test('update modifies book', function () {
    $book = Book::factory()->create();

    $this->put(route('books.update', $book), [
        'title' => 'Atjaunināts nosaukums',
        'isbn' => $book->isbn,
        'available_copies' => 5,
    ])->assertRedirect(route('books.index'));

    $this->assertDatabaseHas('books', ['title' => 'Atjaunināts nosaukums']);
});

test('destroy deletes book', function () {
    $book = Book::factory()->create();

    $this->delete(route('books.destroy', $book))
        ->assertRedirect(route('books.index'));

    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});

test('isbn must be unique', function () {
    Book::factory()->create(['isbn' => '9789984380099']);

    $this->post(route('books.store'), [
        'title' => 'Vēl viena grāmata',
        'isbn' => '9789984380099',
        'available_copies' => 1,
    ])->assertSessionHasErrors(['isbn']);
});
