<?php
    if(isset($_POST["username"]) && isset($_POST["password"])){
        if(Login::validateLogin($_POST)){
            if($_GET["page"] == "login-register"){
                Functions::goToPage("page.php?page=products");
            } else {
                Functions::removeFromQueryString("action=");
            }
        } else {
            echo "Unsuccessful login";
        }
    } else {
        Functions::removeFromQueryString("action=");
    }
?>