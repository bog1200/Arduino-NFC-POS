<?php
require_once("config.php");
require_once("bootstrap.php");
if (!isset($_SESSION['user']) || !isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("Location: login.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $product = R::findOne('product', 'id=?', [$_POST['delete']]);
        R::trash($product);
    } else if (isset($_POST['add']) && isset($_POST['name']) && isset($_POST['price'])) {
        $db = R::dispense('product');
        $db->name = $_POST['name'];
        $db->price = $_POST['price'];
        R::store($db);
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
    <title>rPay</title>
</head>

<body>
    <h1>Manage products</h1>
    <?php
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
            <h3>Product ID: <?= $product_id ?></h3>
            <h2>Price: <?= $product_price  ?>EUR</h2>
            <form action='products.php' method='post'>
                <input type='hidden' name='delete' value=<?= $product_id ?>>
                <input class='btn btn-danger' type='submit' value='Delete'>
            </form>
        </div>
    <?php
    }
    ?>
    <h1>Add product:</h1>
    <form action='products.php' method='post'>
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="price">Product Price:</label>
        <input type="number" name="price" id="price" step="0.01" min="0.01" required> EUR
        <input type='hidden' name='add' value=1>
        <br>
        <input class='btn btn-success' type='submit' value='Add'>
    </form>
    </div>
    <a href="/" class="btn btn-info">Back</a>
</body>

</html>