<?php
    class Order {
        public $orderedBy;
        public $orderDate;
        public $orderItems;
        public $orderTotal;
        public $orderPlaced = false;

        public function __construct($orderItems){
            $this->orderedBy = $_SESSION["shopping_session"]->userId;
            $this->orderDate = date('H:i:s jS F Y');
            $this->orderItems = $orderItems;
            $this->orderPlaced = false;
        }

        public function placeOrder(){
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            if(Database::createOrder($this)) {
                Email::sendOrderEmail($this);
                //$_SESSION["shopping_session"]->shopping_cart->emptyCart();
            }
        }
    }
?>