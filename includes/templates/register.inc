<?php
    if(InputData::validate($_POST)){
        $sanitisedData = InputData::sanitise($_POST);

        if(Database::addUser($sanitisedData)){
            Functions::goToPage("page.php?page=order");
        } else {
            echo "Unable to add user";
        }
    } else {
        Functions::removeFromQueryString($_SERVER['QUERY_STRING'], "page=", "page.php?page=home");
    }
?>