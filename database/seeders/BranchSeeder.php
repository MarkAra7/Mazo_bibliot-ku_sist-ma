<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    private array $branches = [
        ['name' => 'Centrālā bibliotēka', 'address' => 'Brīvības iela 1, Rīga', 'phone' => '+371 12345678'],
        ['name' => 'Vecrīgas filiāle', 'address' => 'Kaļķu iela 15, Rīga', 'phone' => '+371 23456789'],
        ['name' => 'Pļavnieku filiāle', 'address' => 'Aglonas iela 35, Rīga', 'phone' => '+371 34567890'],
        ['name' => 'Juglas filiāle', 'address' => 'Brīvības gatve 432, Rīga', 'phone' => null],
    ];

    public function run(): void
    {
        foreach ($this->branches as $branch) {
            Branch::firstOrCreate(
                ['name' => $branch['name']],
                $branch
            );
        }
    }
}
