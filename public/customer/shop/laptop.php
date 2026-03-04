<?php
require_once '../../../app/bootstrap.php';

Session::start();

$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']);
$isCustomer = $isLoggedIn && $_SESSION['role'] === 'customer';

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 10;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$products = $product->getBySubCategory(
    'laptop',
    $from_record_num,
    $records_per_page
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop | Cyber-Deals</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link href="../../assets/css/customer/mobile_laptop_desktop.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="full-page">
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="brand">CYBER<span class="gold">-</span>DEALS</a>
            <ul class="nav-links">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <?php if (!$isLoggedIn): ?>
                    <li><a href="../../register.php">Register</a></li>
                    <li><a href="#" id="login-link">Login</a></li>
                <?php endif; ?>
                <?php if ($isCustomer): ?>
                    <li><a href="../cart/cart.php" class="cart-button">Cart</a></li>
                    <li><a href="../order/orders.php">Orders</a></li>
                    <li><a href="../../logout.php" class="logout">Logout</a></li>
                <?php endif; ?>
            </ul>            
            <div class="user-actions">
                <?php if ($isCustomer): ?>
                    <div class="user-email">
                        <?= htmlspecialchars($_SESSION['user_email']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!isset($_SESSION['user_email'])): ?>
                    <div class="login-reminder">
                        Log in to shop
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <h1 class="page-title">LAPTOP <span style="color:#ffd700">COLLECTION</span></h1>
        
        <div class="product-grid">
            <?php foreach ($products as $row): ?>
                <div class="product-item">
                    <div class="product-img">
                        <img src="/Cyber/public/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </div>
                    <div class="product-details">
                        <h3 class="product-name"><?php echo $row['name']; ?></h3>
                        <p class="product-price">Rs. <?php echo (number_format($row['price'], 2)); ?></p>
                        <p class="product-description"><?php echo $row['description']; ?></p>
                        
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <form id="add-to-cart-form-<?php echo $row['id']; ?>" action="../cart/add_to_cart.php" method="post" class="add-to-cart-form" data-page-url="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                <div class="action-row">
                                    <div class="qty-wrapper">
                                        <button type="button" class="qty-btn qty-minus"><i class='bx bx-minus'></i></button>
                                        <input type="number" name="quantity" min="1" value="1" required class="quantity-input">
                                        <button type="button" class="qty-btn qty-plus"><i class='bx bx-plus'></i></button>
                                    </div>
                                    <button type="submit" class="add-to-cart-btn"><i class='bx bx-cart'></i>Add</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="customerLoginForm" class="login-form" action="../../login.php" method="post">
                <h2>CUSTOMER LOGIN</h2>
                <input type="hidden" name="role" value="customer">
                <input type="hidden" name="redirect_to" value="customer/shop/laptop.php">
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-item').forEach(card => {
                const minusBtn = card.querySelector('.qty-minus');
                const plusBtn = card.querySelector('.qty-plus');
                const qtyInput = card.querySelector('.quantity-input');
                
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
