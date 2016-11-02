<?php
    include_once("./autoloader.php");

    if($_GET["action"] == "getProducts"){
        Products::display($_GET["category"]);
    }
?>