<?php
  session_start();  // utilisation des sessions

  include "connect.php"; // connexion au SGBD
  include "moteurtemplate.php";
  include "Controllers/projetsController.php";
  include "Controllers/utilisateursController.php";

  $projController = new ProjetController($bdd, $twig);
  $utiController = new UtilisateurController($bdd, $twig);

  // ============================== connexion / deconnexion - sessions ==================
  $message = "";
  // si la variable de session n'existe pas, on la crée
if(!isset($_SESSION['acces'])){
  $_SESSION['acces']="non";
}

  // click sur le bouton connexion 
  if (isset($_POST["connexion"])) {    
   
    $utilisateur = $utiController->utilisateurConnexion($_POST);
    
  }


  // deconnexion : click sur le bouton deconnexion
  if (isset($_GET["action"]) && $_GET['action']=="logout") { 

    $utilisateur = $utiController->utilisateurDeconnexion();
    
  } 

  if (!isset($_GET["action"]) && empty($_POST)) {
    echo $twig->render('accueil.html.twig', array('acces' => $_SESSION['acces']));
  }

echo $message;

// ============================== connexion / deconnexion - sessions ==================

// formulaire de connexion
if (isset($_GET["action"])  && $_GET["action"]=="login") {
  $utiController->utilisateurFormulaire(); 
}

// ============================== itineraires ==================

// liste des itinéraires dans un tableau HTML
//  https://.../index/php?action=liste
if (isset($_GET["action"]) && $_GET["action"]=="projets") {
  $projController->listeProjets();
}

// liste de mes itinéraires dans un tableau HTML
if (isset($_GET["action"]) && $_GET["action"]=="mesprojets") {
 // $itiController->listeMesItineraires(?? du membre connecté);
}

// formulaire ajout d'un itineraire : saisie des caractéristiques à ajouter dans la BD
//  https://.../index/php?action=ajout
// version 0 : l'itineraire est rattaché automatiquement à un membre déjà présent dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout") {
  $projController->formAjoutProjet();
 }

// ajout de l'itineraire dans la base
// --> au clic sur le bouton "valider_ajout" du form précédent
if (isset($_POST["ajoutproj"])) {
  $projController->ajoutProjet();
}


// supression d'un itineraire dans la base
// --> au clic sur le bouton "valider_supp" du form précédent
if (isset($_POST["action"]) && $_POST["action"]=="supprimer_projet") { 
  $projController->supprimerProjet($_POST['idprojet']);
}

// modification d'un itineraire : choix de l'itineraire
//  https://.../index/php?action=modif
if (isset($_GET["action"]) && $_GET["action"]=="modif") { 
 // $itiController->choixModItineraire( ?? du membre connecté );
}

// modification d'un itineraire : saisie des nouvelles valeurs
// --> au clic sur le bouton "saisie modif" du form précédent
//  ==> version 0 : pas modif de l'iditi ni de l'idmembre
if (isset($_POST["saisie_modif"])) {   
  $projController->saisieModProjet();
}

//modification d'un itineraire : enregistrement dans la bd
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $projController->modProjet();
}

// recherche d'itineraire : saisie des critres de recherche dans un formulaire
//  https://.../index/php?action=recherc
if (isset($_GET["action"]) && $_GET["action"]=="recher") {
  $projController->formRechercheProjet();
}

// recherche des itineraires : construction de la requete SQL en fonction des critères 
// de recherche et affichage du résultat dans un tableau HTML 
// --> au clic sur le bouton "valider_recher" du form précédent
if (isset($_POST["valider_recher"])) { 
  $projController->rechercheProjet();
}


if (isset($_GET["action"]) && $_GET['action']=="accueil") { 

  $projController->projAccueil();
  
} 

if (isset($_GET["action"]) && $_GET['action']=="inscription") { 

  $utiController->inscription();
} 

if (isset($_POST["inscription"])) { 
  $utiController->utilisateurInscription();
}

if (isset($_GET["action"]) && $_GET['action']=="mesprojets") { 

  $projController->mesProjets($_SESSION['idutilisateur']);
  
} 

if (isset($_GET["action"]) && $_GET['action']=="details") { 

  $projController->details();
  
} 

if (isset($_GET["action"]) && $_GET['action']=="profil") { 

  $utiController->monProfil();
  
} 





?>
  </div>	

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>