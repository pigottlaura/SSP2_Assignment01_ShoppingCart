<?php
    include_once("./config.php");
    class Database {
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
                    self::$_connection = new PDO("mysql:host=" . CONF_DB_HOST . ";dbname=" . CONF_DB_NAME, CONF_DB_USERNAME, CONF_DB_PASSWORD);
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

        static public function validateUser($username, $password){
            $statement = self::getConnection()->prepare("SELECT id FROM sUser WHERE username = :username AND password = SHA1(:password);");
            $statement->bindParam(":username", $username);
            $statement->bindParam(":password", $password);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $userId = $result["id"];
            return $userId;
        }

        static public function getUserDetails($userId){
            $user = (object) array();

            $statement1 = self::getConnection()->prepare("SELECT first_name, last_name, email FROM sUser WHERE id = :userId;");
            $statement1->bindParam(":userId", $userId);
            $statement1->execute();
            $user->contact = $statement1->fetch(PDO::FETCH_ASSOC);

            $statement2 = self::getConnection()->prepare("SELECT * FROM sAddress WHERE user_id = :userId;");
            $statement2->bindParam(":userId", $userId);
            $statement2->execute();
            $user->address = $statement2->fetch(PDO::FETCH_ASSOC);

            return $user;
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

        static public function getOrderProductInfo($items){
            $tempProductIds = ShoppingCart::retrieveItemIds($items);
            $productIdsString = implode(",", $tempProductIds);
            $statement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE id IN ($productIdsString) ORDER BY name ASC;");
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

        static public function createOrder(&$order){
            $successful = false;
            $statement = self::getConnection()->prepare("INSERT INTO sOrder(ordered_by, order_total) VALUES(:ordered_by, :order_total);");
            $statement->bindParam(":ordered_by", $order->orderedBy);
            $statement->bindParam(":order_total", $order->orderTotal);
            if($statement->execute()){
                $order->orderId = self::$_connection->lastInsertId();
                if(self::addItemsToOrder($order)){
                    $successful = true;
                }
            }
            return $successful;
        }

        static private function addItemsToOrder(&$order){
            $successful = false;
            foreach($order->orderItems as $key => $item) {
                $statement = self::getConnection()->prepare("INSERT INTO sOrder_items(order_id, product_id, number_items) VALUES(:order_id, :product_id, :number_items);");
                $statement->bindParam(":order_id", $order->orderId);
                $statement->bindParam(":product_id", $item->itemId);
                $statement->bindParam(":number_items", $item->numItems);
                $successful = $statement->execute();
            }
            return $successful;
        }
    }
?>