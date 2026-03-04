<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $product->readPaging($from_record_num, $records_per_page);
$total_rows = $product->count();
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Processing Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/processing/product_view_manage.css" rel="stylesheet">
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
                <li><a href="add_product.php">Add</a></li>
                <li><a href="manage_product.php" class="active">Manage</a></li>
                <li><a href="view_product.php">View</a></li>
            </ul>
            <div class="nav-title">MANAGE <span class="green">PRODUCTS</span></div>
        </div>
    </nav>

    <div class="container">   
        <div class="main">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="col-center">ID</th>
                        <th class="col-center">Name</th>
                        <th class="col-center">Description</th>
                        <th class="col-center">Price (Rs.)</th>
                        <th class="col-center">Main Category</th>
                        <th class="col-center">Sub Category</th>
                        <th class="col-center">Qty</th>
                        <th class="col-center">Image</th>
                        <th class="col-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="col-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="col-start"><?= htmlspecialchars($row['description']) ?></td>
                            <td class="col-center"><?= number_format($row['price'], 2) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['mainCategory']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['subCategory']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['quantity']) ?></td>
                            <td class="col-center">
                                <?php if (!empty($row['image'])): ?>
                                    <img 
                                        src="../../<?= htmlspecialchars($row['image']) ?>" 
                                        class="product-img"
                                        alt="<?= htmlspecialchars($row['name']) ?>">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="col-center">
                                <a href="update_product.php?id=<?= $row['id'] ?>" 
                                    class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                                <button 
                                    class="btn btn-sm btn-danger"
                                    onclick="confirmDelete(<?= $row['id'] ?>, this)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No products found</td>
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

    <script>
        // Alert code
        function confirmDelete(productId, button) {
            if (!confirm("Are you sure you want to permanently delete this product?\nThis action cannot be undone.")) {
                return;
            }

            fetch('delete_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = button.closest('tr');
                    row.remove();
                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(() => {
                showAlert('danger', 'Something went wrong. Please try again.');
            });
        }

        function showAlert(type, message) {
            const alertBox = document.createElement('div');
            alertBox.className = `alert alert-${type} alert-dismissible fade show mt-3`;
            alertBox.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container').prepend(alertBox);
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.add("hide");

                    setTimeout(() => {
                        alertBox.remove();
                    }, 400);
                }, 3000);
            }
        }
    </script>
</body>
</html>
