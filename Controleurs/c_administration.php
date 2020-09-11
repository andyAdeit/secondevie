<?php
require_once "./modeles/m_bdd.php";
require_once "./modeles/m_clients.php";
require_once "./modeles/m_commande.php";
require_once "./modeles/m_codepromo.php";
require_once "./modeles/m_panier.php";
require_once "./modeles/m_produit.php";
require_once "./modeles/m_module.php";
require_once "./assets/inc/function.php";
$conn = bdd();

if(isset($_GET['action']))
{
	$action = $_GET['action'];
}
else
	$action = 'accueil';


switch($action)
{
    case 'accueil':
    redirectionNonAdmin(adminexist($_SESSION['mail']));
    $nbEve = count(voir_tous_evenement()); //nb evenements
    $nbCommande = count(allCommandes()); // nb de commande 
	include('./vues/administration/v_admin_def.php');
	break;
	
    case 'addproduit':
    include('./vues/administration/v_addproduit.php');
    break;  

    case 'addproduitValide':
    $numDerProduit = voirDernierProduit(); // récupère dernier id produit
    $numDerProduit = $numDerProduit['id'] +1;
    $dossier = "./assets/img/produits/$numDerProduit/"; //crée le dossier du  
    mkdir($dossier);
    $img1 = $_FILES['img1']['tmp_name'];
    $img2 = $_FILES['image2']['tmp_name'];
    move_uploaded_file($img1, $dossier.$_FILES['img1']['name']);
    move_uploaded_file($img2, $dossier.$_FILES['image2']['name']);
    addproduit($_POST['nomProduit'],$_POST['marqueProduit'],$_POST['prixProduit'],$_POST['etatProduit'],$_POST['tailleProduit'],$_POST['categorieProduit'],'./assets/img/produits/'.$numDerProduit.'/'.$_FILES['img1']['name'],'./assets/img/produits/'.$numDerProduit.'/'.$_FILES['image2']['name'],$_POST['description'],$_POST['sousCategorieProduit']);
    $errorSuccess = "Le produit est ajouté!";
    include('./vues/administration/v_addproduit.php');
    break;    
   

   
}


?>