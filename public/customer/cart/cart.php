<?php
require_once '../../../app/bootstrap.php';

Session::start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$cart = new Cart($db);
$cart->user_id = $_SESSION['user_id'];
$cartItems = $cart->getCartItems();

$totalPrice = 0;

$back_url = $_SESSION['last_page'] ?? 'shop.php';

$message = $_SESSION['cart_message'] ?? null;
unset($_SESSION['cart_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | Cyber-Deals</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link href="../../assets/css/customer/cart.css" rel="stylesheet">
</head>
<body class="full-page">
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="brand">CYBER<span class="gold">-</span>DEALS</a>                
            <ul class="nav-links">
                <li><a href="../../index.php">Home</a></li>
                <li><a href="<?php echo $back_url; ?>">Back</a></li>
                <li><a href="cart.php" class="active">Cart</a></li>
                <li><a href="../order/orders.php">Orders</a></li>
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
        <?php if (!empty($message)): ?>
            <div class="cart-message">
                <i class='bx bx-info-circle'></i> <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <div class="page-title">
            <h2 class="section-title">YOUR <span class="gold">CART</span></h2>
        </div>
        <div class="cart-content">
            <div class="cart-items-list">
                <?php if ($cartItems->rowCount() > 0): ?>
                    <?php while ($row = $cartItems->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="cart-item">
                            <div class="item-details">
                                <h3><?= htmlspecialchars($row['product_name']) ?></h3>
                                <div class="item-meta">
                                    <span>Unit: <span class="item-name">Rs. <?= number_format($row['product_price'], 2) ?></span></span>
                                    <span style="margin-left:15px;">Qty: <span class="item-name"><?= $row['quantity'] ?></span></span>
                                </div>
                            </div>
                            <div class="item-subtotal">
                                <p class="label">Subtotal</p>
                                <p class="amount">Rs. <?= number_format($row['product_price'] * $row['quantity'], 2) ?></p>
                            </div>
                            <div class="item-actions">
                                <form action="delete_from_cart.php" method="post">
                                    <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn-remove">Remove</button>
                                </form>
                            </div>
                        </div>
                        <?php $totalPrice += $row['product_price'] * $row['quantity']; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-cart">
                        <p style="font-size: 1.5rem; color: var(--text-dim);">SYSTEM: Cart empty.</p>
                        <a href="../shop/shop.php">RE-INITIALIZE SHOPPING</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($cartItems->rowCount() > 0): ?>
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>ORDER SUMMARY</h3>
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>Rs. <?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <div class="summary-line">
                            <span>Delivery</span>
                            <span class="free">Encrypted Free</span>
                        </div>
                        <div class="summary-total">
                            <span>TOTAL</span>
                            <span>Rs. <?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <form action="checkout.php" method="post">
                            <button type="submit" class="btn-checkout">PROCEED TO CHECKOUT</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 <span style="color:var(--accent-gold)">Cyber-Deals</span>. Premium Tech Hub.</p>
        </div>
    </footer>

    <script>
        setTimeout(function () {
            const msg = document.querySelector('.cart-message');
            if (msg) {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            }
        }, 2500);
    </script>

</body>
</html>