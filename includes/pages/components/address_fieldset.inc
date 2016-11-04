<?php
    echo "<fieldset id='address'";
    if(!isset($currentData)) {
        echo "class='hidden'";
    }
    echo ">";
    if(isset($currentData)){
        echo "<input type='hidden' name='address_change'>";
    }

    echo "<legend>Address</legend>";

    echo "<label>House Name:";
    echo "<input type='text' name='houseName' required value='";
        if(isset($currentData)){
            echo $currentData["address_houseName"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>Street";
    echo "<input type='text' name='street' required value='";
        if(isset($currentData)){
            echo $currentData["address_street"];
        }
    echo "'>";
    echo "</label>";
    echo "<br>";

    echo "<label>Town:";
    echo "<input type='text' name='town' required  value='";
        if(isset($currentData)){
            echo $currentData["address_town"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>County:";
    echo "<input type='text' name='county' required value='";
        if(isset($currentData)){
            echo $currentData["address_county"];
        }
    echo "'>";
    echo "</label>";
    echo "<br>";

    echo "<label>Country:";
    echo "<input type='text' name='country' required  value='";
        if(isset($currentData)){
            echo $currentData["address_country"];
        }
    echo "'>";
    echo "</label>";
    echo "<label>ZipCode:";
    echo "<input type='text' name='zipCode' required value='";
        if(isset($currentData)){
            echo $currentData["address_zipCode"];
        }
    echo "'>";
    echo "</label>";
    echo "</fieldset>";
?>