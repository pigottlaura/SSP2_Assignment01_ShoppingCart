<?php
    // Checking if a category has been specified, if not then defaulting to 1 (i.e. all products)
    $category = isset($_GET["category"]) && $_GET["category"] > 0 ? $_GET["category"] : 1;

    // Getting the relevant cateogry heading, or if category is 1 then defaulting it to "All Products"
    $heading = $category > 1 ? Database::getCategoryName($category) : "All Products";
    echo "<h2>" . $heading . "</h2>";
?>
<label for="sortBy">Sort Products by:</label>
<select id="sortBy">
    <option value="price-asc">Price (Ascending)</option>
    <option value="price-desc">Price (Descending)</option>
    <option value="name-asc">Name (Ascending)</option>
    <option value="name-desc">Name (Descending)</option>
    <option value="date_added-desc">Latest</option>
</select>

<label for="itemsPerPage">Items Per Page:</label>
<select id="itemsPerPage">
    <option value="2">2</option>
    <option value="4">4</option>
    <option value="8">8</option>
    <option value="20">20</option>
    <option value="50">50</option>
</select>
<div id="products"></div>

<?php
    // The call for the products to be displayed is made client side, through and AJAX request
    // as soon as the page loads
?>