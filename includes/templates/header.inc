<header>
    <h1><a href="?page=home">My Shopping Cart</a></h1>
    <nav>
        <ul>
            <li><a href="?page=home">Home</a></li>
            <li><a href="?page=products">Products</a>
                <ul class="submenu">
                    <li><a href="?page=category1">Category01</a></li>
                    <li><a href="?page=category2">Category02</a></li>
                    <li><a href="?page=category3">Category03</a></li>
                    <li><a href="?page=category4">Category04</a></li>
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