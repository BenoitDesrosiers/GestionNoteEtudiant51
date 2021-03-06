<?php 

namespace App\Http\ControllersGestion;

use App\Http\ControllersGestion\UsersGestion;
use App\Models\Classe;
use App\Models\User;
use App\Models\Role;

use Input;
use Hash;

class EtudiantsGestion extends UsersGestion{

protected function filter1($filteringItem) {
	return $this->filter1Type($filteringItem, 'e');
}
protected function filter2($filterValue) {	
	//Pour les Etudiants, le filter 2 est la sessionScholaire
	return $this->filter2Type($filterValue, 'e');
}


/**
 * Enregistrement initial dans la BD
 *
 */
public function store($input) {
	return $this->storeTypeRole($input, 'e','etudiant');
}



/* ne permet pas d'effacer un étudiant. Ca doit être fait via les Users 
public function destroy($id){
	$etudiant = $this->model->findOrFail($id);
	$etudiant->classes()->detach();
	$etudiant->delete();
	// Détruit les notes associées à cet etudiant
	$notes = Note::where('etudiant_id', '=', $id)->get();
	foreach($notes as $note) {
		$note->delete();
	}
	
	return true;
}
*/

}