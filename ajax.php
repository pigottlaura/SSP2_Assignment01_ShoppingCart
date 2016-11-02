<?php
    include_once("./autoloader.php");
    session_start();

    if(isset($_GET["action"])){
        switch ($_GET["action"]) {
            case "getProducts": {
                Products::display($_GET["category"]);
                break;
            }
            case "addToCart": {
                if(isset($_GET["productId"])) {
                    $_SESSION["shopping_session"]->shopping_cart->addItem($_GET["productId"]);
                    $updatedCartInfo = array(
                        "shoppingCartTotalItems" => $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems(),
                        "shoppingCartTotalCost" => $_SESSION["shopping_session"]->shopping_cart->calculateTotal()
                    );
                    echo json_encode($updatedCartInfo);
                }
                break;
            }
        }
    }
?>