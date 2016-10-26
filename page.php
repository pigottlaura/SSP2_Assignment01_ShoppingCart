<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        ?>
        <meta charset="UTF-8">
        <title>My Shopping Cart</title>
        <link rel="stylesheet" href="./css/styles.css">
        <link rel="icon" type="image/png" href="./images/logo.png" />
        <script src="js/script.js"></script>
        <?php
            include_once("./autoloader.php");
            session_start();
            //session_destroy();
            if(!$_SESSION) {
                $_SESSION["shopping_session"] = (object) array(
                    "shopping_cart" => new ShoppingCart()
                );
                //echo "new session";
            } else {
                //echo "existing session";
            }
            if(isset($_GET["productId"])){
                $_SESSION["shopping_session"]->shopping_cart->addItem($_GET["productId"]);
                Functions::removeFromQueryString($_SERVER['QUERY_STRING'], "productId=", "page.php");
            } else if(isset($_GET["emptyCart"])) {
                $_SESSION["shopping_session"]->shopping_cart->emptyCart();
                Functions::removeFromQueryString($_SERVER['QUERY_STRING'], "emptyCart", "page.php");
            }
        ?>
    </head>
    <body>
        <?php
            include_once("includes/templates/header.inc");
        ?>
        <section>
            <?php
                if(isset($_GET["page"])) {
                    if ($_GET["page"] == "products") {
                        $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;
                        $products = Database::getProducts(10, $category);
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
                    } else if ($_GET["page"] == "shopping-cart") {
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
                                echo "</div>";
                            }
                            echo "Total: €" . $orderTotal;
                            echo "<a href='page.php?page=order'><button class='placeOrder'>Place Order</button></a>";
                            echo "<a href='page.php?page=shopping-cart&emptyCart'><button>Empty Cart</button></a>";
                            $_SESSION["shopping_session"]->shopping_cart->orderTotal = $orderTotal;
                        } else {
                            echo "<h1>Shopping Cart is Empty</h1>";
                        }
                    } else if ($_GET["page"] == "order") {
                        if(isset($_SESSION["shopping_session"]->userId)){
                            $newOrder = new Order($_SESSION["shopping_session"]->shopping_cart->getItems());
                            $newOrder->placeOrder();
                        } else {
                            include("./includes/templates/login-register.inc");
                        }
                    } else if($_GET["page"] == "login-register") {
                        include("./includes/templates/login-register.inc");
                    } else if($_GET["page"] == "login") {
                        include("./includes/templates/login.inc");
                    } else if($_GET["page"] == "logout") {
                        Login::logout();
                    } else {
                        echo "<h1>Welcome to My Shopping Cart</h1>";
                        echo "<h2>The one stop shop for toys</h2>";
                    }
                }
            ?>
        </section>

        <?php
            include_once("includes/templates/footer.inc");
        ?>
    </body>
</html>