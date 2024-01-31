<?php
/**
 * Définition d'une classe permettant de gérer les Sources
 *   en relation avec la base de données	
 */
class SourceManager
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
	 * ajout d'une source dans la BD
	 * @param Source $source à ajouter
	 * @return mixed
	 */
	public function add(Source $source, $proj)
	{
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idsource) AS maximum FROM sources ");
		$stmt->execute();
		$source->setIdSource($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO sources (idprojet, idsource, liensource) VALUES (?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($proj->idProjet(), $source->idSource(), $source->lienSource()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}

	/**
	 * Récupère les sources associées leurs projets
	 * @param  $idprojet
	 * @return sources[]
	 */
	public function getSource($idprojet)
	{
		$sources = array();
		$req = "SELECT liensource FROM sources WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		while ($donnees = $stmt->fetch()) {
			$sources[] = new Source($donnees);
		}
		return $sources;
	}

	/**
	 * Supprimme la source liée à son projet
	 * @param  aucun
	 * @return rien
	 */
	public function deleteSource(Source $suppsource): bool
	{
		$req = "DELETE FROM sources WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppsource->idProjet()));
	}

	/**
	 * Récupère les sources qui appartiennent à des projets 
	 * @param  idprojet
	 * @return source
	 */
	public function getSourceModif($idprojet)
	{
		$req = "SELECT liensource FROM sources WHERE idprojet=?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idprojet));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		// recup des données
		$source = new Source($stmt->fetch());
		return $source;
	}
	/**
	 * modifie le lien d'une source dans la BD
	 * @param Tag $tag
	 * @return boolean
	 */
	public function updateSource(Source $source): bool
	{
		$req = "UPDATE sources SET liensource = :liensource"
			. " WHERE idprojet= :idprojet";


		$stmt = $this->_db->prepare($req);
		$stmt->execute(
			array(
				":liensource" => $source->lienSource(),
				":idprojet" => $source->idProjet()
			)
		);
		return $stmt->rowCount();

	}


}