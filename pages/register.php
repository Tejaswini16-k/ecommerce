<?php
session_start();
include("../includes/db.php");  // Ensure this file properly connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Securely get form inputs
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';

    // Check if the email already exists
    $check_sql = "SELECT * FROM users WHERE email = :email";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([':email' => $email]);
    if ($check_stmt->rowCount() > 0) {
        $_SESSION['error'] = "Email already exists! Please use a different email.";
        header("Location: register.php");
        exit();
    }

    // Ensure all required fields are filled
    if (!empty($username) && !empty($email) && !empty($password) && !empty($fullname)) {
        $sql = "INSERT INTO users (username, email, password, full_name, phone, address, gender, dob) 
                VALUES (:username, :email, :password, :fullname, :phone, :address, :gender, :dob)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':fullname' => $fullname,
            ':phone' => $phone,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob
        ]);

        // Redirect to login page after successful registration
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f8fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .register-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .register-container label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-top: 10px;
        }

        .register-container input,
        .register-container select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .register-container button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .register-container button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="register-container">
        <h2>Register</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red;'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="register.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Full Name:</label>
            <input type="text" name="fullname" required>

            <label>Phone:</label>
            <input type="text" name="phone" required>

            <label>Address:</label>
            <input type="text" name="address" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label>Date of Birth:</label>
            <input type="date" name="dob" required>

            <button type="submit">Register</button>
        </form>
    </div>

</body>
</html>