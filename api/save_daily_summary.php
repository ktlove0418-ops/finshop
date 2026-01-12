<?php
header('Content-Type: application/json');
require_once 'db.php'; // include PDO connection

// รับข้อมูลจาก frontend (POST JSON)
$input = json_decode(file_get_contents("php://input"), true);

$branch_id       = $input['branch_id'] ?? null;
$summary_date    = $input['summary_date'] ?? date("Y-m-d");
$total_sales     = $input['total_sales'] ?? 0;
$total_discount  = $input['total_discount'] ?? 0;
$cash_recorded   = $input['cash_recorded'] ?? 0;
$cash_counted    = $input['cash_counted'] ?? 0;
$transfer_amount = $input['transfer_amount'] ?? 0;
$other_payment   = $input['other_payment'] ?? 0;
$stocks          = $input['stocks'] ?? []; // array ของ stock balance

if (!$branch_id) {
    echo json_encode(["status" => "error", "message" => "branch_id is required"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // บันทึก summary (ถ้ามีอยู่แล้วให้อัพเดท)
    $stmt = $pdo->prepare("
        INSERT INTO daily_summary 
            (branch_id, summary_date, total_sales, total_discount, 
             cash_recorded, cash_counted, transfer_amount, other_payment)
        VALUES 
            (:branch_id, :summary_date, :total_sales, :total_discount,
             :cash_recorded, :cash_counted, :transfer_amount, :other_payment)
        ON DUPLICATE KEY UPDATE
            total_sales     = VALUES(total_sales),
            total_discount  = VALUES(total_discount),
            cash_recorded   = VALUES(cash_recorded),
            cash_counted    = VALUES(cash_counted),
            transfer_amount = VALUES(transfer_amount),
            other_payment   = VALUES(other_payment),
            updated_at      = CURRENT_TIMESTAMP
    ");
    $stmt->execute([
        ':branch_id'       => $branch_id,
        ':summary_date'    => $summary_date,
        ':total_sales'     => $total_sales,
        ':total_discount'  => $total_discount,
        ':cash_recorded'   => $cash_recorded,
        ':cash_counted'    => $cash_counted,
        ':transfer_amount' => $transfer_amount,
        ':other_payment'   => $other_payment
    ]);

    // ลบ stock balance เก่า (ถ้ามี)
    $pdo->prepare("
        DELETE FROM daily_stock_balance 
        WHERE branch_id = :branch_id AND summary_date = :summary_date
    ")->execute([
        ':branch_id'    => $branch_id,
        ':summary_date' => $summary_date
    ]);

    // บันทึก stock balance ใหม่
    $stmtStock = $pdo->prepare("
        INSERT INTO daily_stock_balance
            (branch_id, product_id, summary_date, opening_qty, sold_qty, received_qty, closing_qty)
        VALUES
            (:branch_id, :product_id, :summary_date, :opening_qty, :sold_qty, :received_qty, :closing_qty)
    ");

    foreach ($stocks as $s) {
        $stmtStock->execute([
            ':branch_id'    => $branch_id,
            ':product_id'   => $s['product_id'],
            ':summary_date' => $summary_date,
            ':opening_qty'  => $s['opening_qty'] ?? 0,
            ':sold_qty'     => $s['sold_qty'] ?? 0,
            ':received_qty' => $s['received_qty'] ?? 0,
            ':closing_qty'  => $s['closing_qty'] ?? 0,
        ]);
    }

    $pdo->commit();

    echo json_encode(["status" => "success", "message" => "Daily summary saved successfully"]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
