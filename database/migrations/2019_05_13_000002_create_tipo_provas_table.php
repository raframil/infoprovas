
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoProvasTable extends Migration
{
    /**
     * Run the migrations.
     * @table prova_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prova_tipos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descricao', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('prova_tipos');
    }
}
