<?php
    include_once("InputData.php");
    class Login {
        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods validateLogin() and logout() instead.");
        }

        public static function validateLogin($loginData){
            // Creating a response associative array, to return to the caller
            $response = array(
                "successful" => false,
                "errors" => array()
            );

            // Validating the data provided in the request
            $validateData = InputData::validate($loginData, array(
                "empty" => array("honeypot"),
                "required" => array("username", "password"),
                "string" => array("username", "password"),
                "validUsername" => array("username")
            ));

            // Checking if the data was validated
            if($validateData["dataValidated"]){
                // Sanitising the data provided in the request
                $sanitisedData = InputData::sanitise($loginData);

                // Attempting a login, and storing the resulting userId
                $userId = Database::validateUser($sanitisedData["username"], $sanitisedData["password"]);

                // Checking if the userId is greater that 0 i.e. was the login successful
                if($userId > 0){
                    // Adding the userId to the session and setting the response associative array to reflect this success
                    self::addUserToSession($userId);
                    $response["successful"] = true;
                } else {
                    // Adding an error to the response associative array
                    array_push($response["errors"], "Incorrect username / password combination");
                }
            } else {
                // Since the data provided was not validated, looping through each error
                // of the validation, and adding it to the response associative array of the login
                foreach($validateData["errors"] as $key => $value){
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
            // Creating a response associative array, to return to the caller
            $response = array(
                "successful" => false,
                "errors" => array()
            );

            // Validating the data provided in the request
            $validateData = InputData::validate($updatedData, array(
                "required" => array("first_name", "last_name", "email"),
                "string" => array("first_name", "last_name", "email"),
                "email" => array("email")
            ));

            // Checking if the user is also changing their password (based on a hidden
            // input that would have been added to the form client side, if they had
            // clicked the "updatePassword" button)
            if(isset($updatedData["password_change"])){
                // Validating the data provided in the request
                $validatePasswordData = InputData::validate($updatedData, array(
                    "required" => array("password", "confirm_password"),
                    "string" => array("password", "confirm_password"),
                    "enum" => array("password" => array($_POST["confirm_password"]))
                ));

                // Multiplying the "dataValidated" boolean of the response associative array by
                // the boolean result of the passwordData validation. Storing the result
                // in the "dataValidated" property of the response associative array i.e. the initial
                // data may have been valid, but if the password data was not then
                // true * false = false (1 * 0 = 0)
                $validateData["dataValidated"] *= $validatePasswordData["dataValidated"];

                // Looping through any errors from the password validation, and adding them
                // to the original validation's error message array
                foreach($validatePasswordData["errors"] as $key => $value){
                    array_push($validateData["errors"], $value);
                }
            }

            // Checking if the user is adding or updating their address(based on a hidden
            // input that would have been added to the form client side, if they had
            // clicked the "addAddress" button)
            if(isset($updatedData["address_new"]) || isset($updatedData["address_change"])){
                // Validating the data provided in the request
                $validateAddressData = InputData::validate($updatedData, array(
                    "required" => array("houseName", "street", "town", "county", "country", "zipCode"),
                    "string" => array("houseName", "street", "town", "county", "country", "zipCode")
                ));

                // Multiplying the "dataValidated" boolean of the response associative array by
                // the boolean result of the addressData validation. Storing the result
                // in the "dataValidated" property of the response associative array i.e. the initial
                // data may have been valid, and the passwordData (if provided) may have been
                // valid, but if the addressData was not then true * false = false (1 * 0 = 0)
                $validateData["dataValidated"] *= $validateAddressData["dataValidated"];

                // Looping through any errors from the address validation, and adding them
                // to the original validation's error message array
                foreach($validateAddressData["errors"] as $key => $value){
                    array_push($validateData["errors"], $value);
                }
            }

            // Checking if all the supplied data has been validated (note - if the user is
            // updating the contact details, password and address, all three validations must be
            // passed for this value to be true
            if ($validateData["dataValidated"]) {
                // Sanitising all of the supplied data from the request
                $sanitisedData = InputData::sanitise($_POST);

                // Updating the user's details in the database, and determing if the update was
                // successful based on the response from the database
                $response["successful"] = Database::updateUserDetails($sanitisedData);
            } else {
                // Looping through any errors reported by the data validations, and adding
                // them to the response associative array's errors array
                foreach($validateData["errors"] as $key => $value){
                    array_push($response["errors"], $value);
                }
            }
            return $response;
        }

        static public function createWelcomeMessage($userId){
            $userDetails = Database::getUserDetails($userId);

            $html = "<table width='600px'>";
            $html .= "<tr><td colspan='2'>&nbsp;</td></tr>";

            $html .= "<tr><td colspan='2'>Hi " . $userDetails->contact["first_name"] . ". </td></tr>";

            $html .= "<tr><td colspan='2'>Welcome to your " . CONF_COMP_NAME . " account</td></tr>";

            $html .= "<tr><td colspan='2'>&nbsp;</td></tr>";

            $html .= "<tr><td colspan='2'>Below are the details you provided at registeration:</td></tr>";

            $html .= "<tr>";
            $html .= "<td><strong>First Name:</strong></td>";
            $html .= "<td>" . $userDetails->contact["first_name"] . "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<td><strong>Last Name:</strong></td>";
            $html .= "<td>" . $userDetails->contact["last_name"] . "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<td><strong>Username:</strong></td>";
            $html .= "<td>" . $userDetails->contact["username"] . "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<td><strong>Email:</strong></td>";
            $html .= "<td>" . $userDetails->contact["email"] . "</td>";
            $html .= "</tr>";

            $html .= "</table>";

            return $html;
        }
    }
?>