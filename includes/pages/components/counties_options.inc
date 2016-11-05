<?php
    echo "<option disabled class='hidden'";
    if(!isset($currentAddressData)){
        echo " selected";
    }
    echo "></option>";

    $counties = array("Co. Antrim", "Co. Armagh", "Co. Carlow", "Co. Cavan", "Co. Clare", "Co. Cork", "Co. Derry", "Co. Donegal", "Co. Down", "Co. Dublin", "Co. Fermanagh", "Co. Galway", "Co. Kerry", "Co. Kildare", "Co. Kilkenny", "Co. Laois", "Co. Leitrim", "Co. Limerick", "Co. Longford", "Co. Louth", "Co. Mayo", "Co. Meath", "Co. Monaghan", "Co. Offaly", "Co. Roscommon", "Co. Sligo", "Co. Tipperary", "Co. Tyrone", "Co. Waterford", "Co. Westmeath", "Co. Wexford", "Co. Wicklow");

    foreach($counties as $county){
        echo "<option value='" . $county . "' ";

        if(isset($currentAddressData) && (strtolower($currentAddressData["county"]) == strtolower($county))){
            echo "selected='selected'";
        }
        echo ">" . $county . "</option>";
    }
?>