<?php
session_start();
require_once '../includes/db.php'; // Ensure path is correct

if (!isset($_SESSION['user_id']) || !isset($_SESSION['checkout_data'])) {
    header("Location: checkout.php"); // Redirect if no checkout data
    exit();
}

$user_id = $_SESSION['user_id'];
$checkout_data = $_SESSION['checkout_data'];
$total_price = $checkout_data['total_price'];

// Handle Payment Selection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $payment_method = $_POST['payment_method'];

    // Update Order with Payment Method
    $stmt = $pdo->prepare("UPDATE orders SET payment_method = ?, status = 'Confirmed' WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$payment_method, $user_id]);

    // Clear session data after order confirmation
    unset($_SESSION['checkout_data']);

    // Redirect to Thank You page
    header("Location: thank_you.php?payment=" . urlencode($payment_method));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            background-color: #f8f9fa; 
            text-align: center; 
            margin: 0; 
            padding: 0;
        }

        .container { 
            width: 40%; 
            margin: 50px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); 
            text-align: left;
        }

        h2 { 
            margin-bottom: 20px; 
            color: #333; 
            text-align: center;
        }

        .payment-option { 
            display: flex; 
            align-items: center; 
            padding: 12px; 
            border: 2px solid #ddd; 
            border-radius: 8px; 
            margin: 8px 0; 
            cursor: pointer; 
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .payment-option:hover { 
            background: #e9ecef; 
            border-color: #007bff;
        }

        .payment-option input { 
            margin-right: 12px; 
            transform: scale(1.2);
        }

        button { 
            width: 100%; 
            padding: 12px; 
            background: #28a745; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 6px; 
            font-size: 18px; 
            margin-top: 20px;
            transition: background 0.3s;
        }

        button:hover { 
            background: #218838; 
        }

        h3 { 
            text-align: center; 
            margin-top: 20px; 
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Choose Your Payment Method</h2>
    <form method="POST">
        <label class="payment-option">
            <input type="radio" name="payment_method" value="Cash on Delivery" required> Cash on Delivery
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="Google Pay" required> Google Pay
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="PhonePe" required> PhonePe
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="UPI" required> UPI (Pay via any App)
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="Credit/Debit Card" required> Credit/Debit Card
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="Pay Later" required> Pay Later
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="Wallets" required> Wallets
        </label>
        <label class="payment-option">
            <input type="radio" name="payment_method" value="EMI" required> EMI
        </label>

        <h3>Total: ₹<?= number_format($total_price, 2); ?></h3>
        <button type="submit">Confirm Payment</button>
    </form>
</div>

</body>
</html>
