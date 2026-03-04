<?php
require_once '../../app/bootstrap.php';

$database = new Database();
$conn = $database->getConnection();

$product_count = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$user_count    = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$order_count   = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Space+Mono:wght@400;700&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../assets/css/staff/admin/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="mobile-topbar">
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <span class="mobile-brand">CYBER<span class="blue">-</span>DEALS</span>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 class="brand-name">CYBER<span class="blue">-</span>DEALS</h2>
            <p class="brand-sub">ADMIN CONSOLE</p>
        </div>

        <hr class="sidebar-divider">

        <nav class="sidebar-nav">
            <div class="sidebar-group">
                <p class="sidebar-label">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M20 7H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2zm-9 3h2v2h-2v-2zm0 4h2v2h-2v-2zM7 10h2v2H7v-2zm0 4h2v2H7v-2zm10 4H7v-2h10v2zm0-4h-2v-2h2v2zm0-4h-2V8h2v2z"/></svg>
                    Product Management
                </p>
                <a href="products/add_product.php" class="btn btn-menu">
                    <span class="btn-icon">+</span> Add Product
                </a>
                <a href="products/view_product.php" class="btn btn-menu">
                    <span class="btn-icon">◎</span> View Details
                </a>
                <a href="products/manage_product.php" class="btn btn-menu">
                    <span class="btn-icon">⊞</span> Manage Products
                </a>
            </div>

            <div class="sidebar-group">
                <p class="sidebar-label">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    User Management
                </p>
                <a href="users/add_user.php" class="btn btn-menu">
                    <span class="btn-icon">+</span> Add User
                </a>
                <a href="users/view_user.php" class="btn btn-menu">
                    <span class="btn-icon">◎</span> View Details
                </a>
                <a href="users/manage_user.php" class="btn btn-menu">
                    <span class="btn-icon">⊞</span> Manage Users
                </a>
            </div>

            <div class="sidebar-group">
                <p class="sidebar-label">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
                    Order Management
                </p>
                <a href="orders/view_order.php" class="btn btn-menu">
                    <span class="btn-icon">◎</span> View Orders
                </a>
                <a href="orders/manage_order.php" class="btn btn-menu">
                    <span class="btn-icon">⊞</span> Manage Orders
                </a>
            </div>

            <div class="sidebar-group">
                <p class="sidebar-label">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                    Analytics &amp; Reports
                </p>
                <a href="reports/stock.php" class="btn btn-menu">
                    <span class="btn-icon">▣</span> Stock Report
                </a>
                <a href="reports/sales.php" class="btn btn-menu">
                    <span class="btn-icon">▦</span> Sales Report
                </a>
            </div>

            <div class="sidebar-group">
                <p class="sidebar-label">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    Home Page
                </p>
                <a href="../logout.php" class="btn btn-home">
                    <span class="btn-icon">⌂</span> Home Page
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content">
        <div class="main-inner">

            <header class="dash-header">
                <div class="dash-header-left">
                    <h1 class="dash-title">Dashboard <span class="blue">Overview</span></h1>
                    <p class="dash-sub">Welcome back, Administrator. Here's what's happening today.</p>
                    <p class="dash-eyebrow">SYSTEM STATUS: <span class="status-online">● ONLINE</span></p>
                </div>
                
                <div class="dash-header-right">
                    <div class="datetime-block">
                        <p class="date-label">CURRENT SESSION</p>
                        <p class="datetime-val" id="liveTime">--:--:--</p>
                    </div>
                </div>
            </header>

            <section class="stats-grid">
                <div class="stat-card card-product">
                    <div class="stat-glow"></div>
                    <div class="stat-top">
                        <span class="stat-tag">PRODUCTS</span>
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 7H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                    </div>
                    <div class="stat-number"><?php echo number_format($product_count); ?></div>
                    <p class="stat-label">Total Products</p>
                    <div class="stat-bar"><div class="stat-bar-fill" style="width: 72%"></div></div>
                </div>

                <div class="stat-card card-product">
                    <div class="stat-glow"></div>
                    <div class="stat-top">
                        <span class="stat-tag">USERS</span>
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                    </div>
                    <div class="stat-number"><?php echo number_format($user_count); ?></div>
                    <p class="stat-label">Total Users</p>
                    <div class="stat-bar"><div class="stat-bar-fill" style="width: 58%"></div></div>
                </div>

                <div class="stat-card card-product">
                    <div class="stat-glow"></div>
                    <div class="stat-top">
                        <span class="stat-tag">ORDERS</span>
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </div>
                    </div>
                    <div class="stat-number"><?php echo number_format($order_count); ?></div>
                    <p class="stat-label">Total Orders</p>
                    <div class="stat-bar"><div class="stat-bar-fill" style="width: 85%"></div></div>
                </div>
            </section>

            <section class="quick-actions">
                <h2 class="section-title">Quick Actions</h2>
                <div class="actions-grid">
                    <a href="products/add_product.php" class="action-card">
                        <p class="action-icon">
                            <svg  width="20px" height="20px" viewBox="0 0 1000 1000" fill="currentColor"><path d="M856 40H142q-42 0-72 30t-30 72v714q0 42 30 72t72 30h714q42 0 72-30t30-72V142q0-42-30-72t-72-30zM754 550H550v204H448V550H244V448h204V244h102v204h204v102z"/></svg>
                        </p>
                        <span>Add Product</span>
                    </a>
                    <a href="users/add_user.php" class="action-card">
                        <p class="action-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </p>
                        <span>Add User</span>
                    </a>
                    <a href="orders/view_order.php" class="action-card">
                        <p class="action-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
                        </p>
                        <span>View Orders</span>
                    </a>
                    <a href="reports/sales.php" class="action-card">
                        <p class="action-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                        </p>
                        <span>Sales Report</span>
                    </a>
                </div>
            </section>

            <section class="video-section">
                <div class="video-header">
                    <h2 class="section-title">Analytics Feed</h2>
                    <span class="live-badge">● LIVE</span>
                </div>
                <div class="video-wrapper">
                    <div class="video-scanline"></div>
                    <video autoplay muted loop playsinline>
                        <source src="../assets/video/graph.mp4" type="video/mp4">
                    </video>
                    <div class="video-overlay-corner tl"></div>
                    <div class="video-overlay-corner tr"></div>
                    <div class="video-overlay-corner bl"></div>
                    <div class="video-overlay-corner br"></div>
                </div>
            </section>

        </div>
    </div>

    <script>
        // Live clock
        function updateTime() {
            const el = document.getElementById('liveTime');
            if (el) el.textContent = new Date().toLocaleTimeString('en-US', { hour12: false });
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Mobile sidebar toggle
        const hamburger = document.getElementById('hamburgerBtn');
        const sidebar   = document.getElementById('sidebar');
        const overlay   = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        hamburger.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);

        // Animate stat numbers on load
        document.querySelectorAll('.stat-number').forEach(el => {
            const target = parseInt(el.textContent.replace(/,/g, ''), 10);
            if (isNaN(target)) return;
            let start = 0;
            const duration = 1200;
            const step = (timestamp) => {
                if (!start) start = timestamp;
                const progress = Math.min((timestamp - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(ease * target).toLocaleString();
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        });

        // Staggered card entrance
        document.querySelectorAll('.stat-card').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.12}s`;
        });
    </script>
</body>
</html>
