 <?php
    require("config.php");
    require("bootstrap.php");

    //if request is post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //if post contains product_id
        if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
            $product = R::findOne('product', 'id=?', [$_POST['product_id']]);
            if (isset($_POST['pos']) && !empty($_POST['pos'])) {
                //convert pos string to number
                $pos = R::findOne('devices', 'id=?', [$_POST['pos']]);
                $pos_ip = $pos->ip;
                //send request to pos without cURL
                $url = "http://$pos_ip/pay?price=$product->price+EUR";
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'GET',
                    )
                );
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $card = R::findOne('card', 'uid=?', [$result]);
                if (!empty($card)) {
                    if ($card->balance >= $product->price) {
                        $card->balance -= $product->price;
                        R::store($card);
                        $success = 1;
                    } else {
                        $success = 0;
                    }
                }
            } else {
                header("Location: /?error=NoPOS");
            }
        } else {
            header("Location: /?error=NoProduct");
        }
    } else {
        header("Location: /?error=NoPOST");
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
         <h1>Buy <?= $product->name ?></h1>
         <h3>Price: <?= $product->price ?> EUR</h3>
         <?php if ($result) {
            ?>
             <h4> Card: <?= $result ?> </h4>
         <?php
            }
            if (!$card) {
                echo "<h4>Invalid Card</h4>";
            } else {
                if ($success) {
                    echo "<h4>Purchase successful!</h4>";
                    echo "<h5>Remaining balance: $card->balance EUR</h4>";
                } else {
                    echo "<h4>Insufficient funds!</h4>";
                }
            }
            ?>
         <a href="/" class="btn btn-info">Back</a>
     </div>
 </body>