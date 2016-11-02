<?php
    // Checking if the user is already logged in, and that there are items in the shopping cart, before proceeding
    if(isset($_SESSION["shopping_session"]->userId) && $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0){
        $newOrder = new Order($_SESSION["shopping_session"]->shopping_cart->tempOrderItemDetails);
        $newOrder->placeOrder();
    } else {
        include("./includes/pages/login-register.inc");
    }
?>