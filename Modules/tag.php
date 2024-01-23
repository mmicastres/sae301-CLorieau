<?php
/**
* définition de la classe tag
*/
class Tag {
	private int $_idtag;   
	private string $_nomtag;
	
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idtag']))       { $this->_idtag =       $donnees['idtag']; }
		if (isset($donnees['nomtag']))  { $this->_nomtag =  $donnees['nomtag']; }
		
	}           
	// GETTERS //
	public function idTag()       { return $this->_idtag;}
	public function nomTag()  { return $this->_nomtag;}

	
	// SETTERS //
	public function setIdTag(int $idtag)             { $this->_idtag = $idtag; }
	public function setNomTag(string $nomtag)   { $this->_nomtag= $nomtag; }
	


}