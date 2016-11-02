<?php
    $orders = Database::getUsersOrders($_SESSION["shopping_session"]->userId);

    if(count($orders) > 0){
        foreach($orders as $key => $order){
            $dateOrdered = date_create($order["date_ordered"]);
            echo "<a href='page.php?page=view-order&orderId=" . $order["id"] . "'>Order #" . $order["id"] . "</a> - " . date_format($dateOrdered, "jS M Y") . " at " . date_format($dateOrdered, "g:i a") . "<br>";
        }
    } else {
        echo "<h2>You have no previous orders</h2>";
    }
?>