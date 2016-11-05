<?php
    include_once("./config.php");
    class Database {
        private static $allowCreate = false;
        private static $_connection;

        public function __construct(){
            if(!self::$allowCreate){
                throw new Exception("Cannot instantiate this class. Please use the static methods provided to access the database instead.");
            }
        }

        static private function getConnection(){
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

        static public function addUser($inputData){
            $statement = self::getConnection()->prepare("INSERT INTO sUser(first_name, last_name, email, username, password) VALUES(:first_name, :last_name, :email, :username, SHA1(:password));");
            $statement->bindParam(":first_name", $inputData["first_name"]);
            $statement->bindParam(":last_name", $inputData["last_name"]);
            $statement->bindParam(":email", $inputData["email"]);
            $statement->bindParam(":username", $inputData["username"]);
            $statement->bindParam(":password", $inputData["password"]);
            $successful = $statement->execute();

            if($successful){
                $userId = self::$_connection->lastInsertId();
                Login::addUserToSession($userId);
            }

            return $successful;
        }

        static public function checkUsernameAvailability($username){
            $statement = self::getConnection()->prepare("SELECT * FROM sUser WHERE username = :username;");
            $statement->bindParam(":username", $username);
            $statement->execute();
            $result = $statement->fetchAll();
            $usernameAvailable = count($result) > 0 ? false : true;
            return $usernameAvailable;
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

            $statement1 = self::getConnection()->prepare("SELECT first_name, last_name, email, username FROM sUser WHERE id = :userId;");
            $statement1->bindParam(":userId", $userId);
            $statement1->execute();
            $user->contact = $statement1->fetch(PDO::FETCH_ASSOC);

            $statement2 = self::getConnection()->prepare("SELECT * FROM sAddress WHERE user_id = :userId;");
            $statement2->bindParam(":userId", $userId);
            $statement2->execute();
            $addressResult = $statement2->fetchAll(PDO::FETCH_ASSOC);
            if(count($addressResult) > 0){
                $user->address = $addressResult[0];
            }

            return $user;
        }

        static public function updateUserDetails($newUserDetails){
            if(isset($newUserDetails["password_change"])){
                $statement = self::getConnection()->prepare("UPDATE sUser SET first_name=:first_name, last_name=:last_name, email=:email, password=SHA1(:password) WHERE id=:userId;");
                $statement->bindParam(":password", $newUserDetails["password"]);
                var_dump($statement);
            } else {
                $statement = self::getConnection()->prepare("UPDATE sUser SET first_name=:first_name, last_name=:last_name, email=:email WHERE id=:userId;");
            }
            $statement->bindParam(":first_name", $newUserDetails["first_name"]);
            $statement->bindParam(":last_name", $newUserDetails["last_name"]);
            $statement->bindParam(":email", $newUserDetails["email"]);
            $statement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
            $successful = $statement->execute();

            if(isset($newUserDetails["address_change"]) ||isset($newUserDetails["address_new"])){
                if(isset($newUserDetails["address_change"])){
                    $statement2 = self::getConnection()->prepare("UPDATE sAddress SET houseName=:houseName, street=:street, town=:town, county=:county, country=:country, zipCode=:zipCode WHERE user_id = :userId;");
                } else if(isset($newUserDetails["address_new"])){
                        $statement2 = self::getConnection()->prepare("INSERT INTO sAddress(user_id, address_houseName, street, town, county, country, zipCode) VALUES(:userId, :houseName, :street, :town, :county , :country, :zipCode)");
                    }
                    $statement2->bindParam(":userId", $_SESSION["shopping_session"]->userId);
                    $statement2->bindParam(":houseName", $newUserDetails["houseName"]);
                    $statement2->bindParam(":street", $newUserDetails["street"]);
                    $statement2->bindParam(":town", $newUserDetails["town"]);
                    $statement2->bindParam(":county", $newUserDetails["county"]);
                    $statement2->bindParam(":country", $newUserDetails["country"]);
                    $statement2->bindParam(":zipCode", $newUserDetails["zipCode"]);

                    $successful *= $statement2->execute();
            }

            return $successful;
        }

        static public function getProducts($numProducts=10, $category=1, $orderBy="name", $ascDesc="desc"){
            $ascDesc = strtoupper($ascDesc);

            if($category == 1){
                $statement = self::getConnection()->prepare("SELECT * FROM sProduct ORDER BY " . $orderBy . " " . $ascDesc . " LIMIT " . $numProducts . ";");
            } else {
                $statement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE category = :category ORDER BY " . $orderBy . " " . $ascDesc . " LIMIT " . $numProducts . ";");
                $statement->bindParam(":category", $category);
            }
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrderProductInfo($items){
            $tempProductIds = ShoppingCart::retrieveItemIds($items);
            $productIdsString = implode(",", $tempProductIds);
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

        static public function getCategoryName($categoryId) {
            $statement = self::getConnection()->prepare("SELECT * FROM sCategory WHERE id = :categoryId;");
            $statement->bindParam(":categoryId", $categoryId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $name = $result["name"];
            return $name;
        }

        static public function createOrder(&$order){
            $successful = false;
            $order_statement = self::getConnection()->prepare("INSERT INTO sOrder(ordered_by, order_total, recipient_first_name, recipient_last_name) VALUES(:ordered_by, :order_total, :recipient_first_name, :recipient_last_name);");
            $order_statement->bindParam(":ordered_by", $order->orderedBy);
            $order_statement->bindParam(":order_total", $order->orderTotal);
            $order_statement->bindParam(":recipient_first_name", $order->deliveryDetails->contact->recipient_first_name);
            $order_statement->bindParam(":recipient_last_name", $order->deliveryDetails->contact->recipient_last_name);
            if($order_statement->execute()){
                $order->orderId = self::$_connection->lastInsertId();
                if(self::addItemsToOrder($order)){
                    $address_statement = self::getConnection()->prepare("INSERT INTO sAddress(order_id, houseName, street, town, county, country, zipCode) VALUES(:order_id, :houseName, :street, :town, :county, :country, :zipCode);");
                    $address_statement->bindParam(":order_id", $order->orderId);
                    $address_statement->bindParam(":houseName", $order->deliveryDetails->address->recipient_houseName);
                    $address_statement->bindParam(":street", $order->deliveryDetails->address->recipient_street);
                    $address_statement->bindParam(":town", $order->deliveryDetails->address->recipient_town);
                    $address_statement->bindParam(":county", $order->deliveryDetails->address->recipient_county);
                    $address_statement->bindParam(":country", $order->deliveryDetails->address->recipient_country);
                    $address_statement->bindParam(":zipCode", $order->deliveryDetails->address->recipient_zipCode);

                    if($address_statement->execute()){
                        $successful = true;
                    }
                }
            }

            return $successful;
        }

        static private function addItemsToOrder(&$order){
            $successful = false;
            foreach($order->orderItems as $item => $itemDetails) {
                $statement = self::getConnection()->prepare("INSERT INTO sOrder_items(order_id, product_id, product_name, number_items, selling_price) VALUES(:order_id, :product_id, :product_name, :number_items, :selling_price);");
                $statement->bindParam(":order_id", $order->orderId);
                $statement->bindParam(":product_id", $itemDetails["id"]);
                $statement->bindParam(":product_name", $itemDetails["name"]);
                $statement->bindParam(":number_items", $itemDetails["numItems"]);
                $statement->bindParam(":selling_price", $itemDetails["price"]);
                $successful = $statement->execute();
            }
            return $successful;
        }

        static public function getUsersOrders($userId){
            $statement = self::getConnection()->prepare("SELECT * FROM sOrder WHERE ordered_by = :userId");
            $statement->bindParam(":userId", $userId);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrder($orderId){
            $statement = self::getConnection()->prepare("SELECT * FROM sOrder WHERE id = :orderId");
            $statement->bindParam(":orderId", $orderId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        static public function getOrderItems($orderId){
            $statement = self::getConnection()->prepare("SELECT * FROM sOrder_items WHERE order_id = :orderId");
            $statement->bindParam(":orderId", $orderId);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrderAddress($orderId){
            $statement = self::getConnection()->prepare("SELECT * FROM sAddress WHERE order_id = :orderId");
            $statement->bindParam(":orderId", $orderId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }
    }
?>