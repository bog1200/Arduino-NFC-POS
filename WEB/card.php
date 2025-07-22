<?php
require("config.php");
require("bootstrap.php");
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: /account.php");
}
//if request is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
    }
    if (!isset($_POST['add']) && !isset($_POST['remove'])) {
        header("Location: /");
    }
    if (isset($_POST['add']) && isset($_POST['pos'])) {
        $pos = R::findOne('devices', 'id=?', [$_POST['pos']]);
        $pos_ip = $pos->ip;
        $url = "http://$pos_ip/pay?price=Add%20Card";
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'GET',
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if (!empty($result)) {
            if (!empty(R::findOne('card', 'uid=?', [$result]))) {
                header("Location: /account.php?err=CARD_ALREADY_EXISTING");
            }
            if (isset($_POST['cardname'])) {
                $name = $_POST['cardname'];
            } else $name = "Unnamed Card";
            $db = R::dispense('card');
            $db->uid = $result;
            $db->name = $name;
            $db->owner_id = $_SESSION['id'];
            $id = R::store($db);
            header("Location: /account.php");
        }
    } else if (isset($_POST['remove']) && isset($_POST['card'])) {
        $card = R::findOne('card', "id=?", [$_POST['card']]);
        if (!empty($card) && $card->owner_id == $_SESSION['id']) {
            R::trash($card);
            header("Location: /account.php");
        }
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
    <div class="container">
        <?php
        if (isset($_POST['add'])) {
        ?>
            <h1>Add card</h1>
            <h3>Balance: 0 EUR</h3>

            <form method="POST" action="card.php">
                <label for="cardname">Card Name</label>
                <input type="text" name="cardname" id="cardname" required></input>
                <br>
                <h4>How do you want to pay?</h4>
                <?php
                $pos = R::findAll('devices');
                for ($i = 1; $i <= count($pos); $i++) {
                    $pos_i = $pos[$i];
                    $pos_name = $pos_i->name;
                    $pos_id = $pos_i->id;
                    $pos_ip = $pos_i->ip;
                    echo "<input type='radio' name='pos' value='$pos_id' required>$pos_name ($pos_ip)</input><br>";
                }
                ?>
                <input type="hidden" name="add" value="1">
                <input type="submit" class="btn btn-primary" value="Add card">
            </form>
    </div>
<?php
        } else {
            echo "<h1>Product not found</h1>";
        }
?>
<a href="/account.php" class="btn btn-info">Back</a>
</body>