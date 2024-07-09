<?php

// Individuel
function formatPrice($prixCentime)
{
    return $prixCentime / 100;
}

function priceExcludingVAT($prixTTC)
{
    $TVA = 20;
    $prixHT = (100 * $prixTTC) / (100 + $TVA);
    return $prixHT;
}

function discountedPrice($prix, $remise)
{
    $prixDiscount = (($prix / 100) * (100 - $remise)) / 100;
    return $prixDiscount;
}

// TOTAL

function quantiteTotal()
{
    $nombreDeProduit = 0;
    foreach ($_SESSION['panier'] as $nom => $quantite) {
        $nombreDeProduit = $nombreDeProduit + intval($quantite);
    }
    return $nombreDeProduit;
}

function prixTotalSansTVA($tableauPanier)
{
    return calculPrixTotal($tableauPanier) - priceExcludingVAT(calculPrixTotal($tableauPanier));
}

function calculPrixTotal($tableauPanier)
{
    $prixUnProduit = 0;
    $prixToutProduit = 0;
    foreach ($_SESSION['panier'] as $nom => $quantite) {
        $nomDuProduit = $tableauPanier[$nom];
        if ($tableauPanier[$nom]['discount'] != null) {
            $prixUnProduit = discountedPrice($nomDuProduit['price'], $nomDuProduit['discount']);
            $prixUnProduit = $prixUnProduit * $quantite;
        } else {
            $prixUnProduit = formatPrice($nomDuProduit['price']);
            $prixUnProduit = $prixUnProduit * $quantite;
        }
        $prixToutProduit = $prixToutProduit + $prixUnProduit;
    }
    return $prixToutProduit;
}

// Livraison
function fraisLivraisonRapide($poidsComplet, $tableauPanier)
{
    $fraisLivraison = 0;
    if ($poidsComplet <= 500) {
        $fraisLivraison = 5;
    } else if ($poidsComplet > 500 && $poidsComplet < 2000) {
        $fraisLivraison = (calculPrixTotal($tableauPanier) * 10) / 100;
    } else if ($poidsComplet > 2000) {
        $fraisLivraison = 0;
    }
    return $fraisLivraison;
}

function fraisLivraisonNormal($poidsComplet, $tableauPanier)
{
    $fraisLivraison = 0;
    if ($poidsComplet <= 500) {
        $fraisLivraison = 3;
    } else if ($poidsComplet > 500 && $poidsComplet < 2000) {
        $fraisLivraison = (calculPrixTotal($tableauPanier) * 5) / 100;
    } else if ($poidsComplet > 2000) {
        $fraisLivraison = 0;
    }
    return $fraisLivraison;
}

function fraisLivraison($tableauPanier)
{
    if (filter_has_var(INPUT_POST, 'transporteur') && $_POST["transporteur"] == "rapide") {
        $fraisLivraison = fraisLivraisonRapide(calculPoidsColis($tableauPanier), $tableauPanier);
    } else {
        $fraisLivraison = fraisLivraisonNormal(calculPoidsColis($tableauPanier), $tableauPanier);
    }
    return $fraisLivraison;
}

function calculPoidsColis($tableauPanier)
{
    $poids = 0;
    $poidsTotal = 0;
    // $products = tableauProduits();
    foreach ($_SESSION['panier'] as $nom => $quantite) {
        $poids = $tableauPanier[$nom]['weight'] * $quantite;
        $poidsTotal = $poids + $poidsTotal;
    }
    // var_dump(($poidsTotal));
    return $poidsTotal;
}


// Fonction requete SQL

function recupProduitsBDD($mysql)
{
    $sqlQuery = 'SELECT * FROM products;';
    $requete = $mysql->prepare($sqlQuery);
    $requete->execute();
    $products = $requete->fetchall(PDO::FETCH_ASSOC);
    return $products;
}

function tableauProduitsPanier($mysql)
{
    $products = [];
    foreach ($_SESSION['panier'] as $nom => $quantite) {
        $sqlQuery = 'SELECT * FROM products WHERE name = :nom;';
        $requete1 = $mysql->prepare($sqlQuery);
        $requete1->bindParam(':nom', $nom, PDO::PARAM_STR);
        $requete1->execute();
        $produitsDansPanier[$nom] = $requete1->fetchall(PDO::FETCH_ASSOC)[0];
    }
    return $produitsDansPanier;
}

function recupOrdersSQL($mysql)
{
    $sqlQuery = 'SELECT * FROM orders ORDER BY id DESC LIMIT 1';
    $requete2 = $mysql->prepare($sqlQuery);
    $requete2->execute();
    $commandes = $requete2->fetchall(PDO::FETCH_ASSOC)[0];
    return $commandes;
}

function insertOrdersBDD($mysql, $tableauPanier)
{
    $fraisLivraison = fraisLivraison($tableauPanier);
    $price = calculPrixTotal($tableauPanier) + $fraisLivraison;
    $sqlQuery = 'INSERT INTO orders(id, date, priceTTCORDER, shippingOrder, customer_id) VALUES(0, NOW(), :price, :fraisLivraison, 2)';
    $requete = $mysql->prepare($sqlQuery);
    $requete->bindParam(':price', $price, PDO::PARAM_STR);
    $requete->bindParam(':fraisLivraison', $fraisLivraison, PDO::PARAM_STR);
    $requete->execute();
}

function insertProductsBDD($mysql, $tableauPanier)
{
    $orderId = $mysql->lastInsertId();
    foreach ($_SESSION['panier'] as $nom => $quantite) {
        $sqlQuery = 'INSERT INTO order_product(id, order_id, quantity, product_id) VALUES(0, :orderId, :quantity, :produit_Id)';
        $requete = $mysql->prepare($sqlQuery);
        $requete->bindParam(':orderId', $orderId, PDO::PARAM_STR);
        $requete->bindParam(':quantity', $quantite, PDO::PARAM_STR);
        $requete->bindParam(':produit_Id', $tableauPanier[$nom]['id'], PDO::PARAM_STR);
        $requete->execute();
    }
}
