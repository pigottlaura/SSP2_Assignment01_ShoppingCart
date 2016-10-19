<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>My Shopping Cart</title>
        <link rel="stylesheet" href="./css/styles.css">
        <link rel="icon" type="image/png" href="./images/logo.png" />
        <script src="js/script.js"></script>
        <?php
            include_once("./autoloader.php");
            session_start();
            if(!$_SESSION) {
                $_SESSION["shopping_session"] = (object) array(
                    "shopping_cart" => new ShoppingCart()
                );
                //echo "new session";
            } else {
                //echo "existing session";
            }
            if(isset($_POST["addToOrder"])){
            } else if(isset($_POST["removeFromOrder"])){

            }
        ?>
    </head>
    <body>
        <?php
            include_once("includes/templates/header.inc");
        ?>
        <section>
            <?php
                if(isset($_GET["page"])){
                    if($_GET["page"] == "products"){
                        $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
                        $products = Database::getProducts(10, $category);
                        foreach($products as $product){
                            echo "<div class='productContainer'>";
                            echo "<h4>" . $product["name"] . "</h4>";
                            echo "<figure>";
                            echo "<img src='./images/products/" . $product["image"] . "' alt='" . $product["name"] . "'>";
                            echo "<figcaption>" . $product["description"] . "</figcaption>";
                            echo "</figure>";
                            echo "<div class='price'>€" . $product["price"] . "</div>";
                            echo "</div>";
                        }
                    }
                }
            ?>
        </section>

        <?php
            include_once("includes/templates/footer.inc");
        ?>
    </body>
</html>