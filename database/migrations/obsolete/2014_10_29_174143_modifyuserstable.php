<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modifyuserstable extends Migration {

	/**
	 * Run the migrations.
	 * Ajout de l'info nécessaire pour qu'un usager soit un prof ou un étudiant
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->string('nom')->nullable();
			$table->string('prenom')->nullable();
			$table->string('type')->nullable(); // e = etudiant, p=prof
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('nom');
			$table->dropColumn('prenom');
			$table->dropColumn('type');
				
		});		
	}

}
