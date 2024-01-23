<?php
include "Modules/projet.php";
include "Models/projetsManager.php";
include "Models/tagsManager.php";
include "Models/sourcesManager.php";
include "Models/categoriesManager.php";
include "Models/contextesManager.php";
include "Models/appartientManager.php";
include "Models/participationManager.php";
include "Models/associerManager.php";

include "Modules/contexte.php";
include "Modules/categorie.php";
include "Modules/tag.php";
include "Modules/source.php";
include "Modules/appartient.php";
include "Modules/participation.php";
include "Modules/associer.php";


/**
 * Définition d'une classe permettant de gérer les projets
 *   en relation avec la base de données	
 */
class ProjetController
{

	private $projetManager; // instance du manager
	private $tagManager;
	private $sourceManager;
	private $categorieManager;
	private $contexteManager;
	private $appartientManager;
	private $participationManager;
	private $associerManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->projetManager = new ProjetManager($db);
		$this->tagManager = new TagManager($db);
		$this->sourceManager = new SourceManager($db);
		$this->categorieManager = new CategorieManager($db);
		$this->contexteManager = new ContexteManager($db);
		$this->appartientManager = new AppartientManager($db);
		$this->participationManager = new ParticipationManager($db);
		$this->associerManager = new AssocierManager($db);
		$this->twig = $twig;

	}

	/**
	 * liste de tous les projets
	 * @param aucun
	 * @return rien
	 */
	public function listeProjets()
	{
		$projets = $this->projetManager->getList();
		echo $this->twig->render('projets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}

	/**
	 * liste de mes itinéraires
	 * @param aucun
	 * @return rien
	 */
	public function listeMesProjets($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('projets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * formulaire ajout
	 * @param aucun
	 * @return rien
	 */
	public function formAjoutProjet()
	{
		$contexte = $this->contexteManager->choixContexte();
		$cate = $this->categorieManager->choixCategorie();
		echo $this->twig->render('projet_ajout.html.twig', array('acces' => $_SESSION['acces'], 'contextes' => $contexte, 'cates' => $cate, 'idutilisateur' => $_SESSION['idutilisateur']));
	}

	/**
	 * ajout dans la BD d'un iti à partir du form
	 * @param aucun
	 * @return rien
	 */


	public function ajoutProjet()
	{
		// Création des instances des modèles avec les données POST
		$proj = new Projet($_POST);
		$tag = new Tag($_POST);
		$source = new Source($_POST);
		$linkcate = new Appartient($_POST);
		$linkuti = new Participation($_POST);
		$linktag =  ($_POST["nomtag"]);





		$okProj = $this->projetManager->add($proj);
		$okTag = $this->tagManager->add($tag);
		$okSource = $this->sourceManager->add($source, $proj);
		$okLinkcate = $this->appartientManager->add($linkcate, $proj);
		$okLinkuti = $this->participationManager->add($linkuti, $proj);
		$okLinktag = $this->associerManager->add($linktag, $proj);







		$message = "Projet ajouté";

		// Gérer les différents cas d'erreur
		if (!$okProj) {
			$message .= "Nom du projet manquant. ";
		}
		if (!$okTag) {
			$message .= "Tag manquant. ";
		}
		if (!$okSource) {
			$message .= "Source manquante. ";
		}
		if (!$okLinkcate) {
			$message .= "Source manquante. ";
		}
		if (!$okLinkuti) {
			$message .= "Source manquante. ";
		}
		if (!$okLinktag) {
			$message .= "Source manquante. ";
		}




		// Affichage de la vue avec le message
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}



	/**
	 * form de choix de l'iti à supprimer
	 * @param aucun
	 * @return rien
	 */
	public function choixSuppProjet($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('itineraire_choix_suppression.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * suppression dans la BD d'un iti à partir de l'id choisi dans le form précédent
	 * @param aucun
	 * @return rien
	 */
	public function suppProjet()
	{
		$proj = new Projet($_POST);
		$ok = $this->projetManager->delete($proj);
		$message = $ok ? "Projet supprimé" : "probleme lors de la supression";
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}
	/**
	 * form de choix de l'iti à modifier
	 * @param aucun
	 * @return rien
	 */
	public function choixModProjet($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('itineraire_choix_modification.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces']));
	}
	/**
	 * form de saisi des nouvelles valeurs de l'iti à modifier
	 * @param aucun
	 * @return rien
	 */
	public function saisieModProjet()
	{
		$proj = $this->projetManager->get($_POST["idprojet"]);
		echo $this->twig->render('itineraire_modification.html.twig', array('proj' => $proj, 'acces' => $_SESSION['acces']));
	}

	/**
	 * modification dans la BD d'un projet à partir des données du form précédent
	 * @param aucun
	 * @return rien
	 */
	public function modProjet()
	{
		$proj = new Projet($_POST);
		$ok = $this->projetManager->update($proj);
		$message = $ok ? "Projet modifié" : $message = "probleme lors de la modification";
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
	}

	public function projAccueil()
	{
		echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces']));
	}

	public function mesProjets($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('mesprojets.html.twig', array('projets'=> $projets, 'acces' => $_SESSION['acces']));
	}

	public function details()
	{
		$detailprojets = $this->projetManager->getListProjet($_POST["idprojet"]);
		$detailtags = $this->tagManager->getTag($_POST["idprojet"]);
		$detailsources = $this->sourceManager->getSource($_POST["idprojet"]);
		$detailcates = $this->categorieManager->getCategorie($_POST["idprojet"]);
		echo $this->twig->render('detail.html.twig', array('detailprojets'=> $detailprojets, 'detailtags'=> $detailtags, 'detailsources'=> $detailsources, 'detailcates'=> $detailcates, 'acces' => $_SESSION['acces']));
	}

	/**
	 * form de saisie des criteres
	 * @param aucun
	 * @return rien
	 */
	// public function formRechercheProjet() {
	// 	echo $this->twig->render('itineraire_recherche.html.twig',array('acces'=> $_SESSION['acces'])); 
	// }

	/**
	 * recherche dans la BD d'iti à partir des données du form précédent
	 * @param aucun
	 * @return rien
	 */
	// 	public function rechercheProjet() {
// 		$projets = $this->itiManager->search($_POST["lieudepart"], $_POST["lieuarrivee"], $_POST["datedepart"]);
// 		echo $this->twig->render('itineraire_liste.html.twig',array('itis'=>$projets,'acces'=> $_SESSION['acces'])); 
// 	}
}