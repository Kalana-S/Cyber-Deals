<?php
require_once '../app/bootstrap.php';

Session::start(); 

$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];

$product = new Product($db);
$feedback = new Feedback($db);

$stmtProducts = $db->prepare("SELECT * FROM products ORDER BY id DESC");
$stmtProducts->execute();

$stmtFeedbacks = $db->prepare("SELECT * FROM feedbacks ORDER BY rating DESC LIMIT 6");
$stmtFeedbacks->execute();
$feedbacks = $stmtFeedbacks->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $db = new Database();
    $conn = $db->getConnection();
    $product = new Product($conn);
    $stmt = $product->searchProducts($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<div class="search-results">';
    echo '<button class="close-btn" onclick="closeSearchResults()">✕ Close</button>';
    
    if (count($results) > 0) {
        echo '<div class="search-results-grid">';
        foreach ($results as $row) {
            $stockClass = $row['quantity'] > 10 ? 'in-stock' : ($row['quantity'] > 0 ? 'low-stock' : 'out-of-stock');
            $stockText = $row['quantity'] > 10 ? 'In Stock' : ($row['quantity'] > 0 ? 'Low Stock' : 'Out of Stock');
            
            echo '<div class="product">';
            echo '<div class="product-img">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" />';
            echo '</div>';
            
            echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
            echo '<p class="price">Rs. ' . number_format($row['price'], 2) . '</p>';
            echo '<p class="product-desc">' . htmlspecialchars($row['description']) . '</p>';
            echo '<p class="stock ' . $stockClass . '">' . $stockText . ' (' . $row['quantity'] . ')</p>';
            
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="no-results">';
        echo '<h2>No Results Found</h2>';
        echo '<p>Try searching with different keywords</p>';
        echo '</div>';
    }
    
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Cyber-Deals</title>
    <link href="assets/css/customer/index.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="full-page">
    <div class="customer-index">        
        <nav class="navbar">
            <div class="container nav-container">
                <a href="#" class="brand">CYBER<span class="gold">-</span>DEALS</a>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="customer/shop/shop.php">Shop</a></li>                    
                    <?php if (!isset($_SESSION['user_email'])): ?>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="#" id="login-link">Login</a></li>                        
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_email']) && $_SESSION['role'] == 'customer'): ?>
                        <li><a href="customer/cart/cart.php">Cart</a></li>
                        <li><a href="customer/order/orders.php">Orders</a></li>
                        <li><a href="logout.php" class="logout">Logout</a></li>
                    <?php endif; ?>
                </ul>
                <div class="user-section">
                    <?php if (isset($_SESSION['user_email']) && $_SESSION['role'] == 'customer'): ?>
                        <div class="user-email">
                            <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                        </div>
                    <?php else: ?>
                        <div class="login-reminder">Log in to shop</div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <header class="hero-section">
            <h1 class="hero-title">FUTURE OF <span class="gold">SHOPPING</span></h1>
            <p class="hero-subtitle">Experience the next generation of tech retail.</p>           
            <div class="search-bar">
                <form action="index.php" method="GET">
                    <input type="text" name="query" placeholder="Search for products..." autocomplete="off"/>
                    <button type="submit">Search</button>
                </form>
            </div>
        </header>

        <div class="section-container">
            <h2 class="section-title">POPULAR <span class="gold">CATEGORIES</span></h2>
            <div class="product-container">
                <div class="product-item">
                    <div class="product-label">Mobile Phones</div>
                    <div class="img-wrapper"><img src="assets/images/phone1.png" alt="Mobile Phones"></div>
                    <a href="customer/shop/mobile.php" class="shop-now-btn">EXPLORE</a>
                </div>
                <div class="product-item">
                    <div class="product-label">Laptops</div>
                    <div class="img-wrapper"><img src="assets/images/laptop1.png" alt="Laptops"></div>
                    <a href="customer/shop/laptop.php" class="shop-now-btn">EXPLORE</a>
                </div>
                <div class="product-item">
                    <div class="product-label">Desktops</div>
                    <div class="img-wrapper"><img src="assets/images/desktop1.png" alt="Desktops"></div>
                    <a href="customer/shop/desktop.php" class="shop-now-btn">EXPLORE</a>
                </div>
            </div>
        </div>
        
        <div class="latest-products-section">
            <h2 class="section-title">LATEST <span class="gold">ARRIVALS</span></h2>
            <div class="marquee-wrapper">
                <div class="latest-products-container">
                    <?php while ($row = $stmtProducts->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="latest-product-item">
                            <div class="lp-img">
                                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                            </div>
                            <div class="product-info">
                                <h3><?php echo $row['name']; ?></h3>
                                <p class="price">Rs. <?php echo number_format($row['price'], 2); ?></p>
                                <p class="product-desc"><?php echo $row['description']; ?></p>
                                <?php if (isset($_SESSION['user_email'])): ?>
                                    <form class="add-to-cart-form" action="customer/cart/add_to_cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                        <div class="cart-action">
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
                    <?php endwhile; ?>
                </div>
            </div>
        </div> 

        <div class="feedback-contact-section">
            <h2 class="section-title">GET IN <span class="gold">TOUCH</span></h2>
            <div class="dual-column-section">                
                <div class="add-feedback card-style">
                    <h3 class="card-title">Leave Feedback</h3>
                    <form id="feedback-form" class="feedback-form">
                        <div class="form-group">
                            <textarea name="feedback" id="feedback" class="form-control feedback-textarea" placeholder="Tell us about your experience..." required></textarea>
                        </div>
                        <div class="form-group row-group">
                            <select name="rating" id="rating" class="form-control rating-select" required>
                                <option value="" disabled selected>Rate us</option>
                                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                <option value="4">⭐⭐⭐⭐ Very Good</option>
                                <option value="3">⭐⭐⭐ Good</option>
                                <option value="2">⭐⭐ Fair</option>
                                <option value="1">⭐ Poor</option>
                            </select>
                            <button type="submit" name="submit_feedback" class="btn-feedback">
                                <i class='bx bx-send'></i> Submit
                            </button>
                        </div>
                    </form>
                </div>               
                <div class="contact card-style" id="contact">
                    <h3 class="card-title">Contact Us</h3>
                    <ul class="contact-list">
                        <li>
                            <div class="contact-icon">
                                <i class='bx bx-envelope'></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Email</span>
                                <span class="contact-value">support@cyber-deals.com</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class='bx bx-phone'></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Phone</span>
                                <span class="contact-value">011 000 0000</span>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <i class='bx bx-map'></i>
                            </div>
                            <div class="contact-info">
                                <span class="contact-label">Address</span>
                                <span class="contact-value">123 Tech Avenue, Silicon Valley</span>
                            </div>
                        </li>
                    </ul>
                </div>                
            </div>
        </div>

        <div class="feedback-section">
            <h2 class="section-title">CUSTOMER <span class="gold">REVIEWS</span></h2>
            <div class="feedback-container">
                <button class="feedback-nav prev" onclick="navigateFeedback(-1)">
                    <i class='bx bx-chevron-left'></i>
                </button>
                <div class="feedback-display">
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-item">
                            <div class="quote-icon">
                                <i class='bx bxs-quote-alt-left'></i>
                            </div>
                            <div class="stars">
                                <?php 
                                for($i = 0; $i < 5; $i++) {
                                    if($i < $feedback['rating']) {
                                        echo "<i class='bx bxs-star'></i>";
                                    } else {
                                        echo "<i class='bx bx-star'></i>";
                                    }
                                }
                                ?>
                            </div>
                            <p class="feedback-content"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                            <div class="feedback-footer">
                                <div class="user-avatar">
                                    <?php echo strtoupper(substr($feedback['user_email'], 0, 1)); ?>
                                </div>
                                <p class="feedback-user"><?php echo htmlspecialchars($feedback['user_email']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>               
                <button class="feedback-nav next" onclick="navigateFeedback(1)">
                    <i class='bx bx-chevron-right'></i>
                </button>
            </div>
            <div class="feedback-dots"></div>
        </div>

        <div id="loginModal" class="modal">
            <div class="modal-content">
                <h3 class="form-name">LOGIN FORM</h3>
                <span class="close">&times;</span>
                <div id="loginOptions">
                    <button id="customerLoginBtn" class="selection-button">Customer</button>
                    <button id="staffLoginBtn" class="selection-button">Staff</button>
                </div>
                <form id="customerLoginForm" class="login-form" action="login.php" method="post" style="display:none;">
                    <h2>Customer Login</h2>
                    <input type="hidden" name="login_type" value="customer">
                    <div class="input-group">
                        <i class='bx bxl-gmail'></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="sctbtn">Login</button>
                    <button type="button" class="back-btn" id="customerBackBtn">&larr; Back to Selection</button>
                </form>
                <form id="staffLoginForm" class="login-form" action="login.php" method="post" style="display:none;">
                    <h2>Staff Login</h2>
                    <input type="hidden" name="login_type" value="staff">
                    <div class="input-group">
                        <i class='bx bxs-user'></i>
                        <div class="custom-select" id="customRoleSelect" tabindex="0" role="listbox" aria-label="Select Role">
                            <div class="select-selected" id="selectSelected">Select Role</div>
                        </div>
                        <div class="select-items" id="selectItems">
                            <div data-value="admin" role="option">Admin</div>
                            <div data-value="processing team" role="option">Processing Team</div>
                        </div>
                        <input type="hidden" name="role" id="staffRole">
                    </div>
                    <div class="input-group">
                        <i class='bx bxl-gmail'></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="sctbtn">Login</button>
                    <button type="button" class="back-btn" id="staffBackBtn">&larr; Back to Selection</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 <span style="color:var(--accent-gold)">Cyber-Deals</span>. Premium Tech Hub.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {

            // Add to cart Messages
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
                        alert(response.message);
                    },
                    error: function() {
                        alert("An error occurred while adding the product to the cart.");
                    }
                });
            });

            // Feedback Messages
            $("#feedback-form").on("submit", function(e) {
                e.preventDefault();

                var isLoggedIn = <?php echo isset($_SESSION['user_email']) ? 'true' : 'false'; ?>;
                if (!isLoggedIn) {
                    alert("Please login to submit your feedback.");
                    return;
                }

                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    type: "POST",
                    url: "customer/feedback/add_feedback.php",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        alert(response.message);
                        if (response.status) {
                            form[0].reset();
                        }
                    },
                    error: function() {
                        alert("An error occurred while submitting the feedback.");
                    }
                });
            });
        });


        // Login Form
        const modal         = document.getElementById('loginModal');
        const loginLink     = document.getElementById('login-link');
        const closeBtn      = document.querySelector('.close');
        const loginOptions  = document.getElementById('loginOptions');
        const customerForm  = document.getElementById('customerLoginForm');
        const staffForm     = document.getElementById('staffLoginForm');

        function openModal() {
            modal.style.display = 'block';
            document.body.classList.add('modal-open');
            loginOptions.style.display = 'flex';
            customerForm.style.display = 'none';
            staffForm.style.display    = 'none';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            closeDropdown();
        }

        if (loginLink) loginLink.addEventListener('click', e => { e.preventDefault(); openModal(); });
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        window.addEventListener('click', e => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        document.getElementById('customerLoginBtn').onclick = () => {
            loginOptions.style.display = 'none';
            customerForm.style.display = 'flex';
        };
        document.getElementById('staffLoginBtn').onclick = () => {
            loginOptions.style.display = 'none';
            staffForm.style.display    = 'flex';
        };
        document.getElementById('customerBackBtn').onclick = () => {
            loginOptions.style.display = 'flex';
            customerForm.style.display = 'none';
        };
        document.getElementById('staffBackBtn').onclick = () => {
            loginOptions.style.display = 'flex';
            staffForm.style.display    = 'none';
        };


        // Custom Select
        const customSelect   = document.getElementById('customRoleSelect');
        const selectSelected = document.getElementById('selectSelected');
        const selectItems    = document.getElementById('selectItems');
        const hiddenInput    = document.getElementById('staffRole');

        if (customSelect && selectItems) {
            const options = selectItems.querySelectorAll('[data-value]');

            function openDropdown() { customSelect.classList.add('active'); }
            function closeDropdown() { customSelect.classList.remove('active'); }
            function toggleDropdown() { customSelect.classList.toggle('active'); }

            customSelect.addEventListener('click', e => {
                e.stopPropagation();
                toggleDropdown();
            });

            customSelect.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleDropdown();
                }
                if (e.key === 'Escape') closeDropdown();
            });

            options.forEach(option => {
                option.addEventListener('click', e => {
                    e.stopPropagation();

                    const value = option.getAttribute('data-value');
                    const text  = option.textContent;

                    selectSelected.textContent = text;
                    selectSelected.classList.add('has-value');
                    hiddenInput.value = value;

                    options.forEach(o => o.classList.remove('selected-option'));
                    option.classList.add('selected-option');

                    closeDropdown();
                });
            });

            document.addEventListener('click', () => closeDropdown());
            selectItems.addEventListener('click', e => e.stopPropagation());
        }


        // Latest Products Loop
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.latest-products-container');
            if (container) {
                const items = container.innerHTML;
                container.innerHTML += items;
            }
        });


        // Feedback Slider
        document.addEventListener('DOMContentLoaded', () => {

            const feedbackItems = document.querySelectorAll('.feedback-item');
            const dotsContainer = document.querySelector('.feedback-dots');
            const feedbackDisplay = document.querySelector('.feedback-display');

            if (!feedbackItems.length || !dotsContainer) return;

            let currentFeedbackIndex = 0;
            let autoScrollInterval;
            const itemsPerView = window.innerWidth > 768 ? 3 : 1;

            const totalDots = Math.ceil(feedbackItems.length / itemsPerView);

            for (let i = 0; i < totalDots; i++) {
                const dot = document.createElement('div');
                dot.className = 'feedback-dot';
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            function showFeedbacks() {
                feedbackItems.forEach(item => item.style.display = 'none');

                for (let i = 0; i < itemsPerView; i++) {
                    let index = (currentFeedbackIndex + i) % feedbackItems.length;
                    feedbackItems[index].style.display = 'flex';
                }

                updateDots();
            }

            function updateDots() {
                const dots = document.querySelectorAll('.feedback-dot');
                dots.forEach((dot, index) => {
                    dot.classList.remove('active');
                    if (index === Math.floor(currentFeedbackIndex / itemsPerView)) {
                        dot.classList.add('active');
                    }
                });
            }

            function goToSlide(slideIndex) {
                currentFeedbackIndex = slideIndex * itemsPerView;
                showFeedbacks();
                resetAutoScroll();
            }

            window.navigateFeedback = function(direction) {
                currentFeedbackIndex =
                    (currentFeedbackIndex + (direction * itemsPerView) + feedbackItems.length)
                    % feedbackItems.length;
                showFeedbacks();
                resetAutoScroll();
            };

            function resetAutoScroll() {
                clearInterval(autoScrollInterval);
                autoScrollInterval = setInterval(() => {
                    currentFeedbackIndex =
                        (currentFeedbackIndex + itemsPerView) % feedbackItems.length;
                    showFeedbacks();
                }, 3000);
            }

            showFeedbacks();
            resetAutoScroll();

            if (feedbackDisplay) {
                feedbackDisplay.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
                feedbackDisplay.addEventListener('mouseleave', resetAutoScroll);
            }
        });


        // Search Result
        function closeSearchResults() {
            const searchResults = document.querySelector('.search-results');
            if (searchResults) {
                searchResults.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 300);
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const searchResults = document.querySelector('.search-results');
            if (searchResults) {
                document.body.style.overflow = 'hidden';
            }
        });


        // Quantity Buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.latest-product-item').forEach(card => {
                const minusBtn = card.querySelector('.qty-minus');
                const plusBtn = card.querySelector('.qty-plus');
                const qtyInput = card.querySelector('.quantity-input');

                if (minusBtn && plusBtn && qtyInput) {
                    minusBtn.addEventListener('click', function() {
                        let value = parseInt(qtyInput.value) || 1;
                        if (value > 1) qtyInput.value = value - 1;
                    });

                    plusBtn.addEventListener('click', function() {
                        let value = parseInt(qtyInput.value) || 1;
                        qtyInput.value = value + 1;
                    });

                    qtyInput.addEventListener('input', function() {
                        if (this.value < 1) this.value = 1;
                    });
                }
            });
        });
    </script>
</body>
</html>
