<?php
    define("CONF_DEBUG", true);

    define("CONF_OWNER_EMAIL", "k00190475@student.lit.ie");
    define("CONF_ORDERS_EMAIL", "orders@pigottlaura.com");
    define("CONF_ORDERS_WORKING_DAYS", "2");

    define("CONF_COMP_NAME", "Blueberry Toys");
    define("CONF_COMP_CONTACT_EMAIL", "info@pigottlaura.com");
    define("CONF_COMP_CONTACT_NUMBER", "0502-1234567");
    define("CONF_COMP_MAP" , "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2436.376600116386!2d-7.707434684198373!3d52.36359097978498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x484331fee0a6c64f%3A0xe258de902f5f1b83!2sLIT+Clonmel!5e0!3m2!1sen!2sie!4v1478359970383");

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