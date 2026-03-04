<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$message = '';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>
        window.addEventListener('DOMContentLoaded', function() {

            alert('User added successfully.');

            const form = document.querySelector('form');
            if (form) form.reset();

            // Reset custom select (Role)
            const roleValue = document.getElementById('roleValue');
            const roleInput = document.getElementById('role');
            const roleTrigger = document.getElementById('roleTrigger');

            if (roleValue) roleValue.textContent = 'Select Role';
            if (roleInput) roleInput.value = '';
            if (roleTrigger) roleTrigger.classList.remove('cs-has-value');

        });
    </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user->name     = trim($_POST['name'] ?? '');
    $user->email    = trim($_POST['email'] ?? '');
    $user->role     = $_POST['role'] ?? '';
    $user->password = $_POST['password'] ?? '';

    if (strlen($user->password) < 6) {
        $message = "<div class='alert alert-danger'>
                        Password must be at least 6 characters long.
                    </div>";
    }
    elseif ($user->getUserByEmail($user->email)) {

        $message = "<script>
                        document.addEventListener('DOMContentLoaded', function(){
                            showToast('Email already exists. Please use a different email.', 'email');
                        });
                    </script>";
    }
    else {
        if ($user->create()) {
            header("Location: add_user.php?success=1");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>
                            Failed to add user.
                        </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/admin/user_add.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <a class="brand-name">CYBER<span class="blue">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="add_user.php" class="active">Add</a></li>
                <li><a href="manage_user.php">Manage</a></li>
                <li><a href="view_user.php">View</a></li>
            </ul>
            <div class="nav-title">ADD <span class="blue">USERS</span></div>
        </div>
    </nav>
    <div id="formToast" class="form-toast" role="alert" aria-live="assertive"></div>
    <div class="container">
        <div class="main">
            <?php echo $message; ?>
            <div class="product-card">
                <form method="post" novalidate>
                    <span class="form-section-title">General Details</span>
                    <div class="regForm">
                        <label class="form-label" id="nameLabel">User Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter full name" required>
                    </div>
                    <div class="regForm">
                        <label class="form-label" id="emailLabel">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="example@email.com" required>
                    </div>
                    <div class="regForm">
                        <label class="form-label" id="userLabel">User Role</label>

                        <div class="cs-wrapper" id="roleWrapper">
                            <button type="button" class="cs-trigger" id="roleTrigger">
                                <span class="cs-value" id="roleValue">Select Role</span>
                                <svg class="cs-arrow" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>

                            <ul class="cs-dropdown" id="roleDropdown">
                                <li class="cs-option" data-value="admin">Admin</li>
                                <li class="cs-option" data-value="processing team">Processing Team</li>
                                <li class="cs-option" data-value="customer">Customer</li>
                            </ul>

                            <input type="hidden" name="role" id="role" required>
                        </div>
                    </div>
                    <span class="form-section-title">Security</span>

                    <div class="regForm">
                        <label class="form-label" id="passwordLabel">Password</label>
                        <input type="password" name="password" id="password" placeholder="Minimum 6 characters" minlength="6" required>
                    </div>

                    <button type="submit" class="btn-submit">
                        Confirm & Add User
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Custom Select
        class CustomSelect {
            constructor(wrapperId, triggerId, valueId, dropdownId, hiddenId, placeholder) {
                this.wrapper     = document.getElementById(wrapperId);
                this.trigger     = document.getElementById(triggerId);
                this.valueEl     = document.getElementById(valueId);
                this.dropdown    = document.getElementById(dropdownId);
                this.hiddenInput = document.getElementById(hiddenId);
                this.placeholder = placeholder;
                this._bind();
            }

            _bind() {
                this.trigger.addEventListener('click', () => {
                    this.wrapper.classList.toggle('cs-open');
                });

                this.dropdown.addEventListener('click', e => {
                    const opt = e.target.closest('.cs-option');
                    if (!opt) return;

                    const value = opt.dataset.value;
                    const label = opt.textContent.trim();

                    this.hiddenInput.value = value;
                    this.valueEl.textContent = label;
                    this.trigger.classList.add('cs-has-value');
                    this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

                    this.dropdown.querySelectorAll('.cs-option')
                        .forEach(o => o.classList.remove('cs-selected'));
                    opt.classList.add('cs-selected');

                    this.wrapper.classList.remove('cs-open');
                });

                document.addEventListener('click', e => {
                    if (!this.wrapper.contains(e.target)) {
                        this.wrapper.classList.remove('cs-open');
                    }
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            new CustomSelect(
                'roleWrapper',
                'roleTrigger',
                'roleValue',
                'roleDropdown',
                'role',
                'Select Role'
            );
        });
        
        // Alert Dissaphering code
        document.addEventListener("DOMContentLoaded", function () {
            const alertBox = document.querySelector(".alert");

            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.add("hide");

                    setTimeout(() => {
                        alertBox.remove();
                    }, 400);
                }, 3000);
            }
        });

        // Mouse Pointer Auto Focus Function
        function focusField(element) {

            element.focus({ preventScroll: true });

            element.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        // Input Valiation and Toast Messages
        document.querySelector("form").addEventListener("submit", function (e) {

            const role       = document.getElementById("role");
            const name       = document.getElementById("name");
            const email      = document.getElementById("email");
            const password   = document.getElementById("password");
            const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

            clearHighlights();

            // Name Validation
            if (!name.value.trim()) {
                e.preventDefault();
                showToast("Please enter user name.", "name");
                highlightInput(name, "nameLabel");
                focusField(name);
                return;
            }

            // Email Empty Check
            if (!email.value.trim()) {
                e.preventDefault();
                showToast("Please enter user email.", "email");
                highlightInput(email, "emailLabel");
                focusField(email);
                return;
            }

            // Gmail Format Check
            if (!gmailRegex.test(email.value)) {
                e.preventDefault();
                showToast("Email must be a valid address. (ex:-example@gmail.com)", "email");
                highlightInput(email, "emailLabel");
                focusField(email);
                return;
            }

            // Role Validation
            if (!role.value) {
                e.preventDefault();
                showToast("Please select a user role.", "role");
                highlightRole();
                document.getElementById("roleTrigger").focus();
                focusField(role);
                return;
            }

            // Password Empty
            if (!password.value) {
                e.preventDefault();
                showToast("Please enter user password.", "password");
                highlightInput(password, "passwordLabel");
                focusField(password);
                return;
            }

            // Password Length Check
            if (password.value.length < 6) {
                e.preventDefault();
                showToast("Password must be at least 6 characters.", "password");
                highlightInput(password, "passwordLabel");
                focusField(password);
                return;
            }

        });

        // Border and Label Hignlights, and Mouse Pointer Auto Foucus to Non Validated Inputs
        let toastTimer = null;

        function showToast(message, type = null) {
            const toast = document.getElementById("formToast");
            toast.textContent = message;
            toast.classList.add("form-toast--show");

            document.getElementById("roleTrigger").classList.remove("cs-invalid");
            document.getElementById("userLabel").classList.remove("cs-invalid");

            if (type === "role") {
                document.getElementById("roleTrigger").classList.add("cs-invalid");
                document.getElementById("userLabel").classList.add("cs-invalid");
            }

            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove("form-toast--show");
            }, 3500);
        }

        function highlightInput(inputEl, labelId) {
            inputEl.classList.add("input-invalid");
            document.getElementById(labelId).classList.add("cs-invalid");
        }

        function highlightRole() {
            document.getElementById("roleTrigger").classList.add("cs-invalid");
            document.getElementById("userLabel").classList.add("cs-invalid");
        }

        function clearHighlights() {

            document.getElementById("roleTrigger").classList.remove("cs-invalid");
            document.getElementById("userLabel").classList.remove("cs-invalid");

            document.getElementById("name").classList.remove("input-invalid");
            document.getElementById("nameLabel").classList.remove("cs-invalid");

            document.getElementById("email").classList.remove("input-invalid");
            document.getElementById("emailLabel").classList.remove("cs-invalid");

            document.getElementById("password").classList.remove("input-invalid");
            document.getElementById("passwordLabel").classList.remove("cs-invalid");
        }

        document.getElementById("role").addEventListener("change", () => {
            document.getElementById("roleTrigger").classList.remove("cs-invalid");
            document.getElementById("userLabel").classList.remove("cs-invalid");
        });

        ["name", "email", "password"].forEach(id => {
            document.getElementById(id).addEventListener("input", function () {
                this.classList.remove("input-invalid");
                document.getElementById(id + "Label").classList.remove("cs-invalid");
            });
        });
    </script>
</body>
</html>
