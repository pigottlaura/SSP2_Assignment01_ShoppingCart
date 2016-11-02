<?php
    class ShoppingCart {
        private $_items;

        public function __construct(){
            $this->emptyCart();
        }

        public function addItem($itemId, $num=1){
            // If this item is already in the order, just add one to it's total
            if(isset($this->_items->$itemId)){
                $this->_items->$itemId->numItems += $num;
            } else {
                // This is the first time this item has been added to this array
                $this->_items->$itemId = (object) array(
                    "itemId" => $itemId,
                    "numItems" => $num
                );
            }
        }

        public function removeItem($itemId, $num=1) {
            // If this item is already in the order, just add one to it's total
            if(isset($this->_items->$itemId)){
                if($num >= $this->_items->$itemId->numItems) {
                    // Remove this product from the order
                    unset($this->_items->$itemId);
                } else {
                    // Remove the specified number of this product from the order
                    $this->_items->$itemId->numItems -= $num;
                }

            }
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

        public function getItems(){
            return $this->_items;
        }

        public function getItemsDetails(){
            return Database::getOrderProductInfo($this->_items);
        }

        public function getTotalNumItems(){
            $tempTotal = 0;
            foreach($this->_items as $itemName => $itemDetails){
                $tempTotal += $itemDetails->numItems;
            }
            return $tempTotal;
        }

        public function calculateTotal(){
            $tempTotal = 0;
            foreach($this->_items as $itemId => $itemDetails){
                $tempTotal += ($itemDetails->numItems * Database::getItemPrice($itemId));
            }
            return $tempTotal;
        }

        public function emptyCart(){
            $this->_items = (object) array();
        }

        static public function retrieveItemIds($items){
            $itemIds = array();
            foreach($items as $item) {
                array_push($itemIds, $item->itemId);
            }
            return $itemIds;
        }
    }
?>