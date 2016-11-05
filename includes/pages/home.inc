<h2>Welcome to My Shopping Cart</h2>
<h3>The one stop shop for toys</h3>


<h4>vvv LATEST PRODUCTS vvv</h4>
<?php
    // Getting the 3 latest products, from all products, ordered by the date
    // they were added to the database (descending)
    $latestProducts = Database::getProducts(3, 1, "date_added", "desc");
    foreach ($latestProducts as $product) {
        echo "<div class='latestProductContainer'>";
        echo "<h4>" . $product["name"] . "</h4>";
        echo "<figure>";
        echo "<img src='./images/products/" . $product["image"] . "' alt='" . $product["name"] . "'>";
        echo "<figcaption>" . $product["description"] . "</figcaption>";
        echo "</figure>";
        echo "<div class='price'>€" . $product["price"] . "</div>";
        echo "<button class='addToCart' id=" . $product["id"] . "'>Add to Cart</button>";
        echo "</div>";
    }
?>