<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
            exit();
        } elseif ($action == 'add') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            $email = $_POST['email'] ?? null;

            $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $password, $role, $email])) {
                header("Location: manage_users.php?success=1");
                exit();
            } else {
                header("Location: add_user.php?error=" . urlencode("Failed to create user in the database."));
                exit();
            }
        } elseif ($action == 'edit') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $role = $_POST['role'];
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'];
            $params = [$username, $role, $email, $id];
            $sql = "UPDATE users SET username = ?, role = ?, email = ?";

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password = ?";
                $params = [$username, $role, $email, $hashedPassword, $id];
            }

            $sql .= " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt->execute($params)) {
                header("Location: manage_users.php?updated=1");
                exit();
            } else {
                header("Location: edit_user.php?id=$id&error=" . urlencode("Failed to update user."));
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            exit();
        }
    } catch (PDOException $e) {
        // Check for duplicate username error
        if ($e->getCode() === '23000' && strpos($e->getMessage(), 'username') !== false) {
            header("Location: add_user.php?error=Username already exists");
            exit();
        }
        header("Location: manage_users.php?error=" . urlencode("Database error: " . $e->getMessage()));
        exit();
    }
} else {
    header("Location: manage_users.php");
    exit();
}
?>