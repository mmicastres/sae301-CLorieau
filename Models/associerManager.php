<?php
include_once("Models/tagsManager.php");
// include_once("Modules/tag.php");
/**
* Définition d'une classe permettant de gérer les Projet 
*   en relation avec la base de données	
*/
class AssocierManager {
    
	private $_db; // Instance de PDO - objet de connexion au SGBD
    private $tagManager;
        
	/**
	* Constructeur = initialisation de la connexion vers le SGBD
	*/
	public function __construct($db) {
		$this->_db = $db;
        $this->tagManager = new TagManager($db);

	}
        
	/**
	* ajout d'une Association dans la BD
	* @param Associer à ajouter
	* @return int true si l'ajout a bien eu lieu, false sinon
	*/
	public function add($linktag, $proj ) {
       $tag =  $this->tagManager->chercher($linktag);
      if($tag){
		
		// requete d'ajout dans la BD
		$req = "INSERT INTO associer (idprojet, idtag) VALUES (?,?)";
		$stmt = $this->_db->prepare($req);
		$res  = $stmt->execute(array($proj->idProjet(), $tag->idTag()));		
		// pour debuguer les requêtes SQL
		$errorInfo = $stmt->errorInfo();
		if ($errorInfo[0] != 0) {
			print_r($errorInfo);
		}
		return $res;
	}else {
        echo "le tag n'existe pas";
    }
} 
   
}

