<?php
    $dataValidated = InputData::validate($_POST, array(
        "empty" => array("honeypot"),
        "required" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
        "string" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
        "email" => array("email"),
        "enum" => array("password" => array($_POST["confirm_password"]))
    ));

    if($dataValidated){
        $sanitisedData = InputData::sanitise($_POST);

        if(Database::addUser($sanitisedData)){
            Functions::goToPage("page.php?page=order");
        } else {
            echo "Unable to add user";
        }
    }
?>