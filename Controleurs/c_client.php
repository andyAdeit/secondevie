<?php
require_once "./modeles/m_clients.php";
require_once "./modeles/m_commande.php";
require_once('modeles/m_statutCommande.php');
require_once('modeles/m_modeLivraison.php');
require_once('modeles/m_profil.php');
require_once('modeles/m_genre.php');
require_once('modeles/m_taille.php');
require_once('modeles/m_produit.php');
require_once('modeles/m_avatar.php');
require_once "./assets/inc/function.php";

if(isset($_GET['action']))
	$action = $_GET['action'];
else{
	$action = "profil";
}
switch ($action){
	case 'profil' :
	if(isset($_SESSION['id']))
	{
		if(isset($_SESSION['id'])){
				$id = $_SESSION['id'];
		} else {
				$id = isset($_GET['ajx']);
		}
		$cli = informationsRest($id);
		if(!isset($_GET['ajx'])){ //appel normal
				$cli = json_decode($cli);
				$cli = $cli->result->clients[0];
				$mesProfils = mesProfils($id);
				$allGenre =  allGenre();
				$tailleHaut = tailleHaut();
				$taillePantalon = taillePantalon();
				$allAvatar = allAvatar();
				require_once('vues/v_profil.php');
		} else	{ // Appel Ajax
				appelAjax($cli);
		}	
	}else{
		redirectUrl("index.html");
	}
	break;

	///////////////////////////////////////////////////////////////////
	///////////////// 		PROFIL
	///////////////////////////////////////////////////////////////////
	// Ajoute un profil
	case 'addProfil':
		addProfil($_POST['tailleHClient'],$_POST['taillePClient'],$_POST['genreClient'],$_POST['imgAvatarForm'],$_SESSION['id'], $_POST['prenom']); // ajoute l'avatar
		redirectUrl("index.php?c=profil&partie=foyer");
	break;


	case 'editProfil':
		if(verifProfilById($_POST['idProfil'])){
			updateProfil($_POST['tailleB'],$_POST['tailleH'],$_POST['idProfil']);
		}
		echo reponse_json(true,'','Le profil est bien modifié!');
	break;

	case 'supprimerprofil':
		if(verifProfilById($_POST['id'])){ // verifie si le profil est à lui
			deleteProfil($_POST['id']);
		}
		echo reponse_json(true,'','Le profil est bien supprimé!');
	break;



	case 'mescommandes' : 
	if(isset($_SESSION['id']))
	{  
		$commandes= mescommandes($_SESSION['id']);
		if(!isset($_GET['ajx'])){ //appel normal
			$commandes = json_decode($commandes);
			$commandes = $commandes->result;
			require_once('vues/v_mescommandes.php');
		} else	{ // Appel Ajax
			appelAjax($commandes);
		}	
	}else{
		redirectUrl("index.html");
	}
	break;
	
	case 'confirmLivraison':
		$commande = voirCommandeToken($_GET['id']);
		if($commande != false){ // si elle existe 
			if($commande['statutCommande'] <2){ // si y a pas de rendez-vous
				require_once('./vues/v_confirmLivraison.php');
			}else{
				redirectUrl('index.php?c=profil&action=macommande&id='.$_GET['id']);
			}
		}else{
			redirectUrl("index.html");
		}
	break ;

	case 'validLivraison':
		$commande = voirCommandeToken($_POST['id']);
		if($commande != false){ // si elle existe 
			updateStatutCommande($commande['idCommande'],2);
			redirectUrl('index.php?c=profil&action=macommande&id='.$_POST['id']);
		}else{
			redirectUrl("index.html");
		}
	break ;

	case 'macommande' :
	if(isset($_SESSION['id'])){    
		$commande = voirCommandeToken($_GET['id']);
		if($commande != false){ // si elle n'existe pas
			$allStatutCommande = allStatutCommande(); // récupère tout les statuts
			$statutCommande = statutCommande($commande['statutCommande']); // le statut de la commande
			$modeLivraison = modeLivraison($commande['modeLivraison']); // le mode de livraison de la commande
			$produits = voirProduitByCommande($commande['idCommande']); // tout les produits de la commande
			// recuperer les produits de la commande    
			require_once('./vues/v_commande.php');
		} else{
			redirectUrl("index.html");
		}	
	} else{
		redirectUrl("index.html");
	}	

	break;
	

	/*** retourne tout les clients json */
	case 'allclient': 
		if(adminexist() == 0){
		redirectionNonAdmin(adminexist());
		} else {
		appelAjax(allClient($_GET['term']));
		}
	break;

	case 'modif' : 
	$message = modifclient($_POST['adresse'],$_POST['cp'],$_POST['ville'],$_POST['telephone']);	
	echo reponse_json(true,$message,'Vos informations sont modifiés!');
	break;

	//Modifier le mot de passe avec un token
	case 'modifMdpToken' : 
	$message = updateMdpClient($_POST['token'],$_POST['mdp']);
	$rep = reponse_json(true, $message , 'Votre mot de passe est réinitialisé, vous pouvez désormais vous connecter!');
	appelAjax($rep);
	break;

	// Action en ajax qui permet de mettre un token dans la bdd pour réinitialiser le mot de passe
	case 'reinitMdp' : 
		$token = updateTokenClient($_POST['email']);
		$configSite = initConfigSite();
		initParamBoolSite($configSite);
		$mail = mailMdpClient($configSite,$token,$_POST['email']);
		echo reponse_json(true,'','Nous vous avons envoyé un mail de réinitialisation de votre mot de passe!');
	break;

	// réinitialiser le mot de passe
	case 'formMdp' : 
		$client = getClientToken($_GET['token']);
		if(isset($client[0])){
			require_once('vues/v_reinitMdp.php');
		}else{
			redirectUrl('index.php?c=connexion&action=oublie');
		}
		
	break;


	//Test si mail existe
	case 'verifMail' : 
	if(isset($_GET['ajx'])){
		$erreur = userMailExist($_GET['email']);
		appelAjax($erreur);
	}
	break;

	default :
	if((isset($_SESSION['id']) && isset($_GET['ajx'])))
	{  
		$cli = informationsRest($_SESSION['id']);
		if(!isset($_GET['ajx'])){ //appel normal
			$cli = json_decode($cli);
			$mesProfils = mesProfils($id);
			require_once('vues/vue_profil.php');
		} else	{ // Appel Ajax
			appelAjax($cli);
		}
	}else{
		redirectUrl("index.html");
	}
        break;
}

?>