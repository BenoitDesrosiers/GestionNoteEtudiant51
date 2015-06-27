<?php
/**
 * ensemble de fonctions utilitaires
 * 
 * Ce fichier est chargé automatiquement dans la section autoload de composer.json
 */


/**
 * vérifie si un objet de la classe $classe ayant l'id $itemId existe. 
 * Si oui, retourne $itemdId, si non, retourne $defaut
 *  
 * @param int $defaut la valeur a retourner si $itemId n'est pas bon.
 * @param int $itemId l'id de l'objet à vérifier
 * @param class $OOclass le nom de la classe à laquelle l'objet devrait appartenir
 * @return int $itemId ou $defaut
 */



function checkLinkedId($defaut, $itemId, $OOclass) {
	if($itemId <> 0) {
		//verifie que l'id passé en paramêtre existe.
		try {
			$item = $OOclass::findOrFail($itemId);
		} catch (Exception $e) {
			//si il n'existe pas, on prend celui du premier item dans la liste
			$itemId = $defaut;
		}

	} else {
		//par default on prend la valeur par défaut
		$itemId= $defaut;
	}
	return $itemId;
}

/*function ObsoletecheckLinkedId2($defaut, $itemId, $filteringClass) {   obsolete 24 juin 2015
	if($itemId <> 0) {
		//verifie que l'id passé en paramêtre existe.
		try {
			$item = $filteringClass->findOrFail($itemId);
		} catch (Exception $e) {
			//si il n'existe pas, on prend celui du premier item dans la liste
			$itemId = $defaut;
		}

	} else {
		//par default on prend la valeur par défaut
		$itemId= $defaut;
	}
	return $itemId;
}
*/



/**
 * Vérifie que tous les $ids sont valide pour des objets de la class $OOclass
 * @param array $ids une liste d'ids à vérifier
 * @param string $OOclass le nom de la classe. 
 * @return boolean Un objet doit exister avec chacun des ids pour que cette function retourne vrai
 */
function allIdsExist($ids, $OOclass ) {
		$retour = true;
		foreach($ids as $id) {
			if($id <> 0) {
				try { //verifie que la classe existe
					$dummy = $OOclass::findOrFail($id); //TODO optimiser pour ne pas créer les objets .. un simple select ferait l'affaire
				} catch (Exception $e) {
					$retour = false;
				}
			}
		}
	return $retour;
}

/**
 * helper pour créer les select dans les views
 */

/**
 * Création de la liste utilisée dans le select
 *
 *  
 * @param collection $items la collection des objets à afficher
 * @param string $valeur0 optionnelle, si utilisée, ca sera la valeur à l'indice 0 dans le select
 * @param function $func une fonction à rappeller pour créer la string à afficher comme valeur des options du select.
 * @return array l'array à utiliser dans le select. Contient l'indice et le texte à afficher.
 */


function createSelectOptions($items, $func, $valeur0=null) {
	if(isset($valeur0)) {
		$optionsList[0]=$valeur0;
	}
	foreach($items as $item) {
		$optionsList[$item->id]=call_user_func($func,$item);
	}
	return $optionsList;
}
function createSelectOptions2($items, $func, $valeurSpeciales=null) {
	if(isset($valeurSpeciales)) {
		foreach($valeurSpeciales as $indice=>$valeur){
			$optionsList[$indice]=$valeur;
		}
	}
	foreach($items as $item) {
		$optionsList[$item->id]=call_user_func($func,$item);
	}
	return $optionsList;
}
/**
 * Regroupement des classes par sessioncholaire
 * @param boolean $tous Est-ce que la liste doit avoir un choix "Tous"
 * @return une array associative:
 * 			"groupes" => une array bidimensionnelle dont la clé de la première dimension est l'id de la session,
 * 						 et la deuxième dimension contient les ids des classes associées à cette session.
 * 			"selectList" =>  une array dont la clé est l'id de chaque sessions, et la valeur est le nom de cette session.
 * 							 cette array est parfaite pour être utilisée dans un select sur une view.
 *
 */
function createFiltreParSessionPourClasses($classes, $tous) {
	$groupes=[];
	$selectList=[];
	if($tous)
	{$selectList[0]="Tous";}
	foreach($classes as $classe) {
		if($tous) {
			$groupes["0"][]=$classe->id;
		}
		$groupes[$classe->sessionscholaire->id][]=$classe->id;
		$selectList[$classe->sessionscholaire->id] = $classe->sessionscholaire->nom;
	}
	if($tous) { //ajoute l'id 0 à tous les groupes afin que "Tous" soit aussi sélectionné
		foreach($groupes as $key => $value) {
			$groupes[$key][]=0;
		}
	}
	$retour= ["groupes"=>$groupes, "selectList" => $selectList];
	return $retour;
}

/**
 * Regroupement des TPs par Classe
 * @param boolean $tous Est-ce que la liste doit avoir un choix "Tous"
 * @return une array associative:
 * 			"groupes" => une array bidimensionnelle dont la clé de la première dimension est l'id de la classe,
 * 						 et la deuxième dimension contient les ids des TPs associées à cette classe.
 * 			"selectList" =>  une array dont la clé est l'id de chaque classe, et la valeur est le nom de cette classe.
 * 							 cette array est parfaite pour être utilisée dans un select sur une view.
 *
 */
function createFiltreParClassePourTP($tps, $tous) {
	$groupes=[];
	$selectList=[];
	if($tous)
		{$selectList[0]="Tous";}
	//un tp est associé à plusieurs classes (potentiellement les mêmes), et ces classes ont plusieurs TPs qui ne sont pas nécessairement
	//dans la liste de $tps. Je dois donc commencer par batir une liste des valeurs unique des classes, ensuite la liste des TPs qui sont dans $tps et qui
	//sont liés à chacune de ces classes. 
	foreach($tps as $tp) {
		if($tous) {
			$groupes[0][]=$tp->id;
		}
		foreach($tp->classes as $classe) {
			$groupes[$classe->id][]=$tp->id;
			$selectList[$classe->id] = $classe->nom;
		}
	}
	if($tous) { //ajoute l'id 0 à tous les groupes afin que "Tous" soit aussi sélectionné
		foreach($groupes as $key => $value) {
			$groupes[$key][]=0;
		}
	}
	$retour= ["groupes"=>$groupes, "selectList" => $selectList];
	return $retour;
}
