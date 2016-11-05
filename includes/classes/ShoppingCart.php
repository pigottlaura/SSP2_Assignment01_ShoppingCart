<?php
    class ShoppingCart {
        private $_items;

        public function __construct(){
            // Create an empty shopping cart
            $this->emptyCart();
        }

        public function addItem($itemId, $num=1){
            // If this item is already in the order, just add the number specified to it's total
            if(isset($this->_items->$itemId)){
                $this->_items->$itemId->numItems += $num;
            } else {
                // This is the first time this item has been added to this array, so adding
                // it, along with the number specified
                $this->_items->$itemId = (object) array(
                    "itemId" => $itemId,
                    "numItems" => $num
                );
            }
        }

        public function removeItem($itemId, $num=1) {
            // If this item is already in the order, just remove the number specified from it's total
            if(isset($this->_items->$itemId)){
                // If removing the number specified from this item's total will mean than it
                // will result in this item being 0 or less, just remove the item from the order
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

            // Looping through all of the items in the shopping cart
            foreach($this->_items as $key => $value){
                // If this is the item requested, return it to the user using the
                // tempItem
                if($key == $itemId){
                    $tempItem = $value;
                }
            }
            return $tempItem;
        }

        public function getItems(){
            // Return all items in the shopping cart
            return $this->_items;
        }

        public function getItemsDetails(){
            // Get the product information for all items in the shopping cart
            return Database::getOrderProductInfo($this->_items);
        }

        public function getTotalNumItems(){
            $tempTotal = 0;

            // Loop through all of the items in the shopping cart, and add the number
            // of them required to the tempTotal i.e. how many individual items are there
            // in the shopping cart, even if they are multiples of the same item
            foreach($this->_items as $itemName => $itemDetails){
                $tempTotal += $itemDetails->numItems;
            }
            return $tempTotal;
        }

        public function calculateTotal(){
            $tempTotal = 0;

            // Loop through all of the items in the shopping cart, and add their total
            // value to the tempTotal i.e. their price multiplied by the number of them
            // that has been added
            foreach($this->_items as $itemId => $itemDetails){
                $tempTotal += ($itemDetails->numItems * Database::getItemPrice($itemId));
            }

            return $tempTotal;
        }

        public function emptyCart(){
            // Empty the shopping cart
            $this->_items = (object) array();
        }

        static public function retrieveItemIds($items){
            $itemIds = array();

            // Loop through all of the items in the shopping cart, and retrieve just their ids
            // i.e. ignore the number of them that is required
            foreach($items as $item) {
                array_push($itemIds, $item->itemId);
            }

            return $itemIds;
        }

        static public function display(){
            $html = "<h1>Order Details</h1>";

            // Loop through all of the temporary order details that have just been stored on the user's
            // session, and display them as a shopping cart
            foreach ($_SESSION["shopping_session"]->tempOrderDetails->orderItems as $item => $itemDetails) {
                $_SESSION["shopping_session"]->shopping_cart->orderTotal += $itemDetails["price"] * $itemDetails["numItems"];

                $html .= "<div class='cartItemContainer'>";
                $html .= "<img src='./images/products/" . $itemDetails["image"] . "' alt='" . $itemDetails["name"] . "'>";
                $html .= "<strong>" . $itemDetails["name"] . "</strong>";
                $html .= " @ €" . $itemDetails["price"] . " x " . $itemDetails["numItems"] . " = €" . ($itemDetails["price"] * $itemDetails["numItems"]);
                $html .= "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=adjust-num-items&adjustBy=-1&productId=" . $itemDetails["id"] . "' class='addNumItems'><button>-</button></a>";
                $html .= "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=adjust-num-items&adjustBy=1&productId=" . $itemDetails["id"] . "' class='removeNumItems'><button>+</button></a>";
                $html .= "</div>";
            }
            $html .= "Total: €" . $_SESSION["shopping_session"]->shopping_cart->orderTotal;
            $html .= "<a href='page.php?page=delivery-details'><button class='placeOrder'>Place Order</button></a>";
            $html .= "<a href='page.php?page=shopping-cart&emptyCart'><button>Empty Cart</button></a>";

            // Return the HTML to the caller
            return $html;
        }
    }
?>