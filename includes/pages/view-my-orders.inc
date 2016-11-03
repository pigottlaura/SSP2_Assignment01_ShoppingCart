<?php
    $orders = Database::getUsersOrders($_SESSION["shopping_session"]->userId);

    if(count($orders) > 0){
        foreach($orders as $key => $order){
            $dateOrdered = date_create($order["date_ordered"]);
            echo "Order #" . $order["id"] . " - " . date_format($dateOrdered, "jS M Y") . " at " . date_format($dateOrdered, "g:i a") . " ";
            echo "<a href='view-order.php?orderId=" . $order["id"] . "'>View</a> or <a href='view-order.php?orderId=" . $order["id"] . "&action=download'>Download</a><br>";
        }
    } else {
        echo "<h2>You have no previous orders</h2>";
    }
?>