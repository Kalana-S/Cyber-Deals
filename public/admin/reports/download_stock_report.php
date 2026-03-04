<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$mainCategory = $_POST['mainCategory'] ?? '';
$subCategory  = $_POST['subCategory'] ?? '';

$sql = "SELECT name, quantity, price FROM products
        WHERE (:mainCategory = '' OR mainCategory = :mainCategory)
          AND (:subCategory  = '' OR subCategory  = :subCategory)
        ORDER BY name";

$stmt = $db->prepare($sql);
$stmt->execute([
    ':mainCategory' => $mainCategory,
    ':subCategory'  => $subCategory
]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$products) {
    exit('No products found.');
}

if (ob_get_length()) {
    ob_end_clean();
}

$filename = 'stock_report_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Name', 'Quantity', 'Price']);

foreach ($products as $row) {
    fputcsv($output, [
        $row['name'],
        $row['quantity'],
        $row['price']
    ]);
}

fclose($output);
exit;
