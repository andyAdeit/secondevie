

  <!--- Include Header et menu --->
  <?php include('./assets/inc/header.php');?>
  <?php include('./assets/inc/menu.php');
  $number = 0;
  $nbImg = 5;
  $nbProfil = 3;
  ?>

  

  <!-- Content -->
  <div id="content"> 
    <!--======= PAGES INNER =========-->
    <section class="chart-page padding-top-30 padding-bottom-100 ">
      <div class="container">         
        <!-- Payments Steps -->
        <div class="shopping-cart"> 
          
          <!-- SHOPPING INFORMATION -->
          <div class="cart-ship-info S'inscrire">
            <div class="row"> 
              <div class="col-sm-4 ">
              <h5 class="textAlignCenter border-bottom-1 margin-bottom-30 padding-bottom-10">Menu</h5>
              <h5 class="cursor shop-tittle margin-bottom-30" onclick="visible(divPrecedente,'general')">Mes informations <span class="cursor" id="spanGenre" ></span></h5>
              <h5 class="cursor shop-tittle margin-bottom-30" onclick="visible(divPrecedente,'spanGenre')">Changer mon mot de passe <span class="cursor" id="spanGenre" ></span></h5>
              <h5 class="cursor shop-tittle margin-bottom-30" onclick="visible(divPrecedente,'foyer')">Mes profils<span class="cursor" id="spanGenre" ></span></h5>
              </div>
              <!-- ESTIMATE SHIPPING & TAX -->
              <div id="general" style='display:none' class="col-sm-8">
                <?=genererError(22);?>
                <p>

                  <ul class="row">
                    
                    <!-- Name -->
                    <li class="col-md-6">
                      <label>Prénom
                        <input type="text" name="prenom" class='not-allowed' value="<?=$cli->PRE_CLIENTS;?>"  disabled>
                      </label>
                    
                    </li>
                    <!-- LAST NAME -->
                    <li class="col-md-6">
                      <label>Nom
                        <input type="text" name="nom" class='not-allowed' value="<?=$cli->NOM_CLIENTS;?>" disabled>
                      </label>
                    </li>
                    
                    <!-- EMAIL ADDRESS -->
                    <li class="col-md-6">
                      <label>Email
                        <input type="email" name="email"  id="email" class='not-allowed'  value="<?=$cli->MAIL_CLIENTS;?>" disabled >
                      </label>
                    </li>
                    <!-- PHONE -->
                    <li class="col-md-6">
                      <label>Téléphone 
                        <input type="tel" name="telephone" id='tel' maxlength="10" value="<?=$cli->TEL_CLIENTS;?>" placeholder="" required >
                      </label>
                    </li>
                    <li class="col-md-6"> 
                      <!-- ADDRESS -->
                      <label>Adresse <span class="rouge"><span class="rouge">*</span></span>
                        <input type="text" name="adresse" id='adr' value="<?=$cli->ADRESSE;?>" placeholder="" required>
                      </label>
                    </li>      
                
                    <!-- TOWN/CITY -->
                    <li class="col-md-6">
                      <label>Ville <span class="rouge"><span class="rouge">*</span></span>
                        <input type="text" name="ville" id='ville' value="<?=$cli->VILLE;?>" placeholder="" required>
                      </label>
                    </li>
                    
                    
                    <!-- TOWN/CITY -->
                    <li class="col-md-6">
                      <label>Code postal <span class="rouge"><span class="rouge">*</span></span>
                        <input type="number" name="cp"  id='cp' value="<?=$cli->CODEPOSTAL;?>" placeholder="" required>
                      </label>
                    </li>
                    
                    <!-- PHONE -->
                    <li class="col-md-12 textAlignCenter">
                      <button id='btnModifyGeneral' class="btn">Modifier</button>
                    </li>
                    
                  </ul>
              </div>
              <div id='foyer' class="col-sm-8 " style="display:none">
                <?=genererError(26);?>
                <p class="textAlignCenter">
                 <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProfil">
                  Ajouter un profil
                </button>
                  <ul class="row">
                    <?php foreach($mesProfils as $profil){
                      $avatar = monAvatar($profil['idAvatar']);
                        if ($number % $nbProfil == 0) { // si c'est une nouvelle ligne ?>
                        </ul>
                        <ul class='row margin-top-10'>
                        <?php } $number++; ?>
                              <!-- Member -->
                    <li id="avatar<?=$profil["idProfil"];?>" class="col-md-4 text-center">
                      <article> 
                        <!-- abatar -->
                        <div class="avatar"> 
                          <img class="img-circle imgAvatar" src="<?=$avatar["lienAvatar"];?>" alt="" > 
                        </div>
                        <!-- Team Detail -->
                        <div class="team-names margin-top-5">
                          <h5><?= $profil['nomProfil'];?> <i id="<?=$profil['idProfil'];?>" data-toggle="tooltip" data-placement="top" title="Supprimer le profil" class="suppProfil fas fa-trash-alt"></i></h5>
                          <p>Taille Haut: <?= nomTaille($profil['tailleBasProfil']);?><br>
                          Taille Bas:  <?= nomTaille($profil['tailleHautProfil']);?></p>
                          <a  class="btn btnSmall blanc" href="index.php?c=profil&partie=modifyProfil&id=<?= $profil["idProfil"];?>" ><i class="fas fa-user-edit"></i> Modifier</button></a>
                        </div>
                      </article>
                    </li>
              

           
                        <?php } ?>
                  </ul>       
      </div>     

    <div id='modifyProfil' class="col-sm-8 " style="display:none">
    <?php if(isset($_GET['id'])){
    if(verifProfilById($_GET['id'])){
    $monProfil = monProfil($_GET['id']);
    genererError(27);?>
    <p>Nom profil: <b><?= $profil['nomProfil'];?></b></p>
    <ul class="row margin-bottom-5">
        <form action="index.php?c=profil&action=editProfil" method='post'>
          <!-- TAILLE DE HAUT -->
          <li class="col-md-6">
            <label>Taille de haut</label>
              <select class="form-control" id="updTailleProfilH" name="tailleHaut">
                <?php foreach ($tailleHaut as $haut){
                  $s = '';
                  if($haut['idTaille'] ==  $monProfil['tailleHautProfil']){ $s= 'Selected'; }
                  echo "<option ".$s." value=".$haut['idTaille'].">".$haut['nomTaille']."</option>";
                }?> 
              </select>
          </li>
          <!-- TAILLE DE BAS -->
          <li class="col-md-6">
            <label>Taille de pantalon</label>
              <select class="form-control" id="updTailleProfilB" name="tailleBasProfil">
                <?php foreach ($taillePantalon as $pantalon){
                    $s = '';
                    if($pantalon['idTaille'] ==  $monProfil['tailleBasProfil']){ $s= 'Selected'; }
                  echo "<option ".$s." value=".$pantalon['idTaille'].">".$pantalon['nomTaille']."</option>";
                }?> 
              </select>
          </li>
          <div class="col-md-12 textAlignCenter">
            <hr>
            <input class="transparent" id="UpdIdProfil" value='<?=$_GET['id'];?>'>
            <button type="button" class="btn btn-secondary btnUpdateProfil" ><i class="fas fa-edit"></i> Modifier</button>
          </div>
        </form>
      </ul>
      <?php }else {
        genererError(28);
      }
      } else{ 
         genererError(28);
      }?>
    </div>
  </section>




<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="addProfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un profil</h5>
      </div>
      <div class="modal-body"> 
      <ul class="row margin-bottom-5">
        <form action="index.php?c=profil&action=addProfil" method='post'>
                     <!-- TAILLE DE HAUT -->
                     <li class="col-md-3">
                      <label>Nom</label>
                        <input type="text" class="form-control"  name="prenom" value="" placeholder="" required>
                    </li>
                    <li class="col-md-3">
                      <label>Désignation</label>
                        <select class="form-control" id='designation' name="genreClient">
                            <?php foreach ($allGenre as $genre){
                              echo "<option value=".$genre['idGenre'].">".$genre['libGenre']."</option>";
                            }?> 
                        </select>
                    </li>
                     <!-- TAILLE DE HAUT -->
                    <li class="col-md-3">
                      <label>Taille de haut</label>
                        <select class="form-control" name="tailleHClient">
                            <?php foreach ($tailleHaut as $haut){
                              echo "<option value=".$haut['idTaille'].">".$haut['nomTaille']."</option>";
                            }?> 
                        </select>
                    </li>
                     <!-- TAILLE DE BAS -->
                    <li class="col-md-3">
                      <label>Taille de pantalon</label>
                        <select class="form-control" name="taillePClient">
                            <?php foreach ($taillePantalon as $pantalon){
                              echo "<option value=".$pantalon['idTaille'].">".$pantalon['nomTaille']."</option>";
                            }?> 
                        </select>
                    </li>
                  </ul>
                  <hr>
                  <ul class="row margin-top-10">
                    <li class="col-md-12"> 
                    <label>Choisir mon avatar</label>
                    </li>
                  </ul>
                  <div id='icone1'style='display:block'>
                  <ul  class="row">
                      <?php 
                      $number = 0;
                      foreach($allAvatar as $avatarEF){
                        if ($number % $nbImg == 0) { // si c'est une nouvelle ligne ?>
                        </ul>
                        <ul  class='row margin-top-10'>
                        <?php } $number++; ?>
                      <li  class="col-md-2">
                        <div class="avatar imgAvatar" id="<?=$avatarEF["idAvatar"];?>">
                          <img width='80%' class="img-circle imgProfil img<?=$avatarEF["idAvatar"];?>" id="<?=$avatarEF["idAvatar"];?>" src="<?=$avatarEF["lienAvatar"];?>" alt="" > 
                        </div>
                      </li>
                      <?php }?>
                    </ul>
                  </div>

                  
                  <input class="transparent" type="radio" id="imgAvatarForm" name="imgAvatarForm" value='1' checked>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
        <button type="submit" id="addProfilForm" class="btn btn-primary">Ajouter</button>
      </div>
    </div>
  </div>

</div>
  <script>
    
  $('.btnUpdateProfil').click(function(e){ 
    select = document.getElementById("updTailleProfilB");
    choice = select.selectedIndex  // Récupération de l'index du <option> choisi
    tailleB = select.options[choice].value; // Récupération du texte du <option> d'index "choice"

    select = document.getElementById("updTailleProfilH");
    choice = select.selectedIndex  // Récupération de l'index du <option> choisi
    tailleH = select.options[choice].value; // Récupération du texte du <option> d'index "choice"

    idProfil = document.getElementById('UpdIdProfil').value;

    param = 'tailleH='+tailleH+'&tailleB='+tailleB+'&idProfil='+idProfil;
    url= 'index.php?c=profil&action=editProfil&ajx=1&'+param;
    messageRetour = '';
    reponse= postAjax(param,url,messageRetour);
    rep = reponse.responseText;
    rep = jQuery.parseJSON(rep);
    alertJs(rep.success,rep.msg);
  });

  $('#btnModifyGeneral').click(function(e){ 
    Email = document.getElementById('email').value;
    tel = document.getElementById('tel').value;
    ville = document.getElementById('ville').value;
    adr = document.getElementById('adr').value;
    cp = document.getElementById('cp').value;
    param = 'email='+Email+'&telephone='+tel+'&ville='+ville+'&adresse='+adr+'&cp='+cp;
    url= 'index.php?c=profil&action=modif&ajx=1&'+param;
    messageRetour = '';
    reponse= postAjax(param,url,messageRetour);
    rep = reponse.responseText;
    rep = jQuery.parseJSON(rep);
    alertJs(rep.success,rep.msg);
  });

    // quand click sur l'avatar
    $('.imgAvatar').click(function(e){ 
    document.getElementById('imgAvatarForm').value = e.target.id;
    for (let index = 0; index < 150; index++) {
      $('.img'+index).removeClass("imgActive");     // supp la bordure
    }
    $('.img'+e.target.id).addClass("imgActive"); // ajoute une bordure autour
    });


    // quand click sur supprimer un profil
    $('.suppProfil').click(function(e){ 
      idProfil = e.target.id; // id du profil
      param = 'id='+idProfil;
      url= 'index.php?c=profil&action=supprimerprofil&ajx=1';
      reponse= postAjax(param,url);
      rep = reponse.responseText;
      rep = jQuery.parseJSON(rep);
      alertJs(rep.success,rep.msg); // affiche l'alert de suppression
      document.getElementById('avatar'+idProfil).remove()
      
    });


  
  function visible(divPrec,divId){
    divPrecedente = visibilite(divPrec,divId);
  }
 
  if($_GET('partie')){
    divPrecedente = $_GET('partie');
  }else{
    divPrecedente = 'general';
  }
  console.log(divPrecedente)
  document.getElementById(divPrecedente).style.display = 'block';
</script>

  <?php include('./assets/inc/footer.php');?>
  