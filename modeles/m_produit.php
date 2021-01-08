<?php 

///////////////// Connexion à la base de données///////////////////////
require_once('./modeles/m_bdd.php');     
require_once ('./classes/myQuery.php');
require_once ('./classes/templateRest.php');

//////////////// AJOUT d'un client /////////////////////////
function addproduit($nom,$marque,$prix,$etat,$taille,$categorie,$image1,$image2,$description,$sousCategorie,$genre,$reduc){
    $conn = bdd();
 
    $conditions = array();
    array_push($conditions, array('nameChamps'=>'nom','name'=>'nom','value'=>$nom));
    array_push($conditions, array('nameChamps'=>'marque','name'=>'marque','value'=>$marque));
    array_push($conditions, array('nameChamps'=>'prix','name'=>'prix','value'=>$prix));
    array_push($conditions, array('nameChamps'=>'etat','name'=>'etat','value'=>$etat));
    array_push($conditions, array('nameChamps'=>'taille','name'=>'taille','value'=>$taille));
    array_push($conditions, array('nameChamps'=>'categorie','name'=>'categorie','value'=>$categorie));
    array_push($conditions, array('nameChamps'=>'image1','name'=>'image1','value'=>$image1));
    array_push($conditions, array('nameChamps'=>'image2','name'=>'image2','value'=>$image2));
    array_push($conditions, array('nameChamps'=>'description','name'=>'description','value'=>$description));
    array_push($conditions, array('nameChamps'=>'sousCategorie','name'=>'sousCategorie','value'=>$sousCategorie));
    array_push($conditions, array('nameChamps'=>'genre','name'=>'genre','value'=>$genre));
    array_push($conditions, array('nameChamps'=>'reduction','name'=>'reduction','value'=>$reduc));
    array_push($conditions, array('nameChamps'=>'etatDuProduit','name'=>'etatDuProduit','value'=>'0'));
    $req =  new myQueryClass('produit',$conditions);
	$r = $req->myQueryInsert();
    $conn = null ; //Quitte la connexion

}


//function retrouve le dernier produit 
function voirDernierProduit(){
$order = array();
array_push($order, array('nameChamps'=>'id','sens'=>'desc'));
$req =  new myQueryClass('produit','',$order);
$r = $req->myQuerySelect();
return $r[0] ;
}


//function retrouve le dernier produit 
function voir10DernierProduit($id = ''){
    $order = array();
    $limit = "limit 3";
    if($id == ''){
        $conditions = '';
    }else {
    $conditions = array();
    array_push($conditions, array('nameChamps'=>'id','type'=>'!=','name'=>'id','value'=>$id));
    array_push($conditions, array('nameChamps'=>'etatDuProduit','type'=>'!=','name'=>'id','value'=>'2'));
    }

    array_push($order, array('nameChamps'=>'id','sens'=>'desc'));
    $req =  new myQueryClass('produit',$conditions,$order,'',$limit);
    $r = $req->myQuerySelect();
    return $r;
    }
    
    
function allProduit(){
	$req =  new myQueryClass('produit');
	$r = $req->myQuerySelect();
	return $r;
}


//voir produit de la commande
function voirProduitByCommande($id){
    $conditions = array();
    array_push($conditions, array('nameChamps'=>'idCommande','type'=>'=','name'=>'idCommande','value'=>$id));
    $req =  new myQueryClass('produit',$conditions);
    $r = $req->myQuerySelect();
    if(count($r)==0){
        $r = false;
    }else{
        $r = $r;
    }
	return $r;
}




//voir produit selon l'id
function voirProduitById($id){
    $conditions = array();
    array_push($conditions, array('nameChamps'=>'id','type'=>'=','name'=>'id','value'=>$id));
    $req =  new myQueryClass('produit',$conditions);
    $r = $req->myQuerySelect();
    if(count($r)==0){
        $r = false;
    }else{
        $r = $r[0];
    }
	return $r;
}


function allProduitByCategorie($id,$get){
    $order = array();
    $conditions = array(); 
    array_push($conditions, array('nameChamps'=>'categorie','type'=>'=','name'=>'categorie','value'=>$id));
    array_push($conditions, array('nameChamps'=>'etatDuProduit','type'=>'!=','name'=>'etatDuProduit','value'=>'2'));
    // pour chaque get
   
    foreach($get as $g => $value){
        if(($g != 'order' ) && ($g != 'prixMin') && ($g != 'prixMax')){
            $value = explode(",", $value);
            $nb = count($value) -1; // nbre de valeur
            $cp = 0;
            foreach($value as $v){
                if($cp == 0){
                    array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v));
                }else{
                    if($nb != $cp){
                        array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v ,'operator'=>'OR'));
                        }else{
                        array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v ,'operator'=>'OR' ));
                        }
                }
            
                $cp++;
            }
        } else{
            if($g == 'order'){
                array_push($order, array('nameChamps'=>'prix','sens'=>$value));
            }
            if($g == 'prixMin'){
                array_push($conditions, array('nameChamps'=> 'prix','type'=>'>=','name'=>'prixMin' ,'value'=> $value));
            }
            if($g == 'prixMax'){
                array_push($conditions, array('nameChamps'=> 'prix','type'=>'<=','name'=>'prixMax' ,'value'=> $value));
            }
            
        
      
        }
       
    }
    
    $req =  new myQueryClass('produit',$conditions,$order);
    $r = $req->myQuerySelect();
	return $r;
}

function countProduitByCategorie($idCategorie,$idSousCategorie){
	$conditions = array();
    array_push($conditions, array('nameChamps'=>'sousCategorie','type'=>'=','name'=>'sousCategorie','value'=>$idSousCategorie));
    array_push($conditions, array('nameChamps'=>'categorie','type'=>'=','name'=>'categorie','value'=>$idCategorie));
	$req =  new myQueryClass('produit',$conditions);
	$r = $req->myQuerySelect("count(*) as nbre");
	return $r[0][0];
}


//voir produit selon l'etat 
// 1 : bon
// 2 :  tres bonne
// 3 : neuf
function voirProduitParEtat($id){
    $conditions = array();
    array_push($conditions, array('nameChamps'=>'etatDuProduit','type'=>'=','name'=>'etatDuProduit','value'=>$id));
    $req =  new myQueryClass('produit',$conditions);
    $r = $req->myQuerySelect();
	return $r;
}

//Voir produit selon la recherche
function searchProduit($recherche,$ask){
    $conditions = array();
    array_push($conditions, array('nameChamps'=> 'marque','type'=>'=','name'=>'recherche' ,'value'=>$recherche ,'operator'=>'OR'));
    array_push($conditions, array('nameChamps'=> 'nom','type'=>'like','name'=>'recherche2','value'=>$recherche.'%','operator'=>'OR' ));
    array_push($conditions, array('nameChamps'=>'etatDuProduit','type'=>'!=','name'=>'etatDuProduit','value'=>'2'));

    if(isset($_GET['order'])){
        $order =array();
        array_push($order, array('nameChamps'=>'prix','sens'=>$_GET['order']));
        $req =  new myQueryClass('produit',$conditions,$order);
    }else{
        $req =  new myQueryClass('produit',$conditions);
    }
    $r = $req->myQuerySelect();
	return $r;
}


// Les produits selon la taille du monsieur ou madame
function allProduitBySelection($get,$infoProfil = null){
    $order = array();
    $conditions = array(); 
 
    // pour chaque get
   
    foreach($get as $g => $value){
        if(($g != 'order' ) && ($g != 'prixMin') && ($g != 'prixMax') && ($g != 'profil')){
            $value = explode(",", $value);
            $nb = count($value) -1; // nbre de valeur
            $cp = 0;
            foreach($value as $v){
                if($cp == 0){
                    array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v));
                }else{
                    if($nb != $cp){
                        array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v ,'operator'=>'OR'));
                        }else{
                        array_push($conditions, array('nameChamps'=> $g,'type'=>'=','name'=>$g.''.$cp ,'value'=>$v ,'operator'=>'OR' ));
                        }
                }
            
                $cp++;
            }
        } else{
            if($g == 'profil'){
                array_push($conditions, array('nameChamps'=> 'taille','type'=>'=','name'=>'d','value'=>$infoProfil["tailleHautProfil"] ,'operator'=>'OR'));
                array_push($conditions, array('nameChamps'=> 'taille','type'=>'=','name'=>'t' ,'value'=>$infoProfil['tailleBasProfil'] ,'operator'=>'OR'));
                array_push($conditions, array('nameChamps'=> 'genre','type'=>'=','name'=>'genre' ,'value'=>$infoProfil['genre'] ));
            }
            if($g == 'order'){
                array_push($order, array('nameChamps'=>'prix','sens'=>$value));
            }
            if($g == 'prixMin'){
                array_push($conditions, array('nameChamps'=> 'prix','type'=>'>=','name'=>'prixMin' ,'value'=> $value));
            }
            if($g == 'prixMax'){
                array_push($conditions, array('nameChamps'=> 'prix','type'=>'<=','name'=>'prixMax' ,'value'=> $value));
            }
            
        
      
        }
       
       
    }
    if($infoProfil != null){
        array_push($conditions, array('nameChamps'=> 'taille','type'=>'=','name'=>'d','value'=>$infoProfil["tailleHautProfil"] ,'operator'=>'OR'));
        array_push($conditions, array('nameChamps'=> 'taille','type'=>'=','name'=>'t' ,'value'=>$infoProfil['tailleBasProfil'] ,'operator'=>'OR'));
        array_push($conditions, array('nameChamps'=> 'genre','type'=>'=','name'=>'genre' ,'value'=>$infoProfil['genre'] ));
    }
    
    $req =  new myQueryClass('produit',$conditions,$order);
    $r = $req->myQuerySelect();
	return $r;
}


function changeProduitStatut($idProduit,$valeur,$idCommande = null,$idClient =NULL,$date = null)
{
	$conditions = array();
	$values = array();
	array_push($conditions, array('nameChamps'=>'id','type'=>'=','name'=>'id','value'=>$idProduit));
    array_push($values, array('nameChamps'=>'etatDuProduit','name'=>'etatDuProduit','value'=>$valeur));
    array_push($values, array('nameChamps'=>'idClient','name'=>'idClient','value'=>$idClient));
    array_push($values, array('nameChamps'=>'idCommande','name'=>'idCommande','value'=>$idCommande));
    if($date != 'false'){ // si c'est false on ne mets pas la tête
    array_push($values, array('nameChamps'=>'dateReservation','name'=>'dateReservation','value'=>$date));
    }
	$req =  new myQueryClass('produit',$conditions,'',$values);
	$r = $req->myQueryUpdate();
	$conn = null ; //Quitte la connexion
}

function changeProduitDateReservation($idProduit,$valeur,$idClient =NULL)
{
	$conditions = array();
    $values = array();
    
    $date = date('Y-m-d H:i:s');
    $year = substr($date, 0, -15);   
    $month = substr($date, 5, 2);   
    $day = substr($date, 8,2); 
    $heure = substr($date, 11,2); 
    $minute = substr($date, 14,2);     

 
    // récupère la date du jour
    $date_string = mktime($heure,$minute,0,$month,$day,$year);
    $datee = date("Y-m-d H:i:s", $date_string); 
	array_push($conditions, array('nameChamps'=>'id','type'=>'=','name'=>'id','value'=>$idProduit));
    array_push($values, array('nameChamps'=>'etatDuProduit','name'=>'etatDuProduit','value'=>$valeur));
    array_push($values, array('nameChamps'=>'idClient','name'=>'idClient','value'=>$idClient));
    array_push($values, array('nameChamps'=>'dateReservation','name'=>'dateReservation','value'=>$datee));
	$req =  new myQueryClass('produit',$conditions,'',$values);
	$r = $req->myQueryUpdate();
	$conn = null ; //Quitte la connexion
}


function changeProduitDateReservationNull()
{
	$conditions = array();
    $values = array();
    $date = date('Y-m-d H:i:s');
    $year = substr($date, 0, -15);   
    $month = substr($date, 5, 2);   
    $day = substr($date, 8,2); 
    $heure = substr($date, 11,2); 
    $minute = substr($date, 14,2);     

 
    // récupère la date du jour
    $date_string = mktime($heure,$minute,0,$month,$day,$year);
    // Supprime une heure
    $dateMoinsUneHeure = $date_string -  3600;
    
    $dateMoinsUneHeure = date("Y-m-d H:i:s", $dateMoinsUneHeure); 
    
    array_push($conditions, array('nameChamps'=>'dateReservation','type'=>'<','name'=>'dateReservatio',
    'value'=>$dateMoinsUneHeure));
    array_push($values, array('nameChamps'=>'idClient','name'=>'idClient','value'=>null));
    array_push($values, array('nameChamps'=>'etatDuProduit','name'=>'etatDuProduit','value'=>0));
    array_push($values, array('nameChamps'=>'dateReservation','name'=>'dateReservation','value'=>null));
	$req =  new myQueryClass('produit',$conditions,'',$values);
	$r = $req->myQueryUpdate();
	$conn = null ; //Quitte la connexion*/
}


?>