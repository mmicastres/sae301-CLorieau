<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class CategorieManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db = $db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Categorie à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function choixCategorie() {
		$cates = array();
		// requete d'ajout dans la BD
		$req = "SELECT idcate, nomcate FROM categorie";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}

	public function getCategorie($idprojet) {

		$cates = array();
		$req = "SELECT nomcate FROM categorie NATURAL JOIN appartient WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}

}