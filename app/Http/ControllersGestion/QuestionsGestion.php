<?php 


namespace App\Http\ControllersGestion;

use App\Http\ControllersGestion\BaseFilteredGestion;
use App\Models\Question;
use App\Models\TP;
use App\Models\Classe;
use App\Models\Note;
use Input;

class QuestionsGestion extends BaseFilteredGestion{

public function __construct(Question $model, TP $filteringClass){
	parent::__construct($model, $filteringClass);
}

protected function filter1($filteringItem) { 
	//$filteringItems doit être un TP
	return $filteringItem->questions->sortBy('nom');
}
protected function filter2($filterValue) {
	//Pour les questions, le filter 2 est une Classe
	if($filterValue == 0) {// 0 indique 'Tous' sur filter2
		$lignes = $this->model->all()->sortBy('nom');
	} else {
		try {
			$tps = Classe::find($filterValue)->tps; //cherche les TPs associés à la Classe sélectionnée
			$modelIds = [];
			foreach($tps as $tp) { //créé la liste des ids des questions pour tous ces TPs.
				$modelIds=array_merge($modelIds,$this->filter1($tp)->lists('id')->all());
			}
			//une questions peut être avec 2 TPs, il faut donc aller les chercher par leur id afin d'enlever les doublons
			if(count($modelIds)>0) {
				$lignes = $this->model->whereIn('id', $modelIds)->get()->sortBy('nom');
			} else {
				$lignes = new Illuminate\Database\Eloquent\Collection;
			}
		} catch (Exception $e) {
			$lignes = new Illuminate\Database\Eloquent\Collection;
		}
	}
	return $lignes;
}



public function index() {
	return $this->displayView( 'Tous',Input::get('belongsToId')); //TODO ce Input devrait venir de BaseResourcesController, mais pour cela faut changer tout les gestionnaires pour changer leur interfacs
}

/**
 * Création d'un question
 * 
 * @param multitype $callBackRoute la route à prendre lors du submit. Permet d'utiliser la page de création sur un autre controlleur. 
 * @return multitype:
 */
public function create($callBackRoute, $selectedValue) { //create recoit $selectedValue parce que le QuestionController overwrite son create. 
	$parms = $this->displayView('Aucun TP', $selectedValue);	
	$parms['callBackRoute'] = $callBackRoute;
	return $parms;
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
public function store($input) {
	$tpId = 0;
	//vérifie que les ids de TP passés en paramêtre sont bons
	if(isset($input['belongsToListSelect'])) {
		$tpIds = $input['belongsToListSelect'];
		if(!allIdsExist($tpIds, 'App\Models\TP')) {
			App::abort(404);
		}
	} else {
		$tpIds = [];
	}
	$question = new $this->model; //TODO ajouter un constructeur et une méthode de classe newWithTPs
	return $question->createWithTPs($input, $tpIds);

}

public function show($id){
	return $this->displayView(null,Input::get('belongsToId'), $this->model->findOrFail($id),true); //TODO  Input doit venir du ctrl
}

public function edit($id){
	return $this->displayView('Aucun TP',Input::get('belongsToId'), $this->model->findOrFail($id));//TODO  Input doit venir du ctrl
}

public function update($id, $input){
		//verifie que les ids de classe passé en paramêtre sont bons
		if(isset($input['belongsToListSelect'])) {
				$tpIds = $input['belongsToListSelect'];
				if(!allIdsExist($tpIds, 'App\Models\TP')){
					App::abort(404); 
				}
		} else {
				$tpIds =[]; 
		}

		$question = $this->model->findOrFail($id);
		return $question->updateForTPs($input,$tpIds);
}

public function destroy($id)
	{
		$question = $this->model->findOrFail($id);
		$question->tps()->detach(); //TODO: resynch l'ordre
		$question->delete(); 
		
		// Détruit les notes associées à cette question
		$notes = Note::where('question_id', '=', $id)->get();
		foreach($notes as $note) {
			$note->delete();
		}
		
		return true;			
	}

private function displayView( $option0, $selectedValue, $item=null, $displayOnlyLinked=null) {
		if(isset($item) and isset($displayOnlyLinked) ) {
			$lesTPs = $item->tps;//affiche seulement les tps associées à cet item. (utile pour show)
		} else {//sinon affiche tous.
			$lesTPs = TP::all()->sortby("nom");
		}
		$belongsToList = createSelectOptions($lesTPs,[get_class(), 'createOptionsValue'], $option0);
		if(isset($item)) { //si on a un item, on sélectionne seulement ce qui est associées
			$belongsToSelectedIds =  $item->TPs->pluck('id')->toArray();
		} else { //sinon, on sélectionne ce qui a été passée en paramêtre (si c'est bon, sinon, la première de la liste
			$belongsToSelectedIds = checkLinkedId(array_keys($belongsToList)[0], $selectedValue, 'App\Models\TP');
		}
		$filtre1 = createFiltreParClassePourTP($lesTPs, true);
		$question = $item;
		return compact('question', 'belongsToList', 'belongsToSelectedIds','filtre1');
}
	
/**
 * Helpers
 *
 */
static function createOptionsValue($item) {
	return  $item->nom;
}

}