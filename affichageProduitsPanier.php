<?php
include "database.php";

$products = tableauProduitsPanier($mysql);
// var_dump($products);


// var_dump($_SESSION['panier']);
//  Boucle afin de parcourir le tableau [panier] et afficher les produits qui y ont été ajouter
foreach ($_SESSION['panier'] as $nom => $quantite) {
?>
    <hr class="my-4">
    <div class="row mb-4 d-flex justify-content-between align-items-center">
        <div class="col-md-2 col-lg-2 col-xl-2">
            <!-- Affiche l'image du produit -->
            <img class="image" src="<?php echo $products[$nom]["picture_url"]; ?>" alt="Image Produit">
        </div>
        <div class="col-md-3 col-lg-3 col-xl-3">
            <h6 class="text-muted">
                <!-- Affiche le nom du produit -->
                <?php echo $products[$nom]["name"]; ?>
            </h6>
        </div>
        <div class="col-md-3 col-lg-3 col-xl-2 d-flex wrap">
            <!-- Formulaire modification quantité de produit -->
            <form action="panier.php" method="post" class="formulaire">
                <h6 class="mb-0"> Quantité: <br></h6>
                <input type="hidden" name="nomDuProduit" value="<?php echo $products[$nom]["name"] ?>">
                <input id="nombre" min="0" name="combienDeProduits" value="<?php echo $quantite ?>" type="number" class="form-control form-control-sm" />
                <input type="submit" value="Modifier" class="form-control form-control-sm">
            </form>
            <form action="panier.php" method="post" class="formulaire">
                <input type="hidden" name="nomDuProduit" value="<?php echo $products[$nom]["name"] ?>">
                <input type="hidden" name="combienDeProduits" value="0" class="form-control form-control-sm" />
                <input type="submit" value="Supprimer" class="form-control form-control-sm">
            </form>
        </div>
        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
            <h6 class="mb-0"> Prix Unitaire: <br>
                <!-- Affiche le prix avec et aprés remise du produit -->
                <?php if ($products[$nom]["discount"] != null) {
                    echo discountedPrice($products[$nom]["price"], $products[$nom]["discount"]); ?>
                    € <br>
                    <strike><?php echo formatPrice($products[$nom]["price"]); ?>€<br></strike>
                    <?php } else {
                    echo  formatPrice($products[$nom]["price"]); ?>€
                <?php } ?>
            </h6>
        </div>
    </div>
<?php }
?>