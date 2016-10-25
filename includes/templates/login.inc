<?php
    if(isset($_POST["username"]) && isset($_POST["password"])){
        if(Login::validateLogin($_POST["username"], $_POST["password"])){
            Functions::goToPage("page.php?page=order");
        } else {
            echo "Unsuccessful login";
        }
    } else {
        Functions::removeFromQueryString($_SERVER['QUERY_STRING'], "page=", "page.php?page=home");
    }
?>