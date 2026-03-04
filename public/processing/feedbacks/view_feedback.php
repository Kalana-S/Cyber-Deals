<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$feedback = new Feedback($db);
$stmt = $feedback->viewFeedbacks();

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $feedback->readPaging($from_record_num, $records_per_page);
$total_rows = $feedback->count();
$total_pages = ceil($total_rows / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedbacks | Processing Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/processing/feedback_view_manage.css" rel="stylesheet">
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
                <li><a href="manage_feedback.php">Manage</a></li>
                <li><a href="view_feedback.php" class="active">View</a></li>
            </ul>
            <div class="nav-title">VIEW <span class="green">FEEDBACKS</span></div>
        </div>
    </nav>

    <div class="container">
        <div class="main">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th class="col-center">ID</th>
                        <th class="col-center">User ID</th>
                        <th class="col-center">User Email</th>
                        <th class="col-center">Feedback</th>
                        <th class="col-center">Rating</th>
                        <th class="col-center">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="col-center"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="col-center"><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td class="col-center"><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td class="col-start"><?php echo htmlspecialchars($row['feedback']); ?></td>
                            <td class="col-center"><?php echo htmlspecialchars($row['rating']); ?></td>
                            <td class="col-center"><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
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
