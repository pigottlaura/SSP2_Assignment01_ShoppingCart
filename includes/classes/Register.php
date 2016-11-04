<?php
    class Register {
        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods checkUsernameAvailability() and registerNewUser() instead.");
        }

        static public function registerNewUser($formData){
            $newUserRegistered = false;

            $dataValidated = InputData::validate($formData, array(
                "empty" => array("honeypot"),
                "required" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "string" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "email" => array("email"),
                "enum" => array("password" => array($_POST["confirm_password"]))
            ));

            if ($dataValidated) {
                $sanitisedData = InputData::sanitise($_POST);
                if (Database::addUser($sanitisedData)) {
                    $newUserRegistered = true;
                }
            }
            return $newUserRegistered;
        }

        static public function checkUsernameAvailability($requestedUsername){
            $response = array(
                "usernameAvailable" => false,
                "dataValidated" => false
            );
            if(strlen($requestedUsername) > 0){
                $response["dataValidated"] = InputData::validate(array("username" => $requestedUsername), array(
                    "required" => array("username"),
                    "string" => array("username"),
                    "noSpecialChars" => array("username")
                ));

                if($response["dataValidated"]){
                    $sanitisedData = InputData::sanitise(array("username" => $requestedUsername));
                    $response["usernameAvailable"] = Database::checkUsernameAvailability($sanitisedData["username"]);
                }
            }
            return $response;
        }
    }
?>