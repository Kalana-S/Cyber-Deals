<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$message = "";

$categories = [
    'mobile' => ['phone', 'tablet', 'charger', 'earbud', 'headphone', 'cable'],
    'computer' => ['desktop', 'laptop', 'motherboard', 'processor', 'ram', 'storage', 'casing']
];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET['id'])) {
    echo "<div style='padding:40px;font-family:sans-serif'>
            <h3>No product selected</h3>
            <a href='manage_product'>Go back to products</a>
          </div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product->id           = $_POST['id'];
    $product->name         = trim($_POST['name']);
    $product->description  = trim($_POST['description']);
    $product->mainCategory = $_POST['mainCategory'];
    $product->subCategory  = $_POST['subCategory'];
    $product->price        = $_POST['price'];
    $product->quantity     = $_POST['quantity'];
    $product->image        = $_POST['current_image'];

    if (!empty($_FILES['image']['name'])) {
        $uploaded = $product->uploadImage($_FILES['image']);
        if ($uploaded) {
            $product->image = $uploaded;
        } else {
            $message = "<div class='alert alert-danger'>Image upload failed.</div>";
        }
    }

    if ($product->update()) {

        echo "<script>
            alert('Product updated successfully.');
            window.location.href = 'manage_product.php';
        </script>";
        exit;

    } else {
        echo "<script>alert('Unable to add product.');</script>";
    }

    $product_detail = $product->getProductById();

} else {
    $product->id = $_GET['id'];
    $product_detail = $product->getProductById();

    if (!$product_detail) {
        $message = "<div class='alert alert-danger'>Product not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Product | Processing Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/staff/processing/product_update.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <div class="brand-section">
                <a class="brand-name">CYBER<span class="blue">-</span>DEALS</a>
            </div>
            <ul class="nav-links">
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="add_product.php">Add</a></li>
                <li><a href="manage_product.php" class="active">Manage</a></li>
                <li><a href="view_product.php">View</a></li>
            </ul>
            <div class="nav-title">UPDATE <span class="blue">PRODUCTS</span></div>
        </div>
    </nav>

    <div id="formToast" class="form-toast" role="alert" aria-live="assertive"></div>

    <div class="container">
        <div class="main">
            <?php echo $message; ?>
            <div class="product-card">
                <form method="post" enctype="multipart/form-data" novalidate>

                    <input type="hidden" name="id" value="<?= htmlspecialchars($product_detail['id']) ?>">
                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($product_detail['image']) ?>">

                    <span class="form-section-title">General Details</span>

                    <div class="regForm">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($product_detail['name']) ?>" required>
                    </div>

                    <div class="regForm">
                        <label class="form-label">Description</label>
                        <textarea name="description" required><?= htmlspecialchars($product_detail['description']) ?></textarea>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label">Main Category</label>
                                <div class="cs-wrapper" id="mainCatWrapper">
                                    <button type="button" class="cs-trigger" id="mainCatTrigger">
                                        <span class="cs-value" id="mainCatValue">Select Category</span>
                                        <svg class="cs-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </button>
                                    <ul class="cs-dropdown" id="mainCatDropdown">
                                        <?php foreach ($categories as $main => $subs): ?>
                                            <li class="cs-option"
                                                data-value="<?= $main ?>"
                                                <?= $main === $product_detail['mainCategory'] ? 'data-selected="true"' : '' ?>>
                                                <?= ucfirst($main) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <input type="hidden" name="mainCategory" id="mainCategory" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label">Sub Category</label>
                                <div class="cs-wrapper" id="subCatWrapper">
                                    <button type="button" class="cs-trigger" id="subCatTrigger">
                                        <span class="cs-value" id="subCatValue">Select Sub Category</span>
                                        <svg class="cs-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
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
                                    <input type="number" step="100" name="price" id="price"
                                        value="<?= htmlspecialchars($product_detail['price']) ?>"
                                        min="0" required>
                                    <button type="button" class="num-btn num-inc" data-target="price">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="regForm">
                                <label class="form-label">Stock Quantity</label>
                                <div class="num-input-wrapper">
                                    <button type="button" class="num-btn num-dec" data-target="quantity">−</button>
                                    <input type="number" name="quantity" id="quantity"
                                        value="<?= htmlspecialchars($product_detail['quantity']) ?>"
                                        min="0" required>
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
                                <img id="imagePreview"
                                    src="../../<?= htmlspecialchars($product_detail['image']) ?>"
                                    alt="Preview">
                                <div class="preview-placeholder" id="previewPlaceholder">
                                    <span>No Image</span>
                                </div>
                            </div>

                            <div class="image-upload-controls">
                                <p class="upload-hint">Accepted: <strong>JPG, PNG, WEBP</strong> · Max: <strong>5MB</strong></p>
                                <input type="file" name="image" id="imageInput"
                                    accept="image/*" onchange="previewImage(event)">
                                <p class="image-meta" id="imageMeta"></p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">
                        Confirm & Update Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const categories = <?= json_encode($categories) ?>;
        const selectedMain = "<?= $product_detail['mainCategory'] ?>";
        const selectedSub  = "<?= $product_detail['subCategory'] ?>";

        // Custom Select
        class CustomSelect {
            constructor(wrapperId, triggerId, valueId, dropdownId, hiddenId, placeholder) {
                this.wrapper = document.getElementById(wrapperId);
                this.trigger = document.getElementById(triggerId);
                this.valueEl = document.getElementById(valueId);
                this.dropdown = document.getElementById(dropdownId);
                this.hiddenInput = document.getElementById(hiddenId);
                this.placeholder = placeholder;
                this.selected = null;
                this._bind();
            }

            _bind() {
                this.trigger.addEventListener('click', () => this.wrapper.classList.toggle('cs-open'));

                this.dropdown.addEventListener('click', e => {
                    const opt = e.target.closest('.cs-option');
                    if (!opt) return;

                    this.selected = opt.dataset.value;
                    this.hiddenInput.value = this.selected;
                    this.valueEl.textContent = opt.textContent;
                    this.wrapper.classList.remove('cs-open');

                    [...this.dropdown.querySelectorAll('.cs-option')].forEach(o =>
                        o.classList.toggle('cs-selected', o === opt)
                    );

                    this.hiddenInput.dispatchEvent(new Event('change'));
                });

                document.addEventListener('click', e => {
                    if (!this.wrapper.contains(e.target))
                        this.wrapper.classList.remove('cs-open');
                });
            }

            setOptions(items, selectedValue = null) {
                this.dropdown.innerHTML = '';
                this.selected = null;
                this.hiddenInput.value = '';

                this.valueEl.textContent = this.placeholder;

                items.forEach(val => {
                    const li = document.createElement('li');
                    li.className = 'cs-option';
                    li.dataset.value = val;
                    li.textContent = val.charAt(0).toUpperCase() + val.slice(1);

                    if (val === selectedValue) {
                        li.classList.add('cs-selected');
                        this.selected = val;
                        this.hiddenInput.value = val;
                        this.valueEl.textContent = li.textContent;
                    }

                    this.dropdown.appendChild(li);
                });
            }
        }

        const mainSel = new CustomSelect(
            'mainCatWrapper', 'mainCatTrigger', 'mainCatValue',
            'mainCatDropdown', 'mainCategory', 'Select Category'
        );

        const subSel = new CustomSelect(
            'subCatWrapper', 'subCatTrigger', 'subCatValue',
            'subCatDropdown', 'subCategory', 'Select Sub Category'
        );

        // Initialize main category
        document.querySelectorAll('#mainCatDropdown .cs-option').forEach(opt => {
            if (opt.dataset.value === selectedMain) {
                opt.click();
            }
        });

        // Load sub categories
        document.getElementById('mainCategory').addEventListener('change', function() {
            const val = this.value;

            if (categories[val]) {

                subSel.setOptions(categories[val]);

                const firstOption = subSel.dropdown.querySelector('.cs-option');
                if (firstOption) {
                    firstOption.click();
                }
            }
        });

        if (selectedMain) {
            subSel.setOptions(categories[selectedMain], selectedSub);
        }

        document.querySelector("form").addEventListener("submit", function (e) {

            const mainVal = document.getElementById("mainCategory").value;
            const subVal  = document.getElementById("subCategory").value;

            if (!mainVal || !subVal) {
                e.preventDefault();

                if (!mainVal) {
                    document.getElementById("mainCatTrigger").classList.add("cs-invalid");
                }

                if (!subVal) {
                    document.getElementById("subCatTrigger").classList.add("cs-invalid");
                }
            }
        });

        // Number Buttons (Number Input)
        document.querySelectorAll('.num-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const step = parseFloat(input.step) || 1;
                const min = parseFloat(input.min) || 0;
                let val = parseFloat(input.value) || 0;

                if (btn.classList.contains('num-inc')) val += step;
                else val = Math.max(min, val - step);

                input.value = val;
            });
        });

        document.addEventListener("DOMContentLoaded", function () {

            const preview     = document.getElementById("imagePreview");
            const previewBox  = document.getElementById("previewBox");
            const wrapper     = document.querySelector(".image-upload-wrapper");
            const metaEl      = document.getElementById("imageMeta");

            if (preview && preview.getAttribute("src") && preview.getAttribute("src").trim() !== "") {

                preview.style.display = "block";
                previewBox.classList.add("has-image");
                wrapper.classList.add("has-image");

                const src = preview.getAttribute("src");

                if (src && src.trim() !== "") {
                    const parts = src.split('/');
                    const filename = parts[parts.length - 1];
                    metaEl.textContent = filename;
                }
            }

        });

        // Image Preview
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const previewBox = document.getElementById('previewBox');
            const wrapper = document.querySelector('.image-upload-wrapper');
            const metaEl = document.getElementById('imageMeta');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    previewBox.classList.add('has-image');
                    wrapper.classList.add('has-image');
                    metaEl.textContent = `${file.name} · ${sizeMB} MB`;
                };

                reader.readAsDataURL(file);
            }
        }

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
            const description = document.querySelector('textarea[name="description"]');
            const price       = document.getElementById("price");
            const quantity    = document.getElementById("quantity");
            const mainCat     = document.getElementById("mainCategory");
            const subCat      = document.getElementById("subCategory");

            // Clear all previous highlights
            clearHighlights();

            if (!name.value.trim()) {
                e.preventDefault();
                showToast("Product name is required.", "name");
                return;
            }

            if (!description.value.trim()) {
                e.preventDefault();
                showToast("Product description is required.", "description");
                return;
            }

            if (!mainCat.value) {
                e.preventDefault();
                showToast("Please select a main category.", "main");
                return;
            }

            if (!subCat.value) {
                e.preventDefault();
                showToast("Please select a sub category.", "sub");
                return;
            }

            if (!price.value || parseFloat(price.value) < 0) {
                e.preventDefault();
                showToast("Please enter a valid price.", "price");
                return;
            }

            if (!quantity.value || parseInt(quantity.value) < 0) {
                e.preventDefault();
                showToast("Please enter a valid stock quantity.", "quantity");
                return;
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
                    focusField(nameInput);
                    break;

                case "description":
                    const desc = document.querySelector('textarea[name="description"]');
                    desc.classList.add("input-invalid");
                    focusField(desc);
                    break;

                case "main":
                    const mainTrig = document.getElementById("mainCatTrigger");
                    mainTrig.classList.add("cs-invalid");
                    focusField(mainTrig);
                    break;

                case "sub":
                    const subTrig = document.getElementById("subCatTrigger");
                    subTrig.classList.add("cs-invalid");
                    focusField(subTrig);
                    break;

                case "price":
                    const price = document.getElementById("price");
                    price.closest(".num-input-wrapper").classList.add("num-invalid");
                    focusField(price);
                    break;

                case "quantity":
                    const qty = document.getElementById("quantity");
                    qty.closest(".num-input-wrapper").classList.add("num-invalid");
                    focusField(qty);
                    break;
            }

            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove("form-toast--show");
            }, 3500);
        }

        // Remove border when user types or changes value
        document.querySelectorAll('input, textarea').forEach(el => {

            el.addEventListener('input', () => {
                if (el.value.trim() !== '') {
                    el.classList.remove('input-invalid');
                }
            });

            el.addEventListener('change', () => {
                if (el.value.trim() !== '') {
                    el.classList.remove('input-invalid');
                }
            });

            el.addEventListener('input', () => {
                if (el.value.trim() !== '') {
                    el.closest(".num-input-wrapper")?.classList.remove("num-invalid");
                }
            });

            el.addEventListener('change', () => {
                if (el.value.trim() !== '') {
                    el.closest(".num-input-wrapper")?.classList.remove("num-invalid");
                }
            });

        });

        document.getElementById("mainCategory").addEventListener("change", () => {
            document.getElementById("mainCatTrigger").classList.remove("cs-invalid");
        });

        document.getElementById("subCategory").addEventListener("change", () => {
            document.getElementById("subCatTrigger").classList.remove("cs-invalid");
        });

        function clearHighlights() {
            document.querySelectorAll(".input-invalid").forEach(el =>
                el.classList.remove("input-invalid")
            );

            document.querySelectorAll(".cs-invalid").forEach(el =>
                el.classList.remove("cs-invalid")
            );

            document.querySelectorAll(".num-invalid").forEach(el =>
                el.classList.remove("num-invalid")
            );
        }
    </script>
</body>
</html>
