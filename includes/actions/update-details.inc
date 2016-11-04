<?php
    $updateDetails = Login::updateUserDetails($_POST);
    if ($updateDetails["successful"]) {
        Functions::goToPage("page.php?page=home");
    } else {
        echo "Unable to update user details<br>";
        foreach ($updateDetails["errors"] as $key => $value){
            echo $value . "<br>";
        }
    }
?>