<?php

/**
 * Définition d'une classe permettant de gérer les utilisateurs 
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
		$req = "SELECT idutilisateur, nom, prenom, statut FROM utilisateur WHERE mail=:login and mdp=:password ";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array(":login" => $login, ":password" => $password));
		if ($data = $stmt->fetch()) {
			$utilisateur = new Utilisateur($data);
			return $utilisateur;
		} else
			return false;
	}

	/**
	 * retourne l'ensemble des utilisateurs présents dans la BD 
	 * @param  $idutilisateur
	 * @return utilisateurs[]
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

	/**
	 * inscription d'un utilisateur dans la BD 
	 * @param  Utilisateur $utilisateur
	 * @return $ok
	 */
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


	/**
	 * retourne le nom et le prenom des utis qui ont participé à un projet 
	 * @param  $idprojet 
	 * @return utis[]
	 */
	public function getDetailUti($idprojet)
	{

		$utis = array();
		$req = "SELECT nom, prenom FROM utilisateur NATURAL JOIN participation WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$utis[] = new Utilisateur($donnees);
		}
		return $utis;
	}

	/**
	 * retourne les infos d'un utilisateur
	 * @param  $idutilisateur 
	 * @return utis[]
	 */
	public function getProfilUti($idutilisateur)
	{

		$utis = array();
		$req = "SELECT idutilisateur, nom, prenom, idiut, mail, mdp, statut, photoprofil FROM utilisateur WHERE idutilisateur = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$utis[] = new Utilisateur($donnees);
		}
		return $utis;
	}



	/**
	 * ajoute un utilisateur dans la BD
	 * @param  Utilisateur $utilisateur
	 * @return $ok
	 */
	public function ajoutUti(Utilisateur $utilisateur)
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

	/**
	 * recupere les utilisateurs qui ne participent a aucun projet
	 * @param  aucun
	 * @return utis[]
	 */
	public function getUtiSuppr()
	{

		$utis = array();

		// Requête SQL pour exclure les utilisateurs présents dans la table "participation"
		$req = "SELECT idutilisateur, nom, prenom, idiut, mail, mdp, statut, photoprofil FROM utilisateur WHERE idutilisateur NOT IN (SELECT idutilisateur FROM participation)";

		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour déboguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$utis[] = new Utilisateur($donnees);
		}
		return $utis;
	}

	/**
	 * supprime un utilisateur dans la BD
	 * @param  Utilisateur $supputi
	 * @return utis[]
	 */
	public function supprUti(Utilisateur $supputi): bool
	{
		$req = "DELETE FROM utilisateur WHERE idutilisateur = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($supputi->idUtilisateur()));
	}

	/**
	 * modifie les infos d'un utilisateur dans la BD
	 * @param  Utilisateur $utilisateur
	 * @return mixed
	 */
	public function updateUtilisateur(Utilisateur $utilisateur): bool
	{
		$req = "UPDATE utilisateur SET nom = :nom, "
			. "prenom = :prenom, "
			. "idiut  = :idiut, "
			. "mail  = :mail "
			. " WHERE idutilisateur= :idutilisateur";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":nom" => $utilisateur->nom(),
				":prenom" => $utilisateur->prenom(),
				":idiut" => $utilisateur->idIut(),
				":mail" => $utilisateur->mail(),
				":idutilisateur" => $utilisateur->idUtilisateur(),

			)
		);
		return $stmt->rowCount() > 0;

	}

}
?>