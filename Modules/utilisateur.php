<?php
/** 
* définition de la classe itineraire
*/
class Utilisateur {
        private int $_idutilisateur;
        private string $_nom;
        private string $_prenom;
		private string $_idiut;
		private string $_mail;
		private string $_mdp;
		private int $_statut;
		private string $_photoprofil;
		
		
        // contructeur
        public function __construct(array $donnees) {
		// initialisation d'un produit à partir d'un tableau de données
			if (isset($donnees['idutilisateur'])) { $this->_idutilisateur = $donnees['idutilisateur']; }
			if (isset($donnees['nom'])) { $this->_nom = $donnees['nom']; }
			if (isset($donnees['prenom'])) { $this->_prenom = $donnees['prenom']; }
			if (isset($donnees['idiut'])) { $this->_idiut = $donnees['idiut']; }
			if (isset($donnees['mail'])) { $this->_mail = $donnees['mail']; }
			if (isset($donnees['mdp'])) { $this->_mdp = $donnees['mdp']; }
			if (isset($donnees['statut'])) { $this->_statut = $donnees['statut']; }
			if (isset($donnees['photoprofil'])) { $this->_photoprofil = $donnees['photoprofil']; }
        }           
        // GETTERS //
		public function idUtilisateur() { return $this->_idutilisateur;}
		public function nom() { return $this->_nom;}
		public function prenom() { return $this->_prenom;}
		public function idIut() { return $this->_idiut;}
		public function mail() { return $this->_mail;}
		public function mdp() { return $this->_mdp;}
		public function statut() { return $this->_statut;}
		public function photoProfil() { return $this->_photoprofil;}
	
		
		
		// SETTERS //
		public function setIdUtilisateur(int $idutilisateur) { $this->_idutilisateur = $idutilisateur; }
        public function setNom(string $nom) { $this->_nom= $nom; }
		public function setPrenom(string $prenom) { $this->_prenom = $prenom; }
		public function setIdIut(string $idiut) { $this->_idiut = $idiut; }
		public function setMail(string $mail) { $this->_mail = $mail; }
		public function setMdp(string $mdp) { $this->_mdp = $mdp; }
		public function setSexe(int $statut) { $this->_statut = $statut; }
		public function setPhotoProfil(string $photoprofil) { $this->_photoprofil = $photoprofil; }		

    }

?>