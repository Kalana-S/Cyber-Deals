<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$message = "";

$available_roles = ['admin', 'processing team', 'customer'];

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: view_user.php");
    exit;
}

$user_id = $_GET['id'] ?? $_POST['id'];
$user->id = $user_id;

$user_detail = $user->getUserById();

if (!$user_detail) {
    $message = "<div class='alert alert-danger'>User not found.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user->id    = $_POST['id'];
    $user->name  = trim($_POST['name']);
    $user->email = trim($_POST['email']);
    $user->role  = $_POST['role'];

    if (!empty($_POST['password'])) {
        $user->password = $_POST['password'];
    } else {
        $user->password = null;
    }

    $existingUser = $user->getUserByEmail($user->email);

    if ($existingUser && $existingUser['id'] != $user->id) {

        $message = "<script>
                        document.addEventListener('DOMContentLoaded', function(){
                            showToast('Email already exists. Please use a different email.', 'email');
                        });
                    </script>";

    } else {

        if ($user->update()) {

            echo "<script>
                    alert('User updated successfully.');
                    window.location.href = 'manage_user.php';
                  </script>";
            exit;

        } else {
            $message = "<div class='alert alert-danger'>
                            Failed to update user.
                        </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/admin/user_update.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <a class="brand-name">CYBER<span class="blue">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="add_user.php">Add</a></li>
                <li><a href="manage_user.php" class="active">Manage</a></li>
                <li><a href="view_user.php">View</a></li>
            </ul>
            <div class="nav-title">UPDATE <span class="blue">USERS</span></div>
        </div>
    </nav>
    <div id="formToast" class="form-toast" role="alert" aria-live="assertive"></div>

    <div class="container">
        <div class="main">
            <?php echo $message; ?>
            <?php if (!empty($user_detail)): ?>
                <div class="product-card">
                    <form method="post" novalidate>

                        <input type="hidden" name="id" value="<?= htmlspecialchars($user_detail['id']) ?>">

                        <span class="form-section-title">User Details</span>

                        <div class="regForm">
                            <label class="form-label" id="nameLabel">User Name</label>
                            <input type="text" name="name" id="name"
                                value="<?= htmlspecialchars($user_detail['name']) ?>" required>
                        </div>

                        <div class="regForm">
                            <label class="form-label" id="emailLabel">Email Address</label>
                            <input type="email" name="email" id="email"
                                value="<?= htmlspecialchars($user_detail['email']) ?>" required>
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
                                    <?php foreach ($available_roles as $role): ?>
                                        <li class="cs-option <?= $role === $user_detail['role'] ? 'cs-selected' : '' ?>"
                                            data-value="<?= $role ?>">
                                            <?= ucfirst($role) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <input type="hidden" name="role" id="role" value="<?= htmlspecialchars($user_detail['role']) ?>" required>
                            </div>
                        </div>

                        <span class="form-section-title">Security</span>

                        <div class="regForm">
                            <label class="form-label" id="passwordLabel">New Password</label>
                            <input type="password" name="password" id="password" placeholder="Leave blank to keep current password" minlength="6">
                            <small class="text-muted">
                                Password will only change if a new one is entered
                            </small>
                        </div>

                        <button type="submit" class="btn-submit">
                            Confirm & Update User
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const roles = <?= json_encode($available_roles) ?>;
        const selectedRole = "<?= $user_detail['role'] ?>";
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

            const select = new CustomSelect(
                'roleWrapper',
                'roleTrigger',
                'roleValue',
                'roleDropdown',
                'role',
                'Select Role'
            );

            // Initialize preselected role
            if (selectedRole) {
                const hidden = document.getElementById('role');
                const valueEl = document.getElementById('roleValue');
                const trigger = document.getElementById('roleTrigger');

                hidden.value = selectedRole;
                valueEl.textContent = selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1);
                trigger.classList.add('cs-has-value');

                const option = document.querySelector(`.cs-option[data-value="${selectedRole}"]`);
                if (option) option.classList.add('cs-selected');
            }
        });

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

        function focusField(element) {

            element.focus({ preventScroll: true });

            element.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        // Input Valiation Toast Messages
        document.querySelector("form").addEventListener("submit", function (e) {

            const role       = document.getElementById("role");
            const name       = document.getElementById("name");
            const email      = document.getElementById("email");
            const password   = document.getElementById("password");
            const gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

            // Clear ALL previous highlights
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
                return;
            }

            // Password Validation (ONLY if entered)
            if (password.value.trim() !== "") {

                if (password.value.length < 6) {
                    e.preventDefault();
                    showToast("Password must be at least 6 characters.", "password");
                    highlightInput(password, "passwordLabel");
                    focusField(password);
                    return;
                }

            }
        });

        let toastTimer = null;

        function showToast(message, type = null) {
            const toast = document.getElementById("formToast");
            toast.textContent = message;
            toast.classList.add("form-toast--show");

            // Clear previous highlights
            document.getElementById("roleTrigger").classList.remove("cs-invalid");
            document.getElementById("userLabel").classList.remove("cs-invalid");

            // Highlight based on type
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

            // Role
            document.getElementById("roleTrigger").classList.remove("cs-invalid");
            document.getElementById("userLabel").classList.remove("cs-invalid");

            // Name
            document.getElementById("name").classList.remove("input-invalid");
            document.getElementById("nameLabel").classList.remove("cs-invalid");

            // Email
            document.getElementById("email").classList.remove("input-invalid");
            document.getElementById("emailLabel").classList.remove("cs-invalid");

            // Password
            document.getElementById("password").classList.remove("input-invalid");
            document.getElementById("passwordLabel").classList.remove("cs-invalid");
        }

        // Clear the red highlight once user makes a selection
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
