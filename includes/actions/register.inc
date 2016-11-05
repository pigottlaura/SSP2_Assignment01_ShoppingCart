<?php
    // Checking that at least one of the required fields has been provided (as this is the one that could
    // cause an error when it is being passed to the enum password array)
    if(isset($_POST["confirm_password"])) {
        // Attempting to register the user through the Register class (where the data will be fully
        // validated and sanitised)
        $register = Register::registerNewUser($_POST);

        // Checking if the registeration was successful
        if ($register["successful"]) {

            // Sending an email to the user to welcome them to their new account
            $emailSent = Email::sendNewUserEmail($_SESSION["shopping_session"]->userId);

            // Determing where to redirect the user based on the current page they are on
            if ($_GET["page"] == "login-register") {
                Functions::goToPage("page.php?page=products");
            } else {
                Functions::removeFromQueryString("action=");
            }
        } else {
            // This registeration was unsuccessful
            echo "Unable to add user<br>";

            // Looping through any errors that were returned from the registeration attempt (which
            // could have resulted from validation, sanitising or inserting of the data into the database)
            foreach ($register["errors"] as $key => $value){
                echo $value . "<br>";
            }
        }
    } else {
        // Not enough details provided, redirecting the user back to the login/register page
        Functions::goToPage("page.php?page=login-register");
    }
?>