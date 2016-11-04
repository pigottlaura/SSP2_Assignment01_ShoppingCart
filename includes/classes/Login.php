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
    }
?>