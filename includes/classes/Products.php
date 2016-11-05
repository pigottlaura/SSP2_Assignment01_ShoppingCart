<?php
    class Products {
        public function __construct(){
            // Not allowing this class to be instantiated
            throw new error("Cannot instantiate this class. Please use the static method display() instead.");
        }

        static public function display($category=1, $numRows=4, $sortBy="name", $sortOrder="asc", $startRow=0){
            $html = "";

            // Checking if there is valid cookie data that could be used to refine the products display
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

            // If there is valid cookie data, then changing the values passed in to reflect this
            // Note - if the cookie data is not valid, we merely ignore it (no error is displayed)
            if($cookieDataValid["dataValidated"]) {
                // Number of products to get
                $numRows = $_COOKIE["itemsPerPage"];
                // Column name to sort them by
                $sortBy = $_COOKIE["sortBy"];
                // Whether to sort them ascending or descending
                $sortOrder = $_COOKIE["sortOrder"];
                // Page number multiplies by number of products to get in each go, to determine
                // which row of the database to start at
                $startRow = $_COOKIE["productPage"] * $numRows;
            }

            // Getting the products from the database, sorted as specified by the parameters above.
            // Getting one more product than specified by numRows, so that I will always know if there
            // are more products to display after this page i.e. is this the last page
            $products = Database::getProducts($startRow . "," . ($numRows + 1), $category, $sortBy, $sortOrder);

            // Determing whether or not this is the last page of products, based on whether the number returned
            // from the query to the database is greater than the number of rows requested i.e. are there more
            // products to display
            $lastPage = count($products) > $numRows ? false: true;
            // If this is the last page, then loop through all of the results, but if it is not the last page,
            // then loop through one less than the number returned (as this additional product was only requested
            // to see if there was more products to display)
            $loopProducts = $lastPage ? count($products) : count($products) - 1;

            // Loop through and display the products
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


            // Pagination Buttons
            $html .= "<div align='center'>";
                // Previous Page Button
                $html .= "<button id='prevPage'";
                if($startRow == 0){
                    // If this is the first page, disable this button
                    $html .= " disabled";
                }
                $html .= "><<< Previous " . $numRows . " products</button>";

                // Next Page Button
                $html .= "<button id='nextPage'";
                if($lastPage){
                    // If this is the last page, disable this button
                    $html .= " disabled";
                }
                $html .= "> Next " . $numRows . " products >>></button>";
            $html .= "</div>";

            echo $html;
        }
    }
?>