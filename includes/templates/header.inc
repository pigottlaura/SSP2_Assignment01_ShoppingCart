<header>
    <h1><a href="?page=home">My Shopping Cart</a></h1>
    <nav>
        <ul>
            <li><a href="?page=home">Home</a></li>
            <li><a href="?page=products">Products</a>
                <ul class="submenu">
                    <li><a href="?page=products&category=1">All Products</a></li>
                    <li><a href="?page=products&category=2">Teddy Bears</a></li>
                    <li><a href="?page=products&category=3">Category03</a></li>
                    <li><a href="?page=products&category=4">Category04</a></li>
                </ul>
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
    </div>
</header>