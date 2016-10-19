<?php
    class Order extends ShoppingCart {
        private $_orderDate;
        private $_orderTotal;
        private $_orderPlaced;

        public function __construct($ordered){
            $this->_orderDate = date('H:i:s jS F Y');
            $this->_orderPlaced = false;
        }

        public function placeOrder(){
            $this->_orderTotal = $this->calculateTotal();
            Email::sendOrderEmail($this);
        }
    }
?>