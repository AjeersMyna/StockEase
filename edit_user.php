<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT id, username, role, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: manage_users.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error fetching user data: " . $e->getMessage();
    header("Location: manage_users.php?error=1");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id, name FROM roles");
    $stmt->execute();
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching roles: " . $e->getMessage();
    $roles = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #F5F5DC; /* Seashell */
        }
        h2 {
            color: #2c3e50; /* Darker, more professional heading */
            font-weight: 700;
        }
        .form-control {
            border-color: #ADD8E6; /* Powder Blue */
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #87CEFA; /* Light Powder Blue */
            box-shadow: 0 0 0 0.25rem rgba(135, 206, 250, 0.25);
        }
        .form-select {
            border-color: #ADD8E6; /* Powder Blue */
            border-radius: 0.5rem;
        }
        .form-select:focus {
            border-color: #87CEFA; /* Light Powder Blue */
            box-shadow: 0 0 0 0.25rem rgba(135, 206, 250, 0.25);
        }
        .btn-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
        }
        .btn-primary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            border-color: #90CAF9 !important;
        }
        .btn-secondary {
            background-color: #E0EEE0 !important; /* Honeydew */
            color: #2c3e50 !important;
            border-color: #E0EEE0 !important;
            border-radius: 0.5rem;
        }
        .btn-secondary:hover {
            background-color: #F0FFF0 !important; /* Lighter shade on hover */
            border-color: #F0FFF0 !important;
        }
        .alert-danger {
            background-color: #F08080 !important; /* Light Coral */
            color: #fff !important;
            border-color: #F08080 !important;
            border-radius: 0.5rem;
        }
        .alert-danger .btn-close {
            color: #fff;
        }
        .invalid-feedback {
            color: #F08080;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-edit me-2"></i>Edit User</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="process_user.php" method="post" id="editUserForm">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                <div id="username-feedback" class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" id="password" name="password">
                <div id="password-feedback" class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['id'] ?>" <?= ($user['role'] == $role['id'] ? 'selected' : '') ?>><?= htmlspecialchars($role['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="role-feedback" class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email (Optional)</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                <div id="email-feedback" class="invalid-feedback"></div>
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editUserForm = document.getElementById('editUserForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const roleSelect = document.getElementById('role');
        const emailInput = document.getElementById('email');

        editUserForm.addEventListener('submit', (event) => {
            let isValid = true;

            usernameInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');
            roleSelect.classList.remove('is-invalid');
            emailInput.classList.remove('is-invalid');
            document.getElementById('username-feedback').textContent = '';
            document.getElementById('password-feedback').textContent = '';
            document.getElementById('role-feedback').textContent = '';
            document.getElementById('email-feedback').textContent = '';

            if (!usernameInput.value.trim()) {
                usernameInput.classList.add('is-invalid');
                document.getElementById('username-feedback').textContent = 'Username is required';
                isValid = false;
            } else if (usernameInput.value.trim().length < 3) {
                usernameInput.classList.add('is-invalid');
                document.getElementById('username-feedback').textContent = 'Username must be at least 3 characters long';
                isValid = false;
            }

            if (passwordInput.value.trim() && passwordInput.value.trim().length < 6) {
                passwordInput.classList.add('is-invalid');
                document.getElementById('password-feedback').textContent = 'Password must be at least 6 characters long';
                isValid = false;
            }

            if (!roleSelect.value) {
                roleSelect.classList.add('is-invalid');
                document.getElementById('role-feedback').textContent = 'Please select a role';
                isValid = false;
            }

            if (emailInput.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                emailInput.classList.add('is-invalid');
                document.getElementById('email-feedback').textContent = 'Invalid email format';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            } else {
                document.getElementById('editUserForm').submit();
            }
        });
    });
</script>
</body>
</html>
