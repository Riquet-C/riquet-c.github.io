<?php
include "header.php";
include "my-functions.php";
include "database.php";

// session_destroy();
?>

<body style="background-color: #d2c9ff;">
    <!-- Affichage produits -->
    <section class="h-100 h-custom">
        <h1> Mes produits </h1>
        <br>
        <div class="catalogue m-3 flex-wrap">
            <?php
            $products = recupProduitsBDD($mysql);
            foreach ($products as $product) { ?>
                <div class="card-deck col-3 m-3 ">
                    <div class="card">
                        <img src="<?php
                                    echo $product["picture_url"] ?>" class="card-img-top small image" alt="Image Produit">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $product['name']; ?><br>
                            </h5>
                            <p class="card-text">
                                <?php if ($product["discount"] != null) { ?>
                                    <strike>
                                        Prix TTC:
                                        <?php $prixTTC = $product["price"];
                                        echo number_format(formatPrice($prixTTC), 2); ?> €
                                    </strike>
                            <p> Remise : <?php echo $product["discount"] ?> % </p>
                            <p> Prix TTC aprés remise: <?php echo discountedPrice($prixTTC, $product["discount"]); ?> € </p>
                        <?php } ?>
                        <?php
                        if ($product["discount"] == null) { ?>
                            <p> Prix TTC:
                                <?php
                                $prixTTC = $product["price"];
                                echo number_format(formatPrice($prixTTC), 2); ?> € <br><br><br><br> </p><?php } ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                <form action="panier.php" method="post" class="formulaire">
                                    Quantité:
                                    <input type="hidden" name="nomDuProduit" value="<?php echo $product['name'] ?>">
                                    <input class="nombre" type="number" name="combienDeProduits" value="1" min="1">
                                    <input class="bouton" type="submit" value="Commander">
                                </form>
                            </small>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</body>

