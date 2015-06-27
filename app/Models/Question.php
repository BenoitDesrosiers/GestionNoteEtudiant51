<?php

namespace App\Models;

use App\Models\EloquentValidating;

class Question extends EloquentValidating
{
	
 /*
  * Mass assignment protection
  */
	protected $guarded = array('id');
	
/*
 * database relationships
 */
	
	// Une question est associée à plusieurs TPs
	public function tps() {
		return $this->belongsToMany('App\Models\TP', 'tps_questions', 'question_id', 'tp_id')->withPivot('ordre', 'sur_local','breakafter');
	}
	
	
/**
 * Opérations de stockage
 * 
 */
	
	/**
	 * save la question dans la bd et ajoute cette question à tous les TPs ayant les id dans $tpIds
	 * 
	 * @param associative array $input [nom, enonce, baliseCorrection, reponse, sur] pour créer la question
	 * @param array $tpIds les ids des TPs qui doivent être associé à cette question
	 * @return boolean true si tout c'est bien passé, validationMessages pour cette question si il y'a un problème
	 */
	public function createWithTPs($input, $tpIds) {
		$this->nom = $input['nom'];
		$this->enonce = $input['enonce'];
		$this->baliseCorrection = $input['baliseCorrection'];
		$this->reponse = $input['reponse'];
		$this->sur = $input['sur'];
		if($this->save()) { //TODO mettre ca dans une transaction
			foreach($tpIds as $tpId) {
				if($tpId <> 0) {	
					TP::find($tpId)->addQuestion($this); 
				}
			}
			return true;
		} else {
			return $this->validationMessages;
		}
	}
	
	public function updateForTPs($input, $tpIds) {
		$this->nom = $input['nom'];
		$this->enonce = $input['enonce'];
		$this->baliseCorrection = $input['baliseCorrection'];
		$this->reponse = $input['reponse'];
		$this->sur = $input['sur'];
		if($this->save()) {//TODO: mettre ca dans une transaction
			$allOldTpIds = $this->tps->lists('id')->all();
			$deletedTpIds = array_diff($allOldTpIds,$tpIds);
			$newTpIds = array_diff($tpIds,$allOldTpIds);
			foreach($deletedTpIds as $tpId) {
				if($tpId<>0){TP::find($tpId)->questions()->detach($this->id);} //TODO resynch l'ordre
			}
			foreach($newTpIds as $tpId) {
				if($tpId<>0){TP::find($tpId)->addQuestion($this);}
			}
			return true;
		} else {
			return $this->validationMessages;
		}
	}
	
	
/*
 * Validation
 * 
 * un question doit avoir: 
 *  nom : obligatoire
 *  enonce : obligatoire
 *  sur : obligatoire
 *  
 */	
	
	
	public function validationRules() {
		return [	 
			'nom'=>'required',
			'enonce'=>'required',
			'sur'=>'required'
	];	
	}
}