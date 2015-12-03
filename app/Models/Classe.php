<?php
/**
 * Représente un cours à une période donnée
 *
 *
 * @author benou
 *
 */

namespace App\Models;

use App\Models\EloquentValidating;


class Classe extends EloquentValidating   //TODO: mettre un namespace
{
 /*
  * Mass assignment protection
  */
	protected $guarded = array('id');
	
	
	/*public function __construct($input) {
		$this->code = $input['code'];
		$this->nom = $input['nom'];
		$this->groupe = $input['groupe'];
		$this->local = $input['local'];
	}
	*/
/*
 * database relationships
 */
	
	// Une Classe a plusieurs Travaux Pratiques (TP)
	public function tps() {
		return $this->belongsToMany('App\Models\TP', 'classes_tps', 'classe_id', 'tp_id')->withPivot('poids_local', 'corrige', 'distribue','commentaire_visible'); //encore ici, je suis obligé de spécifier tp_id, sinon, la clé est t_p_id ????
	}
	

	// Une classe a plusieurs étudiants (users) d'inscrit
	
	public function etudiants() {
		return $this->belongsToMany('App\Models\User', 'users_classes', 'classe_id', 'user_id');
	}
	
	// Une Classe appartient à une Session
	public function sessionscholaire() {
		return $this->belongsTo('App\Models\Sessionscholaire');
	}
	
	// Une Classe a plusieurs Notes
	public function notes() {
		return $this->hasMany('App\Models\Note');
	}
	
	
/*
 * Validation
 * 
 * une classe doit avoir: 
 * 	code: obligatoire, et unique dans toute la table
 *  nom : obligatoire
 *  sessionscholaire_id : obligatoire
 *  Les autres champs sont falcultatifs.  
 */	
	
	
	public function validationRules() {
		return [
			'code' => 'required|unique:classes,code'.($this->id ? ",$this->id" : ''),
			'nom'=>'required',
			'sessionscholaire_id'=>'required',
	];	
	}
	
}
