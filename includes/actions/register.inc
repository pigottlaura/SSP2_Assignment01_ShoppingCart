<?php
    // Checking that at least one of the required fields has been provided (as this is the one that could
    // cause an error when it is being passed to the enum password array)
    if(isset($_POST["confirm_password"])) {
        $dataValidated = InputData::validate($_POST, array(
            "empty" => array("honeypot"),
            "required" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
            "string" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
            "email" => array("email"),
            "enum" => array("password" => array($_POST["confirm_password"]))
        ));

        if ($dataValidated) {
            $sanitisedData = InputData::sanitise($_POST);

            if (Database::addUser($sanitisedData)) {
                if ($_GET["page"] == "login-register") {
                    Functions::goToPage("page.php?page=products");
                } else {
                    Functions::removeFromQueryString("action=");
                }
            } else {
                echo "Unable to add user";
                Functions::removeFromQueryString("action=");
            }
        }
    } else {
        Functions::goToPage("page.php?page=login-register");
    }
?>