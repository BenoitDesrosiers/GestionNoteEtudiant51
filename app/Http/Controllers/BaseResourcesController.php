<?php

namespace App\Http\Controllers; 

/**
 * Classe abstraite permettant de controller une ressource
 * Basée en partie sur l'exemple trouvé sur http://laravel.sl-creation.org/laravel-4-chapitre-34-les-relations-avec-eloquent-2-2/
 * 
 * @version 0.2 
 * @author benou
 *
 */


use App\Http\Controllers\Controller;
use View;
use Input;
use Redirect;

class BaseResourcesController extends Controller
{
	protected $gestion;
	protected $base;
	protected $message_store;
	protected $message_update;
	protected $message_delete;
	protected $message_critical;
	
	
	public function index()
	{	
		return View::make($this->base.'.index', $this->gestion->index());
	}
	
	public function create()
	{
		return View::make($this->base.'.create', $this->gestion->create());
	}
	
	public function store()
	{
		$return = $this->gestion->store(Input::all());
		if($return === true) {
			return Redirect::route($this->base.'.index')->with('message_success', $this->message_store);
		} else {
			return Redirect::route($this->base.'.create')->withInput()->withErrors($return);
		}
	}
	
	public function edit($id)
	{
		return View::make($this->base.'.edit', $this->gestion->edit($id));		
	}
	
	
	public function update($id)
	{
		$return = $this->gestion->update($id, Input::all());
		if($return === true) {
			return Redirect::route($this->base.'.index')->with('message_success', $this->message_update);
		} else {
			return Redirect::route($this->base.'.edit')->withInput()->withErrors($return);
		}	
	}
	
	
	public function destroy($id)
	{
		$this->gestion->destroy($id);
		return Redirect::back()->with('message_success', $this->message_delete);	
	}

}