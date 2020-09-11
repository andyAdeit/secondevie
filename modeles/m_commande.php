<?php 

///////////////// Connexion à la base de données///////////////////////
require_once('./modeles/m_bdd.php');     
require_once ('./classes/myQuery.php');
require_once ('./classes/templateRest.php');


function ajouter_commande($idcli,$prix_commande,$nbre_photo_num,$prix_n,$nbre_photo,$nbre_paquet,$prix_paquet,$nbre_photo_u,$prix_photo_u,$codeP)
{
$conn = bdd();
$jour = date("d");   
$mois = date("m");   
$annee = date("Y");    
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
$newclient = $conn->prepare("INSERT INTO commande (id_client,prix_commande,nbre_photo_numerique,prix_photo_n,nbre_photo,nbre_paquet,prix_paquet,nbre_photo_u,prix_photo_u, statut_commande, idPromo,date) 
							VALUES ($idcli,$prix_commande,$nbre_photo_num,$prix_n,$nbre_photo,$nbre_paquet,
							$prix_paquet,$nbre_photo_u,$prix_photo_u,'1', $codeP,$date)");
$newclient->execute();
}


function dernierecommande(){	
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT * from commande order by id_commande desc limit 1');
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom;
}

function mescommandes($id){
	/*$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT * from commande where id_client = ? order by id_commande desc');
	$dernierecomm-> execute(array($id));
	$laderniercom = $dernierecomm->fetchAll();
	$conn=null;
	return $laderniercom;
*/
	$conditions = array();
	$order = array();
	array_push($conditions, array('nameChamps'=>'id_client','type'=>'=','name'=>'idCli','value'=>$id));
	array_push($order, array('nameChamps'=>'id_commande','sens'=>'desc'));
	$req =  new myQueryClass('commande',$conditions,$order);
	$r = $req->myQuerySelect();
		$success = true;
		$data['commandes'] = $r;
		return reponse_json($success, $data);
}

/**
 * @return array : Retourne toutes les commandes du sites
 */
function allCommandes(){
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT  *, commande.date as commandeDate from commande inner join client on client.ID_CLIENTS = commande.id_client order by id_commande desc');
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetchAll();
	$conn=null;
	return $laderniercom;
}


function photo_commande_p($id){
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT * from photos  where id_commande = ? and choix = 1 ');
	$dernierecomm-> execute(array($id));
	$laderniercom = $dernierecomm->fetchAll();
	$conn=null;
	return $laderniercom;
}

function photo_commande_n($id){
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT * from photos  where id_commande = ? and choix = 2  ');
	$dernierecomm-> execute(array($id));
	$laderniercom = $dernierecomm->fetchAll();
	$conn=null;
	return $laderniercom;
}


function premiere_commandemois(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$dernierecomm = $conn->prepare("SELECT * from commande where date >= $date order by id_commande asc   limit 1 ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom['id_commande'];
}

///////////////////////////////////////

function modifStatutCommande($statutCommande,$idCommande){
    $conn = bdd();
    $modifcli = $conn->prepare('UPDATE commande SET statut_commande=?  WHERE id_commande = ?;');
    $modifcli->execute(array($statutCommande,$idCommande));
    $conn = null ; //Quitte la connexion
    return "Le statut de la commande à été changé!";
}

/**
 * @return mixed Retourne le nombre photo numérique d'une commande
 */
function sum_photo_mois_num(){
	$conn=bdd();
	$id = premiere_commandemois();
	$dernierecomm = $conn->prepare("SELECT sum(qte_photo) as nbre from photos where choix ='2' and id_commande >='$id'");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom['nbre'];
}


/**
 * @return mixed Retourne le nombre photo papier des commande à partir du premier jour du mois
 */
function sum_photo_mois_papier(){
	$conn=bdd();
	$id = premiere_commandemois();
	$dernierecomm = $conn->prepare("SELECT sum(qte_photo) as nbre from photos where choix ='1' and id_commande >='$id'");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom['nbre'];
}


/**
 * @return mixed Retourne les infos d'une commande
 */
function commandeParId($id){
    $conn=bdd();
    $dernierecomm = $conn->prepare("SELECT * from commande where id_commande ='$id'");
    $dernierecomm-> execute();
    $lacommande = $dernierecomm->fetch();
    $conn=null;
    return $lacommande;
}

function sum_photo_num(){
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT sum(qte_photo) as nbre from photos where choix = 2');
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom['nbre'];
}

function sum_photo_papier(){
	$conn=bdd();
	$dernierecomm = $conn->prepare('SELECT sum(qte_photo) as nbre from photos where choix = 1');
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	return $laderniercom['nbre'];
}


function nbre_commandemois(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT count(*) as count from commande where date >= $date   and date <= $date_m  ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}

function nbre_commandemois_papier(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT count(*) as count from commande inner join photos on photos.id_commande = commande.id_commande  where date >= $date   and date <= $date_m and choix = 1");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}



function nbre_commandemois_num(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT count(*) as count from commande inner join photos on photos.id_commande = commande.id_commande  where date >= $date   and date < $date_m and choix = 2 ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}


function chiffre_commandemois_papier(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT sum(prix_commande) as count from commande where date >= $date   and date < $date_m ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}


function chiffre_inscrit_mois(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT count(*) as count from client where date >= $date   and date <= $date_m ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}


function chiffre_connecte_mois(){
	$conn=bdd();
	$jour =  1;  
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 12 ; $i++)
	{	
	$date_m = strtotime("+ 1 months",$date);
		$laderniercom = 0; 
		$conn=bdd();
		$date_format = date('d/m/Y', $date);
	$dernierecomm = $conn->prepare("SELECT count(*) as count from client where date_connecte >= $date   and date_connecte < $date_m ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 months",$date);
	$date_format = date('d/m/Y', $date);

	
	}
	return $commande;
}

function chiffre_connecte_jour(){
	$conn=bdd();
	$jour =  date("d"); 
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 31 ; $i++)
	{	
		$date_m = strtotime("+ 1 days",$date);
		$laderniercom = 0; 
		$conn=bdd();
	$dernierecomm = $conn->prepare("SELECT count(*) as count from client where date_connecte >= $date   and date_connecte < $date_m ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$jour_format = date('d', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$jour_format." ".$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 days",$date);
	$date_format = date('d/m/Y', $date);

	}
	return $commande;
}
function chiffre_inscrit_jour(){
	$conn=bdd();
	$jour =  date("d"); 
	$mois =  date("m");  
	$annee =  date("Y");  
	$date = mktime(0, 0, 0, $mois,  $jour, $annee);
	$commande = array();
	for($i=1; $i <= 31 ; $i++)
	{	
		$date_m = strtotime("+ 1 days",$date);
		$laderniercom = 0; 
		$conn=bdd();
	$dernierecomm = $conn->prepare("SELECT count(*) as count from client where date >= $date   and date < $date_m ");
	$dernierecomm-> execute();
	$laderniercom = $dernierecomm->fetch();
	$conn=null;
	$mois_format = date('m', $date);
	$jour_format = date('d', $date);
	$annee_format = date('Y', $date);
	if($mois_format == '01') { $mois_format = "Janvier"; }
	if($mois_format == '02') { $mois_format = "Février"; }
	if($mois_format == '03') { $mois_format = "Mars"; }
	if($mois_format == '04') { $mois_format = "Avril"; }
	if($mois_format == '05') { $mois_format = "Mai"; }
	if($mois_format == '06') { $mois_format = "Juin"; }
	if($mois_format == '07') { $mois_format = "Juillet"; }
	if($mois_format == '08') { $mois_format = "Août"; }
	if($mois_format == '09') { $mois_format = "Septembre"; }
	if($mois_format == '10') { $mois_format = "Octobre"; }
	if($mois_format == '11') { $mois_format = "Novembre"; }
	if($mois_format == '12') { $mois_format = "Décembre"; }
	$commande[$i] = array($laderniercom,$jour_format." ".$mois_format." ".$annee_format) ;
	
	$date = strtotime("- 1 days",$date);
	$date_format = date('d/m/Y', $date);

	}
	return $commande;
}



function chiffre_inscritTot(){
    $conn=bdd();
        $nbreTotal = $conn->prepare("SELECT count(*) as nbre from client");
        $nbreTotal-> execute();
        $nbreTotal = $nbreTotal->fetch();
    return $nbreTotal['nbre'];
}