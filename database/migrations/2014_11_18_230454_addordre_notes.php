<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddordreNotes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * Ajout de l'ordre des questions dans Notes afin de faciliter l'affichage
	 * @return void
	 */
	public function up()
	{
		Schema::table('notes', function($t)
		{
			$t->integer('ordre');
			
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notes', function($t) 
		{
			$t->dropColumn('ordre');
		});
	}
}
