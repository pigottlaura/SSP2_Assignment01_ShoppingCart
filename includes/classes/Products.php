<?php
    class Products {
        public function __construct(){
            throw new error("Cannot instantiate this class. Please use the static method display() instead.");
        }

        static public function display($category=1, $numRows=4, $sortBy="name", $sortOrder="asc", $startRow=0){
            $html = "";

            $cookieDataValid = InputData::validate(array(
                $_COOKIE
            ), array(
                "required" => array("sortBy", "sortOrder", "productPage"),
                "int" => array("productPage"),
                "enum" => array(
                    "sortBy" => array("price", "name", "date_added"),
                    "sortOrder" => array("asc", "desc")
                )
            ));

            if($cookieDataValid["dataValidated"]) {
                $numRows = $_COOKIE["itemsPerPage"];
                $sortBy = $_COOKIE["sortBy"];
                $sortOrder = $_COOKIE["sortOrder"];
                $startRow = $_COOKIE["productPage"] * $numRows;
            }

            $products = Database::getProducts($startRow . "," . ($numRows + 1), $category, $sortBy, $sortOrder);

            $lastPage = count($products) <= $numRows ? true : false;
            $loopProducts = count($products) <= $numRows ? count($products) : count($products) - 1;

            for($i=0; $i<$loopProducts; $i++) {
                $html .= "<div class='productContainer'>";
                $html .= "<h4>" . $products[$i]["name"] . "</h4>";
                $html .= "<figure>";
                $html .= "<img src='./images/products/" . $products[$i]["image"] . "' alt='" . $products[$i]["name"] . "'>";
                $html .= "<figcaption>" . $products[$i]["description"] . "</figcaption>";
                $html .= "</figure>";
                $html .= "<div class='price'>€" . $products[$i]["price"] . "</div>";
                $html .= "<button class='addToCart' id=" . $products[$i]["id"] . "'>Add to Cart</button>";
                $html .= "</div>";
            }


            $html .= "<div align='center'>";
                // Previous Page Button
                $html .= "<button id='prevPage'";
                if($startRow == 0){
                    $html .= " disabled";
                }
                $html .= "><<< Previous " . $numRows . " products</button>";

                // Next Page Button
                $html .= "<button id='nextPage'";
                if($lastPage){
                    $html .= " disabled";
                }
                $html .= "> Next " . $numRows . " products >>></button>";
            $html .= "</div>";

            echo $html;
        }
    }
?>