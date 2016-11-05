<?php
    // Checking if the adjustment is an increase or a decrease
    if($_GET["adjustBy"] > 0){
        // Add to the shopping cart
        $_SESSION["shopping_session"]->shopping_cart->addItem($_GET["productId"], $_GET["adjustBy"]);
    } else {
        // Remove from the shopping cart
        $_SESSION["shopping_session"]->shopping_cart->removeItem($_GET["productId"], ($_GET["adjustBy"] * -1));
    }

    // Reload the shopping cart page
    Functions::goToPage("page.php?page=shopping-cart");
?>