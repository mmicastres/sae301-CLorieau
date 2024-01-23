<?php
/**
 * définition de la classe tag
 */
class Associer
{
    private int $_idprojet;
    private int $_idtag;


    // contructeur
    public function __construct(array $donnees)
    {
        // initialisation d'un produit à partir d'un tableau de données
        if (isset($donnees['idprojet'])) {
            $this->_idprojet = $donnees['idprojet'];
        }
        if (isset($donnees['idtag'])) {
            $this->_idtag = $donnees['idtag'];
        }

    }
    // GETTERS //
    public function idProjet()
    {
        return $this->_idprojet;
    }
    public function idTag()
    {
        return $this->_idtag;
    }


    // SETTERS //
    public function setIdProjet(int $idprojet)
    {
        $this->_idprojet = $idprojet;
    }
    public function setIdTag(int $idtag)
    {
        $this->_idtag = $idtag;
    }



}