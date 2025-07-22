<?php
require_once("bootstrap.php");
require_once("config.php");
if (isset($_SESSION['user'])) {
    header("Location: /");
}
$error = "";
if (isset($_POST['username']) && isset($_POST['password'])) {
    if (!$error) {
        $db = R::dispense('users');
        $db->username = $_POST['username'];
        $db->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $id = R::store($db);
        Header("Location: login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" href="style.css">
    <title>rPay -> Register</title>
</head>

<body oncontextmenu="return false;">
    <div class="container">
        <div class="row">
            <div class="col-xs-3 col-md-12">
                <div class="container">
                    <h1>Welcome to rPay</h1>
                    <h4>You can create a new account here</h4>
                    <form action="register.php" method="post">
                        <div class="form-group mt-3">
                            <label for="username">Username:</label>
                            <input type="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Username" name="username" min-length="3" required>
                            <div class="text-danger container mt-1">
                                <p class="error-message username-checker" id="username-length-text" style="display: none">Username must be at least 3 characters long</p>
                                <p class="error-message username-checker" id="username-special-text" style="display: none">Username must not contain special characters</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                            <div class="text-danger container mt-1">
                                <p class="error-message password-checker" id="password-length-text">Password must be at least 8 characters long</p>
                                <p class="error-message password-checker" id="password-uppercase-text">Password must contain at least one uppercase letter</p>
                                <p class="error-message password-checker" id="password-lowercase-text">Password must contain at least one lowercase letter</p>
                                <p class="error-message password-checker" id="password-number-text">Password must contain at least one number</p>
                                <p class="error-message password-checker" id="password-special-text">Password must contain at least one special character</p>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label for="confirm-password">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password" name="confirm_password" required>
                            <div class=" text-danger confirm-password-checker container mt-1">
                                <p class="error-message" id="confirm-password-text">Passwords do not match</p>
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                    <a href="login.php" id="login-button" class="btn btn-success">Already have an account?</a>
                </div>
            </div>
        </div>
    </div>
    <script src="js/register.js"></script>
</body>

</html>