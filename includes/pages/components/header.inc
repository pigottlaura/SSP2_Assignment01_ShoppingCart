<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Shopping Cart</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="icon" type="image/png" href="./images/logo.png" />
    <script src="js/script.js"></script>

    <?php
        include_once("./includes/actions/init.inc");
    ?>

</head>
<body>
    <header>
        <h1><a href="?page=home">My Shopping Cart</a></h1>
        <nav>
            <ul>
                <li><a href="?page=home">Home</a></li>
                <li><a href="?page=products">Products</a>
                    <?php
                        echo "<ul class='submenu'>";
                        $categories = Database::getCategories();
                        foreach($categories as $key => $category) {
                            echo "<li><a href='?page=products&category=" . $category["id"] . "'>" . $category["name"] . "</a></li>";
                        }
                        echo "</ul>";
                    ?>
                </li>
                <li><a href="?page=contact-us">Contact Us</a></li>
            </ul>
        </nav>
        <div>
            <?php
                if(isset($_SESSION["shopping_session"]->userId)){
                    echo "<a href='page.php?page=view-my-orders'>View My Orders</a>  / ";
                    echo "<a href='page.php?" . $_SERVER["QUERY_STRING"] . "&action=logout'>Logout</a>";
                } else {
                    echo "<a href='page.php?page=login-register'>Login/Register</a>";
                }
            ?>
            <button>
                <a href="?page=shopping-cart">
                    View Cart
                    <?php
                        echo "(<span id='scNumItems'>" . $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() . "</span>)";
                    ?>
                </a>
            </button>
            <p>
                Total:
                <?php
                    echo "€<span id='scTotal'>" . $_SESSION["shopping_session"]->shopping_cart->calculateTotal() . "</span>";
                ?>
            </p>
        </div>
    </header>