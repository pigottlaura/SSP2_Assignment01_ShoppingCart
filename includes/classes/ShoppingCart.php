<?php
    class ShoppingCart {
        private $_items;

        public function __construct(){
            $this->emptyCart();
        }

        public function addItem($itemId){
            // If this item is already in the order, just add one to it's total
            if(isset($this->_items->$itemId)){
                $this->_items->$itemId->numItems += 1;
            } else {
                // This is the first time this item has been added to this array
                $this->_items->$itemId = (object) array(
                    "itemId" => $itemId,
                    "numItems" => 1
                );
            }
        }

        public function removeItem($item, $num=1) {
            // If this item is already in the order, just add one to it's total
            if(isset($this->_items[$item->name])){
                if($num >= sizeof($this->order["items"][$item->name]["numItems"])) {
                    // Remove this product from the order
                    array_splice($this->_items[$item->name], 1);
                } else {
                    // Remove the specified number of this product from the order
                    $this->_items[$item->name]["numItems"] -= $num;
                }

            } else {
                throw new error("This items is not in this order");
            }
        }

        public function calculateTotal(){
            $tempTotal = 0;
            foreach($this->_items as $itemId => $itemDetails){
                $tempTotal += ($itemDetails->numItems * Database::getItemPrice($itemId));
            }
            return $tempTotal;
        }

        public function getItem($itemId){
            $tempItem = null;
            foreach($this->_items as $key => $value){
                if($key == $itemId){
                    $tempItem = $value;
                }
            }
            return $tempItem;
        }
        public function getItemsDetails(){
            $itemIds = array();
            foreach($this->_items as $item) {
                array_push($itemIds, $item->itemId);
            }
            return Database::getOrderProductInfo($itemIds);
        }

        public function getTotalNumItems(){
            $tempTotal = 0;
            foreach($this->_items as $itemName => $itemDetails){
                $tempTotal += $itemDetails->numItems;
            }
            return $tempTotal;
        }

        public function emptyCart(){
            $this->_items = (object) array();
        }

        public function placeOrder(){
            Database.createOrder($this->_items);
        }
    }
?>