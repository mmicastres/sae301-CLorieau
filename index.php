<?php
session_start();  // utilisation des sessions

include "connect.php"; // connexion au SGBD
include "moteurtemplate.php"; //connexion au moteur de template twig

// Connexion aux controllers
include "Controllers/projetsController.php";
include "Controllers/utilisateursController.php";
include "Controllers/categoriesController.php";
include "Controllers/contextesController.php";



/**
 * Constructeur = initialisation de la connexion vers le SGBD
 */
$projController = new ProjetController($bdd, $twig);
$utiController = new UtilisateurController($bdd, $twig);
$categorieController = new CategorieController($bdd, $twig);
$contexteController = new ContexteController($bdd, $twig);



// ============================== connexion / deconnexion - sessions ==================
$message = "";
// si la variable de session n'existe pas, on la crée
//session acces
if (!isset($_SESSION['acces'])) {
  $_SESSION['acces'] = "non";
}

//session admin
if (!isset($_SESSION['admin'])) {
  $_SESSION['admin'] = "non";
}

//session utilisateur
if (!isset($_SESSION['idutilisateur'])) {
  $_SESSION['idutilisateur'] = "non";
}

if (!isset($_SESSION['nomuti'])) {
  $_SESSION['nomuti'] = "non";
}

// formulaire de connexion
if (isset($_GET["action"]) && $_GET["action"] == "login") {
  $utiController->utilisateurFormulaire();
}

// click sur le bouton connexion 
if (isset($_POST["connexion"])) {
  $utilisateur = $utiController->utilisateurConnexion();
}

// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action'] == "logout") {
  $utilisateur = $utiController->utilisateurDeconnexion();
}

// définition de la page par défaut
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('accueil.html.twig', array('acces' => $_SESSION['acces']));
}
echo $message;


// ============================== projets ==================

// liste des projets de tous
if (isset($_GET["action"]) && $_GET["action"] == "projets") {
  $projController->listeProjets();
}

// liste des projets d'un utilisateur
if (isset($_GET["action"]) && $_GET["action"] == "mesprojets") {
  // $itiController->listeMesItineraires(?? du membre connecté);
}

// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"] == "ajout") {
  $projController->formAjoutProjet();
}

// ajout du projet dans la base
// --> au clic sur le bouton "ajoutproj" du form précédent
if (isset($_POST["ajoutproj"])) {
  $projController->ajoutProjet();
}


// supression d'un projet dans la base
// --> au clic sur le bouton "supprimer_projet" du form précédent
if (isset($_POST["action"]) && $_POST["action"] == "supprimer_projet") {
  $projController->supprimerProjet($_POST['idprojet']);
}

// modification d'un projet : saisie des nouvelles valeurs
// --> au clic sur le bouton "modifier_projet" du form précédent
if (isset($_POST["action"]) && $_POST["action"] == "modifier_projet") {
  $projController->saisieModProjet();
}


//modification d'un projet : enregistrement dans la bd
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $projController->modProjet($_SESSION['idutilisateur']);
}

// recherche des projets : construction de la requete SQL en fonction des critères 
// --> au clic sur le bouton "valider_recher" du form précédent
if (isset($_POST["valider_recher"])) {
  $projController->rechercheProjet();
}

// affichage de la page d'accueil
// --> au clic sur le bouton "accueil" du menu
if (isset($_GET["action"]) && $_GET['action'] == "accueil") {
  $projController->projAccueil();
}

// affichage du formulaire d'inscription
// --> au clic sur le bouton "inscription" du form de connexion
if (isset($_GET["action"]) && $_GET['action'] == "inscription") {
  $utiController->inscription();
}

// ajout d'un nouveau utilisateur dans la base
// --> au clic sur le bouton "inscription" du form d'inscription
if (isset($_POST["inscription"])) {
  $utiController->utilisateurInscription();
}

// affichage de la page mes projets
// --> au clic sur le bouton "mes projets" du menu
if (isset($_GET["action"]) && $_GET['action'] == "mesprojets") {
  $projController->mesProjets($_SESSION['idutilisateur']);
}

// affichage de la page détails
// --> au clic sur le bouton "détails" de la page mes projets
if (isset($_GET["action"]) && $_GET['action'] == "details") {
  $projController->details();
}

// affichage de la page profil
// --> au clic sur le boute "profil"(avatar) du menu
if (isset($_GET["action"]) && $_GET['action'] == "profil") {
  $utiController->monProfil($_SESSION['idutilisateur']);
}

// envoyer un commentaire
// --> au clic sur le bouton "envoyer_comm" de la page détails
if (isset($_POST["envoyer_comm"])) {
  $projController->ajoutCommentaire();
}

// envoyer une note
// --> au clic sur le bouton "envoyer_note" de la page détails
if (isset($_POST["envoyer_note"])) {
  $projController->ajoutNote();
}


// ============================== admin ==================


// affichage de la page gestion utilisateurs
// --> au clic sur le bouton "utiadmin" du menu
if (isset($_GET["action"]) && $_GET['action'] == "utiadmin") {
  $utiController->adminUti($_SESSION['idutilisateur']);
}

// ajouter un utilisateur depuis la page admin
// --> au clic sur le bouton "ajout_uti" du formulaire
if (isset($_POST["ajout_uti"])) {
  $utiController->adminAjoutUti();
}

// supprimer un utilisateur depuis la page admin
// --> au clic sur le bouton "ajout_uti" du formulaire
if (isset($_POST["suppr_uti"])) {
  $utiController->supprimerUti();
}

// affichage de la page gestion des contextes
// --> au clic sur le bouton "contexteadmin" du menu
if (isset($_GET["action"]) && $_GET['action'] == "contexteadmin") {
  $contexteController->adminContexte($_SESSION['idutilisateur']);
}

// ajouter un contexte depuis la page admin
// --> au clic sur le bouton "ajout_uti" du formulaireu
if (isset($_POST["ajout_contexte"])) {
  $contexteController->adminAjoutContexte();
}

// supprimer un contexte depuis la page admin
// --> au clic sur le bouton "ajout_uti" du formulaire
if (isset($_POST["suppr_contexte"])) {
  $contexteController->supprimerContexte();
}

// affichage de la page gestion des catégories
// --> au clic sur le bouton "cateadmin" du menu
if (isset($_GET["action"]) && $_GET['action'] == "cateadmin") {
  $categorieController->adminCategorie();
}

// ajouter une catégorie depuis la page admin
// --> au clic sur le bouton "ajout_cate" du formulaire
if (isset($_POST["ajout_cate"])) {
  $categorieController->adminAjoutCategorie();
}

// supprimer une catégorie depuis la page admin
// --> au clic sur le bouton "suppr_cate" du formulaire
if (isset($_POST["suppr_cate"])) {
  $categorieController->supprimerCategorie();
}

// affichage de la page mes projets
// --> au clic sur le bouton "modifier_profil" du menu
if (isset($_POST["modifier_profil"])) {
  $utiController->modUtilisateur($_SESSION['idutilisateur']);
}



