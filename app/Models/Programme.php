<?php

namespace App\Models;

use App\Models\EloquentValidating;

class Programme extends EloquentValidating
{
	/*
	 * Mass assignment protection
	 */
	protected $guarded = array('id');
	
    
	
	
	
/*
 * database relationships
 */
	
	// Un programme est associé à plusieurs usagers (prof->programme d'enseignement, etudiant->programme d'étude)
	public function users() {
		return $this->hasMany('App\Models\User');
	}
	
	public function etudiants() {
		return $this->users()->where('type','e');
	}
	public function professeurs() {
		return $this->users()->where('type','p');
	}
	
	/*
	 * Validation
	 *
	 * un programme doit avoir:
	 *  nom : obligatoire
	 *
	 */
	
	
	public function validationRules() {
		return [
				'id'=>'required',
				'nom'=>'required',
		];
	}
}
