<?php
//TODO: ajouter une description au TP dans la BD. 
namespace App\Models;

use App\Models\EloquentValidating;

class TP extends EloquentValidating
{
	
	protected $table = 'tps';  // pour une raison x, le nom de la table par défaut était t_ps 
 /*
  * Mass assignment protection
  */
	protected $guarded = array('id');
	
/*
 * database relationships
 */
	
	// Un TP est associé à plusieurs classes
	public function classes() {
		return $this->belongsToMany('App\Models\Classe', 'classes_tps', 'tp_id', 'classe_id')->withPivot('poids_local', 'corrige', 'distribue','commentaire_visible'); //encore ici, je suis obligé de spécifier tp_id, sinon, la clé est t_p_id ????);
	}
	
	// Un TP est associé à plusieurs questions
	public function questions() {
		return $this->belongsToMany('App\Models\Question', 'tps_questions', 'tp_id', 'question_id')->withPivot('ordre','sur_local','breakafter');
	}
	
	public function notes() {
		return $this->hasMany('App\Models\Note','tp_id');
	}
	
	
	/**
	 * Opérations de stockage
	 * 
	 */
	
	public function addQuestion($question) { //TODO si le TP est distribué, il ne faut pas permettre l'édition des questions. 
		$this->questions()->attach($question->id,['sur_local'=>$question->sur]);// pour la création, je transfers les points associé à cette question directement
		$questionsPourTP=$this->questions;
		$maxordre=0;
		foreach($questionsPourTP as $questionPourTP) { //pas elegant, mais c'est la seule facon que j'ai trouvé
			if($questionPourTP->pivot->ordre > $maxordre) {
				$maxordre=$questionPourTP->pivot->ordre;
			}
		}
		$ordre = $maxordre+1;
		$this->questions()->updateExistingPivot($question->id,['ordre' => $ordre], false);
	}	
		
		
	
/*
 * Validation
 * 
 * un TP doit avoir: 
 *  nom : obligatoire
 *  poids : obligatoire
 *  
 */	
		
	public function validationRules() {
		return [	 
			'nom'=>'required',
			'poids'=>'required|integer|min:0|max:100'
	];	
	}

}