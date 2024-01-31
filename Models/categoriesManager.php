<?php
/**
 * Définition d'une classe permettant de gérer les Categories
 *   en relation avec la base de données	
 */
class CategorieManager
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
	 * Récupère l'ensemble des catégories présentes dans la BD
	 * @param aucun
	 * @return cates[]
	 */
	public function choixCategorie()
	{
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
		while ($donnees = $stmt->fetch()) {
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}


	/**
	 * Récupère les categories associés leurs projets
	 * @param  $idprojet
	 * @return cates[]
	 */
	public function getCategorie($idprojet)
	{

		$cates = array();
		$req = "SELECT nomcate FROM categorie NATURAL JOIN appartient WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}


	/**
	 * ajout d'une categorie dans la BD
	 * @param Categorie $cate 
	 * @return mixed
	 */
	public function ajoutCategorie(Categorie $cate)
	{
		//incrementatation de l'id utilisateur
		$stmt = $this->_db->prepare("SELECT max(idcate) AS maximum FROM categorie");
		$stmt->execute();
		$cate->setIdCate($stmt->fetchColumn() + 1);

		$req = "INSERT INTO categorie (idcate, nomcate) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$ok = $stmt->execute(array($cate->idCate(), $cate->nomCate()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $ok;

	}

	/**
	 * Récupère les catégories n'appartenant à aucun projet
	 * @param  aucun
	 * @return cates[]
	 */
	public function getCategorieSuppr()
	{

		$cates = array();
		$req = "SELECT idcate, nomcate FROM categorie WHERE idcate NOT IN (SELECT idcate FROM appartient)";
		$stmt = $this->_db->prepare($req);
		$stmt->execute();
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$cates[] = new Categorie($donnees);
		}
		return $cates;
	}

	/**
	 * Supprimme une categorie en fonction de son id
	 * @param  aucun
	 * @return rien
	 */
	public function supprCategorie(Categorie $suppcate): bool
	{
		$req = "DELETE FROM categorie WHERE idcate = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppcate->idCate()));
	}





}





