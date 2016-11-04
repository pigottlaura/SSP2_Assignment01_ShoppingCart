<?php
    if(isset($_SESSION["shopping_session"]->userId)){
        $userDetails = Database::getUserDetails($_SESSION["shopping_session"]->userId);

        echo "<h2>Update My Detials</h2>";
        echo "<form method='POST' action='";
            echo "page.php?" . $_SERVER["QUERY_STRING"] . "&action=update-details' id='update-details'>";
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

        echo "<label id='password'>Password:";
        echo "<input type='text' disabled value='**********'>";
        echo "<button type='button' id='changePassword'>Change Password</button>";
        echo "</label>";

        echo "<fieldset id='newPassword' class='hidden'>";
        echo "<label>New Password:";
        echo "<input type='password' name='password' data-match='confirm_password'>";
        echo "</label>";
        echo "<label>Confirm Password:";
        echo "<input type='password' name='confirm_password'>";
        echo "<span id='passwordMatch' class='icon'></span>";
        echo "</label>";
        echo "</fieldset>";

        if(isset($userDetails->address)){
            echo $userDetails->address;
        }
        echo "<br>";

        echo "<input type='submit' value='Update My Details'>";
        echo "</form>";
    } else {
        Functions::goToPage("page.php?page=login-register");
    }
?>