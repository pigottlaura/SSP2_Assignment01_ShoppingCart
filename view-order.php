<?php
    include_once("./includes/actions/init.inc");

    // Check that someone is logged in
    if(isset($_SESSION["shopping_session"]->userId)) {

        // Check that they have requested a specific order
        if(isset($_GET["orderId"])){

            // Check that this order belongs to them
            if(Database::getOrder($_GET["orderId"])){
                include("./../libs/mpdf/mpdf.php");
                $mpdf = new mPDF();
                $mpdf->SetDefaultFont("Aegean");

                $receipt = Order::createReceipt($_GET["orderId"]);
                $mpdf->writeHTML($receipt);

                if(isset($_GET["action"]) && $_GET["action"] == "download"){
                    $filename = str_replace(" ", "", CONF_COMP_NAME) . "_Order#" . $_GET["orderId"] . "_Receipt.pdf";
                    $mpdf->output($filename, "D");
                    Functions::goToPage("page.php?page=view-my-orders");
                } else {
                    $mpdf->output();
                }
            } else {
                // This order does not belong to this user
                Functions::goToPage("page.php?page=view-my-orders");
            }
        } else {
            // The user has not supplied an order id
            Functions::goToPage("page.php?page=view-my-orders");
        }
    } else {
        // This user is not logged in
        Functions::goToPage("page.php?page=login-register");
    }
?>