<?php
/**
* définition de la classe tag
*/
class Appartient {
	private int $_idprojet;   
	private int $_idcate;
	
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idprojet']))       { $this->_idprojet =       $donnees['idprojet']; }
		if (isset($donnees['idcate']))  { $this->_idcate =  $donnees['idcate']; }
		
	}           
	// GETTERS //
	public function idProjet()       { return $this->_idprojet;}
	public function idCate()  { return $this->_idcate;}

	
	// SETTERS //
	public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
	public function setIdCate(int $idcate)   { $this->_idcate= $idcate; }
	


}