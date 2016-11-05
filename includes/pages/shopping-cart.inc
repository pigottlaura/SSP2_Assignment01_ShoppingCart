<?php
    // Checking that there are items in the shopping cart
    if ($_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0) {

        $_SESSION["shopping_session"]->shopping_cart->orderTotal = 0;

        // Temporarily storing the order items and delivery details on the session
        // to streamline the process of adding address, and placing the order on the next steps
        $_SESSION["shopping_session"]->tempOrderDetails = (object) array(
            "orderItems" => (object) array(),
            "deliveryDetails" => (object) array()
        );

        // Looping through each item in the shopping cart and adding it (and the number of items required)
        // to the temporary order data
        foreach ($_SESSION["shopping_session"]->shopping_cart->getItemsDetails() as $item => $itemInfo) {
            $itemInfo["numItems"] = $_SESSION["shopping_session"]->shopping_cart->getItem($itemInfo["id"])->numItems;
            $_SESSION["shopping_session"]->tempOrderDetails->orderItems->$itemInfo["id"] = $itemInfo;
        }

        ShoppingCart::display();
    } else {
        echo "<h2>Shopping Cart is Empty</h2>";
        echo "<img src='./images/emptyShoppingBasket.png'>";
    }
?>