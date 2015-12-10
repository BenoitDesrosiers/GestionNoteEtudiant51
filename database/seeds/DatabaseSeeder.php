<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Classe;
use App\Models\Sessionscholaire;
use App\Models\Programme;
use App\Models\Question;
use App\Models\TP;


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
		$this->call('TPTableSeeder');
		$this->call('QuestionTableSeeder');
		
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



class TPTableSeeder extends Seeder {

	public function run()
	{

		$classeCN2 = Classe::where('code', '=', '420-CN2-DM')->first();
		$classeDM1 = Classe::where('code', '=', '420-DM1-DM')->first();
		$classeDM2 = Classe::where('code', '=', '420-DM2-DM')->first();
		
			
		DB::table('classes_tps')->delete();
		DB::table('tps')->delete();

		$tp = new TP();
		$tp->nom='TP1 CN2';
		$tp->poids=50;
		$tp->save();
		$tp->classes()->attach($classeCN2->id,['poids_local'=>50]);
		
		$tp = new TP();
		$tp->nom='TP2 CN2';
		$tp->poids=50;
		$tp->save();
		$tp->classes()->attach($classeCN2->id,['poids_local'=>50]);
		
		$tp = new TP();
		$tp->nom='TP1 DM1';
		$tp->poids=30;
		$tp->save();
		$tp->classes()->attach($classeDM1->id,['poids_local'=>50]);
			
	}
}


class QuestionTableSeeder extends Seeder {

	public function run()
	{
		$tp1cn2 = TP::where('nom','=','TP1 CN2')->first();
		$tp2cn2 = TP::where('nom','=','TP2 CN2')->first();
		$tp1DM1 = TP::where('nom','=','TP1 DM1')->first();
		
		DB::table('tps_questions')->delete();
		DB::table('questions')->delete();
		
		$question = new Question();
		$question->nom = "Q1";
		$question->enonce = "Quel est la couleur du cheval blanc de Napoléon";
		$question->sur = 10;
		$question->baliseCorrection = "blanc = 10, noir =0";
		$question->reponse = "blanc";
		$question->save();
		$tp1cn2->addQuestion($question);
		
		
		$question = new Question();
		$question->nom = "Exponentielle";
		$question->enonce = "<p>Si x = 2</p>

<p>et y = 3</p>

<p>Quelle est la valeur de <strong>x<sup>y</sup></strong>?</p>
		";
		$question->sur = 10;
		$question->baliseCorrection = "<p>un nombre pair = 5p</p>

<p>8 = 10p</p>";
		$question->reponse = "8";
		$question->save();
		$tp1cn2->addQuestion($question);
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
