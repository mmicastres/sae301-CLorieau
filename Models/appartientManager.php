<?php
/**
 * Définition d'une classe permettant de gérer les liens entre les catégorie et les projets
 *   en relation avec la base de données	
 */
class AppartientManager
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
	 * ajout d'une liaison entre un projet et une catégorie
	 * @param Appartient $linkcate
	 * @param $proj
	 * @return mixed
	 */
	public function add(Appartient $linkcate, $proj)
	{

		// requete d'ajout dans la BD
		$req = "INSERT INTO appartient (idprojet, idcate) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $linkcate->idCate()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	/**
	 * Supprimme la liaison entre un projet et une catégorie
	 * @param  aucun
	 * @return rien
	 */
	public function deleteAppartient(Appartient $suppappartient): bool
	{
		$req = "DELETE FROM appartient WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppappartient->idProjet()));
	}


	/**
	 * modifie le categorie d'un projet dans la BD
	 * @param Appartient $cate
	 * @return boolean
	 */
	public function updateCategorie(Appartient $cate): bool
	{
		$req = "UPDATE appartient SET idcate = :idcate "
			. " WHERE idprojet= :idprojet";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":idcate" => $cate->idCate(),
				":idprojet" => $cate->idProjet()
			)
		);
		return $stmt->rowCount();

	}

}