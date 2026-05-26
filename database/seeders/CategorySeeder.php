<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    private array $categories = [
        'Daiļliteratūra',
        'Zinātniskā fantastika',
        'Detektīvs',
        'Vēsture',
        'Bērnu literatūra',
        'Dzeja',
        'Ceļojumi',
        'Zinātne',
        'Filozofija',
        'Tehnoloģijas',
    ];

    public function run(): void
    {
        foreach ($this->categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => "Grāmatas kategorijā \"$name\""]
            );
        }
    }
}
