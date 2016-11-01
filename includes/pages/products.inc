<select id="sortBy">
    <option value="price-asc">Price (Ascending)</option>
    <option value="price-desc">Price (Descending)</option>
    <option value="name-asc">Name (Ascending)</option>
    <option value="name-desc">Name (Descending)</option>
    <option value="date_added-asc">Latest (Ascending)</option>
    <option value="date_added-desc">Latest (Descending)</option>
</select>
<?php
    $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
    $sortDataValid = InputData::validate(array(
        $_COOKIE
    ), array(
        "required" => array("sortBy", "sortOrder"),
        "enum" => array(
            "sortBy" => array("price", "name", "date_added"),
            "sortOrder" => array("asc", "desc")
        )
    ));

    if($sortDataValid){
        $products = Database::getProducts(10, $category, $_COOKIE["sortBy"], $_COOKIE["sortOrder"]);
    } else {
        $products = Database::getProducts(10, $category, "id", "desc");
    }

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