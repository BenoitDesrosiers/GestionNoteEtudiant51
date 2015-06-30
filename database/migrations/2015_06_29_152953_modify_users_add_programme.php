<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersAddProgramme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('programme_id'); //le programme d'étude (pour les étudiants) ou d'enseignement (pour les profs)
		});    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('programme_id');
		});  
    }
}
