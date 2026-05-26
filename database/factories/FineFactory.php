<?php

namespace Database\Factories;

use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Reader;
use Illuminate\Database\Eloquent\Factories\Factory;

class FineFactory extends Factory
{
    protected $model = Fine::class;

    public function definition(): array
    {
        return [
            'borrowing_id' => Borrowing::factory(),
            'reader_id' => Reader::factory(),
            'amount' => fake()->randomFloat(2, 1, 50),
            'reason' => fake()->optional()->sentence(),
            'paid_at' => fake()->optional(0.3)->dateTimeThisMonth(),
        ];
    }
}
