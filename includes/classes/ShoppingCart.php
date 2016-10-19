<?php
    class ShoppingCart {
        private $order;

        public function __construct(){
            $this->order = (object) array(
                "items" => (object) array()
            );
        }

        public function addItem($item){
            // If this item is already in the order, just add one to it's total
            if(isset($this->order["items"][$item->name])){
                $this->order["items"][$item->name]["numItems"] += 1;
            } else {
                // This is the first time this item has been added to this array
                $this->order["items"][$item->name] = array(
                    "numItems" => 1,
                    "pricePerItem" => $item->price / 100,
                    "img" => $item->image
                );
            }
        }

        public function removeItem($item, $num=1) {
            // If this item is already in the order, just add one to it's total
            if(isset($this->order["items"][$item->name])){
                if($num >= sizeof($this->order["items"][$item->name]["numItems"])) {
                    // Remove this product from the order
                    array_splice($this->order["items"][$item->name], 1);
                } else {
                    // Remove the specified number of this product from the order
                    $this->order["items"][$item->name]["numItems"] -= $num;
                }

            } else {
                throw new error("This items is not in this order");
            }
        }

        public function calculateTotal(){

        }
    }
?>