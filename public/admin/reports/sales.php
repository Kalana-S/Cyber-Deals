<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$stmt = $order->read();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalSales = 0;
foreach ($sales as $row) {
    $totalSales += (float) $row['total_price'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/admin/sales_report.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class='bx bx-menu'></i>
                </button>
                <a class="brand-name">CYBER<span class="blue">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="sales.php" class="active">Sales</a></li>
                <li><a href="stock.php">Stock</a></li>
                <li><a href="download_sales_report.php" class="btn-report">Get Report</a></li>
            </ul>
            <div class="nav-title">SALES <span class="blue">CONTROL</span></div>
        </div>
    </nav>
   
    <div class="page-wrapper">
        <div class="main-content">
            <div class="content-header">
                <h2>SALES <span class="blue">OVERVIEW</span></h2>
                <p class="product-count">Tracking all current transactions</p>
            </div>
            <div class="table-container">
                <?php if (!empty($sales)): ?>
                    <table class="cyber-table">
                        <thead>
                            <tr>
                                <th class="col-start">Product Name</th>
                                <th class="col-middle">Product Price</th>
                                <th class="col-middle">Quantity</th>
                                <th class="col-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $row): ?>
                                <tr>
                                    <td class="col-start"><?= htmlspecialchars($row['product_name']) ?></td>
                                    <td class="col-middle">Rs. <?= number_format($row['product_price'], 2) ?></td>
                                    <td class="col-middle"><span class="qty-badge"><?= (int) $row['quantity'] ?></span></td>
                                    <td class="col-end">Rs. <?= number_format($row['total_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" class="col-end"><strong style="color: var(--text-dim); text-transform: uppercase; font-family: 'Orbitron', sans-serif;">Total Revenue</strong></td>
                                <td class="col-end" style="color: var(--accent-blue); font-size: 1.1rem; font-weight: bold;">
                                    Rs. <?= number_format($totalSales, 2) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class='bx bx-ghost' style="font-size: 2rem; color: var(--text-dim); margin-bottom: 10px;"></i>
                        <p>No sales records detected in the database.</p>
                    </div>
                <?php endif; ?>
            </div>  
        </div>
    </div>
</body>
</html>