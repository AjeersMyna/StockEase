<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement using PDO
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: customers.php"); // Redirect to dashboard after login
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #F5F5DC; /* Seashell - Consistent background */
            font-family: 'Inter', sans-serif;
        }

        .login-container {
            background: #FFFFFF;
            padding: 30px;
            border-radius: 12px; /* Slightly more rounded */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08); /* Subtle shadow */
            width: 350px;
            text-align: center;
            border: 1px solid #E0EEE0; /* Light border */
        }

        .login-container h2 {
            margin-bottom: 25px;
            font-size: 28px;
            font-weight: 700; /* Use 700 for bold */
            color: #2c3e50; /* Darker heading color */
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            text-align: left; /* Keep label aligned to the left */
        }

        .form-group label {
            font-weight: 600;  /* Use 600 for semi-bold */
            display: block;
            margin-bottom: 8px;
            color: #495057;
            text-align: left; /* Align label to the left */
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ADD8E6; /* Powder Blue border */
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease; /* Smooth transition */
            align-self: center; /* Center the input field horizontally */
        }

        .form-group input:focus {
            outline: none;
            border-color: #87CEFA; /* Light Powder Blue focus border */
            box-shadow: 0 0 0 3px rgba(135, 206, 250, 0.1);  /* Very subtle shadow */
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: #ADD8E6; /* Powder Blue */
            color: #2c3e50; /* Darker text color */
            border: none;
            font-size: 18px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition */
            font-weight: 600;
        }

        .login-btn:hover {
            background-color: #90CAF9; /* Lighter shade on hover */
            color: #2c3e50;  /* Keep text the same on hover */
        }

        .error {
            color: #F08080; /* Light Coral */
            margin-bottom: 15px;
            font-size: 14px;
            background-color: #FFE0E0;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #F08080;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>