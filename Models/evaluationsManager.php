<?php
/**
 * Définition d'une classe permettant de gérer les evaluations
 *   en relation avec la base de données	
 */
class EvaluationManager
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
	 * ajout d'une evaluation dans la BD
	 * @param Evaluation $eval à ajouter
	 * @return mixed
	 */
	public function add(Evaluation $eval)
	{
		// calcul d'un nouveau code du Projet non déja utilisé = Maximum + 1
		$stmt = $this->_db->prepare("SELECT max(idevaluation) AS maximum FROM evaluation");
		$stmt->execute();
		$eval->setIdEvaluation($stmt->fetchColumn() + 1);

		// requete d'ajout dans la BD
		$req = "INSERT INTO evaluation (idevaluation, note, idutilisateur, idprojet) VALUES (?,?,?,?)";
		$stmt = $this->_db->prepare($req);
		$res = $stmt->execute(array($eval->idEvaluation(), $eval->note(), $eval->idUtilisateur(), $eval->idProjet()));
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}



	/**
	 * Supprimme l'évaluation liée à son projet
	 * @param  Evaluation $suppeval
	 * @return rien
	 */
	public function deleteEvaluation(Evaluation $suppeval): bool
	{
		$req = "DELETE FROM evaluation WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		return $stmt->execute(array($suppeval->idProjet()));
	}


	/**
	 * Récupère la moyenne arrondie des notes pour un projet spécifique
	 * @param $idProjet 
	 * @return evals[] 
	 */
	public function getMoyenneNote($idProjet)
	{
		$evals = array();
		$req = "SELECT idprojet, AVG(note) as note FROM evaluation WHERE idprojet = ?";
		$stmt = $this->_db->prepare($req);
		$stmt->execute(array($idProjet));

		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}

		// récupération de la moyenne
		while ($donnees = $stmt->fetch()) {
			// Arrondit la moyenne à l'entier le plus proche
			$donnees['note'] = round((float) $donnees['note']);
			$eval = new Evaluation($donnees);
			$evals[] = $eval;
		}

		return $evals;
	}
}

