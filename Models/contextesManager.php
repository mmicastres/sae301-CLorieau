<?php
/**
 * Définition d'une classe permettant de gérer les Contextes 
 *   en relation avec la base de données	
 */
class ContexteManager
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
	 * Récupère l'ensemble des contextes présents dans la BD
	 * @param aucun
	 * @return contextes[]
	 */
	public function choixContexte()
	{

		$contextes = array();
		// requete d'ajout dans la BD
		$req = "SELECT idcontexte, identifiant, semestre, intitule FROM contexte";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$contextes[] = new Contexte($donnees);
		}
		return $contextes;
	}

	/**
	 * Récupère les contextes associés leurs projets
	 * @param  $idprojet
	 * @return contextes[]
	 */
	public function getContexte($idprojet)
	{
		$contextes = array();
		$req = "SELECT identifiant, semestre, intitule  FROM contexte NATURAL JOIN projet WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$contextes[] = new Contexte($donnees);
		}
		return $contextes;
	}


	/**
	 * ajout d'un contexte dans la BD
	 * @param Contexte $contexte 
	 * @return mixed
	 */
	public function ajoutContexte(Contexte $contexte)
	{
		//incrementatation de l'id utilisateur
		$stmt = $this->_db->prepare("SELECT max(idcontexte) AS maximum FROM contexte");
		$stmt->execute();
		$contexte->setIdContexte($stmt->fetchColumn() + 1);

		$req = "INSERT INTO contexte (idcontexte, identifiant, semestre, intitule) VALUES (?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$ok = $stmt->execute(array($contexte->idContexte(), $contexte->identifiant(), $contexte->semestre(), $contexte->intitule()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $ok;

	}


	/**
	 * Récupère les contextes n'appartenant à aucun projet
	 * @param  aucun
	 * @return contextes[]
	 */
	public function getContexteSuppr()
	{

		$contextes = array();

		// Requête SQL pour exclure les utilisateurs présents dans la table "participation"
		$req = "SELECT idcontexte, identifiant, semestre, intitule FROM contexte WHERE idcontexte NOT IN (SELECT idcontexte FROM projet)";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour déboguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$contextes[] = new Contexte($donnees);
		}
		return $contextes;

	}


	/**
	 * Supprimme un contexte en fonction de son id
	 * @param  aucun
	 * @return rien
	 */
	public function supprContexte(Contexte $suppcontexte): bool
	{
		$req = "DELETE FROM contexte WHERE idcontexte = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppcontexte->idContexte()));
	}

	/**
	 * modifie le contexte d'un projet dans la BD
	 * @param Projet $contexte
	 * @return boolean
	 */
	public function updateContexte(Projet $contexte): bool
	{
		$req = "UPDATE projet SET idcontexte = :idcontexte "
			. " WHERE idprojet= :idprojet";
		//var_dump($iti);

		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":idcontexte" => $contexte->idContexte(),
				":idprojet" => $contexte->idProjet()
			)
		);
		return $stmt->rowCount();

	}



}

