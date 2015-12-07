<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use App\Models\Role;
use App\Models\Permission;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('PermissionTableSeeder');
		$this->call('RoleTableSeeder');
		$this->call('UserTableSeeder');
		$this->call('SessionTableSeeder');
		
	}
}
	

class PermissionTableSeeder extends Seeder {

	public function run()
	{
		DB::table('Permissions')->delete();
		$permission = new Permission();
		$permission->name         = 'ajout-prof';
		$permission->display_name = 'Ajout de professeur';
		$permission->description  = 'Permet d\'ajouter des professeurs';
		$permission->save();

		$permission = new Permission();
		$permission->name         = 'ajout-etudiant';
		$permission->display_name = 'Ajout d\'étudiant';
		$permission->description  = 'Permet d\'ajouter des étudiants';
		$permission->save();

		$permission = new Permission();
		$permission->name         = 'passer-test';
		$permission->display_name = 'Passer les tests';
		$permission->description  = 'Permet de passer les tests';
		$permission->save();
		
		$permission = new Permission();
		$permission->name         = 'enseigner';
		$permission->display_name = 'donner des cours';
		$permission->description  = 'donner des cours';
		$permission->save();

	}
}

class RoleTableSeeder extends Seeder {

	public function run()
	{
		DB::table('Roles')->delete();
		$role = new Role();
		$role->name         = 'admin';
		$role->display_name = 'Administrateur du système';
		$role->description  = 'Cet usager a tous les droits sur le système, c\'est le super admin';
		$role->save();
		
		$permission = Permission::where('name', '=', 'ajout-prof')->first();
		$role->attachPermission($permission);
		$permission = Permission::where('name', '=', 'ajout-etudiant')->first();
		$role->attachPermission($permission);
		$permission = Permission::where('name', '=', 'passer-test')->first();
		$role->attachPermission($permission);
		$permission = Permission::where('name', '=', 'enseigner')->first();
		$role->attachPermission($permission);
		

		$role = new Role();
		$role->name         = 'professeur';
		$role->display_name = 'Professeur';
		$role->description  = 'Les professeurs en charge des étudiants';
		$role->save();
		
		$permission = Permission::where('name', '=', 'ajout-etudiant')->first();
		$role->attachPermission($permission);
		$permission = Permission::where('name', '=', 'passer-test')->first();
		$role->attachPermission($permission);
		$permission = Permission::where('name', '=', 'enseigner')->first();
		$role->attachPermission($permission);

		$role = new Role();
		$role->name         = 'etudiant';
		$role->display_name = 'Étudiants';
		$role->description  = 'Les étudiants qui sont gérés par les professeurs';
		$role->save();
		
		$permission = Permission::where('name', '=', 'passer-test')->first();
		$role->attachPermission($permission);
		
	}
}

class UserTableSeeder extends Seeder {
	
		public function run()
		{
			DB::table('users')->delete();
			$user = new User();
			$user->name = 'Admin';
			$user->nom = 'systeme';
			$user->prenom = 'un';
			$user->type= 'p';
			$user->email = 'admin@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();
			$role = Role::where('name', '=', 'admin')->first();
			$user->attachRole($role);
				
			$user = new User();
			$user->name = 'prof1';
			$user->nom = 'prof ';
			$user->prenom = 'un';
			$user->type= 'p';
			$user->email = 'prof1@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();
			$role = Role::where('name', '=', 'professeur')->first();
			$user->attachRole($role);
			
			$user = new User();
			$user->name = 'prof2';
			$user->nom = 'prof ';
			$user->prenom = 'un';
			$user->type= 'p';
			$user->email = 'prof2@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();	
			$role = Role::where('name', '=', 'professeur')->first();
			$user->attachRole($role);
			
			$user = new User();
			$user->name = 'etudiant1';
			$user->nom = 'etudiant';
			$user->prenom = 'un';
			$user->type= 'e';
			$user->email = 'etudiant1@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();	
			$role = Role::where('name', '=', 'etudiant')->first();
			$user->attachRole($role);
			
			$user = new User();
			$user->name = 'etudiant2';
			$user->nom = 'etudiant2';
			$user->prenom = 'deux';
			$user->type= 'e';
			$user->email = 'etudiant2@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();
			$role = Role::where('name', '=', 'etudiant')->first();
			$user->attachRole($role);
			
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


