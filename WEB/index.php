<?php
require_once("config.php");
require_once("bootstrap.php");
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = "User";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" content="img/favicon.ico">
    <title>rPay</title>
</head>

<body>
    <div class="container-fluid">
        <h1>Welcome to rPay, <?= $user ?></h1>
        <h2>Please select a product, or log in to manage your cards</h2>
        <?php
        if (!isset($_SESSION['id'])) {
        ?>
            <a href="login.php" class="btn btn-info">Log In</a>
            <?php
        } else {
            if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
            ?>
                <a href="products.php" class="btn btn-info">Manage products</a> <?php } ?>

            <a href="account.php" class="btn btn-info">Manage cards</a> <a href="logout.php" class="btn btn-info">Log Out</a>
        <?php
        }
        // return list of all products
        $products = R::findAll('product');

        for ($i = 1; $i <= count($products); $i++) {
            $product = $products[$i];
            $product_name = $product->name;
            $product_price = $product->price;
            $product_id = $product->id;
        ?>

            <div class='product-container'>
                <h2>Item: <?= $product_name ?></h2>
                <h2>Price: <?= $product_price  ?>EUR</h2>
                <form action='buy.php' method='post'>
                    <input type='hidden' name='product_id' value=<?= $product_id ?>>
                    <input class='btn btn-primary' type='submit' value='Buy'>
                </form>
            </div>
        <?php
        }
        ?>
    </div>


</body>

</html>