<?php
    include_once("./includes/actions/init.inc");
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
?>