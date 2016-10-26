<?php
    class Email {
        static private $clientAddress = array(
            "address_houseName" => "Angel Heights",
            "address_street" => "Dromleigh South",
            "address_town" => "Bantry",
            "address_county" => "Cork",
            "address_country" => "Ireland",
            "address_zipCode" => "XN0044"
        );
        static private $companyAddress = array(
            "address_houseName" => "5 Wolfe Tone Street",
            "address_town" => "Clonmel",
            "address_county" => "Tipperary",
            "address_country" => "Ireland",
            "address_zipCode" => "YNZZ44"
        );
        static private $addressFields = array(
            0 => "address_houseName",
            1 => "address_street",
            2 => "address_town",
            3 => "address_county",
            4 => "address_country",
            5 => "address_zipCode"
        );

        public function __construct(){
            throw new error("Cannont instantiate this class. Please use the static methods sendOrderEmail() and sendNewUserEmail() instead.");
        }

        static public function sendOrderEmail(&$order){
            $html = "<table width='600px'>";

            $html .= "<tr><th colspan='5'>Order Confirmation</th></tr>";

            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            $userDetails = Database::getUserDetails($order->orderedBy);
            $html .= "<tr>";
            $html .= "<td colspan='2'>" . $userDetails->contact["first_name"] . $userDetails->contact["last_name"] . "</td>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>Blueberry Toys</td>";
            $html .= "</tr>";

            foreach(self::$addressFields as $key => $value){
                $html .= "<tr>";
                if(isset($userDetails->address[$value])) {
                    $html .= "<td colspan='2'>" . $userDetails->address[$value] . "</td>";
                } else {
                    $html .= "<td colspan='2'>&nbsp;</td>";
                }
                $html .= "<td>&nbsp;</td>";
                if(isset(self::$companyAddress[$value])){
                    $html .= "<td colspan='2' align='right'>" . self::$companyAddress[$value] . "</td>";
                } else {
                    $html .= "<td colspan='2'>&nbsp;</td>";
                }
                $html .= "</tr>";
            }

            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            $html .= "<tr>";
            $html .= "<th align='left'>Product ID</th>";
            $html .= "<th align='left'>Description</th>";
            $html .= "<th align='left'>Price</th>";
            $html .= "<th align='left'>Quantity</th>";
            $html .= "<th align='left'>Total</th>";
            $html .= "</tr>";

            $tempItemsInfo =  Database::getOrderProductInfo($order->orderItems);
            foreach($tempItemsInfo as $key => $item){
                $tempNumItems = $_SESSION["shopping_session"]->shopping_cart->getItem($item["id"])->numItems;
                $html .= "<tr>";
                $html .= "<td>" . $item["id"] . "</td>";
                $html .= "<td>" . $item["name"] . "</td>";
                $html .= "<td align='right'>€" . number_format($item["price"], 2) . "</td>";
                $html .= "<td align='center'>" . $tempNumItems . "</td>";
                $html .= "<td align='right'>€" . number_format(($item["price"] * $tempNumItems), 2). "&nbsp;" . "</td>";
                $html .= "</tr>";
            }

            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='3'>&nbsp;</td>";
            $html .= "<td align='right'>Order Total:</td>";
            $html .= "<td align='right'>€" . number_format($order->orderTotal, 2) . "</td>";
            $html .= "</tr>";

            $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

            $html .= "</table>";
            echo $html;
            $order->orderPlaced = true;
        }

        static public function sendNewUserEmail($user){

        }
    }
?>