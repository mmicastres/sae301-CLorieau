<?php
/**
* définition de la classe Source
*/
class Source {
	private int $_idprojet;   
	private int $_idsource;
	private string $_liensource;
	
	
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'une source à partir d'un tableau de données
		if (isset($donnees['idprojet']))       { $this->_idprojet =       $donnees['idprojet']; }
		if (isset($donnees['idsource']))  { $this->_idsource =  $donnees['idsource']; }
		if (isset($donnees['liensource'])) { $this->_liensource = $donnees['liensource']; }	
		
	}           
	// GETTERS //
	public function idProjet()       { return $this->_idprojet;}
	public function idSource()  { return $this->_idsource;}
	public function lienSource() { return $this->_liensource;}
	
	
	
	// SETTERS //
	public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
	public function setIdSource(int $idsource)   { $this->_idsource= $idsource; }
	public function setLienSource(string $liensource) { $this->_liensource = $liensource; }
	


}