<?php
include "Modules/utilisateur.php";
include "Models/utilisateursManager.php";
/**
 * Définition d'une classe permettant de gérer les membres 
 *   en relation avec la base de données	
 */
class UtilisateurController
{
	private $utilisateursManager; // instance du manager
	private $twig;

	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{
		$this->utilisateurManager = new UtilisateurManager($db);
		$this->twig = $twig;
	}

	/**
	 * connexion
	 * @param aucun
	 * @return rien
	 */
	function utilisateurConnexion($data)
	{
		// verif du login et mot de passe
		// if ($_POST['login']=="user" && $_POST['passwd']=="pass")
		$utilisateur = $this->utilisateurManager->verif_identification($_POST['login'], $_POST['passwd']);
		if ($utilisateur != false) { // acces autorisé : variable de session acces = oui
			$_SESSION['acces'] = "oui";
			$_SESSION['idutilisateur'] = $utilisateur->idUtilisateur();
			$message = "Bonjour " . $utilisateur->prenom() . " " . $utilisateur->nom() . "!";
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
		} else { // acces non autorisé : variable de session acces = non
			$message = "identification incorrecte";
			$_SESSION['acces'] = "non";
			echo $this->twig->render('index.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));
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
		$message = "vous êtes déconnecté";
		echo $this->twig->render('utilisateur_connexion.html.twig', array('acces' => $_SESSION['acces'], 'message' => $message));

	}


	public function utilisateurInscription() {
		$utilisateur = new Utilisateur($_POST);
		$ok = $this->utilisateurManager->inscriUtilisateur($utilisateur);
		$message = $ok ? "Compte créé avec succès" : "Problème lors de la création du compte";
		echo $this->twig->render('utilisateur_connexion.html.twig', array('message'=> $message, 'acces'=>$_SESSION['acces']));

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

	function detailsUtilisateur()
	{

		$details = $this->utilisateurManager->getUtilisateur($_POST["idutilisateur"]);
		echo $this->twig->render('detail.html.twig', array('details' => $details));

	}

	function inscription()
	{
		echo $this->twig->render('inscription.html.twig', array('acces' => $_SESSION['acces']));
	}

}