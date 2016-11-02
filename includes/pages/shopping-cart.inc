<?php
    if ($_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0) {
        $_SESSION["shopping_session"]->shopping_cart->orderTotal = 0;
        $_SESSION["shopping_session"]->shopping_cart->tempOrderItemDetails = (object) array();

        foreach ($_SESSION["shopping_session"]->shopping_cart->getItemsDetails() as $item => $itemInfo) {
            $itemInfo["numItems"] = $_SESSION["shopping_session"]->shopping_cart->getItem($itemInfo["id"])->numItems;
            $_SESSION["shopping_session"]->shopping_cart->tempOrderItemDetails->$itemInfo["id"] = $itemInfo;
        }

        ShoppingCart::display();
    } else {
        echo "<h2>Shopping Cart is Empty</h2>";
        echo "<img src='./images/emptyShoppingBasket.png'>";
    }
?>