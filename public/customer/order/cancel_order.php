<?php
require_once '../../../app/bootstrap.php';

Session::start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $database = new Database();
    $db = $database->getConnection();

    $batch_id = $_POST['batch_id'];
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM orders WHERE cart_id = :batch_id AND user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':batch_id', $batch_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $_SESSION['order_message'] = "Order cancelled successfully!";
}

header("Location: orders.php");
exit;
