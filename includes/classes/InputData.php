<?php
    class InputData {

        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods validate() and sanitise() instead.");
        }

        static public function validate($data, $options){
            // Creating a response associative array, to store the result and errors from this function
            // to return them to the caller. Originally, this function would have just echoed
            // out these errors, but since it is now utilised by AJAX requests as well, returning
            // a response object means that this errors can be easily managed both server side
            // and client side (depending on the caller)
            $response = array(
                "errors" => array(),
                "dataValidated" => true
            );

            // Looping through all of the data provided i.e. an array of input's that need
            // to be validated
            foreach($data as $key => $value){

                // Checking if the "empty" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["empty"]) && in_array($key, $options["empty"], true)){
                    if(!empty($data[$key])){
                        // This input contains a value, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], "Unusual form activity detected.");
                    }
                }

                // Checking if the "required" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["required"]) && in_array($key, $options["required"], true)){
                    if(empty($data[$key])){
                        // This input is empty, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " is a required field.");
                    }
                }

                // Checking if the "required" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["string"]) && in_array($key, $options["string"], true)){
                    if(!is_string($value)){
                        // This input is not a string, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " contains unusual data.");
                    }
                }

                // Checking if the "email" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["email"]) && in_array($key, $options["email"], true)){
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                        // This input is not a valid email format, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " requires a valid email address.");
                    }
                }

                // Checking if the "int" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["int"]) && in_array($key, $options["int"], true)) {
                    if(!filter_var($value, FILTER_VALIDATE_INT)){
                        // This input is not an integer, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " product page number is not valid.");
                    }
                }

                // Checking if the "notInt" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["notInt"]) && in_array($key, $options["notInt"], true)){
                    if(filter_var($value, FILTER_VALIDATE_INT)){
                        // This input is an integer, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " must contain letters as well as numbers.");
                    }
                }

                // Checking if the "validate" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["validUsername"]) && in_array($key, $options["validUsername"], true)){
                    // The first character must be a letter (case insensitive)
                    if(preg_match("/[^a-z]+/i", substr($value, 0, 1), $matches1)){
                        // This username does not begin with a letter, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " must start with a letter.");
                    }

                    //Checking that character in the rest of the username can is a non-word character
                    // i.e. only a-z, 0-9 and _ are allowed
                    if(preg_match_all("/[\W]+/i", $value, $matches2)) {
                        // This username contains non-word characters, so the data does not meet this validation
                        $response["dataValidated"] = false;
                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " contains unexpected characters.");
                    }
                }

                // Checking if the "enum" option was specified, and that this input needs to
                // meet with this validation
                if(isset($options["enum"]) && array_key_exists($key,$options["enum"])){
                    // Assuming that this input has not passed this validation, until
                    // proven otherwise
                    $enumPassed = false;

                    // Looping through each of the allowed values for this input
                    foreach($options["enum"][$key] as $enumVal) {
                        // Checking if this input's value matches with at least one
                        // of the allowed values supplied to the function
                        if(strtolower($value) == strtolower($enumVal)) {
                            // The input's value has matched with at least one of the allowed values
                            $enumPassed = true;
                        }
                    }

                    // Checking if the result is still false i.e. the input's value did not
                    // match with any of the allowed values
                    if(!$enumPassed) {
                        // Setting the dataValidated property of the response associative array to false
                        $response["dataValidated"] = false;

                        // Adding this as an error to the response errors array
                        array_push($response["errors"], $key . " does not match.");
                    }
                }
            }

            return $response;
        }

        static public function sanitise($data){
            // Creating a temporary array to store the sanitised data
            $sanitisedData = array();

            // Loop through all the inputs provided to the function
            foreach($data as $key => $value) {
                // Incrementally sanitising the data, as it passes through
                // each of the below statements, and eventually is stored
                // back in the temporary array
                $sanitisedData[$key] = trim($value);
                $sanitisedData[$key] = htmlentities($sanitisedData[$key]);
                $sanitisedData[$key] = strip_tags($sanitisedData[$key]);
            }

            // Return the array of sanitised fields
            return $sanitisedData;
        }
    }
?>