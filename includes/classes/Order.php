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
            echo "<p>PLACING ORDER</p>";
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            if(Database::createOrder($this)) {
                var_dump($this);
                echo "<p>ORDER PLACED... Order Total: €" . $this->orderTotal . "</p>";
                //Email::sendOrderEmail($this);
                //$_SESSION["shopping_session"]->shopping_cart->emptyCart();
            }
        }
    }
?>