<?php
require_once '../app/bootstrap.php';

Session::start();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);
$name = $email = '';
$errors = [];

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if ($name === '') {
        $errors[] = 'Please enter a name.';
    }

    if ($email === '') {
        $errors[] = 'Please enter an email.';
    }

    if ($password === '') {
        $errors[] = 'Please enter a password.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($confirm === '') {
        $errors[] = 'Please confirm password.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {

        $created = $userModel->createCustomer($name, $email, $password);

    if ($created) {

        $user = $userModel->getUserByEmail($email);

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role']       = 'customer';

        echo "<script>
                alert('Registration Successful');
                window.location.href = 'index.php';
                </script>";
        exit;
    }
    else {
                $errors[] = 'Email already exists.';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Cyber-Deals</title>
    <link href="assets/css/customer/register.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>    
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
</head>
<body class="full-page">

    <nav class="navbar">
        <div class="nav-container">
            <a href="#" class="brand">CYBER<span class="gold">-</span>DEALS</a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="customer/shop/shop.php">Shop</a></li>
                <li><a href="register.php" class="active">Register</a></li>
                <li><a href="#" class="login-link">Login</a></li>
            </ul>
            <div class="user-actions">
                <div class="login-reminder">
                    Log in to shop
                </div>
            </div>
        </div>
    </nav>

    <div class="register-container">
        <div class="register">
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e): ?>
                        <div style="margin-bottom: 5px;"><i class='bx bx-error-circle'></i> <?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <fieldset>
                    <legend>INITIALIZE ACCOUNT</legend>

                    <div class="regForm">
                        <i class='bx bxs-user'></i>
                        <input type="text" name="name" placeholder="Full Name" required value="<?= htmlspecialchars($name) ?>">
                    </div>

                    <div class="regForm">
                        <i class='bx bxl-gmail'></i>
                        <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($email) ?>">
                    </div>

                    <div class="regForm">
                        <i class='bx bxs-lock'></i>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="regForm">
                        <i class='bx bxs-lock'></i>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>

                    <input type="submit" class="btn" value="CREATE IDENTITY">
                </fieldset>
            </form>
            
            <p style="text-align:center; margin-top:20px; color:var(--text-dim); font-size:0.9rem;">
                Already have an account? <a href="#" class="login-link" style="color:var(--accent-gold); text-decoration:none;">Login here</a>
            </p>
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="customerLoginForm" class="login-form" action="login.php" method="post">
                <h2>CUSTOMER LOGIN</h2>

                <input type="hidden" name="login_type" value="customer">
                <input type="hidden" name="redirect_to" value="register.php">

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

    <script>
        setTimeout(function () {
            const alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 4000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const loginLinks = document.querySelectorAll('.login-link');
            const modal = document.getElementById('loginModal');
            const closeBtn = document.querySelector('#loginModal .close');

            loginLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');
                });
            });

            closeBtn.addEventListener('click', function () {
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
</body>
</html>
