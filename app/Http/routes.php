<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
*/

Route::post('tpsPourClasse', ['as' => 'tpsPourClasse', 'uses' => 'TPsController@itemsFor2Filters']);     //pour l'appel AJAX

Route::group(['middleware'=>'auth'], function() {
	/* 
	 * Routes pouvant être utilisées seulement si l'usager est connecté
	 */
	 	
		Route::group(['middleware'=>['role:admin']], function() {
			Route::resource('professeurs', 'ProfesseursController');
			Route::post('professeursPourClasse', ['as' => 'professeursPourClasse', 'uses' => 'ProfesseursController@itemsFor2Filters']);     //pour l'appel AJAX

		});
	
		Route::group(['middleware'=>['role:admin|professeur']], function() { 
			//Seul l'admin et les professeurs peuvent prendre ces routes. 
			
			/* Classes */
			Route::resource('classes', 'ClassesController');
			
			/* Travaux pratiques (TP) */
			Route::get('tpsDistribuer/{id}', ['as' => 'tps.distribuer', 'uses' => 'TPsController@distribuer']);
			Route::post('tpsDistribuer/{id}',  ['as' => 'tps.doDistribuer', 'uses' => 'TPsController@doDistribuer']);
			Route::get('tpsCorriger/{tpid}/{classeid}',  ['as' => 'tps.corriger', 'uses' => 'TPsController@corriger']);		
			Route::get('tpsAfficherResultats/{tpid}/{classeid}',  ['as' => 'tps.afficherResultats', 'uses' => 'TPsController@afficherResultats']);
			Route::put('tpsDoCorriger',  ['as' => 'tps.doCorriger', 'uses' => 'TPsController@doCorriger']);
			Route::any('afficheReponseAutreEtudiant', ['as' => 'tps.afficheReponseAutreEtudiant', 'uses' => 'TPsController@afficheReponseAutreEtudiant']); //call Ajax
			Route::get('tpsTransmettreCorrection/{tpid}/{classeid}', ['as' => 'tps.transmettreCorrection', 'uses' => 'TPsController@transmettreCorrection']);
			Route::get('tpsRetirerCorrection/{tpid}/{classeid}', ['as' => 'tps.retirerCorrection', 'uses' => 'TPsController@retirerCorrection']);
			Route::get('tpsFormat/{id}', ['as' => 'tps.format', 'uses' => 'TPsController@format']);
			Route::put('tpsDoFormat/{id}', ['as' => 'tps.doFormat', 'uses' => 'TPsController@doFormat']);
			Route::get('tpsChangerPivotClasse/{tpid}/{classeid}', ['as' => 'tps.changerPivotClasse', 'uses' => 'TPsController@changerPivotClasse']);
			Route::put('tpsDoChangerPivotClasse/{tpid}/{classeid}', ['as' => 'tps.doChangerPivotClasse', 'uses' => 'TPsController@doChangerPivotClasse']);
			
			Route::get('tps', 'TPsController1@distribuer');
			Route::resource('tps', 'TPsController');
			
			
			/* Questions */
			Route::get('createAndBackToTP/{tpId}', ['as' => 'questions.createAndBackToTP', 'uses' => 'QuestionsController@createAndBackToTP']);
			Route::post('storeAndBackToTP/{tpId}', ['as' => 'questions.storeAndBackToTP', 'uses' => 'QuestionsController@storeAndBackToTP']);
			Route::post('questionsPourTp', ['as' => 'questionsPourTp', 'uses' => 'QuestionsController@itemsFor2Filters' ]);     //pour l'appel AJAX
			Route::resource('questions', 'QuestionsController');
			
			/* Etudiants */
			Route::post('etudiantsPourClasse', ['as' => 'etudiantsPourClasse', 'uses' => 'EtudiantsController@itemsFor2Filters']);     //pour l'appel AJAX
			
			Route::resource('etudiants', 'EtudiantsController');
				
		});
		
		
		//Routes disponibles à tout le monde (prof ou étudiants)
		
		Route::get('/','HomeController@index');
		Route::get('/home','HomeController@index');
			
		
		
		Route::group(['middleware'=>['permission:passer-test']], function() {
		
			/* Passation des TPs */
			Route::post('tpsPassationPourClasse', ['as' => 'tpsPassationPourClasse', 'uses' => 'TPsPassationController@itemsFor2Filters']);     //pour l'appel AJAX
			Route::get('tpsPassationIndex', ['as' => 'tpsPassation.index', 'uses' => 'TPsPassationController@index']);
			Route::get('tpsPassationRepondre/{classeId}/{tpId}', ['as' => 'tpsPassation.repondre', 'uses' => 'TPsPassationController@repondre']);
			Route::put('tpsPassationDoRepondre', ['as' => 'tpsPassation.doRepondre', 'uses' => 'TPsPassationController@doRepondre']);
			Route::get('tpsPassationVoirCorrection/{classeId}/{tpId}', ['as' => 'tpsPassation.voirCorrection', 'uses' => 'TPsPassationController@voirCorrection']);
			Route::put('tpsPassationVoirSuiteCorrection',  ['as' => 'tpsPassation.voirSuiteCorrection', 'uses' => 'TPsPassationController@voirSuiteCorrection']);
		});
				
			
		
		// la création d'un usager ne peu se faire que par un usager déjà connecté. 
		// TODO: ajouter que seul les gestionnaires peuvent le faire. Mais j'ai besoin de Entrust pour ca
		// Route::get( 'users/create',                 'UsersController@create');
		
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');




// OBSOLETE
// Dashboard route
//obsolete Route::get('userpanel/dashboard', function(){ return View::make('userpanel.dashboard'); });

// Applies auth filter to the routes within admin/
// Route::when('userpanel/*', 'auth');  enlever pour réintroduire auth, mais pas certain 
//

// Confide routes
/*Route::post('users',                        'UsersController@store');
Route::get( 'users/login',                  'UsersController@login');
Route::post('users/login',                  'UsersController@do_login');
Route::get( 'users/confirm/{code}',         'UsersController@confirm');
Route::get( 'users/forgot_password',        'UsersController@forgot_password');
Route::post('users/forgot_password',        'UsersController@do_forgot_password');
Route::get( 'users/reset_password/{token}', 'UsersController@reset_password');
Route::post('users/reset_password',         'UsersController@do_reset_password');
Route::get( 'users/logout',                 'UsersController@logout');

Route::get('users/create', 'UsersController@create');
Route::post('users', 'UsersController@store');
Route::get('users/login', 'UsersController@login');
Route::post('users/login', 'UsersController@doLogin');
Route::get('users/confirm/{code}', 'UsersController@confirm');
Route::get('users/forgot_password', 'UsersController@forgotPassword');
Route::post('users/forgot_password', 'UsersController@doForgotPassword');
Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
Route::post('users/reset_password', 'UsersController@doResetPassword');
Route::get('users/logout', 'UsersController@logout');
*/