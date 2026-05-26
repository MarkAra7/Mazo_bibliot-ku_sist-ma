<?php

use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('index displays readers', function () {
    Reader::factory()->count(3)->create();

    $this->get(route('readers.index'))
        ->assertStatus(200)
        ->assertViewHas('readers');
});

test('create displays form', function () {
    $this->get(route('readers.create'))->assertStatus(200);
});

test('store creates reader', function () {
    $this->post(route('readers.store'), [
        'name' => 'Jānis Testētājs',
        'email' => 'janis@test.lv',
    ])->assertRedirect(route('readers.index'));

    $this->assertDatabaseHas('readers', ['email' => 'janis@test.lv']);
});

test('store validates required fields', function () {
    $this->post(route('readers.store'), [])
        ->assertSessionHasErrors(['name', 'email']);
});

test('update modifies reader', function () {
    $reader = Reader::factory()->create();

    $this->put(route('readers.update', $reader), [
        'name' => 'Atjaunināts Vārds',
        'email' => $reader->email,
    ])->assertRedirect(route('readers.index'));

    $this->assertDatabaseHas('readers', ['name' => 'Atjaunināts Vārds']);
});

test('destroy deletes reader', function () {
    $reader = Reader::factory()->create();

    $this->delete(route('readers.destroy', $reader))
        ->assertRedirect(route('readers.index'));

    $this->assertDatabaseMissing('readers', ['id' => $reader->id]);
});

test('email must be unique on create', function () {
    Reader::factory()->create(['email' => 'janis@test.lv']);

    $this->post(route('readers.store'), [
        'name' => 'Vēlviens',
        'email' => 'janis@test.lv',
    ])->assertSessionHasErrors(['email']);
});

test('email must be unique on update', function () {
    Reader::factory()->create(['email' => 'jana@test.lv']);
    $reader = Reader::factory()->create(['email' => 'peteris@test.lv']);

    $this->put(route('readers.update', $reader), [
        'name' => 'Cits',
        'email' => 'jana@test.lv',
    ])->assertSessionHasErrors(['email']);
});
