<?php
session_start();
require_once '../includes/db.php'; // Make sure path is correct

if (!$pdo) {
    die("Database connection failed."); // Debugging message
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items with product details
$stmt = $pdo->prepare("
    SELECT cart.*, products.name, products.price 
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Handle Address Submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($cart_items)) {
        $_SESSION['checkout_details'] = [
            'fullname' => $_POST['fullname'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'postal' => $_POST['postal'],
            'country' => $_POST['country'],
            'total_price' => $total_price
        ];

        // Store checkout data in session
        $_SESSION['checkout_data'] = [
        'total_price' => $total_price
];

// Redirect to payment page
header("Location: payment.php");
exit();

    } else {
        echo "<p style='color: red; text-align: center;'>Your cart is empty. Add items before checkout.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 40%;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            text-align: left;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container input, .form-container select {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .form-container button {
            margin-top: 20px;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container button:hover {
            background: darkgreen;
        }

        .total {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Checkout</h2>
    
    <form method="POST">
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" required>

        <label for="address">Street Address:</label>
        <input type="text" name="address" required>

        <label for="city">City:</label>
        <input type="text" name="city" required>

        <label for="state">State:</label>
        <input type="text" name="state" required>

        <label for="postal">Postal Code:</label>
        <input type="text" name="postal" required>

        <label for="country">Country:</label>
        <input type="text" name="country" required>

        <h3>Total Price: <span class="total">$<?= number_format($total_price, 2); ?></span></h3>

        <button type="submit">Proceed to Payment</button>
    </form>
</div>

</body>
</html>