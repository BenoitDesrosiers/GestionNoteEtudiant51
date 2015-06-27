<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcategorieTp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * Ajout du flag indiquant que les commentaires de correction peuvent être vu pendant que le tp est en cour
	 * @return void
	 */
	public function up()
	{
		Schema::table('classes_tps', function($t)
		{
			$t->boolean('commentaire_visible');
			
		});		}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('classes_tps', function($t)
		{
			$t->dropColumn('commentaire_visible');
		});
	}

}
