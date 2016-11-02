<?php
    class Email {
        static private $websiteOwnerEmail = "k00190475@student.lit.ie";

        public function __construct(){
            throw new Exception("Cannot instantiate this class. Please use the static methods sendOrderEmail() and sendNewUserEmail() instead.");
        }

        static public function sendOrderEmail(&$order){
            $order->ordered_by = Database::getUserDetails($order->orderedBy);
            $emailBody = Order::createReceipt($order->orderId);
            $order->orderPlaced = self::sendEmail($order->ordered_by->contact["email"], "Order Confirmation - Order #" . $order->orderId, $emailBody);

            return $order->orderPlaced;
        }

        static public function sendNewUserEmail($user){

        }

        static private function sendEmail($to, $subject, $emailBody){
            $sentSuccessfully = false;

            $headers = "From: orders@pigottlaura.com\r\n";
            $headers .= "Bcc: " . self::$websiteOwnerEmail . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            if(@mail($to,$subject,$emailBody,$headers)) {
                $sentSuccessfully = true;
            }

            return $sentSuccessfully;
        }
    }
?>