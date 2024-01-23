<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class ContexteManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db = $db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Contexte à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function choixContexte() {

		$contextes = array();
		// requete d'ajout dans la BD
		$req = "SELECT idcontexte, identifiant, semestre, intitule FROM contexte";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute();		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$contextes[] = new Contexte($donnees);
		}
		return $contextes;
	}

	public function getContexte($idprojet) {
		$contextes = array();
		$req = "SELECT identifiant, semestre  FROM sources WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$sources[] = new Source($donnees);
		}
		return $sources;
	}


}

