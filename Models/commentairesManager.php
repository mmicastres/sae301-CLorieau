<?php
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class CommentaireManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db=$db;
	}
        
	/**
	* ajout d'un Projet dans la BD
	* @param Commentaire à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add(Commentaire $comm) {
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idcommentaire) AS maximum FROM commentaire");
		$stmt->execute();
		$comm->setIdCommentaire($stmt->fetchColumn()+1);
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO commentaire (idcommentaire,avis,idutilisateur,idprojet,datepublication) VALUES (?,?,?,?,NOW())";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($comm->idCommentaire(), $comm->avis(), $comm->idUtilisateur(), $comm->idProjet()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	// Requête réalisée avec ChatGPT
    public function getListCommentaire($idprojet) {
		$comms = array();
		$req = "SELECT commentaire.idcommentaire, commentaire.avis, commentaire.datepublication, utilisateur.prenom, utilisateur.nom 
		FROM commentaire 
		JOIN utilisateur ON commentaire.idutilisateur = utilisateur.idutilisateur 
		WHERE idprojet= ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch())
		{
			$comm = new Commentaire($donnees);
			$comm->setIdCommentaire($donnees['idcommentaire']);
    		$comm->setAvis($donnees['avis']);
    		$comm->setDatePublication($donnees['datepublication']);
    		$comm->setPrenom($donnees['prenom']); // Définir le prénom
    		$comm->setNom($donnees['nom']);       // Définir le nom
    		$comms[] = $comm;
		}
	
		return $comms;
	}

}