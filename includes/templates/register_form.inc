<h2>Register</h2>
<form method="POST" action="page.php?page=register">
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
        <input type="text" name="username" required>
    </label>
    <br>
    <label>
        Password:
        <input type="password" name="password" required>
    </label>
    <label>
        Confirm Password:
        <input type="password" name="confirm_password" required>
    </label>
    <br>
    <input type="submit" value="Register">
</form>