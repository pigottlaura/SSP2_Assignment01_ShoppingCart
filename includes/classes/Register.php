<?php
    class Register {
        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods checkUsernameAvailability() and registerNewUser() instead.");
        }

        static public function registerNewUser($formData){
            // Creating a response associative array, to return to the caller
            $response = array(
                "successful" => false,
                "errors" => array()
            );

            // Validating the data provided in the request - using the enum option to ensure that the password
            // matches with the confirm_password field
            $validateData = InputData::validate($formData, array(
                "empty" => array("honeypot"),
                "username" => array("username"),
                "required" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "string" => array("first_name", "last_name", "email", "username", "password", "confirm_password"),
                "email" => array("email"),
                "enum" => array("password" => array($_POST["confirm_password"]))
            ));

            // Checking if the data was validated
            if ($validateData["dataValidated"]) {
                // Sanitising the data provided in the request
                $sanitisedData = InputData::sanitise($_POST);

                // Checking if this username is available, using the same method as the AJAX requests
                // on the login/register page when creating a new user
                $usernameAvailability = self::checkUsernameAvailability($sanitisedData["username"]);

                // If the username is available, and valid, then add them to the database
                if($usernameAvailability["usernameAvailable"] && $usernameAvailability["dataValidated"]){
                    // Attempting to create this user in the database
                    if (Database::addUser($sanitisedData)) {
                        $response["successful"] = true;
                    }
                } else {
                    // Since the was not available, or was not validated, looping through each error
                    // of the validation, and adding it to the response associative array of the availability check
                    foreach($usernameAvailability["errors"] as $key => $value){
                        array_push($response["errors"], $value);
                    }
                }

            } else {
                // Since the data provided was not validated, looping through each error
                // of the validation, and adding it to the response associative array of the registeration
                foreach($validateData["errors"] as $key => $value){
                    array_push($response["errors"], $value);
                }
            }
            return $response;
        }

        static public function checkUsernameAvailability($requestedUsername){
            // Creating a response associative array, to return to the caller
            $response = array(
                "usernameAvailable" => false,
                "dataValidated" => false,
                "errors" => array()
            );

            // Ensuring the length of the requested username is greater than 0
            if(strlen($requestedUsername) > 0){

                // Validating the data provided in the request
                $dataValidated = InputData::validate(array("username" => $requestedUsername), array(
                    "required" => array("username"),
                    "string" => array("username"),
                    "validUsername" => array("username"),
                    "notInt" => array("username")
                ));

                // Setting the dataValidated property of the response associative array
                $response["dataValidated"] = $dataValidated["dataValidated"];

                // Looping through any errors from the username validation, and adding them
                // to the response's error message array
                foreach($dataValidated["errors"] as $key => $value){
                    array_push($response["errors"], $value);
                }

                // If the username is valid, then pass it to the database to see if it is already
                // in use
                if($response["dataValidated"]){
                    // Sanitising the username
                    $sanitisedData = InputData::sanitise(array("username" => $requestedUsername));

                    // Checking if this username already exists in the database
                    $response["usernameAvailable"] = Database::checkUsernameAvailability($sanitisedData["username"]);

                    if($response["usernameAvailable"]) {
                        array_push($response["errors"], "This username is available.");
                    } else {
                        array_push($response["errors"], "This username is already taken.");
                    }
                }
            }
            return $response;
        }
    }
?>