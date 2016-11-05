<?php
    // Checking if the user is already logged in, and that there are items in the shopping cart, before proceeding
    if(isset($_SESSION["shopping_session"]->userId) && $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0){
        // Creating a new order object, using the temporary order details stored on the user's session
        // since the user viewed their "shopping_cart" (note - this value is reset every time the user view's their
        // shopping cart, to ensure it reflects the latest data
        $newOrder = new Order($_SESSION["shopping_session"]->tempOrderDetails);

        // Placing the order
        $newOrder->placeOrder();
    } else {
        // No user is logged in, so displaying the login/register form to them in path
        include("./includes/pages/login-register.inc");
    }
?>