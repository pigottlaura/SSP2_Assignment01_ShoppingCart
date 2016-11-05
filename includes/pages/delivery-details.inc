<?php
    // Checking if a user is logged in
    if(isset($_SESSION["shopping_session"]->userId)){

        // Getting the current details of the user
        $userDetails = Database::getUserDetails($_SESSION["shopping_session"]->userId);

        // Checking if the user already has a postal address
        if(isset($userDetails->address)){
            // Setting current address data, so it can be used in the address_fieldset below
            $currentAddressData = $userDetails->address;
        }

        echo "<h2>Delivery Details</h2>";

        echo "<form method='POST' action='page.php?" . $_SERVER["QUERY_STRING"] . "&action=save-delivery-details' id='delivery-details'>";

        echo "<label>First Name:";
        echo "<input type='text' name='first_name' required value='" . $userDetails->contact["first_name"] . "'>";
        echo "</label>";
        echo "<label>Last Name:";
        echo "<input type='text' name='last_name' required value='" . $userDetails->contact["last_name"] . "'>";
        echo "</label>";
        echo "<br>";

        // Address Fieldset
        echo "<fieldset id='address'>";
        include("./includes/pages/components/address_inputs.inc");
        echo "</fieldset>";

        echo "<br>";

        echo "<input type='button' value='Clear Address' id='resetFields'>";
        echo "<input type='reset' value='Reset to my Address'>";
        echo "<input type='submit' value='Confirm Address'>";
        echo "</form>";
    } else {
        // No user is logged in
        Functions::goToPage("page.php?page=login-register");
    }
?>