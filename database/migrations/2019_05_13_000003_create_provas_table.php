

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvasTable extends Migration
{
    /**
     * Run the migrations.
     * @table provas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('arquivo', 255);
            $table->smallInteger('ano');
            $table->smallInteger('periodo');
            $table->integer('disciplina_id')->unsigned();
            $table->integer('professor_id')->unsigned();
            $table->integer('tipo_prova_id')->unsigned();

            $table->foreign('disciplina_id')
                ->references('id')->on('disciplinas')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('professor_id')
                ->references('id')->on('professores')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tipo_prova_id')
                ->references('id')->on('prova_tipos')
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

        Schema::drop('provas');
    }
}
