<?php
/**
* définition de la classe categorie
*/
class Categorie {
	private int $_idcate;   
	private string $_nomcate;
	
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idcate']))       { $this->_idcate =       $donnees['idcate']; }
		if (isset($donnees['nomcate']))  { $this->_nomcate =  $donnees['nomcate']; }
		
	}           
	// GETTERS //
	public function idCate()       { return $this->_idcate;}
	public function nomCate()  { return $this->_nomcate;}

	
	// SETTERS //
	public function setIdCate(int $idcate)             { $this->_idcate = $idcate; }
	public function setNomCate(string $nomcate)   { $this->_nomcate= $nomcate; }
	


}