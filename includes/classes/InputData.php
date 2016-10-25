<?php
    class InputData {

        public function __construct(){
        }

        static public function validate($data){
            $error = null;
            return true;
        }

        static public function sanitise($data){
            $sanitisedData = $data;
            return $sanitisedData;
        }
    }
?>