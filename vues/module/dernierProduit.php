
  
  
  <!-- Dernier produit -->
   <section class="padding-top-10 padding-bottom-150">
      <div class="container"> 
        
        <!-- Main Heading -->
        <div class="heading text-center">
          <h4><?php echo $moduleDernierProduit['titre_module'];?></h4>
          <p> <?php echo $moduleDernierProduit['texte_module'];?></p>
          </div>
          
          <div class="papular-block row"> 
            <?php foreach($lesDerniersProduits as $produit){ 
              if(isset($_SESSION['id'])){
                $cli = $_SESSION['id'];
              }else{
                $cli = null;
              }
              if(($produit['idClient'] == null) || ( $produit['idClient']== $cli)){ // s'il est déjààà réservé
              
              ?>
            <!-- Popular Item Slide -->
            
              <!-- Item -->
              <div class="col-md-4">
                <div class="item"> 
                  <!-- Item img -->
                  <a  href="index.php?c=boutique&action=voirProduit&id=<?=$produit['id'];?>">
                    <div class="item-img"> <img class="img-1 imageBoutique" src="<?=$produit['image1'];?>" alt="" > <img class="img-2 imageBoutique" src="<?=$produit['image2'];?>" alt="" > 
                      <!-- Overlay -->
                    </div>
                  </a>
                      <?php $taille = taille($produit['taille']);?>
                     <p class="textAlignCenter"><?= iconeSelonSexe($produit['genre']);?> Taille : <?= $taille['nomTaille'];?></p>
                        <div class="inn">
                          <a href="<?=$produit['image1'];?>" data-lighter><i class="icon-magnifier"></i></a> 
                          <?php if ($_SESSION['panier']->cleExiste($produit['id'])){ ?>
                           <i id="panierAdd-<?=$produit['id'];?>"  style="display:none"  class="addPanier  fas fa-cart-plus"></i>
                           <i id="panierSuppr-<?=$produit['id'];?>" class="supprPanier fas rouge fa-window-close"></i>
                          <?php } else { ?>
                           <i  id="panierAdd-<?=$produit['id'];?>" class="addPanier  fas fa-cart-plus"></i>
                           <i  id="panierSuppr-<?=$produit['id'];?>"  style="display:none"  class="supprPanier  fas rouge fa-window-close"></i>
                          <?php } if(isset($_SESSION['id'])){
                            if(voirSiFavoris($_SESSION['id'],$produit['id']) == 0) { ?>
                            <a id="linkAddFavoris<?=$produit['id'];?>" href="#."   data-toggle="tooltip" data-placement="top" title="Ajouter aux favoris"><i id="<?=$produit['id'];?>"  class="coeur addFavoris icon-heart"></i></a>
                            <a id="linkSupprFavoris<?=$produit['id'];?>" href="#." style="display:none" data-toggle="tooltip" data-placement="top" title="Supprimer des favoris"><i id="<?=$produit['id'];?>" class="coeur rouge supprFavoris fas fa-heart"></i></a>
                           <?php } else { ?>
                            <a id="linkAddFavoris<?=$produit['id'];?>" href="#."  style="display:none" data-toggle="tooltip" data-placement="top" title="Ajouter aux favoris"><i id="<?=$produit['id'];?>"  class="coeur addFavoris icon-heart"></i></a>
                            <a id="linkSupprFavoris<?=$produit['id'];?>" href="#."  data-toggle="tooltip" data-placement="top" title="Supprimer des favoris"><i id="<?=$produit['id'];?>" class="coeur rouge supprFavoris fas fa-heart"></i></a>
                          <?php }}?>
                        </div>
                     
           
                  <!-- Item Name -->
                  <div class="item-name">  <a data-toggle="tooltip" data-placement="top" title="Voir le produit" href="index.php?c=boutique&action=voirProduit&id=<?=$produit['id'];?>"><?=$produit['nom'];?></a>
                    <p><?=$produit['description'];?></p>
                  </div>
                  <!-- Price --> 
                  <span class="price"><?=$produit['prix'];?>€ </div>
              </div>
             
            <?php }}?>
            </div>
        </section>