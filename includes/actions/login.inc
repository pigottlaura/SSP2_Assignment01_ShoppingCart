<?php
    if(isset($_POST)){
        // Ensuring that all required fields have been supplied
        $validateData = InputData::validate($_POST, array(
            "required" => array("username", "password")
        ));

        // If all required fields have been supplied, attempting a login
        if($validateData){
            // Validating the login through the login class, where the data
            // will be fully validated and sanitised
            $login = Login::validateLogin($_POST);

            // Checking if the login was successful
            if($login["successful"]){
                // Determing where to redirect the user to, based on their current page
                if($_GET["page"] == "login-register"){
                    Functions::goToPage("page.php?page=products");
                } else if($_GET["page"] == "shopping-cart") {
                    Functions::goToPage("page.php?page=delivery-details");
                } else {
                    Functions::removeFromQueryString("action=");
                }
            } else {
                // This login was unsuccessful
                echo "Unsuccessful login<br>";

                // Looping through any errors returned from the login attempt and displaying them
                foreach ($login["errors"] as $key => $value){
                    echo $value . "<br>";
                }
            }
        } else {
            // Removing the action from the string, as not all required fields were supplied
            Functions::removeFromQueryString("action=");
        }
    } else {
        // Removing the action from the string, as not no fields were supplied
        Functions::removeFromQueryString("action=");
    }

?>