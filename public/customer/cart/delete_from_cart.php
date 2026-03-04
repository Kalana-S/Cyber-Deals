<?php
require_once '../../../app/bootstrap.php';

Session::start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $database = new Database();
    $db = $database->getConnection();

    $cart = new Cart($db);

    $cart->id = $_POST['cart_id'];
    $cart->user_id = $_SESSION['user_id'];

    if ($cart->deleteProduct()) {
        $_SESSION['cart_message'] = "Product removed successfully!";
    } else {
        $_SESSION['cart_message'] = "Failed to remove product.";
    }
}

header("Location: cart.php");
exit;
