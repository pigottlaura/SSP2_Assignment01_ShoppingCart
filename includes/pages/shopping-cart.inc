<?php
    if ($_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() > 0) {
        $orderTotal = 0;

        echo "<h1>Order Details</h1>";
        foreach ($_SESSION["shopping_session"]->shopping_cart->getItemsDetails() as $item => $itemInfo) {
            $numItems = $_SESSION["shopping_session"]->shopping_cart->getItem($itemInfo["id"])->numItems;
            $orderTotal += $itemInfo["price"] * $numItems;

            echo "<div class='cartItemContainer'>";
            echo "<img src='./images/products/" . $itemInfo["image"] . "' alt='" . $itemInfo["name"] . "'>";
            echo "<strong>" . $itemInfo["name"] . "</strong>";
            echo " @ €" . $itemInfo["price"] . " x " . $numItems . " = €" . ($itemInfo["price"] * $numItems);
            echo "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=adjustNumItems&adjustBy=-1&productId=" . $itemInfo["id"] . "' class='addNumItems'><button>-</button></a>";
            echo "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=adjustNumItems&adjustBy=1&productId=" . $itemInfo["id"] . "' class='removeNumItems'><button>+</button></a>";
            echo "</div>";
        }
        echo "Total: €" . $orderTotal;
        echo "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=order'><button class='placeOrder'>Place Order</button></a>";
        echo "<a href='page.php?page=shopping-cart&emptyCart'><button>Empty Cart</button></a>";
        $_SESSION["shopping_session"]->shopping_cart->orderTotal = $orderTotal;
    } else {
        echo "<h2>Shopping Cart is Empty</h2>";
        echo "<img src='./images/emptyShoppingBasket.png'>";
    }
?>