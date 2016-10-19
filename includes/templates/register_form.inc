<h2>Register</h2>
<form method="POST" action="register.php">
    <label>
        First Name:
        <input type="text" name="firstName" required>
    </label>
    <label>
        Last Name:
        <input type="text" name="lastName" required>
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
        <input type="password" name="confirmPassword" required>
    </label>
    <br>
    <input type="submit" value="Register">
</form>