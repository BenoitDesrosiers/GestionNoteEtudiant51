<?php

/**
 * Le controller pour les questions
 * 
 * @version 0.1
 * @author benou
 *
 */


namespace App\Http\Controllers;

use App\Http\Controllers\BaseFilteredResourcesController;
use App\Http\ControllersGestion\QuestionsGestion;
use View;
use Input;
use Redirect;


class QuestionsController extends BaseFilteredResourcesController {

	public function __construct(QuestionsGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->base = "questions";
		$this->message_store = "La question a été ajoutée";
		$this->message_update = "La question a été modifiée";
		$this->message_delete = "La question a été effacée";
	}
	
	//overwrite la superclasse pour ajouter le paramêtre supplémentaire de create
	public function create()
	{
		return View::make($this->base.'.create', $this->gestion->create( $this->base.".store", Input::get('belongsToId')));
	}
	
	
	public function createAndBackToTP($tp_id)
	{
		
		return View::make($this->base.'.create', $this->gestion->create([$this->base.".storeAndBackToTP", $tp_id], $tp_id));
	}

	public function storeAndBackToTp($tp_id)
	{
		$return = $this->gestion->store(Input::all());
		if($return === true) {
			return Redirect::route('tps.format',$tp_id)->with('message_success', $this->message_store);
		} else {
			return Redirect::route($this->base.'.createAndBackToTP', $tp_id)->withInput()->withErrors($return);
		}
	}
}
