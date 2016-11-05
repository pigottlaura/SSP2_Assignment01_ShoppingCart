<?php
    if(isset($_SESSION["shopping_session"]->userId)){
        $userDetails = Database::getUserDetails($_SESSION["shopping_session"]->userId);

        if(isset($userDetails->address)){
            // Setting current data, so it can be used in the address_fieldset below
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

        echo "<input type='submit' value='Confirm Address'>";
        echo "</form>";
    } else {
        Functions::goToPage("page.php?page=login-register");
    }
?>