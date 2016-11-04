<?php
    class InputData {

        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods validate() and sanitise() instead.");
        }

        static public function validate($data, $options){
            $response = array(
                "errorMessage" => array(),
                "dataValidated" => true
            );

            foreach($data as $key => $value){
                if(isset($options["empty"]) && in_array($key, $options["empty"], true)){
                    if(!empty($data[$key])){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "Unusual form activity detected.");
                    }
                }

                if(isset($options["required"]) && in_array($key, $options["required"], true)){
                    if(empty($data[$key])){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' is a required field.");
                    }
                }

                if(isset($options["string"]) && in_array($key, $options["string"], true)){
                    if(!is_string($value)){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' contains unusual data.");
                    }
                }

                if(isset($options["email"]) && in_array($key, $options["email"], true)){
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' requires a valid email address.");
                    }
                }

                if(isset($options["int"]) && in_array($key, $options["int"], true)) {
                    if(!filter_var($value, FILTER_VALIDATE_INT)){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' product page number is not valid.");
                    }
                }

                if(isset($options["notInt"]) && in_array($key, $options["notInt"], true)){
                    if(filter_var($value, FILTER_VALIDATE_INT)){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' must contain letters as well as numbers.");
                    }
                }

                if(isset($options["noSpecialChars"]) && in_array($key, $options["noSpecialChars"], true)){
                    // First character must be a letter (case insensitive), and no character in the rest of
                    // the can match with any non-word character
                    if(preg_match("/[^a-z]+/i", substr($value, 0, 1), $matches1) || preg_match_all("/[\W]+/i", $value, $matches2)){
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' contains unexpected characters.");
                    }
                }

                if(isset($options["enum"]) && array_key_exists($key,$options["enum"])){
                    $enumPassed = false;

                    foreach($options["enum"][$key] as $enumVal) {
                        if(strtolower($value) == strtolower($enumVal)) {
                            $enumPassed = true;
                        }
                    }

                    if(!$enumPassed) {
                        $response["dataValidated"] = false;
                        array_push($response["errorMessage"], "\'" . $key . "\' does not match.");
                    }
                }
            }

            return $response;
        }

        static public function sanitise($data){
            $sanitisedData = array();

            // Loop through all fields
            foreach($data as $key => $value) {
                $sanitisedData[$key] = trim($value);
                $sanitisedData[$key] = htmlentities($sanitisedData[$key]);
                $sanitisedData[$key] = strip_tags($sanitisedData[$key]);
            }

            // Return the array of sanitised fields
            return $sanitisedData;
        }
    }
?>