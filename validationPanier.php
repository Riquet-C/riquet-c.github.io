<?php
include "header.php";
include "my-functions.php";
include "database.php";

$products = tableauProduitsPanier($mysql);
$commandes = recupOrdersSQL($mysql);
insertOrdersBDD($mysql, $products);
insertProductsBDD($mysql, $products);
// var_dump($commandes);
// var_dump($products);
?>

<body>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:900px;">
        <tr>
            <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:900px;">
                    <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                            <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                            <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                Merci pour votre Commande !
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-top: 20px;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                        Order Confirmation #
                                    </td>
                                    <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                        n°<?php echo $commandes['id'] ?>
                                    </td>
                                </tr>
                                <?php foreach ($_SESSION['panier'] as $nom => $quantite) { ?>
                                    <tr>
                                        <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                            <?php echo $products[$nom]['name'] . ' x' . $quantite ?>
                                        </td>
                                        <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">
                                            <?php if ($products[$nom]["discount"] != null) {
                                                echo discountedPrice($products[$nom]["price"], $products[$nom]["discount"]); ?>
                                                € <br>
                                                <strike><?php echo formatPrice($products[$nom]["price"]); ?>€<br></strike>
                                                <?php } else {
                                                echo  formatPrice($products[$nom]["price"]); ?>€
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                        Livraison
                                    </td>
                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;">
                                        <?php echo number_format(fraisLivraison($products), 2) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-top: 20px;">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td width="75%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                        TOTAL
                                    </td>
                                    <td width="25%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                        <?php echo number_format(calculPrixTotal($products) + fraisLivraison($products), 2) ?>
                                        €
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:900px;">
                    <tr>
                        <td align="center">
                            <img src="./image/Toutou.png" width="250" height="250" style="display: block; border: 0px;" />
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 24px; padding: 5px 0 10px 0;">
                            <p style="font-size: 14px; font-weight: 800; line-height: 18px; color: #333333;">
                                675 Parko Avenue<br>
                                LA, CA 02232
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 24px;">
                            <p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #777777;">
                                If you didn't create an account using this email address, please ignore this email or <a href="#" target="_blank" style="color: #777777;">unsusbscribe</a>.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </td>
    </tr>
    </table>

</body>




<?php
session_unset();
include "footer.php";
?>