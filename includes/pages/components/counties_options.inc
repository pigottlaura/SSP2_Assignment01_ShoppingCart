<?php
    $counties = array("Antrim", "Armagh", "Carlow", "Cavan", "Clare", "Cork", "Derry", "Donegal", "Down", "Dublin", "Fermanagh", "Galway", "Kerry", "Kildare", "Kilkenny", "Laois", "Leitrim", "Limerick", "Longford", "Louth", "Mayo", "Meath", "Monaghan", "Offaly", "Roscommon", "Sligo", "Tipperary", "Tyrone", "Waterford", "Westmeath", "Wexford", "Wicklow");

    foreach($counties as $county){
        echo "<option value='Co. " . $county . "' ";

        if(isset($currentAddressData) && (strtolower($currentAddressData["county"]) == strtolower($county))){
            echo "selected='selected'";
        }
        echo ">Co. " . $county . "</option>";
    }
?>