<?php
    include("./includes/pages/components/header.inc");
?>
<section>
    <?php
        // GET PAGES
        if(isset($_GET["page"])) {
            if ($_GET["page"] == "products") {
                include("./includes/pages/products.inc");
            } else if ($_GET["page"] == "shopping-cart") {
                if(!isset($_GET["action"]) || $_GET["action"] != "order") {
                    include("./includes/pages/shopping-cart.inc");
                }
            } else if($_GET["page"] == "login-register") {
                include("./includes/pages/login-register.inc");
            } else if($_GET["page"] == "contact-us") {
                include("./includes/pages/contact-us.inc");
            } else if($_GET["page"] == "view-my-orders") {
                include("./includes/pages/view-my-orders.inc");
            } else {
                include("./includes/pages/home.inc");
            }
        } else {
            include("./includes/pages/home.inc");
        }

        // COMPLETE ACTIONS
        if(isset($_GET["action"])){
            if ($_GET["action"] == "order") {
                include("./includes/actions/order.inc");
            } else if ($_GET["action"] == "adjustNumItems") {
                include("./includes/actions/adjustNumItems.inc");
            } else if($_GET["action"] == "login") {
                include("./includes/actions/login.inc");
            } else if($_GET["action"] == "register") {
                include("./includes/actions/register.inc");
            } else if($_GET["action"] == "logout") {
                Login::logout();
            }
        }
    ?>
</section>
<?php
    include("./includes/pages/components/footer.inc");
?>