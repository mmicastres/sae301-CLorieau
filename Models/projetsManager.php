<?php
/**
 * Définition d'une classe permettant de gérer les Projet 
 *   en relation avec la base de données	
 */
class ProjetManager
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
	 * ajout d'un Projet dans la BD
	 * @param Projet à ajouter
	 * @return int true si l'ajout a bien eu lieu, false sinon
	 */
	public function add(Projet $proj)
	{
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idprojet) AS maximum FROM projet");
		$stmt->execute();
		$proj->setIdProjet($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO projet (idprojet,titre,description,image,liendemo,idcontexte,annee) VALUES (?,?,?,?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $proj->titre(), $proj->description(), $proj->image(), $proj->lienDemo(), $proj->idContexte(), $proj->annee()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	/**
	 * nombre de Projets dans la base de données
	 * @return int le nb de Projet
	 */
	public function count(): int
	{
		$stmt = $this->_db->prepare('SELECT COUNT(*) FROM projet');
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	/**
	 * suppression d'un Projet dans la base de données
	 * @param Projet 
	 * @return boolean true si suppression, false sinon
	 */


	public function delete(Projet $suppproj): bool
	{
		$req = "DELETE FROM projet WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppproj->idProjet()));
	}

	/**
	 * recherche dans la BD d'un Projets à partir de son id
	 * @param int $iditi 
	 * @return Projet 
	 */
	public function get(int $idprojet): Projet
	{
		$req = 'SELECT idprojet,idutilisateur,titre,description,image,liendemo,idcontexte,annee FROM projet NATURAL JOIN participation WHERE idprojet=?';
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$proj = new Projet($stmt->fetch());
		return $proj;
	}

	/**
	 * retourne l'ensemble des Projets présents dans la BD 
	 * @return Projet[]
	 */
	public function getList()
	{
		$projets = array();
		$req = "SELECT idprojet,titre,description,image,liendemo,idcontexte,annee FROM projet";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// récup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	/**
	 * retourne l'ensemble des infos des Projets présents dans la BD pour un utilisateur
	 * @param int idmembre
	 * @return Projets[]
	 */
	public function getListUtilisateur(int $idutilisateur)
	{
		$projets = array();
		$req = "SELECT idprojet,idutilisateur,titre,description,image,liendemo,idcontexte, annee FROM projet NATURAL JOIN participation WHERE idutilisateur=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idutilisateur));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}

	/**
	 * retourne l'ensemble des infos  relatives à un projet
	 * @param int idmembre
	 * @return Projets[]
	 */
	public function getListProjet($idprojet)
	{
		$req = "SELECT idprojet,titre,description,image,liendemo,idcontexte,annee FROM projet WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtets SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		while ($donnees = $stmt->fetch()) {
			$projets[] = new Projet($donnees);
		}
		return $projets;
	}
	/**
	 * méthode de recherche d'un Projet dans la BD à partir des critères passés en paramètre
	 * @param string $titre
	 * @param string $description
	 * @return Projs[]
	 */
	public function search(string $titre, string $description)
	{
		$req = "SELECT idprojet, titre, description, image, liendemo, idcontexte, annee FROM projet";
		$cond = '';

		if ($titre <> "") {
			$cond = $cond . " titre like '%" . $titre . "%'";
		}
		if ($description <> "") {
			if ($cond <> "")
				$cond .= " AND ";
			$cond = $cond . " description like '%" . $description . "%'";
		}
		if ($cond <> "") {
			$req .= " WHERE " . $cond;
		}
		// execution de la requete				
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		$projs = array();
		while ($donnees = $stmt->fetch()) {
			$projs[] = new Projet($donnees);
		}
		return $projs;
	}

	/**
	 * retourne l'ensemble des infos  relatives à un projet
	 * @param int idmembre
	 * @return Projets[]
	 */
	public function getListProjetModif($idprojet)
	{
		$req = "SELECT idprojet,titre,description,image,liendemo,idcontexte,annee FROM projet WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtets SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		$proj = new Projet($stmt->fetch());
		return $proj;
	}










	/**
	 * modification d'un Projet dans la BD
	 * @param Projet
	 * @return boolean 
	 */

	public function updateProjet(Projet $proj): bool
	{
		$req = "UPDATE projet SET titre = :titre, "
			. "description = :description, "
			. "image = :image, "
			. "liendemo  = :liendemo, "
			. "idcontexte  = :idcontexte, "
			. "annee  = :annee "
			. " WHERE idprojet= :idprojet";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":titre" => $proj->titre(),
				":description" => $proj->description(),
				":image" => $proj->image(),
				":liendemo" => $proj->lienDemo(),
				":idcontexte" => $proj->idContexte(),
				":annee" => $proj->annee(),
				":idprojet" => $proj->idProjet()
			)
		);
		return $stmt->rowCount() > 0;

	}
}
