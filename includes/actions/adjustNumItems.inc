<?php
    if($_GET["adjustBy"] > 0){
        $_SESSION["shopping_session"]->shopping_cart->addItem($_GET["productId"], $_GET["adjustBy"]);
    } else {
        $_SESSION["shopping_session"]->shopping_cart->removeItem($_GET["productId"], ($_GET["adjustBy"] * -1));
    }

    Functions::goToPage("page.php?page=shopping-cart");
?>