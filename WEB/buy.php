<?php
require("config.php");
require("bootstrap.php");

//if request is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $product = R::findOne('product', 'id=?', [$_POST['product_id']]);
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
        if (isset($product)) {
        ?>
            <h1>Buy <?= $product->name ?></h1>
            <h3>Price: <?= $product->price ?> EUR</h3>

            <h4>How do you want to pay?</h4>
            <form method="POST" action="pay.php">
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
                <input type="hidden" name="product_id" value="<?= $product->id ?>">
                <input type="submit" class="btn btn-primary" value="Pay">
            </form>
            <a href="/" class="btn btn-info">Back</a>
    </div>
<?php
        } else {
            echo "<h1>Product not found</h1>";
        }
?>
</body>