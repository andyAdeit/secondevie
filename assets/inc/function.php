<?php

function redirectionNonAdmin($bool){
    if($bool == 0){?>
        <SCRIPT LANGUAGE="JavaScript">
            document.location.href="index.php"
        </SCRIPT>
    <?php }
    else{
    }
}

function redirectUrl($url){
    ?>
        <SCRIPT LANGUAGE="JavaScript">
            document.location.href= <?php echo  json_encode($url);?>
        </SCRIPT>
    <?php }

function ouiOuNon($val){
    if($val == 1){
        $r = 'Oui';
    } else{
        $r = 'non';
    }
    return $r;
}
// Generer l'erreur ou le message à afficher entièrement
function genererError($idModule){
        $module = voirModule($idModule);
        $alert = voirAlert($module['alert']);

        if($module['alert'] == 5){
            echo $alert['part1Alert'].$alert['part2Alert'].$module['texte_module'].$alert['part3Alert'];
        }else{
            echo $alert['part1Alert'].$module['titre_module'].$alert['part2Alert'].$module['texte_module'].$alert['part3Alert'];
        }

}

// Permet de retrouver le prix dans le panier, le total des remises, et les prix avec remises
function totalPrixPanier(){
    $totalPrix = 0;
    $totalRemise = 0;
    //Pour chaque produit
    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
        $totalPrix += $produitPanier->getPrix();
        $totalRemise += ($produitPanier->getPrix() * (1 - $produitPanier->getReduction() )) ;

    }
    $retour = array('totalAvecRemise'=>number_format(($totalRemise ),2),'totalSansRemise'=>number_format($totalPrix,2),'totalRemise'=>number_format($totalPrix - $totalRemise,2));
    return $retour;
}

// Permet de retrouver le  poids du panier
function totalPanierPoids(){
    $totalPoids = 0;
    //Pour chaque produit
    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
        $totalPoids += $produitPanier->getPoids();

    }
    return $totalPoids;
}

function siCheckUrl($valeur,$tableau){
    $retour = false;
    if(isset($_GET[$tableau])){ // si le get existe
    $value = explode(",", $_GET[$tableau]);
        foreach($value as $v){ // pour chaque valeur
            if($valeur == $v){
                return true;
            }
        }
    }
    return $retour;
}

// PERMET DE SAVOIR S'IL EST CONNECTé
function isConnected(){
    $r = false;
    if(isset($_SESSION['id'])){
        $r = true;
    }
    return $r;
}

// Permet de retrouver le prix dans le panier pour chaque catégorie
function totalPrixPanierParCategorie(){
    $allCategorie = allCategorie(); // récupere les catégories
    //Pour chaque catérogies
    $totalPanier = array();
    foreach ($allCategorie as $categorie) {
        //Pour chaque produit du panier
        $totalPrix = 0;
        $totalRemise = 0;
        foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
            if($produitPanier->getCategorie() == $categorie['idCategorie']){
                $totalPrix += $produitPanier->getPrix();
                $totalRemise += ($produitPanier->getPrix() * (1 - $produitPanier->getReduction() )) ;
            }
       
        }
        array_push($totalPanier, array('id'=>$categorie['idCategorie'],'totalAvecRemise'=>number_format(($totalRemise ),2),'totalSansRemise'=>number_format($totalPrix,2),'totalRemise'=>number_format($totalPrix - $totalRemise,2)));
    }

    return $totalPanier;
}


// Permet de retrouver le prix de la commande pour chaque catégorie
function totalPrixCommandeParCategorie($id){
    $allCategorie = allCategorie(); // récupere les catégories
    //Pour chaque catérogies
    $totalPanier = array();
    foreach ($allCategorie as $categorie) {
        //Pour chaque produit du panier
        $totalPrix = 0;
        $totalRemise = 0;
        $produits = voirProduitByCommande($id); // tout les produits de la commande
        foreach ($produits as $produitPanier) {
            if($produitPanier['categorie'] == $categorie['idCategorie']){
                $totalPrix += $produitPanier['prix'];
                $totalRemise += ($produitPanier['prix'] * (1 - $produitPanier['reduction'] )) ;
            }
       
        }
        array_push($totalPanier, array('id'=>$categorie['idCategorie'],'totalAvecRemise'=>number_format(($totalRemise ),2),'totalSansRemise'=>number_format($totalPrix,2),'totalRemise'=>number_format($totalPrix - $totalRemise,2)));
    }

    return $totalPanier;
}


function iconeSelonSexe($idSexe){
    if(($idSexe == 2) || ($idSexe == 4) ){
        $r = '<i class="fas fa-lg rose fa-female"></i>';
    }else{
        $r = '<i class="fas fa-lg blue fa-male"></i>';
    }
    return $r;

}

function prixReel($prix){
    $margeFixe = 0.25;
    $margePourcent = 0.014;
    $prixReel = ($prix * (1-$margePourcent)) - $margeFixe;
    return $prixReel;

}

function appelAjax($data){
	header('Content-Type: application/json');
	echo $data;
}


//Savoir si y a des produits dans les panier
function voirNbPanier(){
    $nbProd = count($_SESSION['panier']->getCollection());
    if($nbProd > 0){
        return $nbProd;
    }else{
        return false;
    }
}

// Verifie les réservations dans le panier si ça fait plus d'une heure qu'il est réservé
function supprReservationProduitPanier(){
    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
        $produit = voirProduitById($produitPanier->getId());
        
        $year = substr($produit['dateReservation'], 0, -15);   
        $month = substr($produit['dateReservation'], 5, 2);   
        $day = substr($produit['dateReservation'], 8,2); 
        $heure = substr($produit['dateReservation'], 11,2); 
        $minute = substr($produit['dateReservation'], 14,2);     
    
        // récupère la date du jour
        $date_string = mktime($heure,$minute,0,$month,$day,$year);
        $dateTimeStamp = ($date_string +3600);
        $difference = (time() + 3600) - $dateTimeStamp; // diffrence entre les deux heures
        var_dump($difference);
        if($difference >= 3600){ // temps de réservation au dessus d'une heure
            $_SESSION['panier']->supprimer($produitPanier->getId()); // supprime le produit du panier
            changeProduitStatut($produitPanier->getId(),'0',null,null,null);
        }
    }
}


// Verifie les réservations dans le panier si ça fait plus d'une heure qu'il est réservé
function tempsRestantReservation($id){
        $produit = voirProduitById($id);
        $year = substr($produit['dateReservation'], 0, -15);   
        $month = substr($produit['dateReservation'], 5, 2);   
        $day = substr($produit['dateReservation'], 8,2); 
        $heure = substr($produit['dateReservation'], 11,2); 
        $minute = substr($produit['dateReservation'], 14,2);     
    
        // récupère la date du jour
        $date_string = mktime($heure,$minute,0,$month,$day,$year);
        $dateTimeStamp = ($date_string +3600);
        $dateActuelle = (time() + 3600);
        $d = $dateActuelle - $dateTimeStamp;
        $tempsRestant = (60 - round($d/60));
        return $tempsRestant; 
}



// Quand se connecte réaffecte les produit reserve à lui 
function associerProduitAuPanier($id){
    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
        changeProduitStatut($produitPanier->getId(),'1',null,$_SESSION['id'],'false');
    }

}

function videReservation(){
    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {
        changeProduitStatut($produitPanier->getId(),'0',null,null,null);
    }
}



// FUNCTION qui sert à envoyer des mails : 
// si bdd = null : Prendre message, sinon prendre le message correspondant à l'id dans messsage
function envoieMail($configSite,$sujet,$mail,$message_txt,$message_html, $bdd = null){
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
    {
        $passage_ligne = "\r\n";
    }
    else{
        $passage_ligne = "\n";
    }
    //=====Création de la boundary
    $boundary = "-----=".md5(rand());
    //==========
    
    //=====Création du header de l'e-mail.
    $header = "From: \"Deuxièmevie\"<".$configSite->emailSite.">".$passage_ligne;
    $header.= "Reply-to: \"Deuxiemevie\"<".$configSite->emailSite.">".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    //==========
    
    //=====Création du message.
    $message = $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format texte.
    $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_txt.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary.$passage_ligne;
    //=====Ajout du message au format HTML
    $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_html.$passage_ligne;
    //==========
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    //==========
    
    //=====Envoi de l'e-mail.
    mail($mail,$sujet,$message,$header);
    //==========
    }



function mailCommandeClient($configSite,$commande,$produits){

$sujet = 'Votre commande est validée!';
$mailClient = $_SESSION['mail']; // Déclaration de l'adresse de destination.
$tel = $_SESSION['tel'] ;

if($commande['modeLivraison'] == 1){
$messageInfo = 'Merci votre commande est un succès ! <br>Après avoir pris un rendez-vous pour la livraison à domicile, vous allez recevoir un mail de confirmation de votre livraison.<br>
Le statut de votre commande sur notre site se mettra à jour d\'ici quelques heure.';

}else{
    $messageInfo = 'Merci votre commande est un succès !<br> Votre commande va être traitée dans les plus brefs délai par notre équipe.<br>';
}

$tableauProduit = '<table><tr><td>Produit</td><td>Prix</td></tr>';

foreach($produits as $produit){
    $tableauProduit.='<tr><td>'.$produit['nom'].'</td><td>'.$produit['prix'] * $produit['reduction'].'</td><tr>';
}


//=====Déclaration des messages au format texte et au format HTML.
$message_txt = "Bonjour, Voici le récapitulatif de votre commande ! ";
$message_html = "<html><head></head><body><b>Bonjour,</b><br>".$messageInfo."
Voici un résumé des informations de votre commande : <br>
Email : $mailClient <br>
Tel : $tel <br>
Prix de la commande : ".$commande['prixCommande']." 	&#8364;<br>

Voici les produits achetés :
<table>
$tableauProduit	
</table>


 </body></html>";

 envoieMail($configSite,$sujet,$mailClient,$message_txt,$message_html);
}


function mailMdpClient($configSite,$token,$mailClient){

    $sujet = 'Votre commande est validée!';
    $lien =  'https://unedeuxiemevie.fr/index.php?c=profil&action=formMdp&token='.$token;
    
    
    //=====Déclaration des messages au format texte et au format HTML.
    $message_txt = "Bonjour, voici le lien pour réinitialiser votre mot de passe pour ainsi accéder à unedeuxiemevie.fr:".$lien;
    $message_html = "<html><head></head><body><b>Bonjour,</b><br>, voici le lien pour réinitialiser votre mot de passe pour ainsi accéder à unedeuxiemevie.fr:
    ".$lien;
    var_dump($message_html);
     envoieMail($configSite,$sujet,$mailClient,$message_txt,$message_html);
    }
    


// Function qui permet de créer un token
function genererChaineAleatoire($longueur = 10)
{
 $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 $longueurMax = strlen($caracteres);
 $chaineAleatoire = '';
 for ($i = 0; $i < $longueur; $i++)
 {
 $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
 }
 return $chaineAleatoire;
}
?>

