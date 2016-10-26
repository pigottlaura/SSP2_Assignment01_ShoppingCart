<h2>Login</h2>
<form method="POST" action="
    <?php
        echo "page.php?" . $_SERVER["QUERY_STRING"] . "&action=login"
    ?>
">
    <label>
        Username:
        <input type="text" name="username" required>
    </label>
    <label>
        Password:
        <input type="password" name="password" required>
    </label>
    <input type="hidden" name="honeypot">
    <input type="submit" value="Login">
</form>