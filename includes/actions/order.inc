<?php
    if(isset($_SESSION["shopping_session"]->userId) && $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0){
        $newOrder = new Order($_SESSION["shopping_session"]->shopping_cart->getItems());
        $newOrder->placeOrder();
    } else {
        include("./includes/templates/login-register.inc");
    }
?>