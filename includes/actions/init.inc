<?php
    include_once("./autoloader.php");
    include_once("./config.php");

    if(CONF_DEBUG){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    session_start();

    if(!$_SESSION) {
        $_SESSION["shopping_session"] = (object) array(
            "shopping_cart" => new ShoppingCart()
        );
    }

    if(isset($_GET["productId"])){
        $_SESSION["shopping_session"]->shopping_cart->addItem($_GET["productId"]);
        Functions::removeFromQueryString("productId=");
    } else if(isset($_GET["emptyCart"])) {
        $_SESSION["shopping_session"]->shopping_cart->emptyCart();
        Functions::removeFromQueryString("emptyCart");
    }
?>