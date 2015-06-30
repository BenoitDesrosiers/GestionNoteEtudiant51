<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/*
 * Les programmes d'études
 * 
 * ex: 420-Informatique
 */
class CreateProgramme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('programmes', function (Blueprint $table) {
            $table->string('id'); //l'id est le numéro du programme, ex: informatique = 420.AA, arts lettres et comm = 500.45
            $table->timestamps();
            $table->string( 'nom' );
			});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('programmes');
    }
}