<?php 

namespace App\Http\ControllersGestion;

use App\Http\ControllersGestion\BaseFilteredGestion;
use App\Models\Classe;
use App\Models\User;
use App\Models\Role;

use Input;
use Hash;



abstract class UsersGestion extends BaseFilteredGestion{

public function __construct(User $model, Classe $filteringClass){
	parent::__construct($model, $filteringClass);
}

protected function filter1Type($filteringItem,$type) {
	//$filteringItems doit être une Classe
	return $filteringItem->users()->where('type','=',$type)->get()->sortBy('nom');
}
protected function filter2Type($filterValue,$type) {
	if($filterValue == 0) {// 0 indique 'Tous' sur filter2
		$lignes = $this->model->where('type','=',$type)->get()->sortBy('nom');
	} else {
		try {
			$filterByItems = $this->filteringClass->where('sessionscholaire_id', '=' , $filterValue)->get(); //va chercher les classes pour cette session
			$modelIds = [];
			foreach($filterByItems as $item) { //créé la liste des ids des TPs pour toutes ces classes.
				$modelIds=array_merge($modelIds,$this->filter1($item)->lists('id'));
			}
	
			//un user peut être avec 2 classes, il faut donc aller les chercher par leur id afin d'enlever les doublons
			if(count($modelIds)>0) {
				$lignes = $this->model->whereIn('id', $modelIds)->get()->sortBy('nom');
			} else { //aucun TP de retourné, on créé donc une liste vide.
				$lignes = new Illuminate\Database\Eloquent\Collection;
			}
		} catch (Exception $e) {
			$lignes = new Illuminate\Database\Eloquent\Collection;
		}
	}
	return $lignes;
}

public function index() {
	return $this->displayView( 'Tous');
	
}

public function create() {
	return $this->displayView('Aucune Classe');
}

/**
 * Enregistrement initial dans la BD
 *
 *
 * @param[in] get int belongsToListSelect les ids des classes auxquelles cet étudiant sera associé.
 * 					Si vide, alors l'étudiant ne sera associé à rien.
 * 				 	Les ids doivent être valide, sinon une page d'erreur sera affichée.
 *
 */
protected function storeTypeRole($input,$type,$role) {
	$classeId = 0;
	//verifie que les ids de classe passé en paramêtre sont bons
	if(isset($input['belongsToListSelect'])) {
		$classeIds = $input['belongsToListSelect'];
		if(!allIdsExist($classeIds, 'App\Models\Classe')){
			App::abort(404);
		}
	} else {
		$classeIds =[];
	}
	$user = new $this->model([
					'name'=>$input['name'], 
					'prenom'=>$input['prenom'],
					'nom'=>$input['nom'],
					'email'=>$input['email'],
					'password'=> Hash::make($input['password']),
					'programme_id'=>$input['programme_id'],
					'type'=>$type
			
					]);
	
	
	if($user->save()) {//TODO: mettre ca dans une transaction
		foreach($classeIds as $classeId) {
			if($classeId <>0 ){
				//associe la classe aux users (many to many)
				$user->classes()->attach($classeId);
			}
		}
		
		$unRole = Role::where('name', '=', $role)->first();
		$user->attachRole($unRole);
		return true;
	} else {
		return $user->validationMessages;
	}

}

/**
 * Enregistrement initial dans la BD
 *
 *
 * @param[in] get int belongsToListSelect les ids des classes auxquelles ce TP sera associé.
 * 					Si vide, alors le tp ne sera associé à rien.
 * 				 	Les ids doivent être valide, sinon une page d'erreur sera affichée.
 *
 */

public function show($id){
	return $this->displayView(null,$this->model->findOrFail($id),true);
}

public function edit($id){
	$user =  $this->model->findOrFail($id); //TODO: catch exception
	$user->password = "ne peut être modifié";
	return $this->displayView('Aucune classe', $user);
}

/**
 * @param int $id
 * @param array $input l'input provenant de la view.
 * @return boolean
 */

public function update($id, $input){
		//verifie que les ids de classe passé en paramêtre sont bons
		if(isset($input['belongsToListSelect'])) {
				$classeIds = $input['belongsToListSelect'];
				if(!allIdsExist($classeIds, 'App\Models\Classe')){
					App::abort(404); 
				}
		} else {
				$classeIds =[]; 
		}
					
		$user = $this->model->findOrFail($id); //TODO catch l'exception
		
		$user->name = $input['name'];
		$user->prenom = $input['prenom'];
		$user->nom = $input['nom'];
		$user->email = $input['email'];
		//$user->password =  Hash::make($input['password']); Pour l'instant, je ne permet pas de changer le password
		$user->programme_id = $input['programme_id'];
		
		if($user->save()) {
			$user->classes()->sync($classeIds);
			return true;
		} else {
			return $user->validationMessages;
			}
}

/**
 * Construit les objets utilisées dans la view Index
 * 
 * @param string $option0 le texte de la première option dans le pull down affichant les classes
 * @param User   $item    un User en particulier, sert à filtrer la liste des classes à afficher dans le pulldown
 * @param bool   $displayOnlyLinked  true indique de n'afficher que les classes liées à $item. False affichera toute les classes même si $item isset. 
 * @return le User passer dans $item, la liste des classes à mettre dans le pull down, l'id de la classe sélectionnée, et le filtre1
 */

private function displayView( $option0, $item=null, $displayOnlyLinked=null) {
	if(isset($item) and isset($displayOnlyLinked) ) {
		$lesClasses = $item->classes;//affiche seulement les classes associées à cet item. (utile pour show)
	} else {//sinon affiche toutes les classes.
		$lesClasses = Classe::all()->sortby("sessionscholaire_id"); //ce n'est pas exactement par session, mais si les id sont dans le bon ordre, ca le sera.
	}
	$belongsToList = createSelectOptions($lesClasses,[get_class(), 'createOptionsValue'], $option0);
	if(isset($item)) { //si on a un item, sélectionne toutes les classes déjà associées
		$belongsToSelectedIds =  $item->classes->pluck('id')->toArray();
	} else { //sinon, on sélectionne la classe qui a été passée en paramêtre (si elle est bonne, sinon, la première de la liste)
		$belongsToSelectedIds = checkLinkedId(array_keys($belongsToList)[0], Input::get('belongsToId'), 'App\Models\Classe'); //TODO: belongsToId devrait être passé en paramêtre. 
	}
	$filtre1 = createFiltreParSessionPourClasses($lesClasses, true);
	$user = $item;
	return compact('user', 'belongsToList', 'belongsToSelectedIds','filtre1');
}


/**
 * Helpers
 *
 */
static function createOptionsValue($item) {
	return $item->sessionscholaire->nom." ". $item->code." ".$item->nom;
}

}