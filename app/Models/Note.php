<?php
namespace App\Models;

use App\Models\EloquentValidating;

class Note extends EloquentValidating
{
	
 /*
  * Mass assignment protection
  */
	protected $guarded = array('id');
	
/*
 * database relationships
 */
	
	// Une note est associée à un Classe/TP/Etudiant/Question
	public function classe() {
		return $this->belongsTo('App\Models\Classe');
	}
	public function tp() {
		return $this->belongsTo('App\Models\TP');
	}
	public function etudiant() {
		return $this->belongsTo('App\Models\Etudiant');
	}
	public function question() {
		return $this->belongsTo('App\Models\Question');
	}
	
	/**
	 * scope 
	 * 
	 * functions permettant de chainer les requetes afin de trouver une note plus simplement
	 */
	
	public function scopeForClasse($query, $classeId) {
		return $query->where('classe_id', '=', $classeId);
	}
	public function scopeForTP($query,$tpId){
		return $query->where('tp_id', '=', $tpId);
	}	
	public function scopeForEtudiant($query, $etudiantId) {
		return $query->where('etudiant_id', '=', $etudiantId);
	}
	public function scopeForQuestion($query, $questionId) {
		return $query->where('question_id', '=', $questionId);
	}
	
	
/*
 * Validation
 * 
 * un note doit avoir: 
 *  classe_id : obligatoire
 *  tp_id : obligatoire
 *  etudiant_id : obligatoire
 *  question_id : obligatoire
 *  
 */	
	
	
	public function validationRules() {
		return [	 
			'classe_id'=>'required',
			'tp_id'=>'required',
			'etudiant_id'=>'required',
			'question_id'=>'required',
			'note'=> 'numeric',
	];	
	}

}