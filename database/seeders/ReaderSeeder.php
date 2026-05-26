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
            ['name' => 'Roberts Kalniņš', 'email' => 'roberts.kalnins@inbox.lv'],
            ['name' => 'Elīna Bērziņa', 'email' => 'elina.berzina@inbox.lv'],
            ['name' => 'Kārlis Zariņš', 'email' => 'karlis.zarins@inbox.lv'],
            ['name' => 'Baiba Ozoliņa', 'email' => 'baiba.ozolina@inbox.lv'],
            ['name' => 'Artūrs Liepa', 'email' => 'arturs.liepa@inbox.lv'],
            ['name' => 'Dace Vītoliņa', 'email' => 'dace.vitolina@inbox.lv'],
            ['name' => 'Edgars Pētersons', 'email' => 'edgars.petersons@inbox.lv'],
            ['name' => 'Liene Avotiņa', 'email' => 'liene.avotina@inbox.lv'],
            ['name' => 'Gints Krūmiņš', 'email' => 'gints.krumins@inbox.lv'],
            ['name' => 'Aija Saulīte', 'email' => 'aija.saulite@inbox.lv'],
            ['name' => 'Sandis Vējš', 'email' => 'sandis.vejs@inbox.lv'],
            ['name' => 'Monta Priede', 'email' => 'monta.priede@inbox.lv'],
        ];

        foreach ($readers as $reader) {
            Reader::firstOrCreate(['email' => $reader['email']], $reader);
        }
    }
}
