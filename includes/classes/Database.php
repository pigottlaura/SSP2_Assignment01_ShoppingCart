<?php
    include_once("./config.php");
    class Database {
        private static $_connection;

        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods provided to access the database instead.");
        }

        static private function getConnection(){
            // Only methods within this class can access the database connection.
            // Reusing the same connection for all queries to the database, so if no connection yet exists
            // then creating a new connection and storing it within the class.
            // The next time the connection is requested, the existing one will be returned
            // All connection details for the database are stored in config.php
            //      # 1 - so they are not visible within the code
            //      # 2 - so different connection details can be used depending on whether
            //            the website is running locally or remotely (localhost or sandbox.pigottlaura.com)
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

            // Determing if the username is available based on the number of results returned
            // i.e. if no results were returned, then this username is avaialble
            $result = $usernameAvailabilityStatement->fetchAll();
            $usernameAvailable = count($result) > 0 ? false : true;

            return $usernameAvailable;
        }

        static public function validateUser($username, $password){
            $validateUserStatement = self::getConnection()->prepare("SELECT id FROM sUser WHERE username = :username AND password = SHA1(:password);");
            $validateUserStatement->bindParam(":username", $username);
            $validateUserStatement->bindParam(":password", $password);
            $validateUserStatement->execute();

            // Returning the id of the result as the "userId"
            // Note - if this login was unsuccessful, this userId will be null or 0
            $result = $validateUserStatement->fetch(PDO::FETCH_ASSOC);
            $userId = $result["id"];

            return $userId;
        }

        static public function getUserDetails($userId){
            $response = (object) array();

            // Getting the user's contact details
            $userContactDetailsStatement = self::getConnection()->prepare("SELECT first_name, last_name, email, username FROM sUser WHERE id = :userId;");
            $userContactDetailsStatement->bindParam(":userId", $userId);
            $userContactDetailsStatement->execute();
            // Storing the user's contact details in the response object
            $response->contact = $userContactDetailsStatement->fetch(PDO::FETCH_ASSOC);

            // Getting the user's address
            $userAddressDetailsStatement = self::getConnection()->prepare("SELECT * FROM sAddress WHERE user_id = :userId;");
            $userAddressDetailsStatement->bindParam(":userId", $userId);
            $userAddressDetailsStatement->execute();
            // Checking if any address was returned for the user (not all users will have addresses saved)
            $addressResult = $userAddressDetailsStatement->fetchAll(PDO::FETCH_ASSOC);
            if(count($addressResult) > 0){
                // Storing the user's address in the response object
                $response->address = $addressResult[0];
            }

            return $response;
        }

        static public function updateUserDetails($newUserDetails){
            // Checking if the user is changing their password, as well as their contact details
            //(based on a hidden input that would have been added to the form client side, if they had
            // clicked the "updatePassword" button)
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
            // Storing whether the statement was successful or not based on it's execution
            $successful = $userStatement->execute();

            // Checking if the user is changing/adding an address, as well as their contact details
            //(based on a hidden input that would have been added to the form client side, if they had
            // clicked the "addAddress" button, or just had an existing address already stored)
            if(isset($newUserDetails["address_change"]) ||isset($newUserDetails["address_new"])){
                $addressResult = false;
                if(isset($newUserDetails["address_change"])){
                    $addressStatement = self::getConnection()->prepare("UPDATE sAddress SET houseName=:houseName, street=:street, town=:town, county=:county, country=:country, zipCode=:zipCode WHERE user_id = :userId;");
                    $addressStatement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
                    $addressStatement->bindParam(":houseName", $newUserDetails["houseName"]);
                    $addressStatement->bindParam(":street", $newUserDetails["street"]);
                    $addressStatement->bindParam(":town", $newUserDetails["town"]);
                    $addressStatement->bindParam(":county", $newUserDetails["county"]);
                    $addressStatement->bindParam(":country", $newUserDetails["country"]);
                    $addressStatement->bindParam(":zipCode", $newUserDetails["zipCode"]);
                    $addressResult = $addressStatement->execute();
                } else if(isset($newUserDetails["address_new"])){
                    $addressResult = self::addNewUserAddress($newUserDetails);
                }

                // Multiplying the current succesful value by the result of the address result
                // so that the final response will reflect if all of the request was successful i.e.
                // if the user details were updated, but the address failed true * false = false (1 * 0 = 0)
                $successful *= $addressResult;
            }

            return $successful;
        }

        static public function addNewUserAddress($newUserAddress){
            $addressStatement = self::getConnection()->prepare("INSERT INTO sAddress(user_id, houseName, street, town, county, country, zipCode) VALUES(:userId, :houseName, :street, :town, :county , :country, :zipCode)");
            $addressStatement->bindParam(":userId", $_SESSION["shopping_session"]->userId);
            $addressStatement->bindParam(":houseName", $newUserAddress["houseName"]);
            $addressStatement->bindParam(":street", $newUserAddress["street"]);
            $addressStatement->bindParam(":town", $newUserAddress["town"]);
            $addressStatement->bindParam(":county", $newUserAddress["county"]);
            $addressStatement->bindParam(":country", $newUserAddress["country"]);
            $addressStatement->bindParam(":zipCode", $newUserAddress["zipCode"]);

            return $addressStatement->execute();
        }

        static public function getProducts($numProducts=10, $category=1, $orderBy="name", $ascDesc="desc"){
            // Temporarily uppercasing the ASC or DESC value (for symantically correct SQL)
            $ascDesc = strtoupper($ascDesc);

            // Checking if the category is 1 (in which case we want to get all products), or
            // if a specific category has been supplied
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
            // Retrieving the items ides of the products currently in the shopping cart
            $tempProductIds = ShoppingCart::retrieveItemIds($items);
            // Turning this array of ids into a string, seperated by commas
            $productIdsString = implode(",", $tempProductIds);

            $productsInfoStatement = self::getConnection()->prepare("SELECT * FROM sProduct WHERE id IN ($productIdsString);");
            $productsInfoStatement->execute();

            return $productsInfoStatement->fetchAll(PDO::FETCH_ASSOC);
        }

        static public function getItemPrice($itemId){
            $itemPriceStatement = self::getConnection()->prepare("SELECT price FROM sProduct WHERE id = :itemId;");
            $itemPriceStatement->bindParam(":itemId", $itemId);
            $itemPriceStatement->execute();

            // Accessing the price of the items based on the result from the database
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

            // Accessing the name of the category based on the result from the database
            $result = $categoryNameStatement->fetch(PDO::FETCH_ASSOC);
            $name = $result["name"];

            return $name;
        }

        static public function createOrder(&$order){
            $successful = false;

            // Creating a new order
            $orderStatement = self::getConnection()->prepare("INSERT INTO sOrder(ordered_by, order_total, recipient_first_name, recipient_last_name) VALUES(:ordered_by, :order_total, :recipient_first_name, :recipient_last_name);");
            $orderStatement->bindParam(":ordered_by", $order->orderedBy);
            $orderStatement->bindParam(":order_total", $order->orderTotal);
            $orderStatement->bindParam(":recipient_first_name", $order->deliveryDetails->contact->recipient_first_name);
            $orderStatement->bindParam(":recipient_last_name", $order->deliveryDetails->contact->recipient_last_name);

            // If the order is successfully created (as executed in this statement) continuing to add the order items to the database
            if($orderStatement->execute()){

                // Storing the orderId of the order created above, on the $order object that was passed to the
                // function (as it was passed by reference, this value will be accessible on the original object,
                // outside of this function)
                $order->orderId = self::$_connection->lastInsertId();

                // If the order items are successfully created (as completed by another static function in this
                // class) continue to store the delivery details
                if(self::addItemsToOrder($order)){

                    // Storing the full address details of the order, even if they are those of the current user,
                    // so that if these details change after the order has been placed, this order will still reflect
                    // the details as per the date/time they were ordered
                    $addressStatement = self::getConnection()->prepare("INSERT INTO sAddress(order_id, houseName, street, town, county, country, zipCode) VALUES(:order_id, :houseName, :street, :town, :county, :country, :zipCode);");
                    $addressStatement->bindParam(":order_id", $order->orderId);
                    $addressStatement->bindParam(":houseName", $order->deliveryDetails->address->recipient_houseName);
                    $addressStatement->bindParam(":street", $order->deliveryDetails->address->recipient_street);
                    $addressStatement->bindParam(":town", $order->deliveryDetails->address->recipient_town);
                    $addressStatement->bindParam(":county", $order->deliveryDetails->address->recipient_county);
                    $addressStatement->bindParam(":country", $order->deliveryDetails->address->recipient_country);
                    $addressStatement->bindParam(":zipCode", $order->deliveryDetails->address->recipient_zipCode);

                    if($addressStatement->execute()){
                        // The success of this function is only ever set to true if the order, order items added
                        // and address were all successfully added to the database
                        $successful = true;
                    }
                }
            }

            return $successful;
        }

        static private function addItemsToOrder(&$order){
            $successful = false;

            // Looping through each items of the array, and adding it to the database.
            // Storing it's selling price and name, so that if these details change after
            // the order has been placed, this order will still reflect the details as per
            // the date/time they were ordered
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