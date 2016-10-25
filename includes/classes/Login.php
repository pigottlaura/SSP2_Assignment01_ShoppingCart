<?php
    include_once("InputData.php");
    class Login {
        public function __construct(){
        }

        public static function validateLogin($username, $password){
            $loginValidated = false;
            if(InputData::validate(array($username, $password))){
                $sanitisedData = InputData::sanitise(array("username" => $username, "password" => $password));
                $userId = Database::validateUser($sanitisedData["username"], $sanitisedData["password"]);
                if($userId > 0){
                    self::addUserToSession($userId);
                    $loginValidated = true;
                } else {
                    self::loginError("Wrong credentials");
                }
            } else {
                self::loginError("Invalid Data");
            }
            return $loginValidated;
        }

        private static function addUserToSession($userId){
            $_SESSION["shopping_session"]->userId = $userId;
        }

        public static function logout(){
            unset($_SESSION["shopping_session"]->userId);
            Functions::goToPage("page.php");
        }

        private static function loginError($error){
            throw new Exception($error);
        }
    }
?>