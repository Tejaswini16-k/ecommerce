<?php
session_start();
if (!isset($_GET['payment'])) {
    header("Location: index.php");
    exit();
}
$payment_method = htmlspecialchars($_GET['payment']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: green;
        }
        p {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✅ Payment Successful!</h2>
    <p>Thank you for your order.</p>
    <p>Your selected payment method: <strong><?= $payment_method; ?></strong></p>
    <p>We will process your order shortly.</p>
    
    <a href="/ecommerce/index.php">Continue to Shop</a>

</div>

</body>
</html>
