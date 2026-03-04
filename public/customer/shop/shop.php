<?php
require_once '../../../app/bootstrap.php';

Session::start();

$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']);
$isCustomer = $isLoggedIn && $_SESSION['role'] === 'customer';

$categories = [
    'mobile' => ['phone', 'tablet', 'charger', 'earbud', 'headphone', 'cable'],
    'computer' => ['desktop', 'laptop', 'motherboard', 'processor', 'ram', 'storage', 'casing']
];

$mainCategory = $_GET['mainCategory'] ?? '';
$subCategory  = $_GET['subCategory'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($mainCategory)) {
    $sql .= " AND mainCategory = :mainCategory";
    $params[':mainCategory'] = $mainCategory;
}

if (!empty($subCategory)) {
    $sql .= " AND subCategory = :subCategory";
    $params[':subCategory'] = $subCategory;
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | Cyber-Deals</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/customer/shop.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="full-page">

    <nav class="navbar">
        <div class="nav-content">
            <a href="#" class="brand-name">CYBER<span class="gold">-</span>DEALS</a>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class='bx bx-menu'></i>
            </button>
            <ul class="nav-links">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="shop.php" class="active">Shop</a></li>               
                <?php if (!$isLoggedIn): ?>                    
                    <li><a href="../../register.php">Register</a></li>
                    <li><a href="#" id="login-link">Login</a></li>
                <?php endif; ?>
                <?php if ($isCustomer): ?>
                    <li><a href="../cart/cart.php">Cart</a></li>
                    <li><a href="../order/orders.php">Orders</a></li>
                    <li><a href="../../logout.php" class="logout">Logout</a></li>
                <?php endif; ?>
            </ul>

            <div class="user-info">
                <?php if (!$isLoggedIn): ?>
                    <span class="login-msg">Log in to shop</span>
                <?php else: ?>
                    <span class="user-email"><?= htmlspecialchars($_SESSION['user_email']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar">
        <div class="sidebar-section">
            <p class="section-label"><i class='bx bx-category'></i> Main Categories</p>
            <ul>
                <?php foreach ($categories as $main => $subs): ?>
                    <li class="<?= ($mainCategory === $main) ? 'active' : '' ?>">
                        <a href="?mainCategory=<?= $main ?>">
                            <?= ucfirst($main) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!empty($mainCategory)): ?>
        <div class="sidebar-section">
            <p class="section-label"><i class='bx bx-subdirectory-right'></i> Sub Categories</p>
            <ul>
                <?php foreach ($categories[$mainCategory] as $sub): ?>
                    <li class="<?= ($subCategory === $sub) ? 'active' : '' ?>">
                        <a href="?mainCategory=<?= $mainCategory ?>&subCategory=<?= $sub ?>">
                            <?= ucfirst($sub) ?>
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
                <h2>
                    <?= $mainCategory ? ucfirst($mainCategory) : 'All Products' ?>
                    <?= $subCategory ? ' <i class="bx bx-chevron-right"></i> ' . ucfirst($subCategory) : '' ?>
                </h2>
                <p class="product-count">Showing <?= count($products) ?> items</p>
            </div>

            <div class="product-grid">
                <?php foreach ($products as $row): ?>
                    <div class="product-card">
                        <div class="product-img">
                            <img src="/Cyber/public/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= htmlspecialchars($row['name']) ?></h3>
                            <p class="product-price">Rs. <?= number_format($row['price'], 2) ?></p>
                            <p class="product-desc"><?= htmlspecialchars($row['description']) ?></p>
                            <?php if ($isCustomer): ?>
                                <form class="add-to-cart-form" method="POST" action="../cart/add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?= $row['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?= $row['price']; ?>">
                                    <div class="cart-controls">
                                        <div class="qty-wrapper">
                                            <button type="button" class="qty-btn qty-minus"><i class='bx bx-minus'></i></button>
                                            <input type="number" name="quantity" min="1" value="1" required class="qty-input">
                                            <button type="button" class="qty-btn qty-plus"><i class='bx bx-plus'></i></button>
                                        </div>
                                        <button type="submit" class="btn-add"><i class="bx bx-cart"></i>Add</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>                 
                    </div>
                <?php endforeach; ?>
            </div>
        </main>

        <div id="loginModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form id="customerLoginForm" class="login-form" action="../../login.php" method="post">
                    <h2>CUSTOMER LOGIN</h2>
                    <input type="hidden" name="login_type" value="customer">
                    <input type="hidden" name="redirect_to" value="customer/shop/shop.php">
                    <div class="input-field">
                        <input type="email" name="email" placeholder="Email" required>
                        <i class='bx bxl-gmail'></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" placeholder="Password" required>
                        <i class='bx bxs-lock'></i>
                    </div>
                    <input type="submit" value="Login" class="sctbtn">
                </form>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p>&copy; 2026 <span style="color:var(--accent-gold)">Cyber-Deals</span>. Premium Tech Hub.</p>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".add-to-cart-form").on("submit", function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();
                var actionUrl = form.attr("action");

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while adding the product to the cart.");
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var loginLink = document.getElementById('login-link');
            var modal = document.getElementById('loginModal');
            var span = document.querySelector('.close');
            
            if (!loginLink) return;
            
            loginLink.addEventListener('click', function (e) {
                e.preventDefault();
                modal.style.display = 'block';
                document.body.classList.add('modal-open');
            });
            
            span.addEventListener('click', function () {
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
            });
            
            window.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            });
            
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            });
        });
    </script>

    <script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        if (sidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth <= 992) {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    toggleSidebar();
                });
            });
        }
    });

    window.addEventListener('resize', function() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.sidebar-overlay');
        
        if (window.innerWidth > 992) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-card').forEach(card => {
                const minusBtn = card.querySelector('.qty-minus');
                const plusBtn = card.querySelector('.qty-plus');
                const qtyInput = card.querySelector('.qty-input');
                
                if (minusBtn && plusBtn && qtyInput) {
                    minusBtn.addEventListener('click', function() {
                        let value = parseInt(qtyInput.value) || 1;
                        if (value > 1) {
                            qtyInput.value = value - 1;
                        }
                    });
                    
                    plusBtn.addEventListener('click', function() {
                        let value = parseInt(qtyInput.value) || 1;
                        qtyInput.value = value + 1;
                    });
                    
                    qtyInput.addEventListener('input', function() {
                        if (this.value < 1) {
                            this.value = 1;
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
