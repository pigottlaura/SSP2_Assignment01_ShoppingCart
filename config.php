<?php
    define("CONF_DEBUG", true);
    define("CONF_COMP_NAME", "Blueberry Toys");
    define("CONF_OWNER_EMAIL", "k00190475@student.lit.ie");
    define("CONF_ORDERS_EMAIL", "orders@pigottlaura.com");
    define("CONF_ORDERS_WORKING_DAYS", "2");

    define("CONF_COMP_ADDRESS_HOUSENAME", "The Showgrounds");
    define("CONF_COMP_ADDRESS_STREET", "5 Wolfe Tone Street");
    define("CONF_COMP_ADDRESS_TOWN", "Clonmel");
    define("CONF_COMP_ADDRESS_COUNTY", "Tipperary");
    define("CONF_COMP_ADDRESS_COUNTRY", "Ireland");
    define("CONF_COMP_ADDRESS_ZIPCODE", "YNZZ44");

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