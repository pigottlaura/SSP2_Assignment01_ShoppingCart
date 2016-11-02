<?php
    class Products {
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

            if($cookieDataValid) {
                $numRows = $_COOKIE["itemsPerPage"];
                $sortBy = $_COOKIE["sortBy"];
                $sortOrder = $_COOKIE["sortOrder"];
                $startRow = $_COOKIE["productPage"] * $numRows;
            }

            $products = Database::getProducts($startRow . "," . $numRows, $category, $sortBy, $sortOrder);

            if(count($products) == 0){
                setcookie("lastPage", true, time() + 2500, "/");
                if($_COOKIE["productPage"] > 0){
                    setcookie("productPage", $_COOKIE["productPage"] - 1);
                }
                Functions::reloadPage();
            }

            foreach ($products as $product) {
                $html .= "<div class='productContainer'>";
                $html .= "<h4>" . $product["name"] . "</h4>";
                $html .= "<figure>";
                $html .= "<img src='./images/products/" . $product["image"] . "' alt='" . $product["name"] . "'>";
                $html .= "<figcaption>" . $product["description"] . "</figcaption>";
                $html .= "</figure>";
                $html .= "<div class='price'>€" . $product["price"] . "</div>";
                $html .= "<a class='addToCart' id=" . $product["id"] . "'><button>Add to Cart</button></a>";
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
                if(isset($_COOKIE["lastPage"]) || count($products) < $numRows){
                    $html .= " disabled";
                }
                $html .= "> Next " . $numRows . " products >>></button>";
            $html .= "</div>";

            echo $html;
        }
    }
?>