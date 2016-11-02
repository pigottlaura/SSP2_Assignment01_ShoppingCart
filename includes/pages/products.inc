<select id="sortBy">
    <option value="price-asc">Price (Ascending)</option>
    <option value="price-desc">Price (Descending)</option>
    <option value="name-asc">Name (Ascending)</option>
    <option value="name-desc">Name (Descending)</option>
    <option value="date_added-asc">Latest (Ascending)</option>
    <option value="date_added-desc">Latest (Descending)</option>
</select>
<?php
    $numRows = 2;
    $startRow = 0;
    setcookie("lastPage", false, time() + 2500, "/");
    $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
    $sortBy = "id";
    $sortOrder = "desc";

    $sortDataValid = InputData::validate(array(
        $_COOKIE
    ), array(
        "required" => array("sortBy", "sortOrder", "productPage"),
        "int" => array("productPage"),
        "enum" => array(
            "sortBy" => array("price", "name", "date_added"),
            "sortOrder" => array("asc", "desc")
        )
    ));

    if($sortDataValid){
        $sortBy = $_COOKIE["sortBy"];
        $sortOrder = $_COOKIE["sortOrder"];
        $startRow = $_COOKIE["productPage"] * $numRows;
    }

    $products = Database::getProducts($startRow . "," . $numRows, $category, $sortBy, $sortOrder);

    if(count($products) == 0){
        setcookie("lastPage", true, time() + 2500, "/");
        setcookie("productPage", $_COOKIE["productPage"] - 1);
        Functions::reloadPage();
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

    echo "<div align='center'>";
    echo "<button id='prevPage'";
    if($startRow == 0){
        echo " disabled";
    }
    echo "><<< Previous " . $numRows . " products</button>";
    echo "<button id='nextPage'";
    if(isset($_COOKIE["lastPage"])){
        echo " disabled";
    }
    echo "> Next " . $numRows . " products >>></button>";
    echo "</div>";
?>