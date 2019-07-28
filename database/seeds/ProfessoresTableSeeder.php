<?php

use Illuminate\Database\Seeder;

class ProfessoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 1000; $i++) {
            DB::table('professores')->insert([
                'nome' => Str::random(10),
                'email' => Str::random(10) . '@gmail.com',
            ]);
        }
    }
}
