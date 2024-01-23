<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class SourceManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Source à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Source $source, $proj) {
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idsource) AS maximum FROM sources ");
		$stmt->execute();
		$source->setIdSource($stmt->fetchColumn()+1);
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO sources (idprojet, idsource, liensource) VALUES (?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($proj->idProjet(), $source->idSource(), $source->lienSource()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	public function getSource($idprojet) {
		$sources = array();
		$req = "SELECT liensource FROM sources WHERE idprojet=?";
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