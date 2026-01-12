<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// $connect = new PDO("mysql:host=localhost;dbname=society_db", "root", "");

include('connect.php');

$data = json_decode(file_get_contents('php://input'), true);

switch($data['post']){
  case 'get_warehouses':
    $stmt = $connect->query("SELECT id, name FROM warehouses");
    echo json_encode(['status'=>true, 'data'=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
    break;

  case 'get_products_in_wh':
    $stmt = $connect->prepare("SELECT p.id AS product_id, p.name AS product_name, w.unit, w.price, w.max
      FROM pd_in_whs w 
      JOIN products p ON w.prooduct_id = p.id
      WHERE w.warehouses_id=?");
    $stmt->execute([$data['wh']]);
    echo json_encode(['status'=>true,'data'=>$stmt->fetchAll(PDO::FETCH_ASSOC)]);
    break;

  case 'transfer':
    try {
      $connect->beginTransaction();

 
        $from = $received_data->from;
        $to = $received_data->to;
        $items = $received_data->items;
    
        if (!$from || !$to || !is_array($items) || empty($items)) {
            echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
            exit;
        }
    
        // ดึงชื่อคลัง
        $from_name = $connect->prepare("SELECT name FROM warehouses WHERE id=?");
        $from_name->execute([$from]);
        $from_name = $from_name->fetchColumn();
    
        $to_name = $connect->prepare("SELECT name FROM warehouses WHERE id=?");
        $to_name->execute([$to]);
        $to_name = $to_name->fetchColumn();
    
        // สร้างเอกสารใหม่
        $doc_no = 'TRF' . date("YmdHis");
        $transfer_date = date("d/m/Y H:i");
        $html_items = "";
    
        foreach ($items as $item) {
            $product_id = $item->product_id ?? null;
            $qty = (int)($item->qty ?? 0);
    
            if (!$product_id || $qty <= 0) continue;
    
            // 1. ตรวจสอบสินค้าในต้นทาง
            $stmt = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$from, $product_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$row || $row['unit'] < $qty) {
                echo json_encode(['status' => false, 'message' => "สินค้า ID: $product_id ในคลังต้นทางไม่เพียงพอ"]);
                exit;
            }
    
            // 2. หักจากต้นทาง
            $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = unit - ? WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$qty, $from, $product_id]);
    
            // 3. เพิ่มเข้าคลังปลายทาง
            $stmt = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$to, $product_id]);
    
            if ($stmt->rowCount()) {
                $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = unit + ? WHERE warehouses_id=? AND prooduct_id=?");
                $stmt->execute([$qty, $to, $product_id]);
            } else {
                $price = $row['price'];
                $max = 999;
                $created_at = date('Y-m-d H:i:s');
                $stmt = $connect->prepare("INSERT INTO pd_in_whs (warehouses_id, prooduct_id, unit, price, max, created_at) 
                                          VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$to, $product_id, $qty, $price, $max, $created_at]);
            }
    
            // 4. อัปเดต warehouses_id ใน products
            $stmt = $connect->prepare("SELECT warehouses_id FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $wh_ids = json_decode($product['warehouses_id'], true);
            if (!is_array($wh_ids)) $wh_ids = [];
    
            if (!in_array($to, $wh_ids)) {
                $wh_ids[] = (int)$to;
                $stmt = $connect->prepare("UPDATE products SET warehouses_id = ? WHERE id = ?");
                $stmt->execute([json_encode($wh_ids), $product_id]);
            }
    
            // 5. สร้าง HTML สำหรับสินค้าแต่ละรายการ
            $stmt = $connect->prepare("SELECT name FROM products WHERE id=?");
            $stmt->execute([$product_id]);
            $product_name = $stmt->fetchColumn();
            $html_items .= "สินค้า: $product_name | จำนวน: $qty หน่วย<br>";
    
            // 6. บันทึกเอกสารการโอนแต่ละรายการ
            $filename = 'uploads/transfer_docs/transfer_' . $doc_no . '.html';
            $stmt = $connect->prepare("INSERT INTO transfer_documents 
                (document_no, file_name, from_warehouse, to_warehouse, product_id, qty) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$doc_no, $filename, $from, $to, $product_id, $qty]);
        }
    
        // 7. บันทึก HTML ไฟล์รวม
        $html = "
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>ใบโอนสินค้า</title>
                <style>
                    body {
                        font-family: 'TH Sarabun New', sans-serif;
                        font-size: 16pt;
                    }
                </style>
            </head>
            <body>
                <h2>ใบโอนสินค้าออก</h2>
                เลขที่เอกสาร: $doc_no<br>
                วันที่: $transfer_date<br>
                จากคลัง: $from_name → ไปยัง: $to_name<br><br>
                <hr>
                <b>รายการ:</b><br>
                $html_items
            </body>
            </html>
        ";
    
        $filename = 'uploads/transfer_docs/transfer_' . $doc_no . '.html';
        if (!file_exists('uploads/transfer_docs')) mkdir('uploads/transfer_docs', 0777, true);
        file_put_contents($filename, $html);
    
        echo json_encode([
            'status' => true,
            'message' => 'โอนสินค้าสำเร็จ',
            'doc_url' => $filename,
            'doc_no' => $doc_no,
            'from_name' => $from_name,
            'to_name' => $to_name
        ]);
    
    

      $connect->commit();
      echo json_encode(['status'=>true]);

    } catch(Exception $e){
      $connect->rollBack();
      echo json_encode(['status'=>false,'message'=>$e->getMessage()]);
    }
    break;

  default:
    echo json_encode(['status'=>false,'message'=>'Invalid request']);
}

?>