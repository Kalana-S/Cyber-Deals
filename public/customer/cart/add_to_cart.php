<?php
require_once '../../../app/bootstrap.php';

Session::start();

header('Content-Type: application/json');

$response = [
    "status" => false,
    "message" => "Unable to add product to cart"
];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    $response['message'] = "Please login as customer.";
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $database = new Database();
    $db = $database->getConnection();

    $cart = new Cart($db);

    $cart->product_id = $_POST['product_id'];
    $cart->product_name = $_POST['product_name'];
    $cart->product_price = $_POST['product_price'];
    $cart->quantity = $_POST['quantity'];
    $cart->user_id = $_SESSION['user_id'];

    if ($cart->addProduct()) {
        $response['status'] = true;
        $response['message'] = "Product added to cart successfully!";
    }
}

echo json_encode($response);
