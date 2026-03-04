<?php
require_once '../../../app/bootstrap.php';

Session::start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$order->user_id = $_SESSION['user_id'];

$stmt = $order->getOrdersByUserId();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedOrders = [];

foreach ($orders as $row) {
    $groupedOrders[$row['cart_id']][] = $row;
}

$message = $_SESSION['order_message'] ?? null;
unset($_SESSION['order_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order | Cyber-Deals</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link href="../../assets/css/customer/orders.css" rel="stylesheet">
</head>
<body class="full-page">
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="brand">CYBER<span class="gold">-</span>DEALS</a>                
            <ul class="nav-links">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="../shop/shop.php">Shop</a></li>
                <li><a href="../cart/cart.php">Cart</a></li>
                <li><a href="orders.php" class="active">Orders</a></li>
                <li><a href="../../logout.php">Logout</a></li>
            </ul>
            <div class="user-section">
                <div class="user-email">
                    <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-title">
            <h2 class="section-title">MISSION <span class="gold">LOGS</span></h2>
        </div>

        <?php if (empty($groupedOrders)): ?>
            <div class="empty-orders">
                <p>NO ORDER DATA FOUND IN SYSTEM.</p>
                <a href="../shop/shop.php">RE-INITIALIZE SHOPPING</a>
            </div>
        <?php else: ?>
            <div class="orders-grid">
                <?php 
                $orderIndex = 1;
                foreach ($groupedOrders as $batch_id => $items): 
                    $batchTotal = 0;
                ?>
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-header-info">
                                <h3>ORDER_ID: #<?= str_pad($orderIndex, 3, '0', STR_PAD_LEFT) ?></h3>
                                <span class="batch-id">REF_HASH: <?= htmlspecialchars($batch_id) ?></span>
                            </div>
                            <form action="cancel_order.php" method="post" onsubmit="return confirm('Abort this transaction?')">
                                <input type="hidden" name="batch_id" value="<?= $batch_id ?>">
                                <button type="submit" class="btn-cancel">Abort Order</button>
                            </form>
                        </div>

                        <div class="order-details">
                            <table class="order-table">
                                <thead>
                                    <tr>
                                        <th>Product Manifest</th>
                                        <th>Units</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): 
                                        $batchTotal += $item['total_price'];
                                    ?>
                                        <tr>
                                            <td class="item-name"><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td class="text-right">Rs. <?= number_format($item['total_price'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right total-label">AGGREGATE TOTAL:</td>
                                        <td class="text-right total-amount">Rs. <?= number_format($batchTotal, 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                <?php 
                    $orderIndex++; 
                endforeach; 
                ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 <span style="color:var(--accent-gold)">Cyber-Deals</span>. Premium Tech Hub.</p>
        </div>
    </footer>

    <?php if ($message): ?>
    <script>
        alert("<?= htmlspecialchars($message) ?>");
    </script>
    <?php endif; ?>
</body>
</html>