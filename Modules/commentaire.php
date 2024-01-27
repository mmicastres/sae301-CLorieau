<?php
/**
* définition de la classe itineraire
*/
class Commentaire {
	private int $_idcommentaire;   
	private string $_avis;
	private int $_idutilisateur;
	private int $_idprojet;
	private $_datepublication;
	private $_prenom;
    private $_nom;
		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'un produit à partir d'un tableau de données
		if (isset($donnees['idcommentaire']))       { $this->_idcommentaire =       $donnees['idcommentaire']; }
		if (isset($donnees['avis']))  { $this->_avis =  $donnees['avis']; }
		if (isset($donnees['idutilisateur'])) { $this->_idutilisateur = $donnees['idutilisateur']; }
		if (isset($donnees['idprojet']))  { $this->_idprojet =  $donnees['idprojet'];}		
		if (isset($donnees['datepublication'])) { $this->_datepublication = $donnees['datepublication']; }
		if (isset($donnees['prenom']))  { $this->_prenom =  $donnees['prenom'];}		
		if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }			
	}           
	// GETTERS //
	public function idCommentaire()       { return $this->_idcommentaire;}
	public function avis()  { return $this->_avis;}
	public function idUtilisateur() { return $this->_idutilisateur;}
	public function idProjet() { return $this->_idprojet;}
	public function datePublication()  { return $this->_datepublication;}
	public function prenom() { return $this->_prenom;}
	public function nom()  { return $this->_nom;}
	
	
	// SETTERS //
	public function setIdCommentaire(int $idcommentaire)             { $this->_idcommentaire = $idcommentaire; }
	public function setAvis(string $avis)   { $this->_avis= $avis; }
	public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
	public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
	public function setDatePublication($datepublication)   { $this->_datepublication = $datepublication; }
	public function setPrenom(string $prenom) { $this->_prenom = $prenom;}
	public function setNom(string $nom)  { $this->_nom = $nom;}


}