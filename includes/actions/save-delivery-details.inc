<?php
    // Validating all details provided in the request
    $validateDeliveryDetails = InputData::validate($_POST, array(
        "required" => array("first_name", "last_name", "houseName", "street", "town", "county", "country", "zipCode"),
        "string" => array("first_name", "last_name", "houseName", "street", "town", "county", "country", "zipCode"),
    ));

    // If all details were validated, proceeding to saving the delivery details
    if($validateDeliveryDetails["dataValidated"]){
        // Sanitising all data passed to the request
        $sanitisedData = InputData::sanitise($_POST);

        // Storing the delivery details in the temporary order details object, stored on the user's
        // session, so they can be brought forward to the next step of the order
        $_SESSION["shopping_session"]->tempOrderDetails->deliveryDetails = (object) array(
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

        // Taking the user on to the next step of the order process
        Functions::goToPage("page.php?page=shopping-cart&action=place-order");
    } else {
        // The details provided did not pass the validation
        echo "Invalid Address<br>";

        // Looping through any errors returned from the validation attempt and displaying them
        foreach ($validateDeliveryDetails["errors"] as $key => $value){
            echo $value . "<br>";
        }
    }

?>