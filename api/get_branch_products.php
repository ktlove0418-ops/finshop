<?php
header('Content-Type: application/json');
require_once 'db.php';

$branch_id = $_GET['branch_id'] ?? null;
if (!$branch_id) {
    echo json_encode(["status" => "error", "message" => "branch_id required"]);
    exit;
}

$sql = "SELECT p.id AS product_id, p.name, p.price, 
               IFNULL(sb.closing_qty, 0) AS opening_qty
        FROM products p
        LEFT JOIN daily_stock_balance sb
          ON p.id = sb.product_id
         AND sb.branch_id = :branch_id
         AND sb.summary_date = (SELECT MAX(summary_date) 
                                FROM daily_stock_balance 
                                WHERE branch_id = :branch_id)
        WHERE p.branch_id = :branch_id";

$stmt = $pdo->prepare($sql);
$stmt->execute([":branch_id" => $branch_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $products]);
