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
                if(!isset($_GET["action"]) || $_GET["action"] != "place-order") {
                    include("./includes/pages/shopping-cart.inc");
                }
            } else if($_GET["page"] == "delivery-details") {
                if(!isset($_GET["action"]) || $_GET["action"] != "save-delivery-details") {
                    include("./includes/pages/delivery-details.inc");
                }
            } else if($_GET["page"] == "login-register") {
                include("./includes/pages/login-register.inc");
            } else if($_GET["page"] == "contact-us") {
                include("./includes/pages/contact-us.inc");
            } else if($_GET["page"] == "view-my-orders") {
                include("./includes/pages/view-my-orders.inc");
            } else if($_GET["page"] == "edit-my-details") {
                if(!isset($_GET["action"]) || $_GET["action"] != "update-details") {
                    include("./includes/pages/edit-my-details.inc");
                }
            } else {
                include("./includes/pages/home.inc");
            }
        } else {
            include("./includes/pages/home.inc");
        }

        // COMPLETE ACTIONS
        if(isset($_GET["action"])){
            if ($_GET["action"] == "place-order") {
                include("./includes/actions/place-order.inc");
            } else if ($_GET["action"] == "adjust-num-items") {
                include("./includes/actions/adjust-num-items.inc");
            } else if($_GET["action"] == "login") {
                include("./includes/actions/login.inc");
            } else if($_GET["action"] == "register") {
                include("./includes/actions/register.inc");
            } else if($_GET["action"] == "update-details") {
                include("./includes/actions/update-details.inc");
            } else if($_GET["action"] == "save-delivery-details") {
                include("./includes/actions/save-delivery-details.inc");
            } else if($_GET["action"] == "logout") {
                Login::logout();
            }
        }

        // EMPTY THE SHOPPING CART
        if(isset($_GET["emptyCart"])) {
            $_SESSION["shopping_session"]->shopping_cart->emptyCart();
            Functions::removeFromQueryString("emptyCart");
        }
    ?>
</section>
<?php
    include("./includes/pages/components/footer.inc");
?>