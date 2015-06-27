<?php

namespace App\Http\ControllersGestion; 


use App\Http\ControllersGestion\BaseGestion;


/**
 * Class abstraite permettant de gérer le fait d'avoir 2 filtres permettant de 
 * limiter les lignes d'items retourner pour l'affichage de l'index. 
 * 
 * @author benou
 *
 */
abstract class BaseFilteredGestion extends BaseGestion
{
	
public function __construct($model, $filteringClass) {
	$this->model = $model;
	
	/*
	 * pour le call ajax
	*/
	$this->filteringClass = $filteringClass;
}

protected $filteringClass;
 
abstract protected function filter1($filteringItem);
abstract protected function filter2($filterValue);


/**
 * Ajax
 */

/**
 * Filtre les items à afficher en se servant de 2 filtres.
 * Le filtre #2 réduit l'ensemble des valeurs du filtre #1. 
 * Le filtre #2 est un valeur d'une colonne de la class utilisée pour filtrer les items ($this->filterinClass). 
 * Ex: 
 * 		filtre #1 est l'id d'une Classe qui permet de choisir les TPs associés à celle-ci. 
 * 		filtre #2 est l'id d'une sessionscholaire qui permet de choisir les classes qui sont données durant cette session. 
 * 		le résultat sera soit les TPs qui sont pour une classe spécifique, soit les TPs qui sont pour les classes données durant une session spécifique. 
 * 
 * @param int $filter1 la valeur de l'id de la class utilisée pour filtrer les items 
 * @param int $filter2 la valeur de l'id de la clé étrangère utilisée pour sélectionner un subset de filtre #1
 * @return compact array les items à afficher ainsi que la valeur du filtre #1
 */
public function itemsFor2Filters($filter1, $filter2) {
	if($filter1 <> 0) { //Si un choix du filter1 est sélectionnée, retourne les items pour celui-ci
		try {
			$filterObject = $this->filteringClass->findOrFail($filter1);
			$lignes = $this->filter1($filterObject);				
		} catch (Exception $e) {
			//la valeur de $filter1 n'est pas bonne. On retourne une liste vide, et reset $filter1 à 0 
			$lignes= new Illuminate\Database\Eloquent\Collection;  //TODO: je crée une collection, mais dans TPsPassationGestion, $lignes est une array. Faudrait toujours retourner une array
			$filter1 = 0; 
		}
	} else { //filter1 est sur "Tous", mais il peut y avoir un choix sur filter2
		$lignes = $this->filter2($filter2);
	}
	$belongsToId = $filter1;
	return compact('lignes', 'belongsToId' );	
}

}