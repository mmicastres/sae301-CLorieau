<?php
/**
* définition de la classe Evaluation
*/
class Evaluation {
	private int $_idevaluation;   
	private int $_note;
	private int $_idutilisateur;
	private int $_idprojet;

		
	// contructeur
	public function __construct(array $donnees) {
	// initialisation d'une evaluation à partir d'un tableau de données
		if (isset($donnees['idevaluation']))  { $this->_idevaluation = $donnees['idevaluation']; }
		if (isset($donnees['note']))  { $this->_note =  $donnees['note']; }
		if (isset($donnees['idutilisateur'])) { $this->_idutilisateur = $donnees['idutilisateur']; }
		if (isset($donnees['idprojet']))  { $this->_idprojet =  $donnees['idprojet'];}		
		
	}           
	// GETTERS //
	public function idEvaluation()       { return $this->_idevaluation;}
	public function note()  { return $this->_note;}
	public function idUtilisateur() { return $this->_idutilisateur;}
	public function idProjet() { return $this->_idprojet;}
	
	
	
	// SETTERS //
	public function setIdEvaluation(int $idevaluation)             { $this->_idevaluation = $idevaluation; }
	public function setNote(int $note)   { $this->_avis= $note; }
	public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
	public function setIdProjet(int $idprojet)             { $this->_idprojet = $idprojet; }
	

}