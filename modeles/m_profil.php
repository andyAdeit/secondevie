<?php 

///////////////// Connexion à la base de données///////////////////////
require_once('./modeles/m_bdd.php');     
require_once ('./classes/myQuery.php');
require_once ('./classes/templateRest.php');


//////////////// AJOUT d'un profil /////////////////////////
function addProfil($tailleH,$tailleB,$genre,$idImg,$idClient,$nomProfil){
    $conn = bdd();
    $conditions = array();
	array_push($conditions, array('nameChamps'=>'idClient','name'=>'idClient','value'=>$idClient));
	array_push($conditions, array('nameChamps'=>'genre','name'=>'genre','value'=>$genre));
    array_push($conditions, array('nameChamps'=>'tailleBasProfil','name'=>'tailleBasProfil','value'=>$tailleB));
	array_push($conditions, array('nameChamps'=>'tailleHautProfil','name'=>'tailleHautProfil','value'=>$tailleH));
	array_push($conditions, array('nameChamps'=>'nomProfil','name'=>'nomProfil','value'=>$nomProfil));
    array_push($conditions, array('nameChamps'=>'idAvatar','name'=>'idAvatar','value'=>$idImg));
    $req =  new myQueryClass('profil',$conditions);
	$r = $req->myQueryInsert();
    $conn = null ; //Quitte la connexion
    return $r= '';

}


function updateProfil($tailleBasProfil,$tailleHaut,$idProfil)
{
	$conditions = array();
	$values = array();
	array_push($conditions, array('nameChamps'=>'idProfil','type'=>'=','name'=>'idProfil','value'=>$idProfil));
	array_push($values, array('nameChamps'=>'tailleBasProfil','name'=>'tailleBasProfil','value'=>$tailleBasProfil));
	array_push($values, array('nameChamps'=>'tailleHautProfil','name'=>'tailleHautProfil','value'=>$tailleHaut));
	$req =  new myQueryClass('profil',$conditions,'',$values);
	$r = $req->myQueryUpdate();
	$conn = null ; //Quitte la connexion
	
}


function mesProfils($id){
	$conditions = array();
	array_push($conditions, array('nameChamps'=>'idClient','type'=>'=','name'=>'idClient','value'=>$id));
	$req =  new myQueryClass('profil',$conditions);
	$r = $req->myQuerySelect();
	return $r;
}


function monProfil($id){
	$conditions = array();
	array_push($conditions, array('nameChamps'=>'idProfil','type'=>'=','name'=>'idProfil','value'=>$id));
	$req =  new myQueryClass('profil',$conditions);
	$r = $req->myQuerySelect();
	return $r[0];
}

// verifier si le profil correspond à l'utilisateur 
function verifProfilById($idProfil){
	$conditions = array();
	array_push($conditions, array('nameChamps'=>'idProfil','type'=>'=','name'=>'idProfil','value'=>$idProfil));
	$req =  new myQueryClass('profil',$conditions);
	$r = $req->myQuerySelect();
	if(isset($r[0]['idClient'])){
	if($r[0]['idClient'] == $_SESSION['id']){
		return true;
	}else{
		return false;
	}}else{
		return false;
	}
}	

// Supprime le profil
function deleteProfil($id){
	$conditions = array();
    array_push($conditions, array('nameChamps'=>'idProfil','type'=>'=','name'=>'idProfil','value'=>$id));
    $req =  new myQueryClass('profil',$conditions);
	$r = $req->myQueryDelete();
	$conn = null ; //Quitte la connexion
}