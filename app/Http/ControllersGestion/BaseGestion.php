<?php

namespace App\Http\ControllersGestion; 

abstract class BaseGestion
{
 
	/**
	 * 
	 * @var unknown $model le modèle associé à ce gestionnaire. 
	 * Ce modele sera injecté dans le constructeur des sous-classes. 
	 * L'avantage de l'injecté est qu'il est facile de changer la classe du modèle
	 * Ce qui permet de faire des mock-up plus facilement. 
	 */
protected $model;
  
protected $validation;
 
public function listePages($pages)
{
	return $this->model->paginate($pages);
}

/**
 * retourne toutes les instances de la classe gérée
 * 
 * @return collection représentant ::ALL()
 */
public function listeAllLignes(){
	return $this->model->all();
}


}