<?php 

namespace App\Http\ControllersGestion;

use App\Http\ControllersGestion\BaseFilteredGestion;

use App\Models\TP;
use App\Models\Classe;
use App\Models\User;
use App\Models\Note;
use Auth;
use Session;

class TPsPassationGestion extends BaseFilteredGestion{

public function __construct(TP $model, Classe $filteringClass){
	parent::__construct($model, $filteringClass);
}

/**
 * Affiche la liste des TPs associés à l'étudiant présentement connecté
 * Permet de restreindre les TPs en choissisant une classe.
 *
 */
public function index() {
		return $this->createFilters('Tous',Auth::user());
}

public function show($id){
	return $this->createFilters(null,$this->model->findOrFail($id),true);	
}

public function repondre($classe_id, $tp_id, $pageCourante, $flagCheckSiCorrige) {
 	$etudiant = Auth::user(); 
	$classe = Classe::findorfail($classe_id);//TODO catch exception (au pire, retourner une liste vide)
	$tp = $classe->tps()->where('tp_id',"=", $tp_id)->first();
	if(!$flagCheckSiCorrige or !$tp->pivot->corrige) {
		return $this->construitQuestionnaire($etudiant,$classe, $tp, $pageCourante);
	} else {
		return false;
	}
}


protected function construitQuestionnaire($etudiant, $classe, $tp, $pageCourante) {
	$toutesLesQuestions = $tp->questions()->orderBy('ordre')->get();
	$toutesLesReponses =  Note::where('classe_id','=',$classe->id)
								->where('tp_id','=',$tp->id)
								->where('etudiant_id','=',$etudiant->id)
								->get();
	//batit la pagination des questions
	$i = 1;
	foreach($toutesLesReponses as $reponse) {
		$page[$i][] = $reponse->id;
		if($toutesLesQuestions->find($reponse->question_id)->pivot->breakafter) { $i++; }
	}
	//batit la liste des réponses déjà soumises par l'étudiant associées aux questions de la page affichée
	$reponsesPageCourante = Note::whereIn('id',$page[$pageCourante])
									->orderBy("ordre")
									->get();
	
	//les indicateurs pour la pagination
	if(!empty($page[$pageCourante+1])) {
		$pageSuivante = $pageCourante+1;
	} else {
		$pageSuivante = null;
	}
	if(!empty($page[$pageCourante-1])) {
		$pagePrecedente = $pageCourante-1;
	} else {
		$pagePrecedente = null;
	}
	
	//le numéro de la première question de la page courante.
	$premiereQuestion = $toutesLesReponses->find($page[$pageCourante][0])->ordre;
	
	//store les ids afin de pouvoir les récupérer au retour afin que l'étudiant ne puisse répondre ou voir le TP des autres.
	Session::put('classeId', $classe->id);
	Session::put('tpId', $tp->id);
	Session::put('pageCourante', $pageCourante);
	return  compact('toutesLesQuestions', 'toutesLesReponses','reponsesPageCourante',
					'tp','classe','etudiant', 
					'pagePrecedente', 'pageCourante','pageSuivante', 'premiereQuestion');
}



/**
 * Sauvegarde les données présentement à l'écran 
 * 
 * @param unknown $input
 * @param unknown $etudiant_id
 * @param unknown $classe_id
 * @param unknown $tp_id
 * @param unknown $pageCourante
 * @return boolean|string
 */
public function doRepondre($reponses, $etudiant_id, $classe_id, $tp_id, $pageCourante) {
	$return = true;
	$etudiant = User::findorfail($etudiant_id);
	$classe = Classe::findorfail($classe_id);
	$tp = TP::findorfail($tp_id);
	$lesQuestions = $tp->questions()->orderBy('ordre')->get();
	//batit la pagination des questions
	$i = 1;
	foreach($lesQuestions as $question) {
		$page[$i][] = $question->id;
		if($question->pivot->breakafter == 1) { $i++; }
	}
	//choisi la bonne page de questions
	$questions = $lesQuestions->filter(function($item) use ($page, $pageCourante) { return in_array($item->id,$page[$pageCourante]);} );
	
	
	//verifie que c'est les bonnes questions qui nous revienne
	$listeIdReponses = 	array_keys($reponses);
	foreach($questions as $question) {
		if(!in_array($question->id, $listeIdReponses)) {
			$return=false; 
		}
	}
	
	if($return) { // on a toutes les réponses, on peut les stocker
		foreach($questions as $question) {
			$note = Note::where('classe_id','=',$classe_id)
					->where('tp_id','=',$tp_id)
					->where('etudiant_id','=',$etudiant_id)
					->where('question_id','=',$question->id)->first(); // cette requete devrait toujour fonctionner
			$note->reponse = $reponses[$question->id];
			$note->save();
		}
	}
	return $return;
}


private function createFilters( $option0, $item=null, $displayOnlyLinked=null) {
	if(isset($item) and isset($displayOnlyLinked) ) {
		$lesClasses = $item->classes->sortby("sessionscholaire_id");//affiche seulement les classes associées à cet item.
	} else {//sinon affiche toutes les classes.
		$lesClasses = Classe::all()->sortby("sessionscholaire_id"); //ce n'est pas exactement par session, mais si les id de session sont dans le bon ordre, ca le sera.
	}
	$belongsToList = createSelectOptions($lesClasses,[get_class(), 'createOptionsValue'], $option0);
	
	if(isset($item)) { //si on a un item, on sélectionne toutes les classes déjà associées
		$belongsToSelectedIds =  $item->classes->pluck('id')->toArray();
	} else { //sinon, on sélectionne la classe qui a été passée en paramêtre (si elle est bonne, sinon, la première de la liste
		$belongsToSelectedIds = checkLinkedId(array_keys($belongsToList)[0], Input::get('belongsToId'), 'App\Models\Classe'); //TODO je devrai pas avoir d'input ici, ca devrait venir en parametre
	}
	$filtre1 = createFiltreParSessionPourClasses($lesClasses, true);
	$tp = $item;	
	return compact('tp', 'belongsToList', 'belongsToSelectedIds','filtre1');
}

/**
 * retourne les tps associés aux filtres. Groupe les TPs par classes
 *
 * @param[in] filteringItem une classe pour laquelle on veut avoir tous les TPs.
 * @return une collection de TP triée par le nom du TP
 */
protected function filter1( $filteringItem) {
	//$filteringItems doit être une Classe
	$lignes = [];
	$idEtudiant = Auth::user()->id;
	$validTps = Note::where('etudiant_id', '=',$idEtudiant)->where('classe_id','=',$filteringItem->id)->select('tp_id')->distinct()->lists('tp_id');
	$lignes[$filteringItem->nom] = $filteringItem->tps()->whereIn('tp_id',$validTps)->get()->sortBy('nom');
	return $lignes;
}
protected function filter2($filterValue) {
	//Pour appeler cette function, filter1 doit être sur TOUS
	//Pour les TPs, le filter 2 est la sessionScholair
	$etudiant = Auth::user();
	$idEtudiant = $etudiant->id;
	
	try {
		if($filterValue == 0) {// 0 indique 'Tous' sur filter2
			$classes = $etudiant->classes->sortBy("sessionscholaire_id"); //TODO si un prof test et que les filtres sont tous/tous, ca n'affiche rien car le prof n'est pas un étudiant de la classe
		} else {
			$classes = $this->filteringClass->where('sessionscholaire_id', '=' , $filterValue)->get(); //va chercher les classes pour cette session
		}
		$lignes = [];
		foreach($classes as $classe) {
			$validTps = Note::where('etudiant_id', '=',$idEtudiant)->where('classe_id','=',$classe->id)->select('tp_id')->distinct()->lists('tp_id');
			$lignes[$classe->nom] = $classe->tps()->whereIn('tp_id',$validTps)->get()->sortBy('nom');
		}
	} catch (Exception $e) {
		$lignes = [];
	}

	return $lignes;
}


/**
 * Call back
 *
 */
static function createOptionsValue($item) {
	return $item->sessionscholaire->nom." ". $item->code." ".$item->nom;
}

}