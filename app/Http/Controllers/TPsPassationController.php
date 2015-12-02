<?php


/**
 * Le controller pour la passsation des travaux pratiques
 *
 *
 * @version 0.1
 * @author benou
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades;
use App\Http\Controllers\BaseFilteredResourcesController;
use App\Http\ControllersGestion\TPsPassationGestion;
use View;
use Session;
use Auth;
use Input;
use Redirect;

class TPsPassationController extends BaseFilteredResourcesController
{
	
	public function __construct(TPsPassationGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->baseView= "tpsPassation";
		
	}

	/**
	 * affiche la view pour répondre à un questionnaire
	 * @param integer $etudiant_id
	 * @param integer $classe_id
	 * @param integer $tp_id
	 * @return la view pour répondre     
	 */
	
	public function repondre( $classe_id, $tp_id) {
		//TODO: vérifie que les id sont bons. 
		$return =  $this->gestion->repondre( $classe_id, $tp_id, 1, true);
		if ($return) {
			return View::make($this->baseView.'.repondre',$return );
		} else {
			$classe = Classe::find($classe_id);
			$tp = $classe->tps()->where('tp_id',"=", $tp_id)->first();
			$etudiant = Auth::user();
			return View::make($this->baseView.'.examenDejaPasse', compact('classe', 'tp', 'etudiant'));
		}
	}
 	
	public function doRepondre() {
		//les ids ont été sauvé dans la session afin de s'assurer que l'étudiant ne triche pas. 
		$pageCourante = Session::pull('pageCourante');
		$etudiant_id = Auth::user()->id;
		$classe_id = Session::pull('classeId');
		$tp_id = Session::pull('tpId');
		$input = Input::all();
		$return = $this->gestion->doRepondre(Input::get('reponse'), $etudiant_id, $classe_id, $tp_id, $pageCourante,true);
		if($return) {
			if(isset($input['sauvegarde'])) {
				return View::make($this->baseView.'.repondre', $this->gestion->repondre($classe_id, $tp_id, $pageCourante,true) ); 				
			} elseif(isset($input['suivant'])){
				return View::make($this->baseView.'.repondre', $this->gestion->repondre($classe_id, $tp_id, $pageCourante+1, true) ); 
			} elseif(isset($input['precedent'])) {
				return View::make($this->baseView.'.repondre', $this->gestion->repondre($classe_id, $tp_id, $pageCourante-1, true) );
			} else {
				return Redirect::route($this->baseView.'.index')->with('message_success', 'Vos réponses sont enregistrées');
			}
		} else {
			return Redirect::route($this->baseView.'.index')->with('message_danger', "Une erreur grave c'est produite, veuillez avertir le professeur");
		}
	}
 
	
	public function voirCorrection( $classe_id, $tp_id) {
		//TODO: vérifie que les id sont bons.
		//voir les réponses utilise "repondre" car j'ai juste besoin d'aller chercher la bonne réponse, la note, et le commentaire
		return View::make($this->baseView.'.voirCorrection', $this->gestion->repondre( $classe_id, $tp_id, 1, false) );
	}
	
	
	
	public function voirSuiteCorrection() {
		$pageCourante = Session::pull('pageCourante');
		$etudiant_id = Auth::user()->id;
		$classe_id = Session::pull('classeId');
		$tp_id = Session::pull('tpId');
		$input = Input::all();

		if(isset($input['suivant'])){
			return View::make($this->baseView.'.voirCorrection', $this->gestion->repondre($classe_id, $tp_id, $pageCourante+1, false) );
		} elseif(isset($input['precedent'])) {
			return View::make($this->baseView.'.voirCorrection', $this->gestion->repondre($classe_id, $tp_id, $pageCourante-1, false) );
		} else {
			return Redirect::route($this->baseView.'.index');
		}
		
	}
}
