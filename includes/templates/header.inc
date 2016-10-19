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
        <a href="?page=login">Login/Register</a>
        <button>
            <a href="?page=shopping-cart">
                View Cart
                <?php
                    echo "(" . $_SESSION["shopping_session"]->shopping_cart->getTotalNumItems() . ")";
                ?>
            </a>
        </button>
        <p>
            Total:
            <?php
                echo "€" . $_SESSION["shopping_session"]->shopping_cart->calculateTotal();
            ?>
        </p>
    </div>
</header>