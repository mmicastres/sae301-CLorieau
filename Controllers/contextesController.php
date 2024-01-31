<?php
include "Modules/contexte.php";

include "Models/contextesManager.php";

/**
 * Définition d'une classe permettant de gérer les contextes
 *   en relation avec la base de données	
 */
class ContexteController
{
    private $contexteManager; // instance du manager
    private $twig;


    /**
     * Constructeur = initialisation de la connexion vers le SGBD
     */
    public function __construct($db, $twig)
    {

        $this->contexteManager = new ContexteManager($db);

        $this->twig = $twig;

    }

    /**
     * affichage contextes sur la page admin
     * @param $idtilisateur
     * @return rien
     */
    function adminContexte($idutilisateur)
    {

        $contextes = $this->contexteManager->getContexteSuppr($idutilisateur);
        echo $this->twig->render('contexte_admin.html.twig', array('contextes' => $contextes, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
    }

    /**
     * ajout d'un contexte par l'admin
     * @param aucun
     * @return rien
     */
    public function adminAjoutContexte()
    {
        $contexte = new Contexte($_POST);
        $ok = $this->contexteManager->ajoutContexte($contexte);
        $contextes = $this->contexteManager->getContexteSuppr();
        $message = $ok ? "Contexte ajouté avec succès" : "Problème lors de l'ajout du contexte";
        echo $this->twig->render('contexte_admin.html.twig', array('contextes' => $contextes, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));

    }

    /**
     * supression d'un contexte par l'admin
     * @param aucun
     * @return rien
     */
    public function supprimerContexte()
    {
        $suppcontexte = new Contexte($_POST);
        $okSuppcontexte = $this->contexteManager->supprContexte($suppcontexte);
        $contextes = $this->contexteManager->getContexteSuppr();

        $message = "Contexte supprimé";

        if (!$okSuppcontexte) {
            $message .= "Erreur lors de la suppression du contexte";
        }

        echo $this->twig->render('contexte_admin.html.twig', array('contextes' => $contextes, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
    }

}