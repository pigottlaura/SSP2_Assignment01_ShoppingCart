<?php
    // Checking if a user is logged in
    if(isset($_SESSION["shopping_session"]->userId)){

        // Getting the current user details of the logged in user
        $userDetails = Database::getUserDetails($_SESSION["shopping_session"]->userId);

        echo "<h2>Update My Details</h2>";
        echo "<form method='POST' action='page.php?" . $_SERVER["QUERY_STRING"] . "&action=update-details' id='update-details'>";
        echo "<label>First Name:";
        echo "<input type='text' name='first_name' required value='" . $userDetails->contact["first_name"] . "'>";
        echo "</label>";
        echo "<label>Last Name:";
        echo "<input type='text' name='last_name' required value='" . $userDetails->contact["last_name"] . "'>";
        echo "</label>";
        echo "<br>";

        echo "<label>Email:";
        echo "<input type='email' name='email' required  value='" . $userDetails->contact["email"] . "'>";
        echo "</label>";
        echo "<label>Username:";
        echo "<input type='text' disabled  value='" . $userDetails->contact["username"] . "'>";
        echo "</label>";
        echo "<br>";

        // Displaying a placeholder for the password, with a button that allows the user
        // to change it (but never see it)
        echo "<label id='password'>Password:";
        echo "<input type='text' disabled value='**********'>";
        echo "<button type='button' id='changePassword'>Change Password</button>";
        echo "</label>";

        // If a user clicks the "changePassword" button above, the following fieldset will
        // be displayed, to allow them to update their password
        echo "<fieldset id='newPassword' class='hidden'>";
        echo "<label>New Password:";
        echo "<input type='password' name='password' data-match='confirm_password'>";
        echo "</label>";
        echo "<label>Confirm Password:";
        echo "<input type='password' name='confirm_password'>";
        echo "<span id='passwordMatch' class='icon'></span>";
        echo "</label>";
        echo "</fieldset>";

        // Checking if the user already has an address saved
        if(isset($userDetails->address)){
            // Setting current address data, so that it can be used in the address_inputs below
            $currentAddressData = $userDetails->address;
        } else {
            // Giving the user the option to add an address
            echo "<button type='button' id='addAddress'>Add Postal Address</button>";
        }

        // Address Fieldset
        echo "<fieldset id='address'";
        if(!isset($currentAddressData)) {
            // Hiding the form until the user decides to add an address
            echo "class='hidden'";
        }
        echo ">";
        if(isset($currentAddressData)){
            // Adding a hidden form element to mark that this user already has an
            // address, and so any changes made in the form should update their
            // existing address, and not create a new one (a similar input would be
            // added client side to identify if a new address is being added
            echo "<input type='hidden' name='address_change'>";
        }
        include("./includes/pages/components/address_inputs.inc");
        echo "</fieldset>";

        echo "<br>";

        echo "<input type='reset' value='Reset'>";
        echo "<input type='submit' value='Update My Details'>";
        echo "</form>";
    } else {
        // No user is loggged in
        Functions::goToPage("page.php?page=login-register");
    }
?>