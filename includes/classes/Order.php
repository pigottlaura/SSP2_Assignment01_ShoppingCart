<?php
    class Order {
        public $orderedBy;
        public $orderDate;
        public $orderItems;
        public $orderTotal;
        public $orderPlaced = false;

        public function __construct($tempOrderItemDetails){
            $this->orderedBy = $_SESSION["shopping_session"]->userId;
            $this->orderDate = date('d F Y');
            $this->orderItems = $tempOrderItemDetails;
            $this->orderPlaced = false;
            unset($_SESSION["shopping_session"]->shopping_cart->tempOrderItemDetails);
        }

        public function placeOrder(){
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            if(Database::createOrder($this)) {
                $_SESSION["shopping_session"]->shopping_cart->emptyCart();
                if(!Email::sendOrderEmail($this)){
                    self::orderError("Order has been successfully placed, but confirmation email failed to send - Order ID #" . $this->orderId);
                    echo "<br><a href='page.php?page=view-order&orderId=" . $this->orderId . "'>View Order Receipt</a>";
                } else {
                    Functions::goToPage("page.php?page=view-order&orderId=" . $this->orderId);
                }
            } else {
                self::orderError("Failed to create order in database");
            }
        }

        static private function orderError($error){
            echo "\r\nERROR - " . $error . "\r\n";
        }

        static public function createReceipt($orderId){
            $order = (object) Database::getOrder($orderId);
            if($_SESSION["shopping_session"]->userId == $order->ordered_by){
                $order->ordered_by = Database::getUserDetails($order->ordered_by);
                $order->items = Database::getOrderItems($orderId);
                $order->date_ordered = date_create($order->date_ordered);

                $companyAddress = array(
                    "address_houseName" => "The Showgrounds",
                    "address_street" => "5 Wolfe Tone Street",
                    "address_town" => "Clonmel",
                    "address_county" => "Tipperary",
                    "address_country" => "Ireland",
                    "address_zipCode" => "YNZZ44"
                );
                $addressFields = array(
                    0 => "address_houseName",
                    1 => "address_street",
                    2 => "address_town",
                    3 => "address_county",
                    4 => "address_country",
                    5 => "address_zipCode"
                );

                $html = "<table width='600px'>";

                // HEADING
                $html .= "<tr><th colspan='5'>Order Confirmation</th></tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // TITLE LINES
                $html .= "<tr>";
                $html .= "<td colspan='2'>" . $order->ordered_by->contact["first_name"] . " " . $order->ordered_by->contact["last_name"] . "</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td colspan='2' align='right'>" . CONF_COMP_NAME . "</td>";
                $html .= "</tr>";

                // ADDRESS LINES
                foreach($addressFields as $key => $value){
                    $html .= "<tr>";
                    if(isset($order->ordered_by->address[$value])) {
                        $html .= "<td colspan='2'>" . $order->ordered_by->address[$value] . "</td>";
                    } else {
                        $html .= "<td colspan='2'>&nbsp;</td>";
                    }
                    $html .= "<td>&nbsp;</td>";
                    if(isset($companyAddress[$value])){
                        $html .= "<td colspan='2' align='right'>" . $companyAddress[$value] . "</td>";
                    } else {
                        $html .= "<td colspan='2'>&nbsp;</td>";
                    }
                    $html .= "</tr>";
                }

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                $html .= "<tr>";
                $html .= "<td colspan='2'><strong>Order ID:</strong> #" . $order->id . "</td>";
                $html .= "<td></td>";
                $html .= "<td colspan='2' align='right'><strong>Order Date:</strong> " . date_format($order->date_ordered, "jS F Y") ."</td>";
                $html .= "</tr>";

                $html .= "<tr>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "<td colspan='2' align='right'>" . date_format($order->date_ordered, "g:i a") ."</td>";
                $html .= "</tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // ITEM DETAILS HEADING
                $html .= "<tr>";
                $html .= "<th align='left'>Product ID</th>";
                $html .= "<th align='left'>Product Name</th>";
                $html .= "<th align='left'>Price</th>";
                $html .= "<th align='left'>Quantity</th>";
                $html .= "<th align='left'>Total</th>";
                $html .= "</tr>";

                // ITEM DETAILS CONTENT
                foreach($order->items as $key => $item){
                    $html .= "<tr>";
                    $html .= "<td>#" . $item["product_id"] . "</td>";
                    $html .= "<td>" . $item["product_name"] . "</td>";
                    $html .= "<td align='right'>€" . number_format($item["selling_price"], 2) . "</td>";
                    $html .= "<td align='center'>" . $item["number_items"] . "</td>";
                    $html .= "<td align='right'>€" . number_format(($item["selling_price"] * $item["number_items"]), 2). "&nbsp;" . "</td>";
                    $html .= "</tr>";
                }

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // ORDER TOTAL
                $html .= "<tr>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "<td align='right'>Order Total:</td>";
                $html .= "<td align='right'>€" . number_format($order->order_total, 2) . "</td>";
                $html .= "</tr>";

                // THANK YOU
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";
                $html .= "<tr><td colspan='5' align='center'><strong>Paid with Thanks</strong></td></tr>";
                $html .= "<tr><td colspan='5' align='center'><em>Your order will be dispatched within 2 working days</em></td></tr>";
                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                $html .= "</table>";
                return $html;
            }
        }
    }
?>