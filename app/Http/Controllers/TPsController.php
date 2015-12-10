<?php

/**
 * Le controller pour les travaux pratiques
 *
 *
 * @version 0.2
 * @author benou
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades;
use App\Http\Controllers\BaseFilteredResourcesController;
use App\Http\ControllersGestion\TPsGestion;
use View;
use Redirect;
use Session;
use Input;


class TPsController extends BaseFilteredResourcesController
{
	
	public function __construct(TPsGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->baseView= "tps";
		$this->message_store = 'Le travail pratique a été ajouté';
		$this->message_update = 'Le travail pratique a été modifié';
		$this->message_delete = 'Le travail pratique a été effacé';
		
	}

	
	public function changerPivotClasse($tp_id, $classe_id) {
		return View::make($this->baseView.'.changerPivotClasse', $this->gestion->changerPivotClasse($tp_id, $classe_id));
	}
	
	public function doChangerPivotClasse($tp_id, $classe_id) {
		$return = $this->gestion->doChangerPivotClasse($tp_id, $classe_id, Input::get('poids_local'), Input::get('commentaire_visible'));
		if($return=== true) {
			return Redirect::route($this->baseView.'.index')->with('message_success', 'Le poids local a été changé');
		} else {
			return Redirect::route($this->baseView.'.changerPivotClasse',$tp_id, $classe_id)->withInput()->withErrors($return);
		}
		
	}
	
	/**
	 * affiche les classes associées à ce TP et permet de distribuer ce TP pour les classes sélectionnées
	 */
	public function distribuer($id) {
		return View::make($this->baseView.'.distribuer', $this->gestion->distribuer($id));
	}
	
	public function doDistribuer($id) {
		$return = $this->gestion->doDistribuer($id, Input::all());
		if($return === true ) {
			return Redirect::route($this->baseView.'.index')->with('message_success', 'Les TPs ont été distribués/retirés correctement');
		} else {
			return Redirect::route($this->baseView.'.distribuer',$id)->withInput()->with('message_danger', "Une erreur c'est produite");
		}
 	}

 	/**
 	 * affiche la liste de questions et permet de les reordonner et de mettre des pages break
 	 */
 	
 	public function format($id) {
 		return View::make($this->baseView.'.format', $this->gestion->format($id));
 	}
 	
 	public function doFormat($id) {
 		$input = Input::all();
 		if(isset($input['ajoutQuestion'])) {
 			return Redirect::route('questions.createAndBackToTP', $id);
 		} else  {
 			$return = $this->gestion->doFormat($id, $input);
 		}
 		if($return === true ) {
 			return Redirect::route($this->baseView.'.index')->with('message_success', "Le format du TP a été mis à jour");
 		} else {
 			return Redirect::route($this->baseView.'.index')->with('message_danger', "Une erreur c'est produite");
  		}
 	}
 	
 	
 	/**
 	 * Correction d'un tp
 	 * 
 	 */
 	
 	public function corriger($tp_id, $classe_id) {
 		Session::put('autreEtudiantOffset', 0); //init de la variable de session
 		return View::make($this->baseView.'.corriger', $this->gestion->corriger($tp_id, $classe_id, 0, 0));
 	}
 	
 	public function doCorriger() {
 		$etudiant_id = Session::pull('etudiantId');
 		$classe_id = Session::pull('classeId');
 		$tp_id = Session::pull('tpId');
 		$question_id = Session::pull('questionId');
 		$offset_etudiant = Session::pull('offsetEtudiant');
 		$offset_question = Session::pull('offsetQuestion');
 		$commentaire= Input::get('commentaire');
 		$commentaire_visible = (Input::get('commentaire_visible')) != null; 	
 		$pointage = Input::get('pointage');	
 		$return = $this->gestion->doCorriger($etudiant_id, $classe_id, $tp_id, $question_id, $commentaire, $commentaire_visible, $pointage);
 		$input = Input::all();
 		if($return){
	 		if(isset($input['terminer'])) {
	 			return Redirect::route($this->baseView.'.index')->with('message_success', 'Vos corrections sont enregistrées');
	 		} else {
	 			if(isset($input['sauvegarde']))	 {					
	 				// les offsets restent les mêmes
	 			} elseif(isset($input['etudiantSuivant']))	 {
	 				$offset_etudiant++;
	 			} elseif(isset($input['etudiantPrecedent']))	 {
	 				$offset_etudiant--;
	 			} elseif(isset($input['questionSuivante']))	 {
	 				$offset_question++;
	 			} elseif(isset($input['questionPrecedente']))	 {
	 				$offset_question--;
	 			}
	 			
	 			return View::make($this->baseView.'.corriger', $this->gestion->corriger($tp_id, $classe_id,  $offset_etudiant, $offset_question) );
	 		}
 		} else {
			return Redirect::route($this->baseView.'.corriger')->withInput()->withErrors($return);
 		}
 	}
 	
 	
 	public function afficherResultats($tp_id, $classe_id) {
 		return View::make($this->baseView.'.afficherResultats', $this->gestion->resultats($tp_id, $classe_id));
 	}
 	
 	
 	public function afficheReponseAutreEtudiant(){
 		$etudiantCourant_id = Session::get('etudiantId');
 		$classe_id = Session::get('classeId');
 		$tp_id = Session::get('tpId');
 		$question_id = Session::get('questionId');
 		return View::make($this->baseView.'.reponseAutreEtudiant_subview', 
 				$this->gestion->afficheReponseAutreEtudiant(Input::get('direction'), $etudiantCourant_id, $classe_id, $tp_id, $question_id));
 	}
 	
 	
 	public function transmettreCorrection($tp_id, $classe_id) {
 		$return = $this->gestion->transmettreCorrection($tp_id, $classe_id);
 		if($return) {
 			return Redirect::route($this->baseView.'.index')->with('message_success', 'Les corrections sont transmises.');
 		} else {
 			return Redirect::route($this->baseView.'.index')->with('message_erreur', "Une erreur s'est produite. Les corrections ne sont pas transmises. Contacter le support");
 		}
 		
 	}
 	public function retirerCorrection($tp_id, $classe_id) {
 		$return = $this->gestion->retirerCorrection($tp_id, $classe_id);
 		if($return) {
 			return Redirect::route($this->baseView.'.index')->with('message_success', 'Les corrections ont été retirées.');
 		} else {
 			return Redirect::route($this->baseView.'.index')->with('message_erreur', "Une erreur s'est produite. Les corrections ne sont pas été retirées. Contacter le support");
 		}
 			
 	}
}
