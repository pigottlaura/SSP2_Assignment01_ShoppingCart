<?php
    // Including all classes (through the autoloader) as well as the configuration file
    include_once("./autoloader.php");
    include_once("./config.php");

    // Forcing Server to display errors when running remotely (if Debug is turned on)
    if(CONF_DEBUG){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }

    session_start();

    // Initialising Session if it does not already exist
    if(!$_SESSION) {
        $_SESSION["shopping_session"] = (object) array(
            "shopping_cart" => new ShoppingCart()
        );
    }

    // Tidying Up URL
    if(isset($_GET["emptyCart"])) {
        $_SESSION["shopping_session"]->shopping_cart->emptyCart();
        Functions::removeFromQueryString("emptyCart");
    }
?>