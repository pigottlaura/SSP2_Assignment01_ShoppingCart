<h2>Register</h2>
<form method="POST" action="
    <?php
    echo "page.php?" . $_SERVER["QUERY_STRING"] . "&action=register"
    ?>
" id="register">
    <label>
        First Name:
        <input type="text" name="first_name" required>
    </label>
    <label>
        Last Name:
        <input type="text" name="last_name" required>
    </label>
    <br>
    <label>
        Email:
        <input type="email" name="email" required>
    </label>
    <label>
        Username:
        <input type="text" name="username" id="requestedUsername" required>
        <span id="requestedUsernameAvailable" class="icon"></span>
    </label>
    <br>
    <label>
        Password:
        <input type="password" name="password" required data-match="confirm_password">
    </label>
    <label>
        Confirm Password:
        <input type="password" name="confirm_password" required>
        <span id="passwordMatch" class="icon"></span>
    </label>
    <br>
    <input type="submit" value="Register">
</form>