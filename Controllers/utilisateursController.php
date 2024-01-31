<?php
include "Modules/utilisateur.php";
include "Models/utilisateursManager.php";


/**
 * Définition d'une classe permettant de gérer les utilisateurs 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateurManager; // instance du manager
	private $projetManager;
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->projetManager = new ProjetManager($db);
		$this->twig = $twig;
	}



	/**
	 * formulaire de connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurFormulaire()
	{
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces']));
	}



	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */

	function utilisateurConnexion()
	{
		// verif du login et mot de passe
		$utilisateurs = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($utilisateurs != false) {
			// Accès autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idutilisateur'] = $utilisateurs->idUtilisateur();
			$_SESSION['admin'] = $utilisateurs->statut(); // Stocker le statut de l'utilisateur
			$_SESSION['nomuti'] = $utilisateurs->prenom() . " " . $utilisateurs->nom();



			// Vérifier si l'utilisateur est un admin
			if ($_SESSION['admin'] == 0) {
				$_SESSION['admin'] = "oui"; // Créer une session spécifique pour l'admin
				$message = "Bonjour Admin " . $utilisateurs->prenom() . " " . $utilisateurs->nom() . "!";
			} else {
				$_SESSION['admin'] = "non"; // Utilisateur non admin
				$message = "Bonjour " . $utilisateurs->prenom() . " " . $utilisateurs->nom() . "!";
			}

			$projets = $this->projetManager->getListUtilisateur($_SESSION['idutilisateur']);

			echo $this->twig->render('mesprojets.html.twig', array('projets' => $projets, 'acces' => $_SESSION['acces'], 'message' => $message, 'admin' => $_SESSION['admin'], 'idutilisateur' => $_SESSION['idutilisateur'], 'nomuti' => $_SESSION['nomuti']));
		} else {
			// Accès non autorisé : variable de session acces = non
			$message = "identification incorrecte";
			$_SESSION['acces'] = "non";
			$_SESSION['admin'] = "non"; // Utilisateur non admin
			echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message, 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
		}
	}



	/**
	 * deconnexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurDeconnexion()
	{
		$_SESSION['acces'] = "non"; // acces non autorisé
		$_SESSION['admin'] = "non";
		$message = "vous êtes déconnecté";
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'message' => $message, 'nomuti' => $_SESSION['nomuti']));

	}


	/**
	 * formulaire d'inscription
	 * @param aucun
	 * @return rien
	 */
	function inscription()
	{
		echo $this->twig->render('inscription.html.twig', array('acces' => $_SESSION['acces']));
	}

	/**
	 * inscription
	 * @param aucun
	 * @return rien
	 */
	public function utilisateurInscription()
	{
		$utilisateur = new Utilisateur($_POST);
		$email = $utilisateur->mail();

		// Vérification de l'adresse e-mail
		if (substr($email, -13) === ".iut-tlse3.fr") {
			$ok = $this->utilisateurManager->inscriUtilisateur($utilisateur);
			$message = $ok ? "Compte créé avec succès" : "Problème lors de la création du compte";
			echo $this->twig->render('utilisateur_connexion.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
		} else {
			// Message d'erreur si l'e-mail n'est pas conforme
			$message = "L'adresse e-mail doit se terminer par .iut-tlse3.fr";
			echo $this->twig->render('inscription.html.twig', array('message' => $message, 'acces' => $_SESSION['acces']));
		}


	}


	/**
	 * affichage profil
	 * @param $utilisateur
	 * @return rien
	 */
	public function monProfil($utilisateur)
	{
		$utis = $this->utilisateurManager->getProfilUti($utilisateur);
		echo $this->twig->render('profil.html.twig', array('utis' => $utis, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * affichage utilisateurs sur la page admin
	 * @param $idtilisateur
	 * @return rien
	 */
	function adminUti($idutilisateur)
	{
		$utis = $this->utilisateurManager->getUtiSuppr($idutilisateur);
		echo $this->twig->render('utilisateur_admin.html.twig', array('utis' => $utis, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * ajout d'un utilisateur par l'admin
	 * @param aucun
	 * @return rien
	 */
	public function adminAjoutUti()
	{
		$utilisateur = new Utilisateur($_POST);
		$ok = $this->utilisateurManager->ajoutUti($utilisateur);
		$utis = $this->utilisateurManager->getUtiSuppr();
		$message = $ok ? "Utilisateur ajouté avec succès" : "Problème lors de l'ajout de l'utilisateur";
		echo $this->twig->render('utilisateur_admin.html.twig', array('utis' => $utis, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));

	}

	/**
	 * supression d'un utilisateur par l'admin
	 * @param aucun
	 * @return rien
	 */
	public function supprimerUti()
	{

		$supputi = new Utilisateur($_POST);

		$okSupputi = $this->utilisateurManager->supprUti($supputi);

		$utis = $this->utilisateurManager->getUtiSuppr();

		$message = "Utilisateur supprimé";

		if (!$okSupputi) {
			$message .= "Erreur lors de la suppression de l'utilisateur";
		}

		echo $this->twig->render('utilisateur_admin.html.twig', array('utis' => $utis, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}


	/**
	 * modification du profil utilisateur
	 * @param $utilisateur
	 * @return rien
	 */
	public function modUtilisateur($utilisateur)
	{


		$uti = new Utilisateur($_POST);
		$email = $_POST['mail'];

		// Vérification de l'adresse e-mail
		if (substr($email, -13) === ".iut-tlse3.fr") {
			$okUti = $this->utilisateurManager->updateUtilisateur($uti);
		$message = "";
		if ($okUti > 0) {
			$message .= "Profil Modifié";
		} else {
			$message .= "Aucune modification effectuée";
		}
		$utis = $this->utilisateurManager->getProfilUti($utilisateur);
		if ($message != "") {
			echo $this->twig->render('profil.html.twig', array('utis' => $utis, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
		}
		} else {
			// Message d'erreur si l'e-mail n'est pas conforme
			$message = "L'adresse e-mail doit se terminer par .iut-tlse3.fr";
			$utis = $this->utilisateurManager->getProfilUti($utilisateur);
			echo $this->twig->render('profil.html.twig', array('utis' => $utis, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
		}

		


	}









}