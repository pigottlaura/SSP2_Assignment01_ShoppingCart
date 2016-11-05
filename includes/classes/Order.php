<?php
    class Order {
        public $orderedBy;
        public $orderDate;
        public $orderItems;
        public $orderTotal;
        public $orderPlaced = false;
        public $deliveryDetails;

        public function __construct($tempOrderDetails){
            $this->orderedBy = $_SESSION["shopping_session"]->userId;
            $this->orderDate = date('d F Y');
            $this->orderItems = $tempOrderDetails->orderItems;
            $this->orderPlaced = false;
            $this->deliveryDetails = $tempOrderDetails->deliveryDetails;
            unset($_SESSION["shopping_session"]->tempOrderDetails);
        }

        public function placeOrder(){
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            if(Database::createOrder($this)) {
                $_SESSION["shopping_session"]->shopping_cart->emptyCart();
                echo "<h2>Thank you for your Order</h2>";
                if(!Email::sendOrderEmail($this)){
                    self::orderError("Order has been successfully placed, but confirmation email failed to send - Order ID #" . $this->orderId);
                }
                echo self::createReceipt($this->orderId);
                echo "<br><a href='page.php?page=view-my-orders'>View All Receipts</a>";
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
                $order->delivery_address = Database::getOrderAddress($orderId);
                $order->items = Database::getOrderItems($orderId);
                $order->date_ordered = date_create($order->date_ordered);

                $companyAddress = array(
                    "houseName" => "The Showgrounds",
                    "street" => "5 Wolfe Tone Street",
                    "town" => "Clonmel",
                    "county" => "Tipperary",
                    "country" => "Ireland",
                    "zipCode" => "YNZZ44"
                );

                $html = "<table width='600px'>";

                // HEADING
                $html .= "<tr><th colspan='5'>Order Confirmation</th></tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // Ordered By
                $html .= "<tr>";
                $html .= "<td colspan='2'><strong>Ordered By:</strong> " . $order->ordered_by->contact["first_name"] . " " . $order->ordered_by->contact["last_name"] . "</td>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "</tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // TITLE LINES
                $html .= "<tr>";
                $html .= "<td colspan='2'><strong>Deliver To:</strong></td>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "</tr>";
                
                $html .= "<tr>";
                $html .= "<td colspan='2'>" . $order->recipient_first_name . " " . $order->recipient_last_name . "</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td colspan='2' align='right'>" . CONF_COMP_NAME . "</td>";
                $html .= "</tr>";

                // ADDRESS LINES
                foreach($companyAddress as $key => $value){
                    $html .= "<tr>";
                    if(isset($order->delivery_address[$key])) {
                        $html .= "<td colspan='2'>" . $order->delivery_address[$key] . "</td>";
                    } else {
                        $html .= "<td colspan='2'>&nbsp;</td>";
                    }
                    $html .= "<td>&nbsp;</td>";
                    if(isset($companyAddress[$key])){
                        $html .= "<td colspan='2' align='right'>" . $companyAddress[$key] . "</td>";
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