<?php
    echo "<legend>Address</legend>";

    echo "<label>House Name:";
    echo "<input type='text' name='houseName' required value='";
        if(isset($currentAddressData)){
            echo $currentAddressData["houseName"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>Street";
    echo "<input type='text' name='street' required value='";
        if(isset($currentAddressData)){
            echo $currentAddressData["street"];
        }
    echo "'>";
    echo "</label>";
    echo "<br>";

    echo "<label>Town:";
    echo "<input type='text' name='town' required  value='";
        if(isset($currentAddressData)){
            echo $currentAddressData["town"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>County:";
    echo "<select name='county' required>";
    include("./includes/pages/components/counties_options.inc");
    echo "</select>";
    echo "</label>";
    echo "<br>";

    echo "<label>Country:";
    echo "<input type='text' name='country' required  value='";
        if(isset($currentAddressData)){
            echo $currentAddressData["country"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>ZipCode:";
    echo "<input type='text' name='zipCode' required value='";
        if(isset($currentAddressData)){
            echo $currentAddressData["zipCode"];
        }
    echo "'>";
    echo "</label>";
?>