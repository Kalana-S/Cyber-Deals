<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$page = isset($_GET['page']) && is_numeric($_GET['page'])
    ? (int)$_GET['page']
    : 1;

$records_per_page = 10;
$from_record_num = ($page - 1) * $records_per_page;

$stmt = $user->readPaging($from_record_num, $records_per_page);
$total_rows = $user->count();
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/admin/user_view_manage.css" rel="stylesheet">
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
                <li><a href="add_user.php">Add</a></li>
                <li><a href="manage_user.php" class="active">Manage</a></li>
                <li><a href="view_user.php">View</a></li>
            </ul>
            <div class="nav-title">MANAGE <span class="blue">USERS</span></div>
        </div>
    </nav>

    <div class="container">
        <div class="main">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="col-center">ID</th>
                        <th class="col-center">Name</th>
                        <th class="col-center">Email</th>
                        <th class="col-center">Role</th>
                        <th class="col-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($stmt->rowCount() > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="col-center"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="col-center"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="col-center"><?= htmlspecialchars(ucfirst($row['role'])) ?></td>
                            <td class="col-center" style="text-align: center;">
                                <a href="update_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary" style="margin-right: 20px;">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-danger"
                                        onclick="confirmDelete(<?= $row['id'] ?>, this)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No users found</td>
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
        function confirmDelete(id, btn) {
            if (!confirm('Are you sure you want to permanently delete this user?\nThis action cannot be undone.')) return;

            fetch('delete_user.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btn.closest('tr').remove();
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(() => alert('Something went wrong'));
        }
    </script>
</body>
</html>
