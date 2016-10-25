<?php
    include_once("InputData.php");
    class Login {
        public function __construct(){
        }

        public static function validateLogin($username, $password){
            if(InputData::validate(array($username, $password))){
                $sanitisedData = InputData::sanitise(array("username" => $username, "password" => $password));
                $userId = Database::validateUser($sanitisedData["username"], $sanitisedData["password"]);
                if($userId > 0){
                    self::addUserToSession($userId);
                } else {
                    self::loginError("Wrong credentials");
                }
            } else {
                self::loginError("Invalid Data");
            }

        }

        private static function addUserToSession($userId){

        }

        public static function logout($username){

        }

        private static function loginError($error){
            throw new error($error);
        }
    }
?>