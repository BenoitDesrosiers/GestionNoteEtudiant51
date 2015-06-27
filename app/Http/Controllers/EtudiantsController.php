<?php
/**
 * Le controller pour les étudiants
 * 
 * 
 * @version 0.1
 * @author benou
 *
 */

namespace App\Http\Controllers;

use App\Http\ControllersGestion\EtudiantsGestion;
use App\Http\Controllers\BaseFilteredResourcesController;

class EtudiantsController extends BaseFilteredResourcesController {

	public function __construct(EtudiantsGestion $gestion) {
		//parent::__construct();
		$this->gestion = $gestion;
		$this->base = "etudiants";
		$this->message_store = "L'étudiant a été ajouté";
		$this->message_update = "L'étudiant a été modifié";
		$this->message_delete = "L'étudiant a été effacé";
	}
	


}
