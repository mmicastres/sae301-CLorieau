<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class AppartientManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db = $db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Appartient à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Appartient $linkcate, $proj) {
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO appartient (idprojet, idcate) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($proj->idProjet(), $linkcate->idCate()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	public function deleteAppartient(Appartient $suppappartient) : bool {
		$req = "DELETE FROM appartient WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppappartient->idProjet()));
	}

}