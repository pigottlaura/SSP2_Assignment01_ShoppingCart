<?php
    include_once("InputData.php");
    class Login {
        public function __construct(){
        }

        public static function validateLogin($loginData){
            $successful = false;
            $dataValidated = InputData::validate($loginData, array(
                "empty" => array("honeypot"),
                "required" => array("username", "password"),
                "string" => array("username", "password")
            ));

            if($dataValidated){
                $sanitisedData = InputData::sanitise($loginData);
                $userId = Database::validateUser($sanitisedData["username"], $sanitisedData["password"]);
                if($userId > 0){
                    self::addUserToSession($userId);
                    $successful = true;
                } else {
                    self::loginError("Wrong credentials");
                }
            } else {
                self::loginError("Invalid Data");
            }
            return $successful;
        }

        public static function addUserToSession($userId){
            $_SESSION["shopping_session"]->userId = $userId;
        }

        public static function logout(){
            unset($_SESSION["shopping_session"]->userId);
            Functions::removeFromQueryString("action=");
        }

        private static function loginError($error){
            //throw new Exception($error);#
            echo "Login error - " . $error . "<br>";
        }
    }
?>