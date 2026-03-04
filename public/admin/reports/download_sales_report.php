<?php
require_once '../../../app/bootstrap.php';

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$stmt = $order->read();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($sales)) {
    exit('No sales found.');
}

$filename = 'sales_report_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

fputcsv($output, ['Product Name', 'Product Price', 'Quantity', 'Total Price']);

$totalSales = 0;

foreach ($sales as $row) {
    fputcsv($output, [
        $row['product_name'],
        number_format($row['product_price'], 2, '.', ''),
        (int) $row['quantity'],
        number_format($row['total_price'], 2, '.', '')
    ]);

    $totalSales += (float) $row['total_price'];
}

fputcsv($output, ['', '', 'Total Sales', number_format($totalSales, 2, '.', '')]);
fclose($output);
exit;
