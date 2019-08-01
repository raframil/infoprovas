<?php

use Illuminate\Database\Seeder;

class DisciplinasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 500; $i++) {
            DB::table('disciplinas')->insert([
                'nome' => Str::random(10),
                'codigo' => Str::random(4),
                'periodo' => rand(1, 10),
                'curso_id' => '1',
            ]);
        }
    }
}
