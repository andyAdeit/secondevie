

 <!--- Include Header et menu --->
 <?php include('./assets/inc/header.php');?>
  <?php include('./assets/inc/menu.php');?> 



  
  <!-- Content -->
  <div id="content"> 
  

 
    <section class="padding-top-30 padding-bottom-100 light-gray-bg shopping-cart small-cart">
      <div class="container">
        <!-- Onglet reservation -->
        <div class="cart-ship-info margin-top-0">
          <div class="row">  
            <div class="col-sm-12">
            <?php genererError(25);?>
              <?php if(isConnected()){?>
              <iframe id="inlineFrameExample"
              title="Inline Frame Example"
              width="100%"
              height="600"
              src="https://book.timify.com/?accountId=5f7b153bc49edc1216a2ebab&hideCloseButton=true">
              </iframe>
              <hr>
            <h6>Vous avez fini de prendre votre rendez-vous? </h6>
              <form action="index.php?c=profil&action=validLivraison" method='post' class="textAlignCenter">
                <input type="text" name="id" class="btn transparent" value="<?=$_GET['id'];?>" >
                <button class="btn validLivraison" type="submit"><i class="vert fas fa-check-circle"></i> Terminer ma commande</button>
              </form>
            </div>
            </div>
              <?php }?>
        </div>
    </section>
  


  <?php include('./js/page/boutique.php');?>
  <?php include('./assets/inc/footer.php');?>
  