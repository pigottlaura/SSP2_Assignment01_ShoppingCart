<?php
    $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
    $products = Database::getProducts(10, $category, "id", "desc");
    foreach ($products as $product) {
        echo "<div class='productContainer'>";
        echo "<h4>" . $product["name"] . "</h4>";
        echo "<figure>";
        echo "<img src='./images/products/" . $product["image"] . "' alt='" . $product["name"] . "'>";
        echo "<figcaption>" . $product["description"] . "</figcaption>";
        echo "</figure>";
        echo "<div class='price'>€" . $product["price"] . "</div>";
        echo "<a class='addToCart' href='?" . $_SERVER['QUERY_STRING'] . "&productId=" . $product["id"] . "'><button>Add to Cart</button></a>";
        echo "</div>";
    }
?>