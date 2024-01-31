<?php
/**
* définition de la classe Projet
*/
class Projet {
	private int $_idprojet;   
	private string $_titre;
	private string $_description;
	private ?string $_image = null;
	private string $_liendemo;
	private int $_idcontexte;
	private int $_annee;
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un projet à partir d'un tableau de données
		if (isset($donnees['idprojet']))       { $this->_idprojet =       $donnees['idprojet']; }
		if (isset($donnees['titre']))  { $this->_titre =  $donnees['titre']; }
		if (isset($donnees['description'])) { $this->_description = $donnees['description']; }
		if (isset($donnees['image'])) { $this->_image = $donnees['image']; }
		if (isset($donnees['liendemo']))  { $this->_liendemo =  $donnees['liendemo'];}		
		if (isset($donnees['idcontexte'])) { $this->_idcontexte = $donnees['idcontexte']; }
		if (isset($donnees['annee']))  { $this->_annee =  $donnees['annee'];}	
		
	}           
	// GETTERS //
	public function idProjet()       { return $this->_idprojet;}
	public function titre()  { return $this->_titre;}
	public function description() { return $this->_description;}
	public function image() { return $this->_image;}
	public function lienDemo()  { return $this->_liendemo;}
	public function idContexte()      { return $this->_idcontexte;}
	public function annee()  { return $this->_annee;}
	
	// SETTERS //
	public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
	public function setTitre(string $titre)   { $this->_titre= $titre; }
	public function setDescription(string $description) { $this->_description = $description; }
	public function setImage(string $image) { $this->_image = $image; }
	public function setLienDemo(string $liendemo)   { $this->_liendemo = $liendemo; }
	public function setIdContexte(int $idcontexte)             { $this->_idcontexte = $idcontexte; }
	public function setAnnee(int $annee)   { $this->_annee = $annee; }


}

