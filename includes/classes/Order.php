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

            // Unsetting the temporary order details on the session object, as their only
            // purpose was to get the user from the shopping cart to creating the order.
            unset($_SESSION["shopping_session"]->tempOrderDetails);
        }

        public function placeOrder(){
            // Getting the total cost of the order by calculating the value of the shopping cart
            $this->orderTotal = $_SESSION["shopping_session"]->shopping_cart->calculateTotal();

            // Creating the order in the database, by passing this instance to the Database class.
            // If this is successful, continuing on to send the user a confirmation email and/or
            // generate a receipt for them
            if(Database::createOrder($this)) {

                // Emptying the shopping cart, as the order has been successfully placed
                $_SESSION["shopping_session"]->shopping_cart->emptyCart();
                $this->orderPlaced = true;

                echo "<h2>Thank you for your Order</h2>";

                // Attempting the send the email to the user. Even if this step fails, the order
                // has already been successfully placed, so the user will just be shown the error, and
                // given the option to view the receipt in their browser (along with any other previous
                // receipts they may have)
                if(!Email::sendOrderEmail($this)){
                    echo "Order has been successfully placed, but confirmation email failed to send - Order ID #" . $this->orderId ."<br><br>";
                }

                // Generating a receipt for this order, and displaying it on screen
                echo self::createReceipt($this->orderId);

                // Providing the user with a link to view all of their previous order receipts
                echo "<br><a href='page.php?page=view-my-orders'>View All Receipts</a>";
            }
        }

        static public function createReceipt($orderId){
            $html = "";

            // Getting the requested order from the database
            $order = (object) Database::getOrder($orderId);

            // Checking that the user that is currently logged in, is the one that placed
            // this order
            if($_SESSION["shopping_session"]->userId == $order->ordered_by){
                // Getting the details of the user that placed the order
                $order->ordered_by = Database::getUserDetails($order->ordered_by);
                // Getting the delivery address of the order (may not be the same as the user's
                // saved address details
                $order->delivery_address = Database::getOrderAddress($orderId);
                // Getting the items that were ordered
                $order->items = Database::getOrderItems($orderId);
                // Creating a date object, from the date value stored for when this order was placed
                $order->date_ordered = date_create($order->date_ordered);

                // Creating a temporary array to store the address details of the company,
                // so that they match with the syntax of the addresses database columns (can
                // only store single values in constants, so can't declare an array() in CONF)
                $companyAddress = array(
                    "houseName" => CONF_COMP_ADDRESS_HOUSENAME,
                    "street" => CONF_COMP_ADDRESS_STREET,
                    "town" => CONF_COMP_ADDRESS_TOWN,
                    "county" => CONF_COMP_ADDRESS_COUNTY,
                    "country" => CONF_COMP_ADDRESS_COUNTRY,
                    "zipCode" => CONF_COMP_ADDRESS_ZIPCODE
                );

                // Creating Table
                $html .= "<table width='600px'>";

                // Main Heading
                $html .= "<tr><th colspan='5'>Order Confirmation</th></tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // Ordered By
                $html .= "<tr>";
                $html .= "<td colspan='2'><strong>Ordered By:</strong> " . $order->ordered_by->contact["first_name"] . " " . $order->ordered_by->contact["last_name"] . "</td>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "</tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                //  Deliver To
                $html .= "<tr>";
                $html .= "<td colspan='2'><strong>Deliver To:</strong></td>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "</tr>";

                $html .= "<tr>";
                $html .= "<td colspan='2'>" . $order->recipient_first_name . " " . $order->recipient_last_name . "</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td colspan='2' align='right'>" . CONF_COMP_NAME . "</td>";
                $html .= "</tr>";

                // Address lines
                // Looping through the companyAddress temporary array, to get the headings for each
                // line of the address, and then accessing these from the delivery address and the
                // company address
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

                // Order Id and Date
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

                // Item details headings
                $html .= "<tr>";
                $html .= "<th>Product ID</th>";
                $html .= "<th>Product Name</th>";
                $html .= "<th>Price</th>";
                $html .= "<th>Quantity</th>";
                $html .= "<th>Total</th>";
                $html .= "</tr>";

                // Item details content
                // Displaying prices with 2 decimal places i.e. €20.00
                foreach($order->items as $key => $item){
                    $html .= "<tr>";
                    $html .= "<td>#" . $item["product_id"] . "</td>";
                    $html .= "<td>" . $item["product_name"] . "</td>";
                    $html .= "<td>€" . number_format($item["selling_price"], 2) . "</td>";
                    $html .= "<td>" . $item["number_items"] . "</td>";
                    $html .= "<td style='text-align:right'>€" . number_format(($item["selling_price"] * $item["number_items"]), 2). "</td>";
                    $html .= "</tr>";
                }

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                // Order Total
                $html .= "<tr>";
                $html .= "<td colspan='3'>&nbsp;</td>";
                $html .= "<td colspan='2' style='text-align:right'>Order Total: €" . number_format($order->order_total, 2) . "</td>";
                $html .= "</tr>";

                $html .= "<tr><td colspan='5'>&nbsp;</td></tr>";

                $html .= "</table>";
            }
            // Returning the HTML to the caller
            return $html;
        }
    }
?>