<?php
include "header.php";
include "my-functions.php";



// Si le panier n'existe pas, on le crée
if (!isset($_SESSION['panier'])) {
    /* Initialisation du panier */
    $_SESSION['panier'] = array();
}

// Si le retour du formulaire "nomDuProduit" est rempli, on ajoute le nom du produit et sa quantité au tableau du panier
if (isset($_POST["nomDuProduit"]) && $_POST["nomDuProduit"] != null) {
    $_SESSION['panier'][$_POST["nomDuProduit"]] = $_POST['combienDeProduits'];
}

if (isset($_POST["nomDuProduit"]) && $_POST["combienDeProduits"] == 0) {
    unset($_SESSION['panier'][$_POST["nomDuProduit"]]);
}

?>

<!-- Début affichage panier -->

<body style="background-color: #d2c9ff;">
    <div class="page">
        <section class="h-100 h-custom">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12">
                        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <div class="col-lg-8">
                                        <div class="p-5">
                                            <!-- Coté Gauche panier -->
                                            <div class="d-flex justify-content-between align-items-center mb-5">
                                                <!-- Si aucun produit n'a été ajouter au panier alors on affiche "votre panier est vide -->
                                                <?php
                                                if ($_SESSION['panier'] == null) { ?>
                                                    <h6 class="mb-0 text-muted">Votre panier est vide :(</h6>
                                                    <!-- Sinon, on affiche le panier et les produits -->
                                                <?php } else { ?>
                                                    <h1 class="fw-bold mb-0">Panier</h1>
                                                    <h6 class="mb-0 text-muted"><?php echo quantiteTotal() ?> produits</h6>
                                            </div>
                                            <!-- Inclus la partie affichage des différents produits présent dans le panier -->
                                            <?php
                                                    include "affichageProduitsPanier.php"
                                            ?>
                                            <!-- Bouton Vider le panier -->
                                            <a href="reset-cart.php" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">Vider le panier</a>
                                            <!-- Bouton retour aux catalogue -->
                                            <div class="pt-5">
                                                <h6 class="mb-0">
                                                    <a href="catalogue.php" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Retour aux Articles</a>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Coté droit du panier / Résumé et TTC -->
                                    <div class="col-lg-4 bg-body-tertiary">
                                        <div class="p-5">
                                            <h3 class="fw-bold mb-5 mt-2 pt-1">Résumé</h3>
                                            <hr class="my-4">
                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 class="text-uppercase">Prix total TTC:</h5>
                                                <h5>
                                                    <!-- Affiche le prix total TTC contenu dans le panier -->
                                                    <?php
                                                    echo number_format(calculPrixTotal($products), 2) ?>
                                                    €
                                                </h5>
                                            </div>
                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 class="text-uppercase">Prix total HT:</h5>
                                                <h5>
                                                    <!-- Affiche le prix total HT contenu dans le panier -->
                                                    <?php echo number_format(priceExcludingVAT(calculPrixTotal($products)), 2) ?>
                                                    €
                                                </h5>
                                            </div>
                                            <div class="d-flex justify-content-between mb-4">
                                                <h5 class="text-uppercase">TVA:</h5>
                                                <h5>
                                                    <!-- Affiche le cout de la TVA -->
                                                    <?php echo number_format(prixTotalSansTVA($products), 2) ?>
                                                    €
                                                </h5>
                                            </div>
                                            <h5 class="text-uppercase mb-3">Livraison</h5>
                                            <!-- Choix du transporteur -->
                                            <div class="mb-4 pb-2">
                                                <form action="panier.php" method="post" class="formulaire">
                                                    <select name="transporteur" id="transporteur">
                                                        <option value="rapide">Rapide</option>
                                                        <option value="normal">Normal</option>
                                                    </select>
                                                    <input class="bouton" type="submit" value="Valider">
                                                </form>
                                            </div>
                                            <hr class="my-4">
                                            <div class="d-flex justify-content-between mb-5">
                                                <h5 class="text-uppercase">Livraison:
                                                    <!-- Affiche le transporteur lorsqu'il a été choisi -->
                                                    <?php
                                                    if (filter_has_var(INPUT_POST, 'transporteur')) {
                                                        echo $_POST["transporteur"]; ?>
                                                        <h5>
                                                            <!-- affiche le montant des frais de Livraison -->
                                                            <?php echo number_format(fraisLivraison($products), 2) ?>
                                                            €
                                                        </h5>
                                                    <?php  } ?>
                                                </h5>
                                            </div>
                                            <div class="d-flex justify-content-between mb-5">
                                                <h5 class="text-uppercase">Total price</h5>
                                                <h5>
                                                    <!-- affiche le montant total TTC + livraison -->
                                                    <?php
                                                    if (filter_has_var(INPUT_POST, 'transporteur')) { ?>
                                                        <h5>
                                                            <!-- affiche le montant total AVEC des frais de Livraison -->
                                                            <?php echo number_format(calculPrixTotal($products) + fraisLivraison($products), 2) ?>
                                                            €
                                                        </h5>
                                                    <?php  } else { ?>
                                                        <h5>
                                                            <!-- affiche le prix sans livraison -->
                                                            <?php echo number_format(calculPrixTotal($products), 2) ?>
                                                            €
                                                        </h5>
                                                    <?php  } ?>
                                                </h5>
                                            </div>
                                            <!-- Bouton Acheter-->
                                            <?php if (filter_has_var(INPUT_POST, 'transporteur')) { ?>
                                                <form action="validationPanier.php" method="post" class="formulaire">
                                                    <input type="hidden" name="nomDuProduit" value="<?php echo $products[$nom]["name"] ?>">
                                                    <input id="number" min="0" name="combienDeProduits" value="<?php echo $quantite ?>" type="hidden" class="form-control form-control-sm" />
                                                    <input type="submit" value="Acheter" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">
                                                <?php  } else { ?>
                                                    <h5 class="text-danger border border-danger rounded p-2">
                                                        Merci de choisir une livraison afin de pouvoir valider votre achat
                                                    </h5>
                                                <?php  } ?>
                                                </form>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

<?php
include "footer.php";
?>