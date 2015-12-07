<?php 

namespace App\Http\ControllersGestion; 

use App\Http\ControllersGestion\BaseGestion;
use App\Models\Classe;
use App\Models\Sessionscholaire;
use Auth;

class ClassesGestion extends BaseGestion{

public function __construct(Classe $model){
	$this->model = $model;
}


public function index() {

	//si l'usager connecté peu ajouter des classes, on liste toutes les classes
	//sinon, c'est un professeur, alors on limite la liste des classes aux siennes
	$u = Auth::user();
	if(Auth::user()->ability('','ajout-classe')) {
		$lignes = $this->listeAllLignes();
	} else {
		$lignes = $this->listeAllLignes()->filter(function($item) use ($u) {return $item->professeurs()->get()->contains("id",$u->id);} );
	}
	$sessionsList= Sessionscholaire::all()->lists( 'nom','id'); //TODO: y a-t-il moyen d'injecter Sessionscholaire?
	$sessionSelected = Sessionscholaire::where("courant", 1)->value("id");
	return compact('lignes','sessionsList', 'sessionSelected');
	
}

public function create() {
	$sessionsList= Sessionscholaire::all()->lists( 'nom','id'); //TODO: y a-t-il moyen d'injecter Sessionscholaire?
	$sessionSelected = Sessionscholaire::where("courant", 1)->value("id");
	return compact('sessionsList', 'sessionSelected');
}

public function store($input) {
	$classe = new $this->model($input);//TODO mettre ca dans une transaction. 
	$sessionScholaire = Sessionscholaire::findOrFail($input['sessionscholaire_id']); //TODO catcher l'exception
	if($sessionScholaire->classes()->save($classe)) {
		return true;
	} else {
		return $classe->validationMessages;
	}
}

public function show($id){
	$classe =  $this->model->find($id); //TODO catcher exception
	return compact('classe');
}

public function edit($id){
	$sessionsList= Sessionscholaire::all()->lists( 'nom','id');
	$classe = $this->model->findOrFail($id);
	$sessionSelected = $classe->sessionscholaire->id;
	return compact('classe',"sessionsList", "sessionSelected");
}

public function update($id, $input){
	$classe = $this->model->findOrFail($id);
	$classe->code = $input['code'];
	$classe->nom = $input['nom'];
	$classe->groupe = $input['groupe'];
	$classe->local = $input['local'];
	$sessionScholaire = Sessionscholaire::findOrFail($input['sessionscholaire_id']); //TODO catcher l'exception
	
	if($sessionScholaire->classes()->save($classe)) {
		return true;
	} else {
		return $classe->validationMessages;
	}
}

public function destroy($id){
	$classe = $this->model->findOrFail($id);
	$classe->tps()->detach();
	
	$classe->delete();
	
	// Détruit les notes associées à cette classes
	//TODO mettre un observer sur le delete afin que tous les dépendants se mettre à jour
	$notes = Note::where('classe_id', '=', $id)->get();  //TODO:: injecter Note
	foreach($notes as $note) {
		$note->delete();
	}
	return true;
}
}