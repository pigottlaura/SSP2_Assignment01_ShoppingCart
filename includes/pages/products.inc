<?php
    $numRows = 4;
    $startRow = 0;
    setcookie("lastPage", false, time() + 2500, "/");
    $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
    $heading = $category > 1 ? Database::getCategoryName($category) : "All Products";
    $sortBy = "name";
    $sortOrder = "asc";

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
        $numRows = $_COOKIE["itemsPerPage"];
        $sortBy = $_COOKIE["sortBy"];
        $sortOrder = $_COOKIE["sortOrder"];
        $startRow = $_COOKIE["productPage"] * $numRows;
    }
    echo "<h2>" . $heading . "</h2>";
?>
<label for="sortBy">Sort Products by:</label>
<select id="sortBy">
    <option class="hidden" selected disabled></option>
    <option value="price-asc">Price (Ascending)</option>
    <option value="price-desc">Price (Descending)</option>
    <option value="name-asc">Name (Ascending)</option>
    <option value="name-desc">Name (Descending)</option>
    <option value="date_added-desc">Latest</option>
</select>

<label for="itemsPerPage">Items Per Page:</label>
<select id="itemsPerPage">
    <option class="hidden" selected disabled></option>
    <option value="2">2</option>
    <option value="4">4</option>
    <option value="8">8</option>
    <option value="20">20</option>
    <option value="50">50</option>
</select>
<?php
    $products = Database::getProducts($startRow . "," . $numRows, $category, $sortBy, $sortOrder);

    if(count($products) == 0){
        setcookie("lastPage", true, time() + 2500, "/");
        setcookie("productPage", $_COOKIE["productPage"] - 1);
        Functions::reloadPage();
    }

    echo "<div id='products'>";
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
    echo "</div>";

    echo "<div align='center'>";
        // Previous Page Button
        echo "<button id='prevPage'";
        if($startRow == 0){
            echo " disabled";
        }
        echo "><<< Previous " . $numRows . " products</button>";

        // Next Page Button
        echo "<button id='nextPage'";
        if(isset($_COOKIE["lastPage"]) || count($products) < $numRows){
            echo " disabled";
        }
        echo "> Next " . $numRows . " products >>></button>";
    echo "</div>";
?>