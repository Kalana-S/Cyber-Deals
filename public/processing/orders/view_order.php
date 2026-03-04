<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$stmt = $order->read();

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $order->readPaging($from_record_num, $records_per_page);
$total_rows = $order->count();
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Orders | Processing Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/processing/order_view_manage.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class='bx bx-menu'></i>
                </button>
                <a class="brand-name">CYBER<span class="green">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="manage_order.php">Manage</a></li>
                <li><a href="view_order.php" class="active">View</a></li>
            </ul>
            <div class="nav-title">VIEW <span class="green">ORDERS</span></div>
        </div>
    </nav>

    <div class="container">
        <div class="main">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="col-center">Order ID</th>
                        <th class="col-center">Customer Name</th>
                        <th class="col-center">Email</th>
                        <th class="col-center">Product</th>
                        <th class="col-center">Price</th>
                        <th class="col-center">Qty</th>
                        <th class="col-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="col-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['user_name']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['user_email']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['product_name']) ?></td>
                            <td class="col-center"><?= number_format($row['product_price'], 2) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['quantity']) ?></td>
                            <td class="col-center"><?= number_format($row['total_price'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No orders found
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>    
    </div>
</body>
</html>
