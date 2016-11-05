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
            $addUserStatement = self::getConnection()->prepare("INSERT INTO sUser(first_name, last_name, email, username, password) VALUES(:first_name, :last_name, :email, :username, SHA1(:password));");
            $addUserStatement->bindParam(":first_name", $inputData["first_name"]);
            $addUserStatement->bindParam(":last_name", $inputData["last_name"]);
            $addUserStatement->bindParam(":email", $inputData["email"]);
            $addUserStatement->bindParam(":username", $inputData["username"]);
            $addUserStatement->bindParam(":password", $inputData["password"]);
            $successful = $addUserStatement->execute();

            if($successful){
                $userId = self::$_connection->lastInsertId();
                Login::addUserToSession($userId);
            }

            return $successful;
        }

        static public function checkUsernameAvailability($username){
            $usernameAvailabilityStatement = self::getConnection()->prepare("SELECT * FROM sUser WHERE username = :username;");
            $usernameAvailabilityStatement->bindParam(":username", $username);
            $usernameAvailabilityStatement->execute();

            $result = $usernameAvailabilityStatement->fetchAll();
            $usernameAvailable = count($result) > 0 ? false : true;
            return $usernameAvailable;
        }

        static public function validateUser($username, $password){
            $validateUserStatement = self::getConnection()->prepare("SELECT id FROM sUser WHERE username = :username AND password = SHA1(:password);");
            $validateUserStatement->bindParam(":username", $username);
            $validateUserStatement->bindParam(":password", $password);
            $validateUserStatement->execute();

            $result = $validateUserStatement->fetch(PDO::FETCH_ASSOC);
            $userId = $result["id"];
            return $userId;
        }

        static public function getUserDetails($userId){
            $user = (object) array();

            $userContactDetailsStatement = self::getConnection()->prepare("SELECT first_name, last_name, email, username FROM sUser WHERE id = :userId;");
            $userContactDetailsStatement->bindParam(":userId", $userId);
            $userContactDetailsStatement->execute();
            $user->contact = $userContactDetailsStatement->fetch(PDO::FETCH_ASSOC);

            $userAddressDetailsStatement = self::getConnection()->prepare("SELECT * FROM sAddress WHERE user_id = :userId;");
            $userAddressDetailsStatement->bindParam(":userId", $userId);
            $userAddressDetailsStatement->execute();

            $addressResult = $userAddressDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
            if(count($addressResult) > 0){
                $user->address = $addressResult[0];
            }

            return $user;
        }

        static public function updateUserDetails($newUserDetails){
            if(isset($newUserDetails["password_change"])){
                $userStatement = self::getConnection()->prepare("UPDATE sUser SET first_name=:first_name, last_name=:last_name, email=:email, password=SHA1(:password) WHERE id=:userId;");
                $userStatement->bindParam(":password", $newUserDetails["password"]);
            } else {
                $userStatement = self::getConnection()->prepare("UPDATE sUser SET first_name=:first_name, last_name=:last_name, email=:email WHERE id=:userId;");
            }
            $userStatement->bindParam(":first_name", $newUserDetails["first_name"]);
            $userStatement->bindParam(":last_name", $newUserDetails["last_name"]);
            $userStatement->bindParam(":email", $newUserDetails["email"]);
            $userStatement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
            $successful = $userStatement->execute();

            if(isset($newUserDetails["address_change"]) ||isset($newUserDetails["address_new"])){
                if(isset($newUserDetails["address_change"])){
                    $addressStatement = self::getConnection()->prepare("UPDATE sAddress SET houseName=:houseName, street=:street, town=:town, county=:county, country=:country, zipCode=:zipCode WHERE user_id = :userId;");
                } else if(isset($newUserDetails["address_new"])){
                    $addressStatement = self::getConnection()->prepare("INSERT INTO sAddress(user_id, address_houseName, street, town, county, country, zipCode) VALUES(:userId, :houseName, :street, :town, :county , :country, :zipCode)");
                }
                $addressStatement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
                $addressStatement->bindParam(":houseName", $newUserDetails["houseName"]);
                $addressStatement->bindParam(":street", $newUserDetails["street"]);
                $addressStatement->bindParam(":town", $newUserDetails["town"]);
                $addressStatement->bindParam(":county", $newUserDetails["county"]);
                $addressStatement->bindParam(":country", $newUserDetails["country"]);
                $addressStatement->bindParam(":zipCode", $newUserDetails["zipCode"]);

                $successful *= $addressStatement->execute();
            }

            return $successful;
        }

        static public function getProducts($numProducts=10, $category=1, $orderBy="name", $ascDesc="desc"){
            $ascDesc = strtoupper($ascDesc);

            if($category == 1){
                $productsStatement = self::getConnection()->prepare("SELECT * FROM sProduct ORDER BY " . $orderBy . " " . $ascDesc . " LIMIT " . $numProducts . ";");
            } else {
                $productsStatement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE category = :category ORDER BY " . $orderBy . " " . $ascDesc . " LIMIT " . $numProducts . ";");
                $productsStatement->bindParam(":category", $category);
            }
            $productsStatement->execute();
            return $productsStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrderProductInfo($items){
            $tempProductIds = ShoppingCart::retrieveItemIds($items);
            $productIdsString = implode(",", $tempProductIds);

            $productsInfoStatement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE id IN ($productIdsString);");
            $productsInfoStatement->execute();

            return $productsInfoStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getItemPrice($itemId){
            $itemPriceStatement = self::getConnection()->prepare("SELECT price FROM sProduct WHERE id = :itemId;");
            $itemPriceStatement->bindParam(":itemId", $itemId);
            $itemPriceStatement->execute();

            $result = $itemPriceStatement->fetch(PDO::FETCH_ASSOC);
            $price = $result["price"];
            return $price;
        }

        static public function getCategories(){
            $categoriesStatement = self::getConnection()->prepare("SELECT * FROM sCategory;");
            $categoriesStatement->execute();
            return $categoriesStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getCategoryName($categoryId) {
            $categoryNameStatement = self::getConnection()->prepare("SELECT * FROM sCategory WHERE id = :categoryId;");
            $categoryNameStatement->bindParam(":categoryId", $categoryId);
            $categoryNameStatement->execute();

            $result = $categoryNameStatement->fetch(PDO::FETCH_ASSOC);
            $name = $result["name"];
            return $name;
        }

        static public function createOrder(&$order){
            $successful = false;

            $orderStatement = self::getConnection()->prepare("INSERT INTO sOrder(ordered_by, order_total, recipient_first_name, recipient_last_name) VALUES(:ordered_by, :order_total, :recipient_first_name, :recipient_last_name);");
            $orderStatement->bindParam(":ordered_by", $order->orderedBy);
            $orderStatement->bindParam(":order_total", $order->orderTotal);
            $orderStatement->bindParam(":recipient_first_name", $order->deliveryDetails->contact->recipient_first_name);
            $orderStatement->bindParam(":recipient_last_name", $order->deliveryDetails->contact->recipient_last_name);

            if($orderStatement->execute()){
                $order->orderId = self::$_connection->lastInsertId();
                if(self::addItemsToOrder($order)){
                    $addressStatement = self::getConnection()->prepare("INSERT INTO sAddress(order_id, houseName, street, town, county, country, zipCode) VALUES(:order_id, :houseName, :street, :town, :county, :country, :zipCode);");
                    $addressStatement->bindParam(":order_id", $order->orderId);
                    $addressStatement->bindParam(":houseName", $order->deliveryDetails->address->recipient_houseName);
                    $addressStatement->bindParam(":street", $order->deliveryDetails->address->recipient_street);
                    $addressStatement->bindParam(":town", $order->deliveryDetails->address->recipient_town);
                    $addressStatement->bindParam(":county", $order->deliveryDetails->address->recipient_county);
                    $addressStatement->bindParam(":country", $order->deliveryDetails->address->recipient_country);
                    $addressStatement->bindParam(":zipCode", $order->deliveryDetails->address->recipient_zipCode);

                    if($addressStatement->execute()){
                        $successful = true;
                    }
                }
            }

            return $successful;
        }

        static private function addItemsToOrder(&$order){
            $successful = false;
            foreach($order->orderItems as $item => $itemDetails) {
                $itemsToOrderStatement = self::getConnection()->prepare("INSERT INTO sOrder_items(order_id, product_id, product_name, number_items, selling_price) VALUES(:order_id, :product_id, :product_name, :number_items, :selling_price);");
                $itemsToOrderStatement->bindParam(":order_id", $order->orderId);
                $itemsToOrderStatement->bindParam(":product_id", $itemDetails["id"]);
                $itemsToOrderStatement->bindParam(":product_name", $itemDetails["name"]);
                $itemsToOrderStatement->bindParam(":number_items", $itemDetails["numItems"]);
                $itemsToOrderStatement->bindParam(":selling_price", $itemDetails["price"]);
                $successful = $itemsToOrderStatement->execute();
            }
            return $successful;
        }

        static public function getUsersOrders($userId){
            $usersOrderStatement = self::getConnection()->prepare("SELECT * FROM sOrder WHERE ordered_by = :userId");
            $usersOrderStatement->bindParam(":userId", $userId);
            $usersOrderStatement->execute();
            return $usersOrderStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrder($orderId){
            $orderStatement = self::getConnection()->prepare("SELECT * FROM sOrder WHERE id = :orderId AND ordered_by = :userId");
            $orderStatement->bindParam(":orderId", $orderId);
            $orderStatement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
            $orderStatement->execute();
            return $orderStatement->fetch(PDO::FETCH_ASSOC);
        }

        static public function getOrderItems($orderId){
            $orderItemsStatement = self::getConnection()->prepare("SELECT * FROM sOrder_items WHERE order_id = :orderId");
            $orderItemsStatement->bindParam(":orderId", $orderId);
            $orderItemsStatement->execute();
            return $orderItemsStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getOrderAddress($orderId){
            $orderAddressStatement = self::getConnection()->prepare("SELECT * FROM sAddress WHERE order_id = :orderId");
            $orderAddressStatement->bindParam(":orderId", $orderId);
            $orderAddressStatement->execute();
            return $orderAddressStatement->fetch(PDO::FETCH_ASSOC);
        }
    }
?>