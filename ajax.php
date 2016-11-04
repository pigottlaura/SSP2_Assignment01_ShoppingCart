<?php
    include_once("./includes/actions/init.inc");

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
            case "checkUsernameAvailability": {
                $usernameAvailability = Register::checkUsernameAvailability($_GET["requestedUsername"]);
                $response = array(
                    "usernameAvailable" => $usernameAvailability["usernameAvailable"],
                    "dataValidated" => $usernameAvailability["dataValidated"],
                    "username" => $_GET["requestedUsername"]
                );
                echo json_encode($response);
                break;
            }
        }
    }
?>