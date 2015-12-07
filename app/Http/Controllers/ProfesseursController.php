<?php
/**
 * Le controller pour les professeurs
 * 
 * 
 * @version 0.1
 * @author benou
 *
 */

namespace App\Http\Controllers;

use App\Http\ControllersGestion\ProfesseursGestion;
use App\Http\Controllers\BaseFilteredResourcesController;

class ProfesseursController extends BaseFilteredResourcesController {

	public function __construct(ProfesseursGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->baseView = "professeurs";
		$this->message_store = "Le professeur a été ajouté";
		$this->message_update = "Le professeur a été modifié";
		$this->message_delete = "Le professeur a été effacé";
	}
	


}
