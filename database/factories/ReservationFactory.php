<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'reader_id' => Reader::factory(),
            'reserved_at' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'status' => 'pending',
        ];
    }
}
