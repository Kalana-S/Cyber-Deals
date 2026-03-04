<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$mainCategory = $_GET['mainCategory'] ?? '';
$subCategory  = $_GET['subCategory'] ?? '';

$mainCategories = $db
    ->query("SELECT DISTINCT mainCategory FROM products ORDER BY mainCategory")
    ->fetchAll(PDO::FETCH_ASSOC);

if ($mainCategory) {
    $stmt = $db->prepare(
        "SELECT DISTINCT subCategory FROM products WHERE mainCategory = ? ORDER BY subCategory"
    );
    $stmt->execute([$mainCategory]);
    $subCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $subCategories = $db
        ->query("SELECT DISTINCT subCategory FROM products ORDER BY subCategory")
        ->fetchAll(PDO::FETCH_ASSOC);
}

$sql = "SELECT name, quantity, price FROM products
        WHERE (:mainCategory = '' OR mainCategory = :mainCategory)
          AND (:subCategory  = '' OR subCategory  = :subCategory)
        ORDER BY name";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':mainCategory' => $mainCategory,
    ':subCategory'  => $subCategory
]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Report | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="../../assets/css/staff/admin/stock_report.css" rel="stylesheet">
</head>
<body>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

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
                <li><a href="sales.php">Sales</a></li>
                <li><a href="stock.php" class="active">Stock</a></li>
                <li>
                    <form action="download_stock_report.php" method="POST">
                        <input type="hidden" name="mainCategory" value="<?= htmlspecialchars($mainCategory) ?>">
                        <input type="hidden" name="subCategory" value="<?= htmlspecialchars($subCategory) ?>">
                        <button type="submit" class="btn-report">
                            <span>Get Report</span>
                        </button>
                    </form>
                </li>
            </ul>
            <div class="nav-title">STOCK <span class="blue">CONTROL</span></div>
        </div>
    </nav>

    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-section">
            <p class="section-label"><i class='bx bx-category'></i> SECTOR: PRIMARY</p>
            <ul>
                <li class="<?= empty($mainCategory) ? 'active' : '' ?>">
                    <a href="stock.php">All Categories</a>
                </li>
                <?php foreach ($mainCategories as $cat): ?>
                    <li class="<?= ($mainCategory === $cat['mainCategory']) ? 'active' : '' ?>">
                        <a href="?mainCategory=<?= urlencode($cat['mainCategory']) ?>">
                            <?= htmlspecialchars($cat['mainCategory']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!empty($mainCategory)): ?>
        <div class="sidebar-section">
            <p class="section-label"><i class='bx bx-subdirectory-right'></i> SECTOR: SUB</p>
            <ul>
                <li class="<?= empty($subCategory) ? 'active' : '' ?>">
                    <a href="?mainCategory=<?= urlencode($mainCategory) ?>">All Sub-Categories</a>
                </li>
                <?php foreach ($subCategories as $cat): ?>
                    <li class="<?= ($subCategory === $cat['subCategory']) ? 'active' : '' ?>">
                        <a href="?mainCategory=<?= urlencode($mainCategory) ?>&subCategory=<?= urlencode($cat['subCategory']) ?>">
                            <?= htmlspecialchars($cat['subCategory']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </aside>

    <div class="page-wrapper">
        <main class="main-content">
            <div class="content-header">
                <h2>INVENTORY <span class="blue">MANIFEST</span></h2>
                <p class="product-count">Displaying <?= count($products) ?> active units in database</p>
            </div>

            <div class="table-container">
                <table class="cyber-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th class="qty">Stock Quantity</th>
                            <th class="price">Unit Price (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($products): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="product-name-cell"><?= htmlspecialchars($p['name']) ?></td>
                                <td class="text-center">
                                    <span class="qty-badge <?= $p['quantity'] < 10 ? 'low-stock' : '' ?>">
                                        <?= (int)$p['quantity'] ?>
                                    </span>
                                </td>
                                <td class="text-right price-cell"><?= number_format($p['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-data">SYSTEM_ERROR: No inventory data found for selected filters.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    </script>
</body>
</html>