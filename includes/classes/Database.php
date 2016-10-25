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
                    //self::$_connection = new PDO("mysql:host=localhost;dbname=SSP2_Assignment01", "root", "");
                    self::$_connection = new PDO("mysql:host=mysql2844.cp.blacknight.com;dbname=db1281003_SSP2_Assignment01", "u1281003_root", "ABCdef123456");
                    self::$_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $err) {
                    echo "Error - " . $err->getMessage();
                    echo "Error - " . $err->errorInfo();
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

        static public function getOrderProductInfo($productIds){
            $productIdsString = implode(",", $productIds);
            $statement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE id IN ($productIdsString);");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getItemPrice($itemId){
            $statement = self::getConnection()->prepare("SELECT price FROM sProduct WHERE id = :itemId;");
            $statement->bindParam(":itemId", $itemId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $price = $result["price"];
            return $price;
        }

        static public function getCategories(){
            $statement = self::getConnection()->prepare("SELECT * FROM sCategory;");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>