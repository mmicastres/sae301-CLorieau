<?php

/**
 * Définition d'une classe permettant de gérer les membres 
 * en relation avec la base de données
 *
 */

class UtilisateurManager
{
	private $_db; // Instance de PDO - objet de connexion au SGBD

	/** 
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
	}

	/**
	 * verification de l'identité d'un membre (Login/password)
	 * @param string $login
	 * @param string $password
	 * @return utilisateur si authentification ok, false sinon
	 */
	public function verif_identification($login, $password)
	{
		//echo $login." : ".$password;
		$req = "SELECT idutilisateur, nom, prenom FROM utilisateur WHERE mail=:login and mdp=:password ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));
		if ($data = $stmt->fetch()) {
			$utilisateur = new Utilisateur($data);
			return $utilisateur;
		} else
			return false;
	}

	/**
	 * retourne l'ensemble des produits présents dans la BD 
	 * @return membre[]
	 */
	public function getUtilisateur($idutilisateur)
	{
		$utilisateurs = array();
		$req = "SELECT idutilisateur, nom, prenom, idiut, mail, mdp, statut, photoprofil FROM utilisateur WHERE idutilisateur = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		$utilisateurs = new Utilisateur($stmt->fetch());
		return $utilisateurs;
		

	}

	public function inscriUtilisateur(Utilisateur $utilisateur)

	{
		//incrementatation de l'id utilisateur
		$stmt = $this->_db->prepare("SELECT max(idutilisateur) AS maximum FROM utilisateur");
		$stmt->execute();
		$utilisateur->setIdUtilisateur($stmt->fetchColumn() + 1);

		$req = "INSERT INTO utilisateur (idutilisateur, nom, prenom, idiut, mail, mdp, statut) VALUES (?,?,?,?,?,?,1)";
		$stmt = $this->_db->prepare($req);
		$ok = $stmt->execute(array($utilisateur->idUtilisateur(), $utilisateur->nom(), $utilisateur->prenom(), $utilisateur->idIut(), $utilisateur->mail(), $utilisateur->mdp()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $ok;
		
	}

	public function getDetailUti($idprojet) {

		$utis = array();
		$req = "SELECT nom, prenom FROM utilisateur NATURAL JOIN participation WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$utis[] = new Utilisateur($donnees);
		}
		return $utis;
	}


	public function getProfilUti($idutilisateur) {

		$utis = array();
		$req = "SELECT idutilisateur, nom, prenom, idiut, mail, mdp, statut, photoprofil FROM utilisateur WHERE idutilisateur = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch())
		{
			$utis[] = new Utilisateur($donnees);
		}
		return $utis;
	}
	

}
?>