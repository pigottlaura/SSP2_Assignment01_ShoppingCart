<?php
    echo "<legend>Address</legend>";

    echo "<label>House Name:";
    echo "<input type='text' name='houseName'";
        if(isset($currentAddressData)){
            echo " value='" . $currentAddressData["houseName"] . "' required";
        }
    echo ">";
    echo "</label>";
    echo "<label>Street";
    echo "<input type='text' name='street'";
        if(isset($currentAddressData)){
            echo " value='" . $currentAddressData["street"] . "' required";
        }
    echo ">";
    echo "</label>";
    echo "<br>";

    echo "<label>Town:";
    echo "<input type='text' name='town'";
        if(isset($currentAddressData)){
            echo " value='" . $currentAddressData["town"] . "' required";
        }
    echo ">";
    echo "</label>";
    echo "<label>County:";
    echo "<select name='county'";
        if(isset($currentAddressData)) {
            echo " required";
        }
    echo ">";
    include("./includes/pages/components/counties_options.inc");
    echo "</select>";
    echo "</label>";
    echo "<br>";

    echo "<label>Country:";
    echo "<input type='text' name='country'";
        if(isset($currentAddressData)){
            echo " value='" . $currentAddressData["country"] . "' required";
        }
    echo ">";
    echo "</label>";
    echo "<label>ZipCode:";
    echo "<input type='text' name='zipCode'";
        if(isset($currentAddressData)){
            echo " value='" . $currentAddressData["zipCode"] . "' required";
        }
    echo ">";
    echo "</label>";
?>