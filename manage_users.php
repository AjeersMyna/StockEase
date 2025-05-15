<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
try {
    $stmt = $conn->prepare("SELECT id, username, role FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $users = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockEase - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #F5F5DC; /* Seashell */
            font-family: 'Inter', sans-serif;
        }

        h2 {
            color: #2c3e50; /* Darker, more professional heading */
            font-weight: 700;
        }

        .btn-primary {
            background-color: #ADD8E6 !important; /* Powder Blue */
            color: #2c3e50 !important;
            border-color: #ADD8E6 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #90CAF9 !important; /* Lighter shade on hover */
            color: #2c3e50 !important;
        }

        .btn-success {
            background-color: #F0FFF0 !important; /* Honeydew */
            color: #2c3e50 !important;
            border-color: #F0FFF0 !important;
            border-radius: 0.5rem;
        }

        .btn-success:hover {
            background-color: #E6FFE6 !important; /* Lighter shade on hover */
            border-color: #E6FFE6 !important;
        }

        .btn-danger {
            background-color: #F08080 !important; /* Light Coral */
            color: #FFFFFF !important;
            border-color: #F08080 !important;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #CD5C5C !important; /* Darker shade on hover */
            border-color: #CD5C5C !important;
        }

        .alert {
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: #F0FFF0 !important; /* Honeydew */
            color: #2c3e50 !important;
            border-color: #F0FFF0 !important;
        }

        .alert-danger {
            background-color: #F08080 !important; /* Light Coral */
            color: #FFFFFF !important;
            border-color: #F08080 !important;
        }

        .btn-close {
            color: #fff;
        }

        .card {
            border: 1px solid #E0EEE0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #F8F8FF; /* Very light background for striped rows */
        }

        .table-striped td,
        .table-striped th {
            border-bottom-color: #E0EEE0;
        }

        .table-striped thead th {
            border-bottom-width: 2px;
            border-bottom-color: #ADD8E6;
            color: #2c3e50;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include('views/partials/sidebar.php'); ?>

    <div class="container-fluid p-4">
        <h2><i class="fas fa-users me-2"></i>Manage Users</h2>
        <div class="mb-4">
            <a href="add_user.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New User</a>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User created successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error: <?php echo htmlspecialchars($_GET['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No users found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary me-2"><i class="fas fa-edit"></i> Edit</a>
                                        <button class="btn btn-sm btn-danger delete-user-btn" data-user-id="<?= $user['id'] ?>"><i class="fas fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch('process_user.php', {
                        method: 'POST',
                        body: new URLSearchParams({
                            'action': 'delete',
                            'id': userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.href = 'manage_users.php?delete_success=1';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
                }
            });
        });
    });
</script>
</body>
</html>
