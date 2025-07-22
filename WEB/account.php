<?php
include "config.php";
include "bootstrap.php";
if (!isset($_SESSION['user'])) {
    header("Location: /login.php");
}
$user =  R::findOne('users', 'id=?', [$_SESSION['id']]);
if ($user) {

    $cards =  R::findAll('card', 'owner_id=?', [$_SESSION['id']]);
    if (!$cards) {
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" content="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <title>rPay</title>
</head>

<body>
    <div class="container-fluid">
        <h1>Welcome <?= $_SESSION['user'] ?></h1>
        <?php
        if (!empty($cards)) {
        ?>
            <h1>Your cards:</h1>
            <?php

            foreach ($cards as $card) {
            ?>
                <div class="container">
                    <h2>Card Name: <?= $card->name ?></h2>
                    <h3>Card UID: <?= $card->uid ?></h3>
                    <h2>Balance: <?= $card->balance ?> EUR<h2>
                            <form method="POST" action="card.php">
                                <input type="submit" class="btn btn-danger" value="Remove Card"></input>
                                <input type="hidden" name="card" value="<?= $card->id ?>">
                                <input type="hidden" name="remove" value="1">
                            </form>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="container">
                <h1>You have no cards</h1>
            </div>
        <?php
        }
        ?>
        <form method="POST" action="card.php">
            <input type="hidden" name="add" value="1">
            <input type="submit" class="btn btn-success" value="Add Card"></input>
        </form>
        <a href="/" class="btn btn-info">Back</a>
        <a href="logout.php" class="btn btn-info">Log out</a>
    </div>
</body>

</html>