<?php
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $login = Login::validateLogin($_POST);
        if($login["successful"]){
            if($_GET["page"] == "login-register"){
                Functions::goToPage("page.php?page=products");
            } else if($_GET["page"] == "shopping-cart") {
                Functions::goToPage("page.php?page=shopping-cart&action=order");
            } else {
                Functions::removeFromQueryString("action=");
            }
        } else {
            echo "Unsuccessful login<br>";
            foreach ($login["errors"] as $key => $value){
                echo $value . "<br>";
            }
        }
    } else {
        Functions::removeFromQueryString("action=");
    }
?>