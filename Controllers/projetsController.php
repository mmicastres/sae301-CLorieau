<?php
include "Modules/projet.php";
include "Modules/tag.php";
include "Modules/source.php";
include "Modules/appartient.php";
include "Modules/participation.php";
include "Modules/associer.php";
include "Modules/commentaire.php";
include "Modules/evaluation.php";


include "Models/projetsManager.php";
include "Models/tagsManager.php";
include "Models/sourcesManager.php";
include "Models/appartientManager.php";
include "Models/participationManager.php";
include "Models/associerManager.php";
include "Models/commentairesManager.php";
include "Models/evaluationsManager.php";




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
	private $utilisateurManager;
	private $commentaireManager;
	private $evaluationManager;
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
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->commentaireManager = new CommentaireManager($db);
		$this->evaluationManager = new EvaluationManager($db);
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
		echo $this->twig->render('projets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
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
		echo $this->twig->render('projet_ajout.html.twig', array('acces' => $_SESSION['acces'], 'contextes' => $contexte, 'cates' => $cate, 'idutilisateur' => $_SESSION['idutilisateur'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * ajout dans la BD d'un projet à partir du form
	 * @param aucun
	 * @return rien
	 */


	public function ajoutProjet()
	{

		$targetDirectory = "img/"; // Le dossier dans lequel vous souhaitez enregistrer les fichiers
		$targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
		$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

		// ajout de l'image provenant de Lucas Boustens réalisée avec chatGPT
		// Vérifier si le fichier est une image réelle ou un faux fichier image
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if ($check === false) {
			echo "Le fichier n'est pas une image.";
		}
		// Vérifier si le fichier existe déjà
		if (file_exists($targetFile)) {
			echo "Désolé, le fichier existe déjà.";
		}
		// Vérifier la taille du fichier
		if ($_FILES["image"]["size"] > 5000000) {
			echo "Désolé, votre fichier est trop volumineux.";
		}
		// Autoriser certains formats de fichiers
		if (
			$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" && $imageFileType != "webp"
		) {
			echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
		}

		$proj = new Projet($_POST);
		// Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
		if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
			// Stockez le nom du fichier dans l'objet $proj
			$proj->setImage(basename($_FILES["image"]["name"]));
			$message = "Le fichier " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé.";
		} else {
			echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
		}



		// Création des instances des modèles avec les données POST

		$tag = new Tag($_POST);
		$source = new Source($_POST);
		$linkcate = new Appartient($_POST);
		$linkuti = new Participation($_POST);
		$linktag = ($_POST["nomtag"]);



		// Laisons avec les fonctions des managers

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
			$message .= "Liaison de la catégorie manquante. ";
		}
		if (!$okLinkuti) {
			$message .= "Liaison de l'utilisateur manquante. ";
		}
		if (!$okLinktag) {
			$message .= "Liaison du tag manquante. ";
		}




		// Affichage de la vue avec le message
		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));

	}




	/**
	 * suppression dans la BD d'un projet à partir de son id
	 * @param $idprojet
	 * @return rien
	 */
	public function supprimerProjet($idprojet)
	{
		$suppsource = new Source($_POST);
		$suppappartient = new Appartient($_POST);
		$suppparticipation = new Participation($_POST);
		$suppassocier = new Associer($_POST);
		$suppproj = new Projet($_POST);
		$suppcommentaire = new Commentaire($_POST);
		$suppeval = new Evaluation($_POST);


		$okSuppsource = $this->sourceManager->deleteSource($suppsource);
		$okSuppappartient = $this->appartientManager->deleteAppartient($suppappartient);
		$okSuppparticipation = $this->participationManager->deleteParticipation($suppparticipation);
		$okSuppassocier = $this->associerManager->deleteAssocier($suppassocier);
		$okSupptag = $this->tagManager->deleteTag();
		$okSuppproj = $this->projetManager->delete($suppproj);
		$okSuppcommentaire = $this->commentaireManager->deleteCommentaire($suppcommentaire);
		$okSuppeval = $this->evaluationManager->deleteEvaluation($suppeval);



		$message = "Projet supprimé";

		if (!$okSuppsource) {
			$message .= "Erreur lors de la suppression de la source";
		}
		if (!$okSuppappartient) {
			$message .= "Erreur lors de la suppression de la liaison avec la catégorie";
		}
		if (!$okSuppparticipation) {
			$message .= "Erreur lors de la suppression de la liaison avec les participants";
		}
		if (!$okSuppassocier) {
			$message .= "Erreur lors de la suppression de la liaison avec le tag";
		}
		if (!$okSupptag) {
			$message .= "Erreur lors de la suppression du tag";
		}
		if (!$okSuppproj) {
			$message .= "Erreur lors de la suppression du projet";
		}
		if (!$okSuppcommentaire) {
			$message .= "Erreur lors de la suppression des commentaires";
		}
		if (!$okSuppeval) {
			$message .= "Erreur lors de la suppression des évaluations";
		}


		echo $this->twig->render('index.html.twig', array('message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}



	/**
	 * form de saisi des nouvelles valeurs du projet à modifier
	 * @param aucun
	 * @return rien
	 */
	public function saisieModProjet()
	{
		$contexte = $this->contexteManager->choixContexte();
		$cate = $this->categorieManager->choixCategorie();
		$projs = $this->projetManager->getListProjetModif($_POST["idprojet"]);
		$tags = $this->tagManager->getTagModif($_POST["idprojet"]);
		$sources = $this->sourceManager->getSourceModif($_POST["idprojet"]);
		echo $this->twig->render('projet_modification.html.twig', array('contextes' => $contexte, 'cates' => $cate, 'projs' => $projs, 'tags' => $tags, 'sources' => $sources, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * modification dans la BD d'un projet à partir des données du form précédent
	 * @param $idutilisateur
	 * @return rien
	 */
	public function modProjet($idutilisateur)
	{
		if ($_FILES["image"]["size"] > 0) {
			$targetDirectory = "img/"; // Le dossier dans lequel vous souhaitez enregistrer les fichiers
			$targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
			$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

			// Vérifier si le fichier est une image réelle ou un faux fichier image
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if ($check === false) {
				echo "Le fichier n'est pas une image.";
			}
			// Vérifier si le fichier existe déjà
			if (file_exists($targetFile)) {
				echo "Désolé, le fichier existe déjà.";
			}
			// Vérifier la taille du fichier
			if ($_FILES["image"]["size"] > 5000000) {
				echo "Désolé, votre fichier est trop volumineux.";
			}
			// Autoriser certains formats de fichiers
			if (
				$imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" && $imageFileType != "webp"
			) {
				echo "Désolé, seuls les fichiers JPG, JPEG, PNG GIF et WEBP sont autorisés.";
			}

			$proj = new Projet($_POST);
			// Si le fichier a été téléchargé avec succès, met à jour $proj avec le nom du fichier
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
				// Stockez le nom du fichier dans l'objet $proj
				$proj->setImage(basename($_FILES["image"]["name"]));
				$message = "Le fichier " . htmlspecialchars(basename($_FILES["image"]["name"])) . " a été téléchargé.";
			}
		} else {
			$proj = $this->projetManager->get($_POST['idprojet']);
			$proj->image(basename($_FILES["image"]["name"]));
		}




		$cate = new Appartient($_POST);
		$tag = new Tag($_POST);
		$source = new Source($_POST);

		$okProj = $this->projetManager->updateProjet($proj);
		$okCate = $this->appartientManager->updateCategorie($cate);
		$okContexte = $this->contexteManager->updateContexte($proj);
		$okTag = $this->tagManager->updateTag($tag);
		$okSource = $this->sourceManager->updateSource($source);

		$message = "";


		if ($okProj > 0 || $okCate > 0 || $okContexte > 0 || $okTag > 0 || $okSource > 0) {

			$message .= "Projet Modifié";

		} else {
			$message .= "Aucune modification effectuée";
		}

		$projets = $this->projetManager->getListUtilisateur($idutilisateur);

		if ($message != "") {
			echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
		}
	}

	/**
	 * Lien vers la page d'accueil
	 * @param aucun
	 * @return rien
	 */
	public function projAccueil()
	{
		echo $this->twig->render('accueil.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * Lien vers la page "mes projets" d'un utilisateur
	 * @param $idutilisateur
	 * @return rien
	 */
	public function mesProjets($idutilisateur)
	{
		$projets = $this->projetManager->getListUtilisateur($idutilisateur);
		echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * Affichage des détails d'un projet
	 * @param aucun
	 * @return rien
	 */
	public function details()
	{
		$detailprojets = $this->projetManager->getListProjet($_POST["idprojet"]);
		$detailtags = $this->tagManager->getTag($_POST["idprojet"]);
		$detailsources = $this->sourceManager->getSource($_POST["idprojet"]);
		$detailcates = $this->categorieManager->getCategorie($_POST["idprojet"]);
		$detailcontextes = $this->contexteManager->getContexte($_POST["idprojet"]);
		$detailutilisateurs = $this->utilisateurManager->getDetailUti($_POST["idprojet"]);
		$comms = $this->commentaireManager->getListCommentaire($_POST["idprojet"]);
		$evals = $this->evaluationManager->getMoyenneNote($_POST["idprojet"]);
		echo $this->twig->render('detail.html.twig', array('detailprojets' => $detailprojets, 'detailtags' => $detailtags, 'detailsources' => $detailsources, 'detailcates' => $detailcates, 'detailcontextes' => $detailcontextes, 'detailutilisateurs' => $detailutilisateurs, 'comms' => $comms, 'evals' => $evals, 'acces' => $_SESSION['acces'], 'idutilisateur' => $_SESSION['idutilisateur'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	
	/**
	 * recherche dans la BD projet selon son titre ou sa description
	 * @param aucun
	 * @return rien
	 */
	public function rechercheProjet()
	{
		$projets = $this->projetManager->search($_POST["titre"], $_POST["description"]);
		echo $this->twig->render('projets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}


	/**
	 * Ajout d'un commentaire
	 * @param aucun
	 * @return rien
	 */
	public function ajoutCommentaire()
	{
		if (isset($_SESSION['acces']) && $_SESSION['acces'] == "oui") {
			$comm = new Commentaire($_POST);
			$ok = $this->commentaireManager->add($comm);
			$detailprojets = $this->projetManager->getListProjet($_POST["idprojet"]);
			$detailtags = $this->tagManager->getTag($_POST["idprojet"]);
			$detailsources = $this->sourceManager->getSource($_POST["idprojet"]);
			$detailcates = $this->categorieManager->getCategorie($_POST["idprojet"]);
			$detailcontextes = $this->contexteManager->getContexte($_POST["idprojet"]);
			$detailutilisateurs = $this->utilisateurManager->getDetailUti($_POST["idprojet"]);
			$comms = $this->commentaireManager->getListCommentaire($_POST["idprojet"]);
			$evals = $this->evaluationManager->getMoyenneNote($_POST["idprojet"]);
			$message = $ok ? "Commentaire ajouté" : $message = "probleme lors de la modification";
			echo $this->twig->render('detail.html.twig', array('comm' => $comm, 'detailprojets' => $detailprojets, 'detailtags' => $detailtags, 'detailsources' => $detailsources, 'detailcates' => $detailcates, 'detailcontextes' => $detailcontextes, 'detailutilisateurs' => $detailutilisateurs, 'comms' => $comms, 'evals' => $evals, 'message' => $message, 'acces' => $_SESSION['acces'], 'idutilisateur' => $_SESSION['idutilisateur'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
		} else {
			$message = "Veuillez vous connecter pour pouvoir ajouter un commentaire";
			echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces'], 'nomuti' => $_SESSION['nomuti']));
		}

	}


	/**
	 * Ajout d'une note
	 * @param aucun
	 * @return rien
	 */
	public function ajoutNote()
	{
		if (isset($_SESSION['acces']) && $_SESSION['acces'] == "oui") {
			$eval = new Evaluation($_POST);
			$ok = $this->evaluationManager->add($eval);
			$detailprojets = $this->projetManager->getListProjet($_POST["idprojet"]);
			$detailtags = $this->tagManager->getTag($_POST["idprojet"]);
			$detailsources = $this->sourceManager->getSource($_POST["idprojet"]);
			$detailcates = $this->categorieManager->getCategorie($_POST["idprojet"]);
			$detailcontextes = $this->contexteManager->getContexte($_POST["idprojet"]);
			$detailutilisateurs = $this->utilisateurManager->getDetailUti($_POST["idprojet"]);
			$evals = $this->evaluationManager->getMoyenneNote($_POST["idprojet"]);
			$comms = $this->commentaireManager->getListCommentaire($_POST["idprojet"]);
			$message = $ok ? "Note ajoutée" : $message = "probleme lors de l'envoie'";
			echo $this->twig->render('detail.html.twig', array('eval' => $eval, 'detailprojets' => $detailprojets, 'detailtags' => $detailtags, 'detailsources' => $detailsources, 'detailcates' => $detailcates, 'detailcontextes' => $detailcontextes, 'detailutilisateurs' => $detailutilisateurs, 'evals' => $evals, 'comms' => $comms, 'message' => $message, 'acces' => $_SESSION['acces'], 'idutilisateur' => $_SESSION['idutilisateur'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));

		} else {
			$message = "Veuillez vous connecter pour pouvoir ajouter une note";
			echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces'], 'nomuti' => $_SESSION['nomuti']));
		}
	}

}
