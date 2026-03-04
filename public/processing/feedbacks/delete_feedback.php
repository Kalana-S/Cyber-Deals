<?php
require_once '../../../app/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid Feedback ID'
    ]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$feedback = new Feedback($db);
$feedback->id = (int) $id;

if ($feedback->delete()) {
    echo json_encode([
        'success' => true,
        'message' => 'Feedback deleted successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to delete feedback'
    ]);
}
