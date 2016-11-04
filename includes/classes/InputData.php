<?php
    class InputData {

        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods validate() and sanitise() instead.");
        }

        static public function validate($data, $options){
            $errorMessage = "";
            $dataValidated = true;

            foreach($data as $key => $value){
                if(isset($options["empty"]) && in_array($key, $options["empty"], true)){
                    if(!empty($data[$key])){
                        $dataValidated = false;
                        $errorMessage .= "<li>Unusual form activity detected.</li>";
                    }
                }

                if(isset($options["required"]) && in_array($key, $options["required"], true)){
                    if(empty($data[$key])){
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' is a required field.</li>";
                    }
                }

                if(isset($options["string"]) && in_array($key, $options["string"], true)){
                    if(!is_string($value)){
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' contains unusual data.</li>";
                    }
                }

                if(isset($options["email"]) && in_array($key, $options["email"], true)){
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' requires a valid email address.</li>";
                    }
                }

                if(isset($options["int"]) && in_array($key, $options["int"], true)) {
                    if(!filter_var($value, FILTER_VALIDATE_INT)){
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' product page number is not valid.</li>";
                    }
                }

                if(isset($options["noSpecialChars"]) && in_array($key, $options["noSpecialChars"], true)){
                    // First character must be a letter (case insensitive), and no character in the rest of
                    // the can matche with any non-word character
                    if(preg_match("/[^a-z]+/i", substr($value, 0, 1)) || preg_match_all("/[\W]+/i", $value)){
                        $dataValidated = false;
                        //$errorMessage .= "<li>'" . $key . "' contains unexpected characters.</li>";
                    }
                }

                if(isset($options["enum"]) && array_key_exists($key,$options["enum"])){
                    $enumPassed = false;

                    foreach($options["enum"][$key] as $enumVal) {
                        echo strtolower($value) . " " . strtolower($enumVal);
                        if(strtolower($value) == strtolower($enumVal)) {
                            $enumPassed = true;
                        }
                    }

                    if(!$enumPassed) {
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' does not match.</li>";
                    }
                }
            }

            if(strlen($errorMessage) > 0){
                echo $errorMessage;
            }

            return $dataValidated;
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