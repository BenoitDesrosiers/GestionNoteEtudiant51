<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('UserTableSeeder');
		$this->call('SessionTableSeeder');
	}
}
	
class UserTableSeeder extends Seeder {
	
		public function run()
		{
			DB::table('users')->delete();
			$user = new User();
			$user->name = 'usager';
			$user->nom = 'usager';
			$user->prenom = 'un';
			$user->type= 'p';
			$user->email = 'usager@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();
		}
}
class SessionTableSeeder extends Seeder {

	public function run()
	{
		$sessions = ["A2014", "H2015", "E2015","A2015", "H2016", "E2016"];
		DB::table('sessionscholaires')->delete();
		foreach($sessions as $session) {
			$courant = ($session == 'H2015');
			DB::table('sessionscholaires')->insert(array('nom'=>$session , 'courant'=>$courant));
		}
	}
}
