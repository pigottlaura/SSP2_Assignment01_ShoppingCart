<?php
    if($_SERVER['SERVER_NAME'] == "localhost"){
        define("CONF_DB_HOST", "localhost");
        define("CONF_DB_NAME", "SSP2_Assignment01");
        define("CONF_DB_USERNAME", "root");
        define("CONF_DB_PASSWORD", "");
    } else {
        define("CONF_DB_HOST", "172.16.2.233");
        define("CONF_DB_NAME", "db1281003_SSP2_Assignment01");
        define("CONF_DB_USERNAME", "u1281003_root");
        define("CONF_DB_PASSWORD", "ABCdef123456");
    }
?>