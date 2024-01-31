<?php
include_once("Models/tagsManager.php");
// include_once("Modules/tag.php");
/**
 * Définition d'une classe permettant de gérer les Association entre un tag et un projet
 *   en relation avec la base de données	
 */
class AssocierManager
{

	private $_db; // Instance de PDO - objet de connexion au SGBD
	private $tagManager;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db)
	{
		$this->_db = $db;
		$this->tagManager = new TagManager($db);

	}

	/**
	 * ajout d'une liaison entre un projet et un tag
	 * @param Associer $linktag
	 * @param $proj
	 * @return mixed
	 */
	public function add($linktag, $proj)
	{
		$tag = $this->tagManager->chercher($linktag);
		if ($tag) {

			// requete d'ajout dans la BD
			$req = "INSERT INTO associer (idprojet, idtag) VALUES (?,?)";
			$stmt = $this->_db->prepare($req);
			$res = $stmt->execute(array($proj->idProjet(), $tag->idTag()));
			// pour debuguer les requêtes SQL
			$errorInfo = $stmt->errorInfo();
			if ($errorInfo[0] != 0) {
				print_r($errorInfo);
			}
			return $res;
		} else {
			echo "le tag n'existe pas";
		}
	}

	/**
	 * Supprimme la liaison entre un projet et un tag
	 * @param  aucun
	 * @return rien
	 */
	public function deleteAssocier(Associer $suppassocier): bool
	{
		$req = "DELETE FROM associer WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppassocier->idProjet()));
	}

}

