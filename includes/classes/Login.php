<?php
    class Login {

        public function __construct(){
        }

        public function validateLogin($username, $password){
            $dataValidated = InputData::validate();
            if($dataValidated == true){
                InputData::sanitise();
                $database = Database::getInstance();
                $this->createNewSession($username);
            } else {
                $this->loginError($dataValidated);
            }

        }

        private function createNewSession($username){

        }

        public function logout($username){

        }

        private function loginError($error){
            throw new error($error);
        }
    }
?>