<?php
    $validateDeliveryDetails = InputData::validate($_POST, array(
    ));

    if($validateDeliveryDetails["dataValidated"]){
        $sanitisedData = InputData::sanitise($_POST);

        $_SESSION["shopping_session"]->shopping_cart->tempOrderItemDetails->deliveryDetails = (object) array(
            "contact" => (object) array(
                "recipient_first_name" => $sanitisedData["first_name"],
                "recipient_last_name" => $sanitisedData["last_name"]
            ),
            "address" => (object) array(
                "recipient_houseName" => $sanitisedData["houseName"],
                "recipient_street" => $sanitisedData["street"],
                "recipient_town" => $sanitisedData["town"],
                "recipient_county" => $sanitisedData["county"],
                "recipient_country" => $sanitisedData["country"],
                "recipient_zipCode" => $sanitisedData["zipCode"]
            )
        );
        Functions::goToPage("page.php?page=shopping-cart&action=place-order");
    } else {
        echo "Invalid Address";
    }

?>