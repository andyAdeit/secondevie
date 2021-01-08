

 <!--- Include Header et menu --->
 <?php include('./assets/inc/header.php');?>
  <?php include('./assets/inc/menu.php');?> 

  
  <!-- Content -->
  <div id="content"> 
    
    <!--======= PAGES INNER =========-->
    <section class="padding-top-30 padding-bottom-100 pages-in chart-page shopping-cart small-cart cart-ship-info">
      <div class="container"> 
      <div class="row"> 
      <?php if($error == true){ echo genererError(16); } ?>
        <div class="col-sm-7">
        <?= genererError(29);?>
          <table id="test" class="table">
                  <thead>
                    <tr>
                      <th scope="col">Produit</th>
                      <th scope="col">Nom</th>
                      <th scope="col">Prix</th>
                      <th scope="col">Temps Restant de réservation</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php    foreach ($_SESSION['panier']->getCollection() as $produitPanier) {?>
                    <tr  id="produit-panier-<?=$produitPanier->getId();?>" >
                      <th scope="row"><a class="item-img"> <img class="media-object" src="<?= $produitPanier->getImage();?>" alt="photo du produit"> </a></th>
                      <td><?= $produitPanier->getNom();?></td>
                     
                      <td>
                      <?php if($produitPanier->getReduction() != '0'){?>
                  <span class="price"><del><?= $produitPanier->getPrix();?>€</del> <?= $produitPanier->getPrix() * (1- $produitPanier->getReduction());?>€ </span>
                <?php }else{?>
                  <span class="price"><?= $produitPanier->getPrix();?>€</span> 
                <?php }?>
                      </td>
                      <td><?= tempsRestantReservation($produitPanier->getId());?> minutes</td>
                  <?php /*   <td id="panierSuppr<?=$produitPanier->getId();?>" ><a class="supprPanierproduit supprPanierLigne" href="#."><i  id="<?=$produitPanier->getId();?>"  class="icon-close"></i></a></td>
                  */ ?> </tr>
                    <?php }?>
                    
                
                  </tbody>
                </table>
                </div>
                <div class="col-sm-5 cart-ship-info">
              <h6>Récapitulatif</h6>
              <div class="grand-total">
                <div class="order-detail">
                  <?php
                  $totPanierCategories =  totalPrixPanierParCategorie(); 
                  foreach($totPanierCategories as $tot){
                    $cat = categorie($tot['id']);?>
                    <p>Produit <?=$cat['nomCategorie'];?> 
                    <?php if($tot['totalRemise'] == '0' ){ ?><span><?= $tot['totalSansRemise'];?>€</span><?php } else{ ?>
                      <span><del><?= $tot['totalSansRemise'];?>€</del><?= $tot['totalAvecRemise'];?>€</span>
                    <?php } ?>
                  </p>
                  <?php } ?>
                  <!-- SUB TOTAL -->
                  <?php $totalPanier = totalPrixPanier();?>
                  <p class="all-total">Total sans réduction: <span><?= $totalPanier['totalSansRemise'];?>€</span></p>
                  <p class="all-total">Total avec réduction: <span ><?= $totalPanier['totalAvecRemise'];?>€</span></p>
                </div>
              </div>
              <br>
            
              <div class="coupn-btn textAlignCenter"> 
                <?php if(isConnected()){ // s'il est connecté
                  if($nbProd){ 
                  ?>
                <a href="index.php?c=panier&action=choixLivraison" class="btn">Continuer</a> 
                <?php }}else{?>
                  <a href="connexion.html" class="btn">Connectez-vous!</a>
                <?php }?>
              </div>
            </div>        
        </div>
      </div>
    </section>
    
    
    
    <?php include('./js/page/boutique.php');?>
  <?php include('./assets/inc/footer.php');?>
  