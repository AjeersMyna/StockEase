<div style="display: flex;">
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <nav class="bg-peachy text-dark p-3" style="position: fixed; width: 250px; height: 100vh; overflow-y: auto; border-right: 1px solid #e0eee0; background-color: #FFDAB9;">
        <div style="display: flex; align-items: center; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid #e0eee0;">
        <img src="https://cdn-icons-png.flaticon.com/512/2512/2512183.png" alt="StockEase Logo" style="margin-right: 0.5rem; height: 24px;">
            <h4 class="text-center" style="color: #2c3e50; margin: 0;">STOCKEASE</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'dashboard.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="dashboard.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'sales.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="sales.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Sales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'purchase.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="purchase.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Purchase</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'inventory.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="inventory.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Inventory</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'customers.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="customers.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $currentPage === 'system.php' ? 'fw-bold text-powderblue' : 'text-dark' ?>" href="system.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">System</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="logout.php" style="padding: 0.75rem 1rem; border-radius: 0.5rem;">Logout</a>
            </li>
        </ul>
    </nav>
    <div style="margin-left: 250px; width: calc(100% - 250px); min-height: 100vh; background-color: #f5f5dc;">
    </div>
</div>
