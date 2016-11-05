<h2>Contact Us</h2>
<iframe id="map" src="
    <?php
        echo CONF_COMP_MAP
    ?>
    " width="400" height="300" frameborder="0" style="border:0" allowfullscreen>
</iframe>
<div id="contactDetails">
    <strong>Company Address:</strong><br>
    <?php
        echo CONF_COMP_NAME . "<br>";
        echo CONF_COMP_ADDRESS_HOUSENAME . "<br>";
        echo CONF_COMP_ADDRESS_STREET . "<br>";
        echo CONF_COMP_ADDRESS_TOWN . "<br>";
        echo CONF_COMP_ADDRESS_COUNTY . "<br>";
        echo CONF_COMP_ADDRESS_COUNTRY . "<br>";
        echo CONF_COMP_ADDRESS_ZIPCODE . "<br>";
    ?>

    <br>
    <strong>Email:</strong>
    <?php
        echo "<a href='mailto:" . CONF_COMP_CONTACT_EMAIL . "'>" . CONF_COMP_CONTACT_EMAIL . "</a><br>";
    ?>

    <strong>Phone</strong>
    <?php
        echo "<a href='tel:" . CONF_COMP_CONTACT_NUMBER . "'>" . CONF_COMP_CONTACT_NUMBER . "</a><br>";
    ?>
</div>
