<hr>
     <!-- Main Heading -->
        <div class="cart-ship-info margin-top-30">
          <h6 class="cursor shop-tittle margin-bottom-30" data-toggle="tooltip" data-placement="top" title="Voir le tableau"  onclick="changeVisibilite('tabAdulte','spanTabAdulte')"><span class="cursor" id="spanTabAdulte" ><i  class="fas fa-angle-up"></i></span> Tableau d'estimation prix d'achat adulte</h5>
          <div id="tabAdulte" style='display:none' >
            <p>Voici un exemple de marque pour le prix normal : Kiabi, celio, h&m ... </p>   
            <p>Voici un exemple de marque pour le prix de marque : Devred, Adidas, Nike ... </p>
              <table id="tabAdulte"  class="table">
                    <thead>
                      <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Prix normal</th>
                        <th scope="col">Prix marque</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    foreach($allProduitAdulte as $produitA){?>
                      <tr>
                        <th scope="row"><?= $produitA['nomSousCategorie'];?></th>
                        <td><?= $produitA['prixAdulteLowCoast'];?>€</td>
                        <td><?= $produitA['prixAdulteMarque'];?>€</td>
                        <td></td>
                      </tr>
                      <?php }?>
                      
                  
                    </tbody>
              </table>
            </div>
          </div>
        
