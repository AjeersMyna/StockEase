<?php
require_once 'db.php';
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
    die("Access Denied! Only admin can add users.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = $_POST["role"];

    // Validate inputs
    if (empty($username) || empty($password) || empty($role)) {
        die("All fields are required.");
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "User added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
</head>
<body>
    <h2>Add New User</h2>
    <form method="post" action="add_user.php">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Role:</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
        </select><br>

        <button type="submit">Add User</button>
    </form>
</body>
</html>
