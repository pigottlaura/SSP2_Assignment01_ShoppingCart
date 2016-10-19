<?php
    class Email {

        public function __construct(){
            throw new error("Cannont instantiate this class. Please use the static methods sendOrderEmail() and sendNewUserEmail() instead.");
        }

        static public function sendOrderEmail(&$order){
            $order->orderPlaced = true;
        }

        static public function sendNewUserEmail($user){

        }
    }
?>