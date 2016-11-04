<?php
    include_once("InputData.php");
    class Login {
        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods validateLogin() and logout() instead.");
        }

        public static function validateLogin($loginData){
            $response = array(
                "successful" => false,
                "errors" => array()
            );
            $validateData = InputData::validate($loginData, array(
                "empty" => array("honeypot"),
                "required" => array("username", "password"),
                "string" => array("username", "password"),
                "noSpecialChars" => array("username")
            ));

            if($validateData["dataValidated"]){
                $sanitisedData = InputData::sanitise($loginData);
                $userId = Database::validateUser($sanitisedData["username"], $sanitisedData["password"]);
                if($userId > 0){
                    self::addUserToSession($userId);
                    $response["successful"] = true;
                } else {
                    array_push($response["errors"], "Incorrect username / password combination");
                }
            } else {
                foreach($validateData["errorMessage"] as $key => $value){
                    array_push($response["errors"], $value);
                }
            }
            return $response;
        }

        public static function logout(){
            unset($_SESSION["shopping_session"]->userId);
            Functions::removeFromQueryString("action=");
        }

        public static function addUserToSession($userId){
            $_SESSION["shopping_session"]->userId = $userId;
        }

        public static function updateUserDetails($updatedData){
            $response = array(
                "successful" => false,
                "errors" => array()
            );

            $validateData = InputData::validate($updatedData, array(
                "required" => array("first_name", "last_name", "email"),
                "string" => array("first_name", "last_name", "email"),
                "email" => array("email")
            ));

            // Checking if the user is also changing the password
            if(isset($updatedData["password_change"])){
                $validatePasswordData = InputData::validate($updatedData, array(
                    "required" => array("password", "confirm_password"),
                    "string" => array("password", "confirm_password"),
                    "enum" => array("password" => array($_POST["confirm_password"]))
                ));

                $validateData["dataValidated"] *= $validatePasswordData["dataValidated"];
                foreach($validatePasswordData["errorMessage"] as $key => $value){
                    array_push($validateData["errorMessage"], $value);
                }
            }

            if ($validateData["dataValidated"]) {
                $sanitisedData = InputData::sanitise($_POST);
                $response["successful"] = Database::updateUserDetails($sanitisedData);
            } else {
                foreach($validateData["errorMessage"] as $key => $value){
                    array_push($response["errors"], $value);
                }
            }
            return $response;
        }
    }
?>