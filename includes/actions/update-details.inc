<?php
    // Passing the request to the Login class to update the user's details (where the data
    // will be sanitised and validates)
    $updateDetails = Login::updateUserDetails($_POST);
    // Determing if the update to the user's details was successful, based on the response object
    if ($updateDetails["successful"]) {
        Functions::goToPage("page.php?page=home");
    } else {
        // The update to the user's deat
        echo "Unable to update user details<br>";

        // Looping through any errors that were returned from the registeration attempt (which
        // could have resulted from validation, sanitising or inserting of the data into the database)
        foreach ($updateDetails["errors"] as $key => $value){
            echo $value . "<br>";
        }
    }
?>