<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use View;

class HomeController extends Controller {

	/*
	 * Le controller principale
	*/

	public function index()
	{
		if(Auth::user()->ability('','enseigner')) {
			return View::make('homePageProf');
		} else {
			return View::make('homePageEtudiant');
		}
	}

}
