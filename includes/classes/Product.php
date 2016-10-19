<?php
    class Product {
        private $_name;
        private $_price;
        private $_description;
        private $_image;

        public function __construct($name, $price, $description, $image){
            $this->_name = $name;
            $this->_price = $price;
            $this->_description = $description;
            $this->_image = $image;
        }

        public function getDetails(){
            $readOnlyObj = (object) array(
                "name" => $this->_name,
                "price" => $this->_price,
                "description" => $this->_description,
                "image" => $this->_image
            );
            return $readOnlyObj;
        }
    }
?>