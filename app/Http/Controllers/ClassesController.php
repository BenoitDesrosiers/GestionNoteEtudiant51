<?php

namespace App\Http\Controllers; 

use App\Http\ControllersGestion\ClassesGestion;
use App\Http\Controllers\BaseResourcesController;

/**
 * Le controller pour les classes
 * 
 * @version 0.1
 * @author benou
 *
 */

class ClassesController extends BaseResourcesController
{
	public function __construct(ClassesGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->base = "classes"; 
		$this->message_store = 'La classe a été ajoutée';
		$this->message_update = 'La classe a été modifiée';
		$this->message_delete = 'La classe a été effacée';
	}
	
	
	
}