<hr>
     <!-- Main Heading -->
        <div class="cart-ship-info margin-top-30">
         
        
          <h6 class="cursor shop-tittle margin-bottom-30" data-toggle="tooltip" data-placement="top" title="Voir le tableau" onclick="changeVisibilite('tabEnfant','spanTabEnfant')"><span class="cursor" id="spanTabEnfant" ><i  class="fas fa-angle-down"></i></span> Tableau d'estimation prix d'achat enfant</h5>
          <div id="tabEnfant" style='display:none' >
          <p>Voici un exemple de marque pour le prix normal : Kiabi, celio, h&m ... </p>   
          <p>Voici un exemple de marque pour le prix de marque : Devred, Adidas, Nike ... </p>
          <table  class="table">
                <thead>
                  <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prix normal</th>
                    <th scope="col">Prix marque</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                foreach($allProduitEnfant as $produitE){?>
                  <tr>
                    <th scope="row"><?= $produitE['nomSousCategorie'];?></th>
                    <td><?= $produitE['prixEnfantLowCoast'];?>€</td>
                    <td><?= $produitE['prixEnfantMarque'];?>€</td>
                    <td></td>
                  </tr>
                  <?php }?>
                  
              
                </tbody>
              </table>
          </div>
          </div>
        
