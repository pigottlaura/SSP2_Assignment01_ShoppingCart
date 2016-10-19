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
                echo "new session";
            } else {
                echo "existing session";
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
                    echo $_GET["page"];
                }
            ?>
        </section>

        <?php
            include_once("includes/templates/footer.inc");
        ?>
    </body>
</html>