<?php
    class Database {
        private static $_database;
        private static $allowCreate = false;
        private static $_connection;

        public function __construct(){
            if(!self::$allowCreate){
                throw new error("Cannot use new() constructor on this class. Please use getInstance() to access the singleton instance instead.");
            }
        }

        static public function getConnection(){
            if(!isset(self::$_connection)){
                try {
                    self::$_connection = new PDO("mysql:host=localhost;dbname=SSP2_Assignment01", "root", "");
                    self::$_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $err) {
                    echo "Error - " . $err->getMessage();
                }
            }
            return self::$_connection;
        }

        static public function addUser($newUser){

        }

        static public function removeUser($rmvUser){

        }

        static public function getProducts($numProducts=10, $category=1){
            $tempProducts = null;
            if($category == 1){
                $statement = self::getConnection()->prepare("SELECT * FROM sProduct LIMIT :numProducts;");
            } else {
                $statement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE category = :category LIMIT :numProducts;");
                $statement->bindParam(":category", $category);
            }
            $statement->bindParam(":numProducts", $numProducts, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>