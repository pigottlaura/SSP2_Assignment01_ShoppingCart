<?php
    class Email {

        public function __construct(){
            // Not allowing this class to be instantiated
            throw new Exception("Cannot instantiate this class. Please use the static methods sendOrderEmail() and sendNewUserEmail() instead.");
        }

        static public function sendOrderEmail(&$order){
            // Getting the details of the user that placed the order
            $order->ordered_by = Database::getUserDetails($order->orderedBy);
            $usersEmail = $order->ordered_by->contact["email"];

            // Creating the subject line, based on the orderId
            $subjectLine = "Order Confirmation - Order #" . $order->orderId;

            // Generating a receipt, to be used as the email body
            $emailBody = Order::createReceipt($order->orderId);

            // Sending the order in an email to the user and website owner. Determining whether
            // or not this was successful and returning this result to the user.
            // Passing in the user's email, subject line and email body
            $emailSent = self::sendEmail($usersEmail, $subjectLine, $emailBody, true);

            return $emailSent;
        }

        static public function sendNewUserEmail($userId){
            $userDetails = Database::getUserDetails($userId);

            $to = $userDetails->contact["email"];
            $subject = "Welcome to " . CONF_COMP_NAME;
            $emailBody = Login::createWelcomeMessage($userId);

            self::sendEmail($to, $subject, $emailBody, false);
        }

        static private function sendEmail($to, $subject, $emailBody, $bccOwner){
            // Setting the email headers. Sending the email from the orders email
            // address, and BCCing the owners email address when requested
            $headers = "From: " . CONF_ORDERS_EMAIL . "\r\n";
            if($bccOwner){
                $headers .= "Bcc: " . CONF_OWNER_EMAIL . "\r\n";
            }
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // Sending the email, and determing if this task was successful
            $sentSuccessfully = @mail($to,$subject,$emailBody,$headers);
            return $sentSuccessfully;
        }
    }
?>