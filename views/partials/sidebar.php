<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-dark text-white p-3 vh-100" style="width: 250px;">
    <h4 class="text-center">STOCKEASE</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'dashboard.php' ? 'fw-bold text-primary' : '' ?>" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'sales.php' ? 'fw-bold text-primary' : '' ?>" href="sales.php">Sales</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'purchase.php' ? 'fw-bold text-primary' : '' ?>" href="purchase.php">Purchase</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'inventory.php' ? 'fw-bold text-primary' : '' ?>" href="inventory.php">Inventory</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'customers.php' ? 'fw-bold text-primary' : '' ?>" href="customers.php">Customers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $currentPage === 'system.php' ? 'fw-bold text-primary' : '' ?>" href="system.php">System</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" href="logout.php">Logout</a>
        </li>
    </ul>
</nav>
