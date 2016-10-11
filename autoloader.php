<?php
    spl_autoload_register("myAutoLoader");

    function myAutoLoader($className) {
        $path = "./includes/classes/";
        include $path.$className . ".php";
    }

?>