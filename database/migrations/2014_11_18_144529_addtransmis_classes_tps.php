<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddtransmisClassesTps extends Migration {

	/**
	 * Run the migrations.
	 * 
	 * Ajout des champs indiquant que le TP est distribué et corrigé
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('classes_tps', function($t)
		{
			$t->boolean('distribue')->nullable()->default(false);
			$t->boolean('corrige')->nullable()->default(false);
			
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('classes_tps', function($t) 
		{
			$t->dropColumn('distribue');
			$t->dropColumn('corrige');
		});
	}
}
