<?php
/**
 * Définition d'une classe permettant de gérer les Tags 
 *   en relation avec la base de données	
 */
class TagManager
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
	 * ajout d'un tag dans la BD
	 * @param Tag $tag à ajouter
	 * @return mixed
	 */
	public function add(Tag $tag)
	{
		// calcul d'un nouveau code du Tag non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idtag) AS maximum FROM tags ");
		$stmt->execute();
		$tag->setIdTag($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO tags (idtag, nomtag) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($tag->idTag(), $tag->nomTag()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	/**
	 * cherche un tag existant dans la bd
	 * @param  $tag 
	 * @return $res si tag existant, null sinon
	 */
	public function chercher($tag)
	{
		print_r($tag);

		// requete d'ajout dans la BD
		$req = "SELECT idtag FROM tags WHERE nomtag LIKE ?";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($tag));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		if ($data = ($stmt->fetch())) {
			$res = new Tag($data);
			return $res;
		} else {

			return null;
		}
	}

	/**
	 * Récupère le/les tags associé(s) à un projet
	 * @param  $idprojet
	 * @return tags[]
	 */
	public function getTag($idprojet)
	{

		$tags = array();
		$req = "SELECT nomtag FROM tags NATURAL JOIN associer WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$tags[] = new Tag($donnees);
		}
		return $tags;
	}

	/**
	 * Supprimme le/les tags qui n'appartiennent pas à un projet existant
	 * @param  aucun
	 * @return rien
	 */
	public function deleteTags()
	{
		$req = "DELETE FROM tags WHERE idtags NOT IN (SELECT idtags from associer)";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute();
	}

	/**
	 * Récupère les tags qui appartiennent à des projets 
	 * @param  idprojet
	 * @return tag
	 */
	public function getTagModif($idprojet)
	{


		$req = "SELECT idtag, nomtag FROM tags NATURAL JOIN associer WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		$tag = new Tag($stmt->fetch());
		return $tag;
	}

	/**
	 * modifie le nom du tag dans la BD
	 * @param Tag $tag
	 * @return boolean
	 */
	public function updateTag(Tag $tag): bool
	{
		$req = "UPDATE tags SET nomtag = :nomtag "
			. " WHERE idtag= :idtag";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":nomtag" => $tag->nomTag(),
				":idtag" => $tag->idTag()
			)
		);
		return $stmt->rowCount();

	}





}