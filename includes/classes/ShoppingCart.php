<?php
    class ShoppingCart {
        private $_items;

        public function __construct(){
            $this->_items = (object) array();
        }

        public function addItem($item){
            // If this item is already in the order, just add one to it's total
            if(isset($this->_items[$item->name])){
                $this->_items[$item->name]["numItems"] += 1;
            } else {
                // This is the first time this item has been added to this array
                $this->_items[$item->name] = array(
                    "numItems" => 1,
                    "item" => $item
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
            foreach($this->_items as $itemName => $itemDetails){
                $tempTotal += ($itemDetails["numItems"] * $itemDetails["item"]->price);
            }
            return $tempTotal;
        }

        public function getTotalNumItems(){
            $tempTotal = 0;
            foreach($this->_items as $itemName => $itemDetails){
                $tempTotal += $itemDetails["numItems"];
            }
            return $tempTotal;
        }
    }
?>