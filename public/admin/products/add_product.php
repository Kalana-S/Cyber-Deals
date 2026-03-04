<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$message = "";

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>
        window.addEventListener('DOMContentLoaded', function() {
            alert('Product added successfully.');

            const form = document.querySelector('form');
            if (form) form.reset();

            // Reset custom selects
            const mainVal = document.getElementById('mainCatValue');
            const subVal  = document.getElementById('subCatValue');
            const mainInput = document.getElementById('mainCategory');
            const subInput  = document.getElementById('subCategory');
            const subTrigger = document.getElementById('subCatTrigger');

            if (mainVal) mainVal.textContent = 'Select Category';
            if (subVal)  subVal.textContent  = 'Select Sub Category';
            if (mainInput) mainInput.value = '';
            if (subInput)  subInput.value  = '';
            if (subTrigger) subTrigger.classList.add('cs-disabled');

            // Clear preview
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('previewPlaceholder');
            const meta = document.getElementById('imageMeta');

            if (preview) preview.src = '';
            if (placeholder) placeholder.style.display = 'flex';
            if (meta) meta.textContent = '';

        });
    </script>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product->name         = trim($_POST['name']);
    $product->description  = trim($_POST['description']);
    $product->price        = $_POST['price'];
    $product->mainCategory = $_POST['mainCategory'];
    $product->subCategory  = $_POST['subCategory'];
    $product->quantity     = $_POST['quantity'];
    $errors = $product->validateProduct();
    
    if (!empty($errors)) {
        $message = "<div class='alert alert-danger'>" . implode("<br>", $errors) . "</div>";
    } else {
        $target_file = $product->uploadImage($_FILES["image"]);
        if ($target_file) {
            $product->image = str_replace($_SERVER['DOCUMENT_ROOT'], '', $target_file);
            if ($product->create()) {

                header("Location: add_product.php?success=1");
                exit();

            } else {
                echo "<script>alert('Unable to add product.');</script>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Image upload failed. Please upload a valid image.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/admin/product_add.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <a class="brand-name">CYBER<span class="blue">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="add_product.php" class="active">Add</a></li>
                <li><a href="manage_product.php">Manage</a></li>
                <li><a href="view_product.php">View</a></li>
            </ul>
            <div class="nav-title">ADD <span class="blue">PRODUCTS</span></div>
        </div>
    </nav>

    <div id="formToast" class="form-toast" role="alert" aria-live="assertive"></div>
    
    <div class="container">
        <div class="main">
            <?php echo $message; ?>
            <div class="product-card">
                <form method="post" enctype="multipart/form-data" novalidate>
                    <span class="form-section-title">General Details</span>
                    <div class="regForm">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" placeholder="Enter product name" required>
                    </div>
                    <div class="regForm">
                        <label class="form-label">Description</label>
                        <textarea name="description" placeholder="Provide a detailed description..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label" id="mainLabel">Main Category</label>
                                <div class="cs-wrapper" id="mainCatWrapper">
                                    <button type="button" class="cs-trigger" id="mainCatTrigger">
                                        <span class="cs-value" id="mainCatValue">Select Category</span>
                                        <svg class="cs-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <ul class="cs-dropdown" id="mainCatDropdown">
                                        <li class="cs-option" data-value="mobile">Mobile</li>
                                        <li class="cs-option" data-value="computer">Computer</li>
                                    </ul>
                                    <input type="hidden" name="mainCategory" id="mainCategory" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label" id="subLabel">Sub Category</label>
                                <div class="cs-wrapper" id="subCatWrapper">
                                    <button type="button" class="cs-trigger cs-disabled" id="subCatTrigger">
                                        <span class="cs-value" id="subCatValue">Select Sub Category</span>
                                        <svg class="cs-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <ul class="cs-dropdown" id="subCatDropdown"></ul>
                                    <input type="hidden" name="subCategory" id="subCategory" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label">Price (RS.)</label>
                                <div class="num-input-wrapper">
                                    <button type="button" class="num-btn num-dec" data-target="price">−</button>
                                    <input type="number" step="100" name="price" id="price" placeholder="0.00" min="0" required>
                                    <button type="button" class="num-btn num-inc" data-target="price">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label">Stock Quantity</label>
                                <div class="num-input-wrapper">
                                    <button type="button" class="num-btn num-dec" data-target="quantity">−</button>
                                    <input type="number" name="quantity" id="quantity" placeholder="0" min="0" required>
                                    <button type="button" class="num-btn num-inc" data-target="quantity">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="form-section-title">Image Preview</span>
                    <div class="regForm">
                        <label class="form-label">Product Image</label>
                        <div class="image-upload-wrapper" tabindex="0">
                            <div class="image-preview-box" id="previewBox">
                                <img id="imagePreview" src="" alt="Preview">
                                <div class="preview-placeholder" id="previewPlaceholder">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                    <span>No Image</span>
                                </div>
                            </div>

                            <div class="image-upload-controls">
                                <p class="upload-hint">Accepted: <strong>JPG, PNG, WEBP</strong> &nbsp;·&nbsp; Max: <strong>5MB</strong></p>
                                <input type="file" name="image" id="imageInput" accept="image/*" required onchange="previewImage(event)">
                                <p class="image-meta" id="imageMeta"></p>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Confirm & Add Product</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const categories = {
            mobile:   ['phone', 'tablet', 'charger', 'earbud', 'headphone', 'cable'],
            computer: ['desktop', 'laptop', 'motherboard', 'processor', 'ram', 'storage', 'casing']
        };

        // Custom select
        class CustomSelect {
            constructor(wrapperId, triggerId, valueId, dropdownId, hiddenId, placeholder) {
                this.wrapper     = document.getElementById(wrapperId);
                this.trigger     = document.getElementById(triggerId);
                this.valueEl     = document.getElementById(valueId);
                this.dropdown    = document.getElementById(dropdownId);
                this.hiddenInput = document.getElementById(hiddenId);
                this.placeholder = placeholder;
                this.selected    = null;
                this._bind();
            }

            _bind() {
                this.trigger.addEventListener('click', () => this.toggle());

                this.dropdown.addEventListener('click', e => {
                    const opt = e.target.closest('.cs-option');
                    if (opt) this._pick(opt);
                });

                this.trigger.addEventListener('keydown', e => {
                    const opts = [...this.dropdown.querySelectorAll('.cs-option')];
                    const cur  = opts.findIndex(o => o.classList.contains('cs-focused'));
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        this.open();
                        this._focus(opts, Math.min(cur + 1, opts.length - 1));
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        this._focus(opts, Math.max(cur - 1, 0));
                    } else if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const focused = this.dropdown.querySelector('.cs-focused');
                        if (focused) this._pick(focused); else this.toggle();
                    } else if (e.key === 'Escape') {
                        this.close();
                    }
                });

                document.addEventListener('click', e => {
                    if (!this.wrapper.contains(e.target)) this.close();
                });
            }

            _focus(opts, idx) {
                opts.forEach(o => o.classList.remove('cs-focused'));
                if (opts[idx]) {
                    opts[idx].classList.add('cs-focused');
                    opts[idx].scrollIntoView({ block: 'nearest' });
                }
            }

            _pick(optEl) {
                const value = optEl.dataset.value;
                const label = optEl.textContent.trim();
                this.selected        = value;
                this.hiddenInput.value = value;
                this.valueEl.textContent = label;
                this.trigger.classList.add('cs-has-value');
                [...this.dropdown.querySelectorAll('.cs-option')].forEach(o =>
                    o.classList.toggle('cs-selected', o === optEl)
                );
                this.close();
                this.trigger.focus();
                this.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            open() {
                if (this.trigger.classList.contains('cs-disabled')) return;
                this.wrapper.classList.add('cs-open');
            }

            close() {
                this.wrapper.classList.remove('cs-open');
                this.dropdown.querySelectorAll('.cs-focused').forEach(o => o.classList.remove('cs-focused'));
            }

            toggle() { this.wrapper.classList.contains('cs-open') ? this.close() : this.open(); }

            setOptions(items) {
                this.dropdown.innerHTML = '';
                items.forEach(val => {
                    const li = document.createElement('li');
                    li.className = 'cs-option';
                    li.dataset.value = val;
                    li.textContent   = val.charAt(0).toUpperCase() + val.slice(1);
                    this.dropdown.appendChild(li);
                });
                this.selected        = null;
                this.hiddenInput.value = '';
                this.valueEl.textContent = this.placeholder;
                this.trigger.classList.remove('cs-has-value');
            }

            enable()  { this.trigger.classList.remove('cs-disabled'); }
            disable() { this.trigger.classList.add('cs-disabled'); this.close(); }
        }

        const mainSel = new CustomSelect(
            'mainCatWrapper', 'mainCatTrigger', 'mainCatValue', 'mainCatDropdown', 'mainCategory',
            'Select Category'
        );
        const subSel = new CustomSelect(
            'subCatWrapper', 'subCatTrigger', 'subCatValue', 'subCatDropdown', 'subCategory',
            'Select Sub Category'
        );

        document.getElementById('mainCategory').addEventListener('change', function () {
            const val = mainSel.selected;
            if (val && categories[val]) {
                subSel.setOptions(categories[val]);
                subSel.enable();
            } else {
                subSel.disable();
            }
        });

        // Image Preview Code
        function previewImage(event) {
            const input   = event.target;
            const preview = document.getElementById('imagePreview');
            const box     = document.getElementById('previewBox');
            const wrapper = box.closest('.image-upload-wrapper');
            const metaEl  = document.getElementById('imageMeta');

            if (input.files && input.files[0]) {
                const file   = input.files[0];
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src           = e.target.result;
                    preview.style.display = 'block';
                    box.classList.add('has-image');
                    wrapper.classList.add('has-image');
                    metaEl.textContent = `${file.name}  ·  ${sizeMB} MB`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src           = '';
                preview.style.display = 'none';
                box.classList.remove('has-image');
                wrapper.classList.remove('has-image');
                metaEl.textContent = '';
            }
        }

        // Number Buttons (Number Input)
        document.querySelectorAll('.num-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const step  = parseFloat(input.step) || 1;
                const min   = parseFloat(input.min)  ?? -Infinity;
                let   val   = parseFloat(input.value) || 0;

                if (btn.classList.contains('num-inc')) {
                    val += step;
                } else {
                    val = Math.max(min, val - step);
                }

                input.value = val;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
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

        // Input Valiation Toast Messages
        document.querySelector("form").addEventListener("submit", function (e) {

            const name        = document.querySelector('input[name="name"]');
            const desc        = document.querySelector('textarea[name="description"]');
            const price       = document.getElementById("price");
            const quantity    = document.getElementById("quantity");
            const imageInput  = document.getElementById("imageInput");

            const main = document.getElementById("mainCategory").value;
            const sub  = document.getElementById("subCategory").value;

            // Name
            if (!name.value.trim()) {
                e.preventDefault();
                showToast("Product name is required.", "name");
                return;
            }

            // Description
            if (!desc.value.trim()) {
                e.preventDefault();
                showToast("Product description is required.", "description");
                return;
            }

            // Categories
            if (!main && !sub) {
                e.preventDefault();
                showToast("Please select a Main Category and Sub Category.", "category");
                return;
            }
            if (!main) {
                e.preventDefault();
                showToast("Please select a Main Category.", "main");
                return;
            }
            if (!sub) {
                e.preventDefault();
                showToast("Please select a Sub Category.", "sub");
                return;
            }

            // Price
            if (!price.value || parseFloat(price.value) < 0) {
                e.preventDefault();
                showToast("Please enter a valid price.", "price");
                return;
            }

            // Stock
            if (!quantity.value || parseInt(quantity.value) < 0) {
                e.preventDefault();
                showToast("Please enter a valid stock quantity.", "quantity");
                return;
            }

            // Image
            if (!imageInput.files.length) {
                e.preventDefault();
                showToast("Please upload a product image.", "image");
                return;
            }
        });

        // Mouse Pointer Auto Focus Function
        function focusField(element) {

            if (!element) return;

            element.focus({ preventScroll: true });

            element.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        // Border and Label Hignlights, and Mouse Pointer Auto Foucus to Non Validated Inputs
        let toastTimer = null;

        function showToast(message, type = null) {

            const toast = document.getElementById("formToast");
            toast.textContent = message;
            toast.classList.add("form-toast--show");

            switch(type) {

                case "name":
                    const nameInput = document.querySelector('input[name="name"]');
                    nameInput.classList.add("input-invalid");
                    nameInput.previousElementSibling.classList.add("cs-invalid");
                    focusField(nameInput);
                    break;

                case "description":
                    const desc = document.querySelector('textarea[name="description"]');
                    desc.classList.add("input-invalid");
                    desc.previousElementSibling.classList.add("cs-invalid");
                    focusField(desc);
                    break;

                case "main":
                    document.getElementById("mainLabel").classList.add("cs-invalid");
                    document.getElementById("mainCatWrapper").classList.add("cs-invalid");
                    focusField(document.getElementById("mainCatTrigger"));
                    break;

                case "sub":
                    document.getElementById("subLabel").classList.add("cs-invalid");
                    document.getElementById("subCatWrapper").classList.add("cs-invalid");
                    focusField(document.getElementById("subCatTrigger"));
                    break;

                case "price":
                    const price = document.getElementById("price");
                    price.closest(".num-input-wrapper").classList.add("num-invalid");
                    price.closest(".regForm").querySelector(".form-label").classList.add("cs-invalid");
                    focusField(price);
                    break;

                case "quantity":
                    const qty = document.getElementById("quantity");
                    qty.closest(".num-input-wrapper").classList.add("num-invalid");
                    qty.closest(".regForm").querySelector(".form-label").classList.add("cs-invalid");
                    focusField(qty);
                    break;

                case "image":
                    const imageWrapper = document.querySelector('.image-upload-wrapper');
                    imageWrapper.classList.add("num-invalid");
                    imageWrapper.closest(".regForm").querySelector(".form-label").classList.add("cs-invalid");
                    focusField(document.getElementById("imageInput"));
                    break;

                case "category":
                    document.getElementById("mainLabel").classList.add("cs-invalid");
                    document.getElementById("mainCatWrapper").classList.add("cs-invalid");
                    document.getElementById("subLabel").classList.add("cs-invalid");
                    document.getElementById("subCatWrapper").classList.add("cs-invalid");
                    focusField(document.getElementById("mainCatTrigger"));
                    break;
            }

            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove("form-toast--show");
            }, 3500);
        }

        // Remove border when user types or changes value
        document.getElementById("mainCategory").addEventListener("change", () => {
            document.getElementById("mainLabel").classList.remove("cs-invalid");
            document.getElementById("mainCatWrapper").classList.remove("cs-invalid");
        });

        document.getElementById("subCategory").addEventListener("change", () => {
            document.getElementById("subLabel").classList.remove("cs-invalid");
            document.getElementById("subCatWrapper").classList.remove("cs-invalid");
        });

        document.querySelectorAll('input[type="text"], textarea').forEach(el => {
            el.addEventListener("input", () => {
                if (el.value.trim() !== "") {
                    el.classList.remove("input-invalid");
                    el.previousElementSibling.classList.remove("cs-invalid");
                }
            });
        });

        document.querySelectorAll('input[type="number"]').forEach(el => {
            el.addEventListener("input", () => {
                if (el.value.trim() !== "") {
                    el.closest(".num-input-wrapper")?.classList.remove("num-invalid");
                    el.closest(".regForm")?.querySelector(".form-label")?.classList.remove("cs-invalid");
                }
            });
        });

        document.getElementById("imageInput").addEventListener("change", function () {
            if (this.files.length) {
                document.querySelector('.image-upload-wrapper').classList.remove("num-invalid");
                document.querySelector('.image-upload-wrapper')
                    .closest(".regForm")
                    .querySelector(".form-label")
                    .classList.remove("cs-invalid");
            }
        });
    </script>
</body>
</html>
