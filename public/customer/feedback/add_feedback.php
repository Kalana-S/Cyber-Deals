<?php
require_once '../../../app/bootstrap.php';

Session::start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    echo json_encode([
        "status" => false,
        "message" => "User not logged in."
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

if (empty($_POST['feedback']) || empty($_POST['rating'])) {
    echo json_encode([
        "status" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

$feedback = new Feedback($db);

$feedback->user_id = $_SESSION['user_id'];
$feedback->user_email = $_SESSION['user_email'];
$feedback->feedback = $_POST['feedback'];
$feedback->rating = $_POST['rating'];

if ($feedback->addFeedback()) {
    echo json_encode([
        "status" => true,
        "message" => "Feedback submitted successfully!"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Failed to submit feedback."
    ]);
}
