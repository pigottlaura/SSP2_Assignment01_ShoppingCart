<?php
    class Register {
        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods checkUsernameAvailability() and registerNewUser() instead.");
        }

        static public function registerNewUser($formData){
            $response = array(
                "successful" => false,
                "errors" => array()
            );

            $validateData = InputData::validate($formData, array(
                "empty" => array("honeypot"),
                "required" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "string" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "email" => array("email"),
                "enum" => array("password" => array($_POST["confirm_password"]))
            ));

            if ($validateData["dataValidated"]) {
                $sanitisedData = InputData::sanitise($_POST);
                $usernameAvailability = self::checkUsernameAvailability($sanitisedData["username"]);

                if($usernameAvailability["usernameAvailable"] && $usernameAvailability["dataValidated"]){
                    if (Database::addUser($sanitisedData)) {
                        $response["successful"] = true;
                    }
                } else {
                    foreach($usernameAvailability["errors"] as $key => $value){
                        array_push($response["errors"], $value);
                    }
                }

            } else {
                foreach($validateData["errorMessage"] as $key => $value){
                    array_push($response["errors"], $value);
                }
            }
            return $response;
        }

        static public function checkUsernameAvailability($requestedUsername){
            $response = array(
                "usernameAvailable" => false,
                "dataValidated" => false,
                "errors" => array()
            );
            if(strlen($requestedUsername) > 0){
                $dataValidated = InputData::validate(array("username" => $requestedUsername), array(
                    "required" => array("username"),
                    "string" => array("username"),
                    "noSpecialChars" => array("username"),
                    "notInt" => array("username")
                ));

                $response["dataValidated"] = $dataValidated["dataValidated"];

                foreach($dataValidated["errorMessage"] as $key => $value){
                    array_push($response["errors"], $value);
                }

                if($response["dataValidated"]){
                    $sanitisedData = InputData::sanitise(array("username" => $requestedUsername));
                    $response["usernameAvailable"] = Database::checkUsernameAvailability($sanitisedData["username"]);
                    if($response["usernameAvailable"] == false) {
                        array_push($response["errors"], "This username is already taken.");
                    }
                }
            }
            return $response;
        }
    }
?>