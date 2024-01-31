<?php
include "Modules/categorie.php";

include "Models/categoriesManager.php";

/**
 * Définition d'une classe permettant de gérer les catégories
 *   en relation avec la base de données	
 */
class CategorieController
{
	private $categorieManager; // instance du manager
	private $twig;


	/**
	 * Constructeur = initialisation de la connexion vers le SGBD
	 */
	public function __construct($db, $twig)
	{

		$this->categorieManager = new CategorieManager($db);

		$this->twig = $twig;

	}

	/**
	 * affichage categories sur la page admin
	 * @param aucun
	 * @return rien
	 */
	function adminCategorie()
	{

		$cates = $this->categorieManager->getCategorieSuppr();
		echo $this->twig->render('categorie_admin.html.twig', array('cates' => $cates, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

	/**
	 * ajout d'une categorie par l'admin
	 * @param aucun
	 * @return rien
	 */
	public function adminAjoutCategorie()
	{
		$cate = new Categorie($_POST);
		$ok = $this->categorieManager->ajoutCategorie($cate);
		$cates = $this->categorieManager->getCategorieSuppr();
		$message = $ok ? "Categorie ajouté avec succès" : "Problème lors de l'ajout de la categorie";
		echo $this->twig->render('categorie_admin.html.twig', array('cates' => $cates, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));

	}

	/**
	 * supression d'une categorie par l'admin
	 * @param aucun
	 * @return rien
	 */
	public function supprimerCategorie()
	{
		$suppcate = new Categorie($_POST);
		$okSuppcate = $this->categorieManager->supprCategorie($suppcate);
		$cates = $this->categorieManager->getCategorieSuppr();

		$message = "Catégorie supprimé";

		if (!$okSuppcate) {
			$message .= "Erreur lors de la suppression de la catégorie";
		}

		echo $this->twig->render('categorie_admin.html.twig', array('cates' => $cates, 'message' => $message, 'acces' => $_SESSION['acces'], 'admin' => $_SESSION['admin'], 'nomuti' => $_SESSION['nomuti']));
	}

}





















