<?php
    class InputData {

        public function __construct(){
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
                    if(!is_email($value)){
                        $dataValidated = false;
                        $errorMessage .= "<li>'" . $key . "' requires a valid email address.</li>";
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