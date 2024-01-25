<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class TagManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db = $db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Tag à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Tag $tag) {
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idtag) AS maximum FROM tags ");
		$stmt->execute();
		$tag->setIdTag($stmt->fetchColumn()+1);
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO tags (idtag, nomtag) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($tag->idTag(), $tag->nomTag()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	public function chercher($tag) {
		print_r($tag);
		
		// requete d'ajout dans la BD
		$req = "SELECT idtag FROM tags WHERE nomtag LIKE ?";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($tag));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		if ($data =  ($stmt->fetch())){
		$res = new Tag ($data);
		return $res;}
		else {

		return null;
		}
	}

	public function getTag($idprojet) {

		$tags = array();
		$req = "SELECT nomtag FROM tags NATURAL JOIN associer WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$tags[] = new Tag($donnees);
		}
		return $tags;
	}

	public function deleteTags(Tag $supptag) : bool {
		$req = "DELETE FROM tags WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($supptag->idProjet()));
	}





}