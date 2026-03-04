<?php
require_once '../../../app/bootstrap.php';

Session::start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

$userModel = new User($db);
$userModel->id = $user_id;
$userData = $userModel->getById();

$user_name = $userData['name'];

$cart = new Cart($db);
$cart->user_id = $user_id;
$cartItems = $cart->getCartItems();

$items = $cartItems->fetchAll(PDO::FETCH_ASSOC);

if (count($items) == 0) {
    header("Location: cart.php");
    exit;
}

$order_batch_id = time();

foreach ($items as $row) {

    $order = new Order($db);

    $order->user_id = $user_id;
    $order->product_id = $row['product_id'];
    $order->cart_id = $order_batch_id;
    $order->user_name = $user_name;
    $order->user_email = $user_email;
    $order->product_name = $row['product_name'];
    $order->product_price = $row['product_price'];
    $order->quantity = $row['quantity'];
    $order->total_price = $row['product_price'] * $row['quantity'];

    $order->create();

    $cart->id = $row['id'];
    $cart->deleteProduct();
}

$_SESSION['order_message'] = "Order placed successfully!";
header("Location: ../order/orders.php");
exit;
