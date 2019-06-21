<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     * @table disciplinas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100);
            $table->string('codigo', 20);
            $table->smallInteger('periodo');
            $table->integer('curso_id')->unsigned();

            $table->foreign('curso_id')
                ->references('id')->on('cursos')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('disciplinas');
    }
}
