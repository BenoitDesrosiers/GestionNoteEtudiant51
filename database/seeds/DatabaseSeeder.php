<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Classe;
use App\Models\Sessionscholaire;
use App\Models\Programme;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('SessionTableSeeder');
		$this->call('ProgrammeTableSeeder');
		$this->call('ClasseTableSeeder');
		$this->call('PermissionTableSeeder');
		$this->call('RoleTableSeeder');
		$this->call('UserTableSeeder');

		
		
	}
}
	
class SessionTableSeeder extends Seeder {

	public function run()
	{
		$sessions = ["A2014", "H2015", "E2015","A2015", "H2016", "E2016"];
		DB::table('sessionscholaires')->delete();
		foreach($sessions as $session) {
			$courant = ($session == 'H2016');
			DB::table('sessionscholaires')->insert(array('nom'=>$session , 'courant'=>$courant));
		}
	}
}
class ProgrammeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('programmes')->delete();

		$programme = new Programme();
		$programme->id = '420AA';	
		$programme->nom = "Techniques Informatique: informatique de gestion";	
		$programme->save();
	}
}
class ClasseTableSeeder extends Seeder {

	public function run()
	{
		DB::table('classes')->delete();

		$classe = new Classe();
		$classe->code = "420-CN2-DM";
		$classe->nom =  "Prog web 2";
		$classe->groupe = "0001";
		$classe->local = "1512-1";
		$sessionscholaire = Sessionscholaire::where('nom', '=', 'A2015')->first();
		$sessionscholaire->classes()->save($classe);
			
		$classe = new Classe();
		$classe->code = "420-DM1-DM";
		$classe->nom =  "Projet 1";
		$classe->groupe = "0001";
		$classe->local = "1512-1";
		$sessionscholaire = Sessionscholaire::where('nom', '=', 'A2015')->first();
		$sessionscholaire->classes()->save($classe);

		$classe = new Classe();
		$classe->code = "420-DM2-DM";
		$classe->nom =  "Projet 2";
		$classe->groupe = "0001";
		$classe->local = "1512-1";
		$sessionscholaire = Sessionscholaire::where('nom', '=', 'H2016')->first();
		$sessionscholaire->classes()->save($classe);
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
		
		$permission = new Permission();
		$permission->name         = 'ajout-classe';
		$permission->display_name = 'Ajout de classes';
		$permission->description  = 'Permet d\'ajouter des classes';
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
		$permission = Permission::where('name', '=', 'ajout-classe')->first();
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
			
			$programme = Programme::where('id', '=', '420AA')->first();
			$classeCN2 = Classe::where('code', '=', '420-CN2-DM')->first();
			$classeDM1 = Classe::where('code', '=', '420-DM1-DM')->first();
			$classeDM2 = Classe::where('code', '=', '420-DM2-DM')->first();
			
			$roleadmin = Role::where('name', '=', 'admin')->first();
			$roleprof  = Role::where('name', '=', 'professeur')->first();
			$roleetudiant = Role::where('name', '=', 'etudiant')->first();
				
			
			DB::table('users_classes')->delete();
			DB::table('users')->delete();
			$user = new User();
			$user->name = 'Admin';
			$user->nom = 'systeme';
			$user->prenom = 'admin';
			$user->type= 'p';
			$user->email = 'admin@chose.com';
			$user->password = Hash::make('usager');;
			$user->save();
			$user->attachRole($roleadmin);
				
			$user = new User();
			$user->name = 'prof1';
			$user->nom = 'prof1 ';
			$user->prenom = 'un';
			$user->type= 'p';
			$user->email = 'prof1@chose.com';
			$user->password = Hash::make('usager');;
			$programme->users()->save($user);
			$user->attachRole($roleprof);
			$user->classes()->sync([$classeCN2->id, $classeDM1->id]);
				
			
			$user = new User();
			$user->name = 'prof2';
			$user->nom = 'prof2 ';
			$user->prenom = 'deux';
			$user->type= 'p';
			$user->email = 'prof2@chose.com';
			$user->password = Hash::make('usager');;
			$programme->users()->save($user);
			$user->attachRole($roleprof);
			$user->classes()->sync([$classeCN2->id, $classeDM2->id]);
				
			
			$user = new User();
			$user->name = 'etudiant1';
			$user->nom = 'etudiant';
			$user->prenom = 'un';
			$user->type= 'e';
			$user->email = 'etudiant1@chose.com';
			$user->password = Hash::make('usager');;
			$programme->users()->save($user);
			$user->attachRole($roleetudiant);
			$user->classes()->sync([$classeCN2->id, $classeDM1->id]);
				
			
			$user = new User();
			$user->name = 'etudiant2';
			$user->nom = 'etudiant2';
			$user->prenom = 'deux';
			$user->type= 'e';
			$user->email = 'etudiant2@chose.com';
			$user->password = Hash::make('usager');;
			$programme->users()->save($user);
			$role = Role::where('name', '=', 'etudiant')->first();
			$user->attachRole($roleetudiant);
			$user->classes()->sync([$classeCN2->id, $classeDM1->id, $classeDM2->id]);
				
			
		}
}


