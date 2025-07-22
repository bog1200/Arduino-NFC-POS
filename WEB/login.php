<?php
require("bootstrap.php");
require("config.php");
if (isset($_SESSION['user'])) {
    header("Location: /");
}
if (isset($_GET['force']) && $_GET['force'] == 1) {
    session_destroy();
}
$login_error = "";
if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = R::findOne('users', 'username=?', [$_POST['username']]);
    if ($user) {
        if (password_verify($_POST['password'], $user->password)) {
            $_SESSION['user'] = $user->username;
            $_SESSION['id'] = $user->id;
            $_SESSION['admin'] = $user->admin;
            header("Location: /");
        }
    } else {
        $login_error = "Wrong username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>rPay -> Login</title>
</head>

<body oncontextmenu="return false;">
    <div class="conainer">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="container">
                    <h1>Welcome to rPay</h1>
                    <h4>Please login to access the site</h4>
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                            <p class="login-error text-danger"><?= $login_error ?></p>
                        </div>

                        <button type="submit" class="btn btn-primary">Log In</button>
                        <a href="register.php" class="btn btn-secondary">Register</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- <p>User: <?= $user ?></p> -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>