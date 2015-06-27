<?php 

namespace App\Http\ControllersGestion; 

use App\Http\ControllersGestion\BaseGestion;
use App\Models\Classe;
use App\Models\Sessionscholaire;

class ClassesGestion extends BaseGestion{

public function __construct(Classe $model){
	$this->model = $model;
}

public function index() {
	$lignes = $this->listeAllLignes();
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