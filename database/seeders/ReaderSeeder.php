<?php

namespace Database\Seeders;

use App\Models\Reader;
use Illuminate\Database\Seeder;

class ReaderSeeder extends Seeder
{
    public function run(): void
    {
        $readers = [
            ['name' => 'Jānis Bērziņš', 'email' => 'janis.berzins@inbox.lv'],
            ['name' => 'Līga Kalniņa', 'email' => 'liga.kalnina@inbox.lv'],
            ['name' => 'Pēteris Ozols', 'email' => 'peteris.ozols@inbox.lv'],
            ['name' => 'Anna Liepiņa', 'email' => 'anna.liepina@inbox.lv'],
            ['name' => 'Mārtiņš Krūmiņš', 'email' => 'martins.krumins@inbox.lv'],
            ['name' => 'Zane Vītola', 'email' => 'zane.vitola@inbox.lv'],
            ['name' => 'Andris Balodis', 'email' => 'andris.balodis@inbox.lv'],
            ['name' => 'Ilze Saulīte', 'email' => 'ilze.saulite@inbox.lv'],
        ];

        foreach ($readers as $reader) {
            Reader::create($reader);
        }
    }
}
