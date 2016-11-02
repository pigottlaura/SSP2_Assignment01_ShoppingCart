<?php
    class Order {
        public $orderedBy;
        public $orderDate;
        public $orderItems;
        public $orderTotal;
        public $orderPlaced = false;

        public function __construct($orderItems){
            $this->orderedBy = $_SESSION["shopping_session"]->userId;
            $this->orderDate = date('d F Y');
            $this->orderItems = $orderItems;
            $this->orderPlaced = false;
        }

        public function placeOrder(){
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            if(Database::createOrder($this)) {
                if(!Email::sendOrderEmail($this)){
                    self::orderError("Order has been successfully placed, but confirmation email failed to send - Order ID #" . $this->orderId);
                    echo "<br>";
                }
                $_SESSION["shopping_session"]->shopping_cart->emptyCart();
                echo $this->confirmationEmail;
            } else {
                self::orderError("Failed to create order in database");
            }
        }

       static private function orderError($error){
            echo "\r\nERROR - " . $error . "\r\n";
        }
    }
?>