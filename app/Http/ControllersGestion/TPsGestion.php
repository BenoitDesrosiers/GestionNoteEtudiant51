<?php 
namespace App\Http\ControllersGestion; 

use App\Http\ControllersGestion\BaseFilteredGestion;
use App\Models\TP;
use App\Models\Classe;
use App\Models\Note;
use App\Models\Question;
use App\Models\User;

use Input;
use Auth;
use Session;

class TPsGestion extends BaseFilteredGestion{

	/*******
	******
	***** TODO: associer un TP à un prof
	*****
	***/
	
	
public function __construct(TP $model, Classe $filteringClass){
	parent::__construct($model, $filteringClass);
}

protected function filter1( $filteringItem) {
	//$filteringItems doit être une Classe
	$lignes = [];
	$lignes[$filteringItem->nom] = $filteringItem->tps->sortBy('nom');
	return $lignes; 
}
protected function filter2($filterValue) {
	//Pour les TPs, le filter 2 est la sessionScholaire
		try {
			if($filterValue == 0) {// 0 indique 'Tous' sur filter2
				$classes = Classe::all();
			} else {
				$classes = $this->filteringClass->where('sessionscholaire_id', '=' , $filterValue)->get(); //va chercher les classes pour cette session
			}
			//Si l'usager connecté n'a pas la capacité de gérer les classes, alors on liste seulement les classes associées à cet usager.
			if(!Auth::user()->ability('','ajout-classe')) {
				$u = Auth::user();
				$classes = $classes->filter(function($item) use ($u) {return $item->professeurs()->get()->contains("id",$u->id);} );
			}
			
			$lignes = [];
			foreach($classes as $classe) { //créé la liste des ids des TPs pour toutes ces classes.
				$lignes[$classe->nom] = $classe->tps->sortBy('nom');
			}	
			
			// liste les TPs qui ne sont associés à aucune classe. 
			$tps = TP::all();
			foreach($tps as $tp){
				if($tp->classes->isempty()) {$listeTPsOrphelins[]=$tp->id;}
			}
			if(!empty($listeTPsOrphelins)) {
				$lignes['Aucune classe associée'] = TP::wherein('id',$listeTPsOrphelins)->get();
			}
		} catch (Exception $e) {
			$lignes = [];
		}	
	
	return $lignes;
}

public function index() {
	return $this->createHeaderForView( 'Tous');
	
}
public function create() {
	return $this->createHeaderForView('Aucune Classe');
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
	$tp = new $this->model(['nom'=>$input['nom'], 'poids'=>$input['poids']]);
	if($tp->save()) {//TODO: mettre ca dans une transaction
		foreach($classeIds as $classeId) {
			if($classeId <>0 ){
				//associe la classe au TP (many to many)
				$tp->classes()->attach($classeId, ['poids_local'=>$tp->poids]); // pour la création, je prends le poids du tp pour le poids local
			}
		}
		return true;
	} else {
		return $tp->validationMessages;
	}

}

public function show($id){
	return $this->createHeaderForView(null,$this->model->findOrFail($id),true);
	
}

public function edit($id){
	return $this->createHeaderForView('Aucune classe', $this->model->findOrFail($id));
}

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
					
		$tp = $this->model->findOrFail($id); //TODO catch l'exception
		$tp->nom = $input['nom'];
		$tp->poids = $input['poids'];
		if($tp->save()) {
			$tp->classes()->sync($classeIds);
			return true;
		} else {
			return $tp->validationMessages;
			}

}

public function destroy($id){
	$tp = $this->model->findOrFail($id);
	$tp->classes()->detach();
	$tp->delete();
	// Détruit les notes associées à ce tp
	$notes = Note::where('tp_id', '=', $id)->get();
	foreach($notes as $note) {
		$note->delete();
	}
	
	return true;
}


public function changerPivotClasse($tp_id, $classe_id) {
	$classe = Classe::findOrFail($classe_id);
	$tp = $classe->tps()->where('tp_id', '=', $tp_id)->first();
	return compact('tp', 'classe');
}
public function doChangerPivotClasse($tp_id, $classe_id, $poids, $commentaire_visible) {
	$classe = Classe::findOrFail($classe_id);
	$tp = $classe->tps()->where('tp_id', '=', $tp_id)->first();
	$tp->classes()->updateExistingPivot($classe_id, ['poids_local'=>$poids, 'commentaire_visible' => isset($commentaire_visible)?1:0]); //TODO valider que le poids est <100
	return true;	
}


/**
 * Crée les variables utilisées pour créer l'entête de la view Index 
 * 
 * @param string $option0 le nom d'une option supplémentaire (tous, aucun...) qui est ajouté à l'index 0 du select
 * @param TP $item le tp sélectionné dans le select précédent cet écran
 * @param boolean $displayOnlyLinked flag indiquant si on doit afficher seulement les classes associés à $item
 * @return multitype:
 */
private function createHeaderForView( $option0, $item=null, $displayOnlyLinked=null) {
	if(isset($item) and isset($displayOnlyLinked) ) {
		$lesClasses = $item->classes;//affiche seulement les classes associées à cet item. (utile pour show)
	} else {//sinon affiche toutes les classes.
		$lesClasses = Classe::all()->sortby("sessionscholaire_id"); //ce n'est pas exactement par session, mais si les id sont dans le bon ordre, ca le sera.
	}
	//Si l'usager connecté n'a pas la capacité de gérer les classes, alors on liste seulement les classes associées à cet usager. 
	if(!Auth::user()->ability('','ajout-classe')) {
		$u = Auth::user();
		$lesClasses = $lesClasses->filter(function($item) use ($u) {return $item->professeurs()->get()->contains("id",$u->id);} );
	}
	
	$belongsToList = createSelectOptions($lesClasses,[get_class(), 'createOptionsValue'], $option0);
	if(isset($item)) { //si on a un item, on sélectionne toutes les classes déjà associées
		$belongsToSelectedIds =  $item->classes->pluck('id')->toArray();
	} else { //sinon, on sélectionne la classe qui a été passée en paramêtre (si elle est bonne, sinon, la première de la liste
		$belongsToSelectedIds = checkLinkedId(array_keys($belongsToList)[0], Input::get('belongsToId'), 'App\Models\Classe');  //TODO: enlever input d'ici, ca crée un lien avec la view. 
	}
	
	$filtre1 = createFiltreParSessionPourClasses($lesClasses, true);
	$tp = $item;	
	return compact('tp', 'belongsToList', 'belongsToSelectedIds','filtre1');
}

/**
 * Affiche la liste des classes associées à ce TP afin de permettre de choisir lesquels distribuer
 * 
 * @param integer $id l'id du TP à distribuer
 * @return view la view pour choisir pour quelles classes distribuer le TP
 */
public function distribuer($id) {
	$lignes= [];
	try {
		$tp = $this->model->findOrFail($id);
		$classes = $tp->classes;
		foreach($classes as $classe) {
			$dejaDistribue = !(Note::forClasse($classe->id)->forTP($tp->id)->get()->isEmpty());
			$lignes[$classe->id] = ['classeid' => $classe->id, 'nom' => $classe->nom, 'session' => $classe->sessionscholaire->nom,'dejaDistribue'=>$dejaDistribue ];
		}
	} catch (Exception $e) {
		$tp = new $this->model;
	}
	
	
	return compact('tp', 'lignes');
}

public function doDistribuer($id, $input){
	$return = true;
	try {
		$tp = $this->model->findOrFail($id);
		
		if(isset($input['distribue'])) {
			//distribue les classes
			$classes = $tp->classes()->wherein('classe_id', $input['distribue'])->get();
			foreach($classes as $classe) { //TODO mettre toute la création dans une transaction
				Note::forClasse($classe->id)->forTP($tp->id)->delete(); //efface les notes déjà distribuées pour ce TP/Classe
				$etudiants= $classe->etudiants;
				$questions = $tp->questions()->orderBy('ordre')->get();
				foreach($etudiants as $etudiant) {
					$i = 1;
					foreach($questions as $question) {
						$note = new Note;
						$note->classe_id = $classe->id;
						$note->tp_id = $tp->id;
						$note->question_id = $question->id;
						$note->etudiant_id = $etudiant->id;
						$note->ordre = $i++;
						$note->save();
					}
				}
				//distribue une copie au prof pour qu'il puisse l'essayer
				$i=1;
				foreach($questions as $question) {
					$note = new Note;
					$note->classe_id = $classe->id;
					$note->tp_id = $tp->id;
					$note->question_id = $question->id;
					$note->etudiant_id = Auth::user()->id;
					$note->ordre = $i++;
					$note->save();
				}
				
				//set le flag pour indiquer que c'est distribué
				$classe->pivot->distribue = true;
				$classe->pivot->save();
					
			}
		}
		
		//retire les classes 
		if(isset($input['retire'])){
			$classes = $tp->classes()->wherein('classe_id', $input['retire'])->get();
			foreach($classes as $classe) { 
				Note::forClasse($classe->id)->forTP($tp->id)->delete(); //efface les notes déjà distribuées pour ce TP/Classe
				//reset le flag pour indiquer que c'est pas distribué
				$classe->pivot->distribue = false;
				$classe->pivot->save();
			}
		}
	} catch (Exception $e) {
		$return = false;
	}
	
	return $return;
}

/**
 * Formattage des questions sur le TP
 */


public function format($id) {
	$tp = TP::findOrFail($id);
	$questions = $tp->questions()->orderBy('ordre')->get();
	return(compact('tp','questions'));
}

public function doFormat($id, $input) {
	$tp = TP::findOrFail($id);
	$ordres= $input['ordre'];
	$questions = $tp->questions();
	foreach($ordres as $id => $ordre) {
		$questions->updateExistingPivot($id, ['ordre' => $ordre], false);
	} 
	
	if(isset($input['break'])) {
		$breaks= $input['break']; 
		foreach($breaks as $id => $value) {
			$questions->updateExistingPivot($id, ['breakafter' => 1], false);
		}
	}
	return true;
}


/**
 * Correction d'un TP
 */


/**
 * Retourne l'information nécessaire pour faire la correction d'une question d'un TP d'une classe pour un étudiant
 * 
 * @param integer $tp_id
 * @param integer $classe_id
 * @param integer $offset_etudiant le numéro de séquence de l'étudiant à corriger 
 * @param integer $offset_question le numéro de séquence de la question à corriger 
 */
public function corriger($tp_id, $classe_id, $offset_etudiant, $offset_question) {   //FIXME: ca plante quand il n'y a rien a corriger. 
	try{
		$classe= Classe::findOrFail($classe_id);
		$tp = $classe->tps()->where("tp_id",'=',$tp_id)->first();
	} catch (Exception $e) {
		throw new Exception("Paramêtres incorrects");
	}
	$questions = $tp->questions()->orderBy('ordre')->get();
	$etudiants = $classe->etudiants()->orderBy('id')->get();
	//batit la liste des réponses déjà soumises par l'étudiant associé aux questions de la page affichée
	$offset_etudiant = max(0,min($offset_etudiant,$etudiants->count()-1));
	$offset_question = max(0,min($offset_question,$questions->count()-1));
	$etudiant = $etudiants->offsetGet($offset_etudiant);
	$question = $questions->offsetGet($offset_question);
	$reponse = Note::where('classe_id','=',$classe->id)
					->where('tp_id','=',$tp->id)
					->where('etudiant_id','=',$etudiant->id)
					->where('question_id',$question->id)
					->first();
	
	//batit le sommaire des notes pour ce TP pour cet étudiant. 	
	$sommaireNotes = Note::where('classe_id','=',$classe->id)
				->where('tp_id','=',$tp->id)
				->where('etudiant_id','=',$etudiant->id)
				->orderBy("ordre")
				->get();
	
	$flagEtudiantSuivant = ($offset_etudiant < $etudiants->count()-1);
	$flagQuestionSuivante = ($offset_question < $questions->count()-1);
	$flagEtudiantPrecedent = ($offset_etudiant > 0);
	$flagQuestionPrecedente = ($offset_question > 0);
	//Sauvegarde les ids de la réponse que l'on traite afin d'être certain que les infos n'auront pas été trafiqués au retour
	Session::put('etudiantId', $etudiant->id);
	Session::put('classeId', $classe_id);
	Session::put('tpId', $tp_id);
	Session::put('questionId', $question->id);
	Session::put('offsetEtudiant', $offset_etudiant);
	Session::put('offsetQuestion', $offset_question);
	return compact('tp', 'classe', 'etudiant','question','reponse', 
						'flagEtudiantPrecedent', 'flagEtudiantSuivant', 'flagQuestionPrecedente', 'flagQuestionSuivante',
						'offset_etudiant', 'offset_question',
						'sommaireNotes', 'questions');
}

/**
 * Sauvegarde la correction 
 * @param unknown $etudiant_id
 * @param unknown $classe_id
 * @param unknown $tp_id
 * @param unknown $questions_id
 * @param unknown $input
 */
public function doCorriger($etudiant_id, $classe_id, $tp_id, $question_id, $commentaire, $commentaire_visible, $pointage) {
	$etudiant = User::findorfail($etudiant_id);
	$classe = Classe::findorfail($classe_id);
	$tp = TP::findorfail($tp_id);
	$question = Question::findorfail($question_id);
	
	$reponse = Note::where('classe_id','=',$classe->id)
		->where('tp_id','=',$tp->id)
		->where('etudiant_id','=',$etudiant->id)
		->where('question_id',$question->id)
		->first();
	$reponse->commentaire = $commentaire;
	$reponse->commentaire_visible = $commentaire_visible;
	$reponse->note = $pointage;
	if($reponse->save()) {
		return true;
	} else {
		return $reponse->validationMessages;
	}
}

public function afficheReponseAutreEtudiant($direction, $etudiantCourant_id, $classe_id, $tp_id, $question_id) {
	$nom = "";
	$reponse = "";
	$pointage = "";
	$commentaire = "";
	$autreEtudiantOffset = Session::pull('autreEtudiantOffset');
	$offsetOriginal = $autreEtudiantOffset;
	if($direction=='precedent') {$autreEtudiantOffset--;} else if($direction=='suivant') {$autreEtudiantOffset++;};
	
	$classe = Classe::findorfail($classe_id);
	$etudiants = $classe->etudiants()->orderBy('id')->get();
	$autreEtudiantOffset =  max(0,min($autreEtudiantOffset,$etudiants->count()-1));
	$autreEtudiant = $etudiants->offsetGet($autreEtudiantOffset);
	if($autreEtudiant->id==$etudiantCourant_id) {
		//saute l'étudiant courant
		if($direction=='precedent') {
			if($autreEtudiantOffset > 0) {
				$autreEtudiantOffset--; //saute
			} else {$autreEtudiantOffset = $offsetOriginal; } //on est au début, on revient ou on était
		} else if($direction=='suivant') {
			if($autreEtudiantOffset<$etudiants->count()-1) {
				$autreEtudiantOffset++; //saute
			} else {$autreEtudiantOffset = $offsetOriginal; } //on est à la fin, on revient ou on était
		}
		//retourne rechercher le nouvel étudiant
		$autreEtudiant = $etudiants->offsetGet($autreEtudiantOffset);
	}
	//
	
	if($autreEtudiant->id <> $etudiantCourant_id) {
		//je dois quand même faire ce if car dans le cas ou il n'y aurait qu'un étudiant (le else), je ne dois rien afficher
		$note =  Note::where('classe_id','=',$classe_id)
		->where('tp_id','=',$tp_id)
		->where('etudiant_id','=',$autreEtudiant->id)
		->where('question_id','=', $question_id)
		->first();	
		$nom = $autreEtudiant->prenom . ' '. $autreEtudiant->nom;
		$reponse = $note->reponse;
		$pointage = $note->note;
		$commentaire = $note->commentaire;	
	}	
	$flagBoutonEtudiantSuivant = ($autreEtudiantOffset < $etudiants->count()-1);
	$flagBoutonEtudiantPrecedent = ($autreEtudiantOffset > 0);
	Session::put('autreEtudiantOffset', $autreEtudiantOffset);
	
	return compact('nom', 'reponse','pointage', 'commentaire', 'flagBoutonEtudiantPrecedent', 'flagBoutonEtudiantSuivant');
}

public function resultats($tp_id, $classe_id) {
	try{
		$classe= Classe::findOrFail($classe_id);
		$tp = $classe->tps()->where("tp_id",'=',$tp_id)->first();
	} catch (Exception $e) {
		throw new Exception("Paramêtres incorrects");
	}
	$etudiants = $classe->etudiants()->orderBy('id')->get();
	$questions = $tp->questions()->orderBy('ordre')->get();
	
	$i=1;
	foreach($etudiants as $etudiant) {
		$resultats[$i]['nom']=$etudiant->prenom.' '.$etudiant->nom;
		$notes = Note::where('classe_id','=',$classe->id)
					->where('tp_id','=',$tp->id)
					->where('etudiant_id', '=', $etudiant->id)
					->orderBy("ordre")
					->get();
		foreach($notes as $note) {
			$resultats[$i]['notes'][$note->ordre] = $note;
		}
		$i++;
	}
	return compact('tp', 'classe', 'etudiants', 'resultats','questions');
}



public function transmettreCorrection($tp_id, $classe_id) {
	$return = true;
	Try {
		$classe= Classe::findOrFail($classe_id);
		$tp = $classe->tps()->where("tp_id",'=',$tp_id)->first();
		$tp->pivot->corrige = true;
		$tp->pivot->save();
	} catch (Exception $e) {
		$return = false;
	}
	return $return;
}

public function retirerCorrection($tp_id, $classe_id) {
	$return = true;
	Try {
		$classe= Classe::findOrFail($classe_id);
		$tp = $classe->tps()->where("tp_id",'=',$tp_id)->first();
		$tp->pivot->corrige = false;
		$tp->pivot->save();
	} catch (Exception $e) {
		$return = false;
	}
	return $return;
}
/**
 * Helpers
 *
 */
static function createOptionsValue($item) {
	return $item->sessionscholaire->nom." ". $item->code." ".$item->nom;
}

}