<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
// $connect = new PDO("mysql:host=localhost;dbname=society_db", "root", "");

include('connect.php');

$received_data = json_decode(file_get_contents("php://input"));
$data = array();
@session_start();

$version = 0.1;
$post = $received_data->post;
$get = $received_data->get;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($received_data->CORS !== '') {
    header("Access-Control-Allow-Origin: *");

    // อนุญาตเฉพาะวิธีที่กำหนด เช่น GET, POST
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    // อนุญาตเฉพาะ Headers ที่กำหนด
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // ตรวจสอบและตอบกลับคำขอ OPTIONS (Preflight Request)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200); // ตอบกลับด้วยสถานะ 200 OK
        exit();
    }

    // โค้ด PHP อื่น ๆ สำหรับ API
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // ตัวอย่างข้อมูลที่ส่งกลับ
        echo json_encode([
            'status' => 'success',
            'message' => 'CORS is working!',
        ]);
    }
}
function generateRandomName($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // ตัวอักษรที่ใช้สุ่ม
    $randomName = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomName .= $characters[rand(0, $maxIndex)];
    }

    return $randomName;
}
if ($post == 'register') {
    $query = "SELECT * FROM members WHERE phone = :phone ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute([':phone' => $received_data->phone]);
    $dataLogin = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$dataLogin) {
        $data_register = array(
            ':fname'       => $received_data->name,
            ':lname'       => "",
            ':phone'       => $received_data->phone,
            ':password'    => $received_data->password,
            ':credit'      => 0,
            ':can_follow'  => 0,
            ':isActive'    => 1,
            ':uid'         => generateRandomName(),
            ':isStatus'    => 'public',
            ':create_at'   => date('Y-m-d H:i:s'),
            ':update_at'   => date('Y-m-d H:i:s')
        );

        $sql_register = "INSERT INTO members(fname, lname, phone, password, credit, uid, isStatus, can_follow, isActive, create_at, update_at) 
                         VALUES (:fname, :lname, :phone, :password, :credit, :uid, :isStatus, :can_follow, :isActive, :create_at, :update_at)";
        $statement = $connect->prepare($sql_register);
        $statement->execute($data_register);

        // ดึงข้อมูลสมาชิกใหม่
        $statement = $connect->prepare($query);
        $statement->execute([':phone' => $received_data->phone]);
        $dataLogin = $statement->fetch(PDO::FETCH_ASSOC);

        @session_start();
        $_SESSION['u_id'] = $dataLogin['uid'];

        echo json_encode([
            'msg' => 'Register Success',
            'data' => $dataLogin,
            'status' => true,
            'start_date' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode([
            'msg' => 'This phone number is already in use.',
            'code' => 409,
            'status' => false,
            'start_date' => date('Y-m-d H:i:s')
        ]);
    }
}
if ($post == 'login') {
    $query = "SELECT * FROM admin WHERE username = :username ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->bindParam(':username', $received_data->username);
    $statement->execute();
    $dataLogin = $statement->fetch(PDO::FETCH_ASSOC);
    $data = array();

    if ($dataLogin) {
        if ($dataLogin['isstatus'] == 0) {
            $data = array(
                'msg' => 'บัญชีของคุณถูกระงับการใช้งาน โปรดติดต่อแอดมิน',
                'code' => 403,
                'status' => false,
                'start_date' => date('Y-m-d H:i:s')
            );
        } else {
            $hash_b = $dataLogin['password'];
            $pass_w = password_verify($received_data->password, $hash_b);

            if ($pass_w) {
                $redirect = '';
                if ($dataLogin['position'] === 'owner') $redirect = 'owner';
                else if ($dataLogin['position'] === 'store') $redirect = 'store';
                else if ($dataLogin['position'] === 'sale') $redirect = 'sale';
                else if ($dataLogin['position'] === 'truck') $redirect = 'truck';
                @session_start();
                $_SESSION['fin_position'] = $dataLogin['position'];
                $_SESSION['fin_username'] = $dataLogin['username'];
                $data = array(
                    'data' => $dataLogin,
                    'access_token' => 'Basic ' . bin2hex(random_bytes(16)),
                    'status' => true,
                    'msg' => 'เข้าระบบสำเร็จ',
                    'redirect' => $redirect,
                    'start_date' => date('Y-m-d H:i:s')
                );
            } else {
                $data = array(
                    'msg' => 'Password ไม่ถูกต้อง',
                    'code' => 204,
                    'status' => false,
                    'start_date' => date('Y-m-d H:i:s')
                );
            }
        }
    } else {
        $data = array(
            'msg' => 'ชื่อเข้าใช้งานไม่ถูกต้อง',
            'code' => 203,
            'status' => false,
            'start_date' => date('Y-m-d H:i:s')
        );
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
if ($post == 'get_type') {
    $query = "SELECT * FROM categories ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute();
    $datatype = $statement->fetchAll();
    $dataSuccessType = array(
        'data' => $datatype,
        'status' => false,
        'start_date' => date('Y-m-d H:i:s')
    );
    echo json_encode($dataSuccessType);
}


if ($post == 'save_type') {
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    // เตรียมข้อมูลสำหรับบันทึก
    $data_post = array(
        ':cate_name'     => $received_data->cateName,
        ':noted'    => $received_data->noted,
        ':person'   => $person,
        ':isActive' => 1,
        ':created_at'    => date('Y-m-d H:i:s')
    );
    // คำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql_post = "INSERT INTO categories (cate_name, noted, person, isActive, created_at) VALUES (:cate_name,:noted,:person, :isActive, :created_at)";

    $statement = $connect->prepare($sql_post);
    $result = $statement->execute($data_post);

    // ตรวจสอบว่าการเพิ่มข้อมูลสำเร็จหรือไม่
    if ($result) {
        $last_id = $connect->lastInsertId(); // ดึง ID ล่าสุดที่ถูกเพิ่ม
        $response = array(
            'status'    => true,
            'message'   => 'เพิ่มข้อมูลสำเร็จ',
            'last_id'   => $last_id,
            'date'      => date('Y-m-d H:i:s')
        );
    } else {
        $response = array(
            'status'  => false,
            'message' => 'Failed to insert data'
        );
    }
    echo json_encode($response);
}

// print_r($post);
if ($post == 'get_warehouses_fproduct') {
    echo 'get_warehouses_fproduct';
    $query = "SELECT * FROM warehouses WHERE isActive < 44 ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute();
    $datatype = $statement->fetchAll();
    $dataSuccessType = array(
        'data' => $datatype,
        'status' => false,
        'start_date' => date('Y-m-d H:i:s')
    );
    echo json_encode($dataSuccessType);
}

if ($post == 'get_warehouses') {
    $query = "
        SELECT 
            w.*,
            IFNULL(SUM(CASE WHEN p.unit < 50 AND p.unit > 0 THEN 1 ELSE 0 END), 0) AS low_stock,
            IFNULL(SUM(CASE WHEN p.unit = 0 THEN 1 ELSE 0 END), 0) AS out_of_stock,
            IFNULL(COUNT(p.id), 0) AS total_products
        FROM warehouses w
        LEFT JOIN pd_in_whs p ON w.id = p.warehouses_id 
        GROUP BY w.id
        ORDER BY w.id DESC
    ";

    $statement = $connect->prepare($query);
    $statement->execute();
    $warehouses = $statement->fetchAll(PDO::FETCH_ASSOC);

    $data = array(
        'data' => $warehouses,
        'status' => true,
        'start_date' => date('Y-m-d H:i:s')
    );

    echo json_encode($data);
}

if ($post == 'searchTransfer') {
    $keyword = '%' . $received_data->keyword . '%';

    $query = "
        SELECT 
            td.id AS transfer_id,
            td.document_no,
            td.file_name,
            td.qty,
            td.created_at ,
            p.name AS product_name,
            w_from.name AS from_name,
            w_to.name AS to_name,
            w_from.person AS transferred_by
        FROM transfer_documents td
        JOIN products p ON p.id = td.product_id
        JOIN warehouses w_from ON w_from.id = td.from_warehouse
        JOIN warehouses w_to ON w_to.id = td.to_warehouse
        WHERE p.name LIKE ?
        ORDER BY td.created_at DESC
    ";

    $statement = $connect->prepare($query);
    $statement->execute([$keyword]);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'data' => $results,
        'start_date' => date('Y-m-d H:i:s')
    ]);
}
if ($post == 'searchTransfer_id') {
    $keyword = '%' . $received_data->keyword . '%';
    $wareHouseId = $received_data->wareHouseId;
    $query = "
        SELECT 
            td.id AS transfer_id,
            td.document_no,
            td.file_name,
            td.qty,
            td.created_at ,
            p.name AS product_name,
            w_from.name AS from_name,
            w_to.name AS to_name,
            w_from.person AS transferred_by
        FROM transfer_documents td
        JOIN products p ON p.id = td.product_id
        JOIN warehouses w_from ON w_from.id = td.from_warehouse
        JOIN warehouses w_to ON w_to.id = td.to_warehouse
        WHERE p.name LIKE ? AND td.from_warehouse = ?
         AND DATE(td.created_at) >= ?
        ORDER BY td.created_at DESC
    ";
    $start_date = date('Y-m-d 00:00:00');
    $statement = $connect->prepare($query);
    $statement->execute([$keyword,$wareHouseId,$start_date]);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'data' => $results,
        'start_date' => date('Y-m-d H:i:s')
    ]);
}


if ($post == 'save_delivered') {
    $transfer_id = $received_data->transfer_id;
    $delivered_by = $received_data->delivered_by;
    $status = $received_data->status;
    $note = $received_data->note ?? null;
    
    $stmt = $connect->prepare("
        INSERT INTO delivery_records (transfer_id, delivered_by, status, note)
        VALUES (:transfer_id, :delivered_by, :status, :note)
    ");
    
    $stmt->bindParam(':transfer_id', $transfer_id, PDO::PARAM_INT);
    $stmt->bindParam(':delivered_by', $delivered_by, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':note', $note, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'บันทึกการจัดส่งเรียบร้อย']);
    } else {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }
}


if ($post == 'searchWarehouses') {
    $keyword = '%' . $received_data->keyword . '%';
    $query = "SELECT * FROM warehouses WHERE name LIKE ? ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute([$keyword]);
    $warehouses = $statement->fetchAll(PDO::FETCH_ASSOC);

    $response = array(
        'status' => true,
        'data' => $warehouses,
        'start_date' => date('Y-m-d H:i:s')
    );

    echo json_encode($response);
}

if ($post == 'deleteWarehouse') {
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    $data_del = array(
        ':id' => $received_data->id,
        ':isActive' => 44,
        ':person'   => $person,
        ':created_at'    => date('Y-m-d H:i:s')
    );
    $sql = "UPDATE warehouses SET isActive=:isActive, person=:person , created_at=:created_at WHERE id=:id";
    // $sql = "DELETE FROM warehouses WHERE id=:id";

    $statement = $connect->prepare($sql);
    $statement->execute($data_del);

    $data = array(
        'status' => true,
        'message' => 'Data deleted successfully'
    );
    echo json_encode($data);
}
if ($post == 'editwarehouse') {
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    $data_post = array(
        ':id'       => $received_data->id, // เพิ่ม id ใน data_post
        ':name'     => $received_data->name,
        ':location' => $received_data->location,
        ':person'   => $person
    );

    // คำสั่ง SQL แก้ไขข้อมูล
    $sql_post = "UPDATE warehouses SET name=:name, location=:location, person=:person WHERE id=:id";

    $statement = $connect->prepare($sql_post);
    $result = $statement->execute($data_post);
    $response = array(
        'status'  => true,
        'message' => 'Success to Edit data'
    );
    echo json_encode($response);
}
if ($post == 'warehouse') {
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    // เตรียมข้อมูลสำหรับบันทึก
    $data_post = array(
        ':name'     => $received_data->name,
        ':location'    => $received_data->location,
        ':person'   => $person,
        ':isActive' => 1,
        ':created_at'    => date('Y-m-d H:i:s')
    );
    // คำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql_post = "INSERT INTO warehouses (name, location, person, isActive, created_at) VALUES (:name,:location,:person, :isActive, :created_at)";

    $statement = $connect->prepare($sql_post);
    $result = $statement->execute($data_post);

    // ตรวจสอบว่าการเพิ่มข้อมูลสำเร็จหรือไม่
    if ($result) {
        $last_id = $connect->lastInsertId(); // ดึง ID ล่าสุดที่ถูกเพิ่ม
        $response = array(
            'status'    => true,
            'message'   => 'เพิ่มข้อมูลสำเร็จ',
            'last_id'   => $last_id,
            'date'      => date('Y-m-d H:i:s')
        );
    } else {
        $response = array(
            'status'  => false,
            'message' => 'Failed to insert data'
        );
    }
    echo json_encode($response);
}


if ($_POST['post'] == 'save_product') {

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $warehouses_id = $_POST['warehouses_id'];


    // 🔧 แปลงให้เป็น array
    if (is_string($warehouses_id)) {
        if (str_starts_with($warehouses_id, '[')) {
            // JSON array string
            $warehouses = json_decode($warehouses_id, true);
        } else {
            // Comma-separated string
            $warehouses = explode(',', $warehouses_id);
        }
    } elseif (is_array($warehouses_id)) {
        $warehouses = $warehouses_id;
    } else {
        $warehouses = []; // fallback
    }

    $uploadDir = '../uploads/'; // โฟลเดอร์สำหรับเก็บไฟล์ (relative path)
    $fullUploadDir = __DIR__ . '/' . $uploadDir; // path เต็มสำหรับ move_uploaded_file

    if (!file_exists($fullUploadDir)) {
        mkdir($fullUploadDir, 0777, true);
        chmod($fullUploadDir, 0777);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image']['name']);
        $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        $targetPath = $fullUploadDir . $safeName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // บันทึกชื่อไฟล์แบบ relative path ลงฐานข้อมูล
            $filenameToSave = $uploadDir . $safeName;
        } else {
            echo json_encode(['status' => false, 'message' => 'ไม่สามารถย้ายไฟล์ได้']);
            exit;
        }
    } else {
        $filenameToSave = '';
    }
    @session_start();
    $person = '<b>' . $_POST['position'] . '</b> ' . $_POST['username'];

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $connect->prepare("INSERT INTO products (category_id,warehouses_id, name, price, quantity, description, image, person) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $warehouses_id, $name, $price, $quantity, $description, $filenameToSave, $person]);

    // 1. ดึง product_id ล่าสุด
    $stmt = $connect->prepare("SELECT id FROM products ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $latestProduct = $stmt->fetch(PDO::FETCH_ASSOC);
    $product_id = $latestProduct['id'];

    // 2. เตรียม insert
    $sql = "INSERT INTO pd_in_whs (warehouses_id, prooduct_id, unit, price, max, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())";
    $insertStmt = $connect->prepare($sql);

    // 3. วนลูป insert
    foreach ($warehouses as $whId) {
        $unit = 1;
        $price = $quantity;
        $max = 999;
        $insertStmt->execute([$whId, $product_id, $unit, $price, $max]);
    }

    echo json_encode(['status' => true]);
}

if ($_POST['post'] == 'save_edit_product') {

    $product_id = $_POST['product_id']; // เพิ่ม id สินค้าที่จะแก้ไข
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $warehouses_id = $_POST['warehouses_id'];

    $uploadDir = '../uploads/';
    $fullUploadDir = __DIR__ . '/' . $uploadDir;

    if (!file_exists(filename: $fullUploadDir)) {
        mkdir($fullUploadDir, 0777, true);
        chmod($fullUploadDir, 0777);
    }

    $filenameToSave = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filename = basename($_FILES['image']['name']);
        $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        $targetPath = $fullUploadDir . $safeName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $filenameToSave = $uploadDir . $safeName;
        } else {
            echo json_encode(['status' => false, 'message' => 'ไม่สามารถย้ายไฟล์ได้']);
            exit;
        }
    }


    $person = '<b>' . $_POST['position'] . '</b> ' . $_POST['username'];

    if ($filenameToSave !== null) {
        //  "หากมีอัปโหลดภาพใหม่";
        $stmt = $connect->prepare("UPDATE products SET category_id=?,warehouses_id=?, name=?, price=?, quantity=?, description=?, image=?, person=? WHERE id=?");
        $stmt->execute([$category_id, $warehouses_id, $name, $price, $quantity, $description, $filenameToSave, $person, $product_id]);
    } else {
        // "ไม่อัปโหลดภาพใหม่";
        $stmt = $connect->prepare("UPDATE products SET category_id=?,warehouses_id=?, name=?, price=?, quantity=?, description=?, person=? WHERE id=?");
        $stmt->execute([$category_id, $warehouses_id, $name, $price, $quantity, $description, $person, $product_id]);
    }

    // แปลง warehouses_id ที่ส่งมา เช่น "1,4,3,2" เป็น array
    $warehouses = explode(',', $_POST['warehouses_id']);

    // ลูปเช็คในแต่ละคลังสินค้า
    $sqlCheck = "SELECT id FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?";
    $checkStmt = $connect->prepare($sqlCheck);

    // เตรียม insert
    $sqlInsert = "INSERT INTO pd_in_whs (warehouses_id, prooduct_id, unit, price, max, created_at) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $insertStmt = $connect->prepare($sqlInsert);

    foreach ($warehouses as $whId) {
        $whId = trim($whId);
        if (!$whId) continue;

        $checkStmt->execute([$whId, $product_id]);
        if ($checkStmt->rowCount() == 0) {
            // ยังไม่มีในคลังนั้น → เพิ่มใหม่
            $price = $quantity;  // หรือกำหนดเป็น 0 แล้วแต่ลอจิก
            $unit = 0;  // หรือกำหนดเป็น 0 แล้วแต่ลอจิก
            $max = 999;
            $insertStmt->execute([$whId, $product_id, $unit, $price, $max]);
        }
    }
    echo json_encode(['status' => true]);
}


if ($received_data->post == 'save_products_in_wh') {
    @session_start();

    // เตรียมข้อมูลสำหรับอัปเดต
    $data_ = array(
        ':id'           => $received_data->warehouses_id, // เพิ่ม id 
        ':price'     => $received_data->price,
        ':unit'     => $received_data->unit,
        ':max'     => $received_data->max,
        ':created_at'    => date('Y-m-d H:i:s')
    );

    // คำสั่ง SQL แก้ไขข้อมูล
    $sql_ = "UPDATE pd_in_whs SET price=:price,unit=:unit,max=:max, created_at=:created_at WHERE id=:id";
    $statement = $connect->prepare($sql_);
    $result = $statement->execute($data_);

    echo json_encode([
        'status' => true
    ]);
}
if ($received_data->post == 'get_products_in_wh') {
    $warehouses_id = $received_data->warehouses_id;

    // ค้นหาสินค้าจาก products
    $sql = "SELECT 
                p.id,
                p.name AS product_name,
                p.person,
                p.quantity,
                p.description,
                p.image,
                p.created_at,
                p.updated_at,
                p.status,
                p.warehouses_id,
                c.id AS category_id,
                c.cate_name AS category_name,
                b.image_path AS image_barcodes
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN barcodes b ON p.id = b.product_id
            WHERE 
                REPLACE(REPLACE(REPLACE(p.warehouses_id, ' ', ''), '\"', ''), '[', '') 
                LIKE ?
            ORDER BY p.created_at DESC";

    $search = '%' . $warehouses_id . '%';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 🔎 สรุปจำนวนสินค้าจากตาราง pd_in_whs
    $sql_summary = "
        SELECT 
            COUNT(*) AS total_products,
            SUM(CASE WHEN unit < 50 AND unit > 0 THEN 1 ELSE 0 END) AS low_stock,
            SUM(CASE WHEN unit = 0 THEN 1 ELSE 0 END) AS out_of_stock
        FROM pd_in_whs
        WHERE warehouses_id = :warehouses_id AND prooduct_id = :pid
    ";
    $stmt_summary = $connect->prepare($sql_summary);
    $stmt_summary->execute([':warehouses_id' => $warehouses_id,  ':pid'  => $products['id']]);
    $summary = $stmt_summary->fetch(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($products as $row) {
        // 📦 ดึงข้อมูลแต่ละสินค้าจาก pd_in_whs
        $sql_pdwh = "SELECT * FROM pd_in_whs 
                     WHERE warehouses_id = :whid AND prooduct_id = :pid ";
        $stmt_pdwh = $connect->prepare($sql_pdwh);
        $stmt_pdwh->execute([
            ':whid' => $warehouses_id,
            ':pid'  => $row['id']
        ]);
        $pd_data = $stmt_pdwh->fetch(PDO::FETCH_ASSOC);
        $image_barcodes = '';
        if ($row['image_barcodes'] != '') {
            $image_barcodes = $row['image_barcodes'];
        }
        $data[] = [
            'id'             => $row['id'],
            'category_id'    => $row['category_id'],
            'category_name'  => $row['category_name'],
            'created_at'     => $row['created_at'],
            'description'    => $row['description'],
            'image'          => $row['image'],
            'person'         => $row['person'],
            'product_name'   => $row['product_name'],
            'quantity'       => $row['quantity'],
            'status'         => $row['status'],
            'warehouses_id'  => $row['warehouses_id'],
            'image_barcodes'  => $image_barcodes,

            'max'            => $pd_data['max'] ?? 999,
            'price'          => $pd_data['price'] ?? 0,
            'unit'           => $pd_data['unit'] ?? 0,
            'pw_id'          => $pd_data['id'] ?? null
        ];
    }

    echo json_encode([
        'products' => $data,
        'summary' => [
            'total_products' => (int)$summary['total_products'],
            'low_stock'      => (int)$summary['low_stock'],
            'out_of_stock'   => (int)$summary['out_of_stock'],
        ],
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ]);
}
if ($post == 'get_products_in_wh_sof') {
    $warehouses_id = $received_data->warehouses_id;
    $start_date = date('Y-m-d 00:00:00');
    $end_date = date('Y-m-d 23:59:59');

    // ดึงข้อมูลสินค้าในคลัง
    $sql = "SELECT 
                p.id,
                p.name AS product_name,
                p.person,
                p.quantity,
                p.description,
                p.image,
                p.created_at,
                p.updated_at,
                p.status,
                p.warehouses_id,
                c.id AS category_id,
                c.cate_name AS category_name,
                b.image_path AS image_barcodes
            FROM products p
            JOIN categories c ON p.category_id = c.id
            JOIN barcodes b ON p.id = b.product_id
            WHERE 
                REPLACE(REPLACE(REPLACE(p.warehouses_id, ' ', ''), '\"', ''), '[', '') LIKE ?";

    $search = '%' . $warehouses_id . '%';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    $total_products = 0;
    $low_stock = 0;
    $out_of_stock = 0;

    foreach ($products as $row) {
        // ข้อมูลคลังสินค้า
        $sql_pdwh = "SELECT * FROM pd_in_whs 
                     WHERE warehouses_id = ? AND prooduct_id = ?";
        $stmt_pdwh = $connect->prepare($sql_pdwh);
        $stmt_pdwh->execute([$warehouses_id, $row['id']]);
        $pd_data = $stmt_pdwh->fetch(PDO::FETCH_ASSOC);

        // สรุปจำนวนสินค้าในคลัง
        if ($pd_data) {
            $total_products++;
            if ($pd_data['unit'] == 0) {
                $out_of_stock++;
            } elseif ($pd_data['unit'] < 50) {
                $low_stock++;
            }
        }

        // ยอดขายของวันนี้
        $sql_s = "SELECT SUM(total) AS total_sale ,SUM(qty) AS sale_qty                  FROM saleproducts 
                  WHERE warehouse_id = ? AND product_id = ? AND created_at BETWEEN ? AND ?";
        $stmt_s = $connect->prepare($sql_s);
        $stmt_s->execute([$warehouses_id, $row['id'], $start_date, $end_date]);
        $s_data = $stmt_s->fetch(PDO::FETCH_ASSOC);
        $sale_total = (int)($s_data['total_sale'] ?? 0);
        $sale_qty = (int)($s_data['sale_qty'] ?? 0);
        $last_qty = (int)($s_data['last_qty'] ?? 0);

        $data[] = [
            'id'             => $row['id'],
            'category_id'    => $row['category_id'],
            'category_name'  => $row['category_name'],
            'created_at'     => $row['created_at'],
            'description'    => $row['description'],
            'image'          => $row['image'],
            'person'         => $row['person'],
            'product_name'   => $row['product_name'],
            'total_sale'     => $sale_total,
            'sale_qty'       => $sale_qty,
            'status'         => $row['status'],
            'warehouses_id'  => $row['warehouses_id'],
            'image_barcodes' => $row['image_barcodes'] ?? '',

            'max'            => $pd_data['max'] ?? 999,
            'price'          => $pd_data['price'] ?? 0,
            'unit'           => $pd_data['unit'] ?? 0,
            'pw_id'          => $pd_data['id'] ?? null
        ];
    }

    echo json_encode([
        'products' => $data,
        'summary' => [
            'total_products' => $total_products,
            'low_stock'      => $low_stock,
            'out_of_stock'   => $out_of_stock,
        ],
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ]);
}


if ($received_data->post == 'get_finish_ofday_id') {
    $warehouses_id = $received_data->warehouses_id;
    $start_date = date('Y-m-d 00:00:00');
    $end_date = date('Y-m-d 23:59:59');

    $sql = "SELECT 
                p.id AS product_id,
                p.name AS product_name,
                p.price AS unit_price,
                IFNULL(SUM(sp.qty), 0) AS total_qty,
                IFNULL(SUM(sp.total), 0) AS total_sale,
                IFNULL(SUM(sp.qty * p.price), 0) AS total_cost,
                IFNULL(SUM(sp.total - (sp.qty * p.price)), 0) AS profit,
                DATE(?) AS sale_date
            FROM products p
            LEFT JOIN saleproducts sp 
                ON sp.product_id = p.id 
               AND sp.warehouse_id = ? 
               AND sp.created_at BETWEEN ? AND ? 
            WHERE p.warehouse_id = ? 
            GROUP BY p.id, p.name, p.price
            ORDER BY p.id ASC";

    $stmt_summary = $connect->prepare($sql);
    $stmt_summary->execute([$start_date, $warehouses_id, $start_date, $end_date, $warehouses_id]);
    $sale = $stmt_summary->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'products' => $sale,
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ]);
}



if ($received_data->post == 'get_finish_ofday') {
    $warehouses_id = $received_data->warehouses_id;
    $start_date = date('Y-m-d 00:00:00');
    $end_date = date('Y-m-d 23:59:59');

    $sql_ofday = "SELECT * FROM sale_finish_ofday WHERE warehouses_id = ? AND created_at BETWEEN ? AND ?";
    $stmt_summary = $connect->prepare($sql_ofday);
    $stmt_summary->execute([$warehouses_id, $start_date, $end_date]);
    $summary = $stmt_summary->fetch(PDO::FETCH_ASSOC);

    if ($summary) {
        $stmt_ = $connect->prepare("SELECT *, update_qty AS _qty FROM product_item_sod WHERE sfo_id = :sid");
        $stmt_->execute([
            ':sid'  => $summary['id']
        ]);
        $result = $stmt_->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $data[] = [
                'id'             => $row['id'],
                'last_qty'          => $row['last_qty'],
                'price'         => $row['price'],
                'product_id'     => $row['product_id'],
                'qty'           => $row['qty'],
                'sfo_id'          => $row['sfo_id'],
                '_qty'          => $row['_qty']
            ];
       }
    } else {
        $result = null;
    }

    echo json_encode([
        'products' => $data,
        'summary' => $summary,
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ]);
}


if ($received_data->post == 'search_products_in_wh') {
    $warehouses_id = $received_data->warehouses_id;
    $search_key = $received_data->keyword;

    // 🔍 ค้นหาสินค้า
    $sql = "SELECT 
                p.id,
                p.name AS product_name,
                p.person,
                p.quantity,
                p.description,
                p.image,
                p.created_at,
                p.updated_at,
                p.status,
                p.warehouses_id,
                c.id AS category_id,
                c.cate_name AS category_name,
                b.image_path AS image_barcodes
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN barcodes b ON p.id = b.product_id
            WHERE 
                REPLACE(REPLACE(REPLACE(p.warehouses_id, ' ', ''), '\"', ''), '[', '') 
                LIKE ? AND p.name LIKE ?
            ORDER BY p.created_at DESC";

    $search = '%' . $warehouses_id . '%';
    $keyword = '%' . $search_key . '%';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$search, $keyword]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];

    foreach ($products as $row) {
        // 🔄 ดึงข้อมูลคลังของสินค้านี้
        $stmt_pdwh = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id = :whid AND prooduct_id = :pid");
        $stmt_pdwh->execute([
            ':whid' => $warehouses_id,
            ':pid'  => $row['id']
        ]);
        $pd_data = $stmt_pdwh->fetch(PDO::FETCH_ASSOC);

        $data[] = [
            'id'             => $row['id'],
            'category_id'    => $row['category_id'],
            'category_name'  => $row['category_name'],
            'created_at'     => $row['created_at'],
            'description'    => $row['description'],
            'image'          => $row['image'],
            'person'         => $row['person'],
            'product_name'   => $row['product_name'],
            'quantity'       => $row['quantity'],
            'status'         => $row['status'],
            'warehouses_id'  => $row['warehouses_id'],
            'image_barcodes' => $row['image_barcodes'] ?? '',

            'max'            => $pd_data['max'] ?? 999,
            'price'          => $pd_data['price'] ?? 0,
            'unit'           => $pd_data['unit'] ?? 0,
            'pw_id'          => $pd_data['id'] ?? null
        ];
    }

    // ✅ สรุปข้อมูลสินค้าคงเหลือในคลัง (รวมทุกสินค้าในคลังนั้น)
    $sql_summary = "
        SELECT 
            COUNT(*) AS total_products,
            SUM(CASE WHEN unit < 50 AND unit > 0 THEN 1 ELSE 0 END) AS low_stock,
            SUM(CASE WHEN unit = 0 THEN 1 ELSE 0 END) AS out_of_stock
        FROM pd_in_whs
        WHERE warehouses_id = :warehouses_id
    ";
    $stmt_summary = $connect->prepare($sql_summary);
    $stmt_summary->execute([':warehouses_id' => $warehouses_id]);
    $summary = $stmt_summary->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'products' => $data,
        'summary' => [
            'total_products' => (int)($summary['total_products'] ?? 0),
            'low_stock'      => (int)($summary['low_stock'] ?? 0),
            'out_of_stock'   => (int)($summary['out_of_stock'] ?? 0),
        ],
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ]);
}


if ($received_data->post == 'get_products') {
    $sql = "SELECT 
                p.id,
                p.name AS product_name,
                p.price,
                p.person,
                p.quantity,
                p.description,
                p.image,
                p.created_at,
                p.updated_at,
                p.status,
                p.warehouses_id,
                c.id AS category_id,
                c.cate_name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'products' => $products
    ]);
}



if ($received_data->post == 'search_products') {
    $keyword = '%' . $received_data->keyword . '%';

    $stmt = $connect->prepare("SELECT 
                p.id,
                p.name AS product_name,
                p.price,
                p.person,
                p.quantity,
                p.description,
                p.image,
                p.created_at,
                p.updated_at,
                p.status,
                c.cate_name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE name LIKE ?
            ORDER BY p.created_at DESC");
    $stmt->execute([$keyword]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['products' => $results]);
    exit;
}

if ($received_data->post == 'categories') {
    $query = "SELECT * FROM categories ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute();
    $datatype = $statement->fetchAll();
    $dataSuccessType = array(
        'data' => $datatype,
        'status' => true,
        'start_date' => date('Y-m-d H:i:s')
    );
    echo json_encode($dataSuccessType);
}

if ($received_data->post == 'del_product_id') {
    $data_del = array(
        'id' => $received_data->id,
    );

    $sql = "DELETE FROM products WHERE id=:id";

    $statement = $connect->prepare($sql);
    $statement->execute($data_del);

    $data = array(
        'status' => true,
        'message' => 'Data deleted successfully'
    );
    echo json_encode($data);
}

if ($received_data->post == 'get_all_products') {
    $date = $received_data->date;
    $warehouse_id = $received_data->wareHouseId;
    if($warehouse_id){
        $stmt = $connect->prepare("
            SELECT d.*, 
                   p.name AS product_name, 
                   fw.name AS from_name, 
                   tw.name AS to_name
            FROM transfer_documents d
            JOIN products p ON d.product_id = p.id
            JOIN warehouses fw ON d.from_warehouse = fw.id
            JOIN warehouses tw ON d.to_warehouse = tw.id
            WHERE DATE(d.created_at) = :search_date
            AND d.to_warehouse = :warehouse_id
    
            ORDER BY d.to_warehouse ASC
        ");
        $stmt->bindParam(':search_date', $date);
        $stmt->bindParam(':warehouse_id', $warehouse_id);
        $stmt->execute();
    }else{
        $stmt = $connect->prepare("
            SELECT 
                d.to_warehouse,
                d.qty,
                tw.name AS to_name,
                d.product_id,
                d.document_no,
                p.name AS product_name
            FROM transfer_documents d
            JOIN products p ON d.product_id = p.id
            JOIN warehouses tw ON d.to_warehouse = tw.id
             WHERE DATE(d.created_at) = :search_date
            ORDER BY d.to_warehouse ASC
        ");
        $stmt->bindParam(':search_date', $date);
        $stmt->execute();
}
    $rawData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // จัดรูปแบบใหม่ให้เป็น array ตามคลัง
    $groupedData = [];

    foreach ($rawData as $row) {
        $warehouseId = $row['to_warehouse'];
        if (!isset($groupedData[$warehouseId])) {
            $groupedData[$warehouseId] = [
                'warehouse_id' => $warehouseId,
                'warehouse_name' => $row['to_name'],
                'total_qty' => 0,
                'document_no' => $row['document_no'],
                'products' => []
            ];
        }

        $groupedData[$warehouseId]['products'][] = [
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'qty' => $row['qty']
        ];

        // รวมยอดทุก product ของคลังนี้
        $groupedData[$warehouseId]['total_qty'] += $row['qty'];
    }

    // reset key index เพื่อให้ JSON เป็น array ไม่ใช่ object
    $groupedData = array_values($groupedData);
    echo json_encode(['status' => true, 'productsList' => $groupedData]);
}


if ($received_data->post == 'transfer_product') {
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
$index = 1;

foreach ($items as $item) {
    $product_id = $item->product_id ?? null;
    $qty = (int)($item->qty ?? 0);

    if (!$product_id || $qty <= 0) continue;

    // ตรวจสอบและหักจากคลังต้นทาง
    $stmt = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id=? AND prooduct_id=?");
    $stmt->execute([$from, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || $row['unit'] < $qty) {
        echo json_encode(['status' => false, 'message' => "สินค้า ID: $product_id ในคลังต้นทางไม่เพียงพอ"]);
        exit;
    }

    $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = unit - ? WHERE warehouses_id=? AND prooduct_id=?");
    $stmt->execute([$qty, $from, $product_id]);

    // เพิ่มเข้าคลังปลายทาง
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

    // อัปเดต warehouses_id ใน products
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

    // ดึงชื่อสินค้า
    $stmt = $connect->prepare("SELECT name FROM products WHERE id=?");
    $stmt->execute([$product_id]);
    $product_name = $stmt->fetchColumn();

    // เพิ่มรายการสินค้าใน HTML
    $html_items .= "
        <tr>
            <td>{$index}</td>
            <td>" . htmlspecialchars($product_name) . "</td>
            <td style='text-align:center'>{$qty}</td>
            <td>
                <div style='display: flex; gap: 10px;justify-content: center;'>
                    <div style='
                        width: 20px;
                        height: 20px;
                        border-radius: 50%;
                        border: 2px solid green;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;'> </div>
                    <div style='
                        width: 20px;
                        height: 20px;
                        border-radius: 50%;
                        border: 2px solid red;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;'> </div>
                    <div style='
                        width: 20px;
                        height: 20px;
                        border-radius: 50%;
                        border: 2px solid blue;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 14px;'> </div>
                </div>
            </td>
        </tr>
    ";

    // บันทึกเอกสารการโอน (ถ้าต้องการเก็บทีละรายการ)
    $filename = 'uploads/transfer_docs/transfer_' . $doc_no . '.html';
    $stmt = $connect->prepare("INSERT INTO transfer_documents 
        (document_no, file_name, from_warehouse, to_warehouse, product_id, qty) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$doc_no, $filename, $from, $to, $product_id, $qty]);

    $index++;
}

// สร้าง HTML รวม
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
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                border: 1px solid black;
                padding: 6px;
                text-align: left;
            }
            th {
                background-color: #f0f0f0;
            }
        </style>
    </head>
    <body>
        <h2>ใบโอนสินค้าออก</h2>
        เลขที่เอกสาร: $doc_no<br>
        วันที่: $transfer_date<br>
        จากคลัง: $from_name → ไปยัง: $to_name<br><br>
        <hr>
        <b>รายการสินค้า</b><br>

        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th style='width: 50px;'>ชื่อสินค้า</th>
                    <th style='width: 80px;text-align:center'>จำนวน</th>
                    <th style='
    text-align: center;
    width: 200px;
    font-size: small;
'>สถานะ (✓ ครบ / ✗ ขาด / + เกิน)</th>
                </tr>
            </thead>
            <tbody>
                $html_items
            </tbody>
        </table>
    </body>
    </html>
";

$filename = 'uploads/transfer_docs/transfer_' . $doc_no . '.html';
if (!file_exists('uploads/transfer_docs')) mkdir('uploads/transfer_docs', 0777, true);
file_put_contents($filename, $html);

// ส่งกลับ
echo json_encode([
    'status' => true,
    'message' => 'โอนสินค้าสำเร็จ',
    'doc_url' => $filename,
    'doc_no' => $doc_no,
    'from_name' => $from_name,
    'to_name' => $to_name
]);

}


//ดึงรายการเอกสารทั้งหมด
if ($received_data->post == 'get_transfer_documents') {
    $stmt = $connect->prepare("
        SELECT d.*, 
               p.name AS product_name, 
               fw.name AS from_name, 
               tw.name AS to_name
        FROM transfer_documents d
        JOIN products p ON d.product_id = p.id
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
        ORDER BY d.created_at DESC
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => true, 'documents' => $data]);
}
//ดึงรายการเอกสาร เฉพาะสาขา
if ($received_data->post == 'get_transfer_documents_id') {
    $warehouse_id = $received_data->wareHouseId;
    $start_date = date('Y-m-d 00:00:00');
    $end_date = date('Y-m-d 23:59:59');
    $stmt = $connect->prepare("
        SELECT d.*, 
            p.name AS product_name, 
            fw.name AS from_name, 
            tw.name AS to_name 
        FROM transfer_documents d
        JOIN products p ON d.product_id = p.id
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
        WHERE d.to_warehouse = :warehouse_id
        AND DATE(d.created_at) >= :start_date
        GROUP BY document_no 
        ORDER BY d.created_at DESC
    ");

    $warehouse_id = $received_data->wareHouseId;
    $start_date = date('Y-m-d 00:00:00'); // ตั้ง default กรณี cookie ไม่มีค่า

    $stmt->bindParam(':warehouse_id', $warehouse_id, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($data){
        $result = [];

        foreach ($data as $row) {
            // 🔄 ดึงข้อมูลคลังของสินค้านี้
            $stmt_dr = $connect->prepare("SELECT * FROM delivery_records WHERE transfer_id = :whid AND delivered_at >= :delivered_at");
            $stmt_dr->execute([
                ':whid' =>  $row['id'],
                ':delivered_at'  => $start_date 
            ]);
            $dr_result = $stmt_dr->fetch(PDO::FETCH_ASSOC);

            $result[] = [
                'created_at'  =>  $row['created_at'],
                'delivered_at'    =>  $dr_result['delivered_at'],
                'delivered_by'    =>  $dr_result['delivered_by'],
                'document_no' =>  $row['document_no'],
                'file_name'   =>  $row['file_name'],
                'from_name'   =>  $row['from_name'],
                'from_warehouse'  =>  $row['from_warehouse'],
                'id'  =>  $row['id'],
                'product_id'  =>  $row['product_id'],
                'product_name'    =>  $row['product_name'],
                'qty' =>  $row['qty'],
                'to_name' =>  $row['to_name'],
                'to_warehouse'    =>  $row['to_warehouse'],
                'delivered' => $dr_result['status'] ?? 'wait'
            ];
        }
    }

    echo json_encode(['status' => true, 'documents' => $result]);
}
if ($received_data->post == 'searchTransferByDate') {
    $date = $received_data->date;
    $warehouse_id = $received_data->wareHouseId;

    $stmt = $connect->prepare("
        SELECT d.*, 
               p.name AS product_name, 
               fw.name AS from_name, 
               tw.name AS to_name
        FROM transfer_documents d
        JOIN products p ON d.product_id = p.id
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
        WHERE DATE(d.created_at) = :search_date
          AND d.from_warehouse = :warehouse_id
        ORDER BY d.created_at DESC
    ");
    $stmt->bindParam(':search_date', $date);
    $stmt->bindParam(':warehouse_id', $warehouse_id);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data){
        $result = [];

        foreach ($data as $row) {
            // 🔄 ดึงข้อมูลคลังของสินค้านี้
            $stmt_dr = $connect->prepare("SELECT * FROM delivery_records WHERE transfer_id = :whid AND delivered_at >= :delivered_at");
            $stmt_dr->execute([
                ':whid' =>  $row['id'],
                ':delivered_at'  => $date 
            ]);
            $dr_result = $stmt_dr->fetch(PDO::FETCH_ASSOC);

            $result[] = [
                'created_at'  =>  $row['created_at'],
                'delivered_at'    =>  $dr_result['delivered_at'],
                'delivered_by'    =>  $dr_result['delivered_by'],
                'document_no' =>  $row['document_no'],
                'file_name'   =>  $row['file_name'],
                'from_name'   =>  $row['from_name'],
                'from_warehouse'  =>  $row['from_warehouse'],
                'id'  =>  $row['id'],
                'product_id'  =>  $row['product_id'],
                'product_name'    =>  $row['product_name'],
                'qty' =>  $row['qty'],
                'to_name' =>  $row['to_name'],
                'to_warehouse'    =>  $row['to_warehouse'],
                'delivered' => $dr_result['status'] ?? 'wait'
            ];
        }
    }
    echo json_encode(['status' => true, 'data' => $result]);
}


if ($received_data->post == 'cart_promotion') {
    // 1. ดึง cart ทั้งหมดของ user ที่ status = 'pending'

    $stmt = $connect->prepare("SELECT * FROM cart WHERE warehouse_id = ? AND status = 'pending'");
    $stmt->execute([$warehouse_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. วนลูปคำนวณราคาต่อชิ้นจริง จากโปรโมชั่น
    foreach ($cartItems as &$item) {
        $product_id = $item['product_id'];
        $qty = $item['qty'];

        // ดึงราคาพื้นฐานสินค้า
        $stmt = $connect->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $price = $product['price'];

        // หาราคาตามโปรโมชั่น
        $stmt = $connect->prepare("
            SELECT promo_price FROM product_promotions 
            WHERE product_id = ? AND min_quantity <= ? 
            ORDER BY min_quantity DESC LIMIT 1
        ");
        $stmt->execute([$product_id, $qty]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);
        $price_per_unit = $promo ? $promo['price'] : $price;

        // คำนวณราคารวม
        $item['price_per_unit'] = $price_per_unit;
        $item['total_price'] = $price_per_unit * $qty;
    }

    // 3. คำนวณยอดรวมทั้งหมด
    $grand_total = array_sum(array_column($cartItems, 'total_price'));

    // 4. แสดงผลหรือส่งกลับ
    echo json_encode([
        'status' => true,
        'cart' => $cartItems,
        'grand_total' => $grand_total
    ]);
}
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($received_data->post == 'add_to_cart') {
    @session_start();
    $user_id = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    // $user_id = $_SESSION['user_id'] ?? 1; // หรือดึงจาก token จริง
// print_r($_SESSION);exit;
    $product_id = $received_data->product_id;
    $warehouse_id = $received_data->warehouse_id;
    $qty = (int)($received_data->qty ?? 1);

    if (!$product_id || $qty < 1) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    // 🔍 1. ดึงจำนวนสินค้าคงเหลือจากคลัง
    $stmt = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?");
    $stmt->execute([$warehouse_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $availableUnit = (int)$row['unit'];
    if (!$row) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบข้อมูลในคลัง']);
        exit;
    } else if ($availableUnit < $qty) {
        echo json_encode(['status' => false, 'message' => 'สินค้ามีจำนวนไม่เพียงพอ']);
        exit;
    }

    // ตรวจสอบว่ามีอยู่แล้วใน cart หรือยัง
    $stmt = $connect->prepare("SELECT * FROM cart WHERE product_id = ? AND warehouse_id=? AND status = 'pending'");
    $stmt->execute([$product_id, $warehouse_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // อัปเดตจำนวน
        $stmt = $connect->prepare("UPDATE cart SET qty = qty + ? WHERE id = ?");
        $stmt->execute([$qty, $existing['id']]);
    } else {
        // เพิ่มใหม่
        $stmt = $connect->prepare("INSERT INTO cart (user_id, product_id,warehouse_id, qty, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$user_id, $product_id, $warehouse_id, $qty]);
    }

    // ดึงตะกร้าทั้งหมดกลับมา
    $sql = "SELECT 
                c.id,
                c.product_id,
                p.name AS product_name,
                p.image,
                p.quantity As price,
                c.qty,
                (p.quantity * c.qty) AS total_price
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.warehouse_id = ? AND c.status = 'pending'";

    $stmt = $connect->prepare($sql);
    $stmt->execute([$warehouse_id]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => true, 'cart' => $cart]);
}

if ($received_data->post == 'get_cart') {
    $warehouse_id = $received_data->warehouse_id;

    if (!$warehouse_id) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบคลังสินค้า']);
        exit;
    }

    $sql = "SELECT 
                c.id,
                c.product_id,
                p.name AS product_name,
                p.image,
                p.quantity AS price,
                c.qty,
                (p.quantity * c.qty) AS total_price
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.warehouse_id = ? AND c.status = 'pending'";

    $stmt = $connect->prepare($sql);
    $stmt->execute([$warehouse_id]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'cart' => $cart
    ]);
}

if ($received_data->post == 'get_promo') {

    $sql = "SELECT 
        c.product_id,
        c.warehouse_id,
        c.min_quantity,
        c.price,
        p.name AS product_name,
        p.image,
        w.name AS warehouse_name
    FROM product_promotions c
    JOIN products p ON c.product_id = p.id
    JOIN warehouses w ON c.warehouse_id = w.id
    ORDER BY c.product_id, c.warehouse_id, c.min_quantity ASC";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // จัดกลุ่มให้อ่านง่าย
    $promotions = [];
    foreach ($rows as $row) {
        $pKey = $row['product_id']; // กลุ่มตามสินค้า

        if (!isset($promotions[$pKey])) {
            $promotions[$pKey] = [
                'product_id'   => $row['product_id'],
                'product_name' => $row['product_name'],
                'image'        => $row['image'],
                'warehouses'   => []
            ];
        }

        $wKey = $row['warehouse_id']; // กลุ่มตามคลัง
        if (!isset($promotions[$pKey]['warehouses'][$wKey])) {
            $promotions[$pKey]['warehouses'][$wKey] = [
                'warehouse_id'   => $row['warehouse_id'],
                'warehouse_name' => $row['warehouse_name'],
                'steps'          => []
            ];
        }

        // เพิ่มขั้นบันได
        $promotions[$pKey]['warehouses'][$wKey]['steps'][] = [
            'min_quantity' => $row['min_quantity'],
            'price'        => $row['price']
        ];
    }

    // จัด array ให้เป็นแบบ index ธรรมดา
    $promotions = array_values(array_map(function ($p) {
        $p['warehouses'] = array_values($p['warehouses']);
        return $p;
    }, $promotions));

    echo json_encode([
        'status' => true,
        'promotions' => $promotions
    ]);
}

if ($received_data->post == 'check_promotion') {
    $cart = $received_data->cart ?? [];
    $warehouse_id = $received_data->warehouse_id;
    $newCart = [];
    $total = 0;

    foreach ($cart as $item) {
        // แปลง $item เป็น array เพื่อให้เข้าถึงได้แบบ []
        $item = (array) $item;

        $product_id = $item['product_id'];
        $price = $item['price'];
        $qty = $item['qty'];

        // 🔍 หาโปรโมชั่นที่เกี่ยวข้องกับสินค้านี้
        $stmt = $connect->prepare("
            SELECT * 
            FROM product_promotions 
            WHERE product_id = ? 
              AND warehouse_id = ?
        ");
        $stmt->execute([$product_id, $warehouse_id]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        $discount = 0;
        if ($promo) {
            if ($qty >= $promo['min_quantity']) {
                // ถ้าโปรโมชั่นเป็นราคาพิเศษแทนที่ราคาเดิม
                $price = $promo['price'];
            }
        }

        // ราคาหลังลดต่อหน่วย
        $final_price_per_unit = max(0, $price - $discount);

        // รวมราคาสินค้านี้
        $total_price_item = $final_price_per_unit * $qty;

        // บวกเข้า total
        $total += $total_price_item;

        // เก็บข้อมูลใหม่ใน cart
        $newCart[] = [
            'product_id'            => $product_id,
            'product_name'          => $item['product_name'],
            'qty'                   => $qty,
            'price_per_unit'        => $price,
            'discount_per_unit'     => $discount,
            'final_price_per_unit'  => $final_price_per_unit,
            'total_price_item'      => $total_price_item
        ];
    }

    echo json_encode([
        'status' => true,
        'cart'   => $newCart,
        'total'  => $total
    ]);
}


if ($received_data->post == 'update_promo') {
    $product_id = $received_data->product_id ?? null;
    $warehouses = $received_data->warehouses ?? [];

    if (!$product_id || empty($warehouses)) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    try {
        $connect->beginTransaction();

        // ลบข้อมูลเก่า ของสินค้านี้ก่อน
        $stmtDel = $connect->prepare("DELETE FROM product_promotions WHERE product_id = ?");
        $stmtDel->execute([$product_id]);

        // เตรียม insert ใหม่
        $stmtInsert = $connect->prepare("
            INSERT INTO product_promotions (product_id, warehouse_id, min_quantity, price)
            VALUES (?, ?, ?, ?)
        ");

        foreach ($warehouses as $wh) {
            $warehouse_id = $wh->warehouse_id ?? null;
            $steps = $wh->steps ?? [];

            if (!$warehouse_id || empty($steps)) continue;

            foreach ($steps as $step) {
                $min_qty = (int)$step->min_quantity;
                $price = (float)$step->price;

                if ($min_qty > 0) {
                    $stmtInsert->execute([$product_id, $warehouse_id, $min_qty, $price]);
                }
            }
        }

        $connect->commit();

        echo json_encode(['status' => true, 'message' => 'แก้ไขโปรโมชั่นสำเร็จ']);
    } catch (Exception $e) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($received_data->post == 'save_promo') {

    // Debug ดูข้อมูลที่ส่งมา
    // (ถ้าไม่อยากให้แสดงก็ปิด comment หลังจากทดสอบ)
    // echo '<pre>'; print_r($received_data); echo '</pre>'; exit;

    $product_ids   = $received_data->product_ids ?? [];
    $warehouse_ids = $received_data->warehouses_id ?? [];
    $steps         = $received_data->steps ?? [];

    // เช็กว่าข้อมูลครบไหม
    if (empty($product_ids) || empty($warehouse_ids) || empty($steps)) {
        echo json_encode([
            'status'  => false,
            'message' => 'ข้อมูลไม่ครบ'
        ]);
        exit;
    }

    // เปิดโหมดให้ PDO แสดง error ถ้า query ล้มเหลว
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $connect->prepare("
            INSERT INTO product_promotions (product_id, warehouse_id, min_quantity, price)
            VALUES (?, ?, ?, ?)
        ");

        $insert_count = 0;

        foreach ($product_ids as $product_id) {
            foreach ($warehouse_ids as $warehouse_id) {
                foreach ($steps as $step) {

                    // รองรับทั้ง object และ array
                    $min_qty = is_object($step) ? (int)$step->quantity : (int)$step['quantity'];
                    $price   = is_object($step) ? (float)$step->price   : (float)$step['price'];

                        $stmt->execute([
                            $product_id,
                            $warehouse_id,
                            $min_qty,
                            $price
                        ]);
                        $insert_count++;
                    
                }
            }
        }

        if ($insert_count > 0) {
            echo json_encode([
                'status'  => true,
                'message' => 'บันทึกโปรโมชั่นสำเร็จ',
                'inserted'=> $insert_count
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'ไม่มีข้อมูลที่บันทึกได้'
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            'status'  => false,
            'message' => 'DB Error: ' . $e->getMessage()
        ]);
    }
}


if ($received_data->post == 'update_cart_bulk') {
    $warehouse_id = $received_data->warehouse_id ?? null;
    $items = $received_data->items ?? [];

    if (!$warehouse_id || empty($items)) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    foreach ($items as $item) {
        $product_id = $item->id ?? null;
        $qty = $item->qty ?? 0;

        if (!$product_id || $qty <= 0) continue;

        // ตรวจสอบว่ามีอยู่ในตะกร้าแล้วหรือยัง
        $stmt = $connect->prepare("SELECT * FROM cart WHERE warehouse_id = ? AND product_id = ? AND status = 'pending'");
        $stmt->execute([$warehouse_id, $product_id]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // อัปเดตจำนวน
            $stmt = $connect->prepare("UPDATE cart SET qty = ? WHERE warehouse_id = ? AND product_id = ? AND status = 'pending'");
            $stmt->execute([$qty, $warehouse_id, $product_id]);
        } else {
            // เพิ่มเข้า cart
            $stmt = $connect->prepare("INSERT INTO cart (warehouse_id, product_id, qty status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
            $stmt->execute([$warehouse_id, $product_id, $qty]);
        }
    }

    echo json_encode(['status' => true, 'message' => 'อัปเดตสินค้าในตะกร้าสำเร็จ']);
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// if (!$received_data) {
//     print_r($received_data);
//     echo json_encode(['status' => false, 'message' => 'Invalid JSON']);
//     exit;
// }
if ($received_data->post == 'update_cart') {
    // echo "!@34";
    $warehouse_id = $received_data->warehouse_id;
    $product_id = $received_data->product_id;
    $qty = $received_data->qty;

    // if (!$warehouse_id || !$product_id || $qty <= 0) {
    //     echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
    //     exit;
    // }
    // 🔍 1. ดึงจำนวนสินค้าคงเหลือจากคลัง
    $stmt = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?");
    $stmt->execute([$warehouse_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $availableUnit = (int)$row['unit'];
    if (!$row) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบข้อมูลในคลัง']);
        exit;
    } else if ($availableUnit < $qty) {
        echo json_encode(['status' => false, 'message' => 'สินค้ามีจำนวนไม่เพียงพอ']);
        exit;
    }

    $stmt = $connect->prepare("SELECT id FROM cart WHERE warehouse_id = ? AND product_id = ? AND status = 'pending'");
    $stmt->execute([$warehouse_id, $product_id]);

    if ($stmt->rowCount()) {
        $stmt = $connect->prepare("UPDATE cart SET qty = ?, created_at = NOW() WHERE warehouse_id = ? AND product_id = ? AND status = 'pending'");
        $stmt->execute([$qty, $warehouse_id, $product_id]);
    } else {
        $stmt = $connect->prepare("INSERT INTO cart (warehouse_id, product_id, qty, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
        $stmt->execute([$warehouse_id, $product_id, $qty]);
    }

    $sql = "SELECT 
        c.id,
        c.product_id,
        p.name AS product_name,
        p.image,
        p.quantity AS price,
        c.qty,
        (p.quantity * c.qty) AS total_price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.warehouse_id = ? AND c.status = 'pending'";

    $stmt = $connect->prepare($sql);
    $stmt->execute([$warehouse_id]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'cart' => $cart
    ]);
}
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if ($received_data->post == 'generate_receipt_html') {
    // $price = $received_data->price;
    $cart = $received_data->cart ?? [];
    $total = $received_data->total ?? 0;
    $received = $received_data->received ?? 0;
    $warehouse_name = $received_data->warehouse_name;
    $cash = $received_data->cash;
    $change = $received - $total;
    $warehouse_id = $received_data->warehouse_id ?? 'unknown';
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];
    if (empty($cart)) {
        echo json_encode(['status' => false, 'message' => 'ไม่มีข้อมูลสินค้า']);
        exit;
    }

    // สร้างเลขที่ใบเสร็จ
    $receipt_id = 'RC' . date('YmdHis');
    $file_name = $receipt_id . '.html';
    $folder_path = __DIR__ . '/../receipts/';

    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0777, true);
    }

    // สร้าง HTML content
    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>ใบเสร็จ</title>';
    $html .= '<style>body{font-family:sans-serif;padding:20px;border: solid 1px #ccc;}table{width:100%;border-collapse:collapse;}td,th{padding:5px;text-align:left;}</style>';
    $html .= '</head><body>';
    $html .= "<h2 style='text-align: right;'>🧾 ใบเสร็จรับเงิน</h2>";
    $html .= "<div style='text-align: right;'>เลขที่: $receipt_id</div>";
    $html .= "<div style='text-align: right;'>วันที่: " . date('Y-m-d H:i:s') . "</div>";
    $html .= "<p>สาขา: $warehouse_name</p>";

    $html .= "<table><thead><tr><th>สินค้า</th><th style='text-align:right'>จำนวน</th><th style='text-align:right'>ราคา</th><th style='text-align:right'>รวม</th></tr></thead><tbody>";
    foreach ($cart as $item) {
        $product = htmlspecialchars($item->product_name);
        $qty = (int)$item->qty;
        $price = number_format($item->price, 2);
        $total_item = number_format($item->qty * $item->price, 2);
        $html .= "<tr><td>$product</td><td style='text-align:right'>x $qty</td><td style='text-align:right'>$price</td><td style='text-align:right'>$total_item</td></tr>";
    }
    $html .= "</tbody><tfoot>";
    $html .= "<tr style='border-top: solid 8px #e3e0e0;'><td colspan='3'><b>รวมทั้งหมด</b></td><td style='text-align:right'>" . number_format($total, 2) . "</td></tr>";
    $html .= "<tr><td colspan='3'><b>เงินที่รับมา</b></td><td style='text-align:right'>" . $cash . ' ' . number_format($received, 2) . "</td></tr>";
    $html .= "<tr><td colspan='3'><b>เงินทอน</b></td><td style='text-align:right'>" . number_format($change, 2) . "</td></tr>";
    $html .= "</tfoot></table>";
    $html .= "<p style='text-align: right;'><b>Emp</b>:" . $person . "</p>";
    $html .= '</body></html>';

    // บันทึกไฟล์ 
    file_put_contents($folder_path . $file_name, $html);

    // บันทึกข้อมูลลงในตาราง receipts
    $stmt = $connect->prepare("
        INSERT INTO receipts (receipt_code, warehouse_id, total, received, change_amount, payment_method, file_path, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $receipt_id,
        $warehouse_id,
        $total,
        $received,
        $change,
        $cash,
        'receipts/' . $file_name
    ]);

    // ดึง ID ล่าสุดของใบเสร็จ
    $receipt_db_id = $connect->lastInsertId();

    // เตรียม insert รายการสินค้า
    $stmt_item = $connect->prepare("
        INSERT INTO receipt_items (receipt_id, product_id, product_name, qty, price, total, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    foreach ($cart as $item) {
        $stmt_item->execute([
            $receipt_db_id,
            $item->id,
            $item->product_name,
            $item->qty,
            $item->price,
            $total
        ]);
    }

    // 🔸 ดึงรายการจากตะกร้า
    $sql = "SELECT 
    c.product_id,
    c.qty,
    p.quantity AS price,
    c.warehouse_id
FROM cart c
JOIN products p ON c.product_id = p.id
WHERE c.warehouse_id = ? AND c.status = 'pending'";

    $stmt_cart = $connect->prepare($sql);
    $stmt_cart->execute([$warehouse_id]);
    $cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

    if ($cart_items) {
        // 🔸 เตรียม statement สำหรับ insert และ update ล่วงหน้า
        $stmt_insert_sale = $connect->prepare("
        INSERT INTO saleproducts (warehouse_id, product_id, qty, price, total, receipt_id, person, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

        $stmt_get_unit = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?");
        $stmt_update_unit = $connect->prepare("UPDATE pd_in_whs SET unit = ? WHERE warehouses_id = ? AND prooduct_id = ?");

        foreach ($cart_items as $cart_item) {
            $warehouse_id = $cart_item['warehouse_id'];
            $product_id   = $cart_item['product_id'];
            $qty          = (int)$cart_item['qty'];
            $price        = (float)$cart_item['price'];
            $total        = (int)$cart_item['qty'] * (float)$cart_item['price'];

            // 🔸 1. บันทึกการขาย
            $stmt_insert_sale->execute([
                $warehouse_id,
                $product_id,
                $qty,
                $price,
                $total,
                $receipt_db_id,
                $person
            ]);

            // 🔸 2. อัปเดตจำนวนสินค้าในคลัง (ตัดสต๊อก)
            $stmt_get_unit->execute([$warehouse_id, $product_id]);
            $row = $stmt_get_unit->fetch(PDO::FETCH_ASSOC);
            $current_unit = (int)($row['unit'] ?? 0);
            $new_unit = max($current_unit - $qty, 0); // ป้องกันค่าติดลบ

            $stmt_update_unit->execute([$new_unit, $warehouse_id, $product_id]);
        }

        // 🔸 3. ลบรายการในตะกร้าที่ขายแล้ว
        $stmt_delete_cart = $connect->prepare("DELETE FROM cart WHERE warehouse_id = ? AND status = 'pending'");
        $stmt_delete_cart->execute([$warehouse_id]);
    }


    echo json_encode([
        'status' => true,
        'message' => 'สร้างใบเสร็จและบันทึกข้อมูลสำเร็จ',
        'file_name' => $file_name,
        'receipt_url' => 'receipts/' . $file_name
    ]);
}

if ($received_data->post == 'get_receipts') {
    $sql = "
        SELECT 
            r.*, 
            w.name AS warehouse_name,
            GROUP_CONCAT(DISTINCT s.person SEPARATOR ', ') AS persons
        FROM receipts r
        LEFT JOIN warehouses w ON r.warehouse_id = w.id
        LEFT JOIN saleproducts s ON s.warehouse_id = r.warehouse_id
        GROUP BY r.id
        ORDER BY r.created_at DESC
    ";

    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'receipts' => $receipts
    ]);
}

if ($received_data->post == 'get_product_by_id') {
    $barcode = $received_data->id ?? null;

    if (!$barcode) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบรหัสบาร์โค้ด']);
        exit;
    }

    // JOIN ตาราง barcodes เพื่อหาสินค้า
    $stmt = $connect->prepare("
        SELECT p.*
        FROM barcodes b
        JOIN products p ON p.id = b.product_id
        WHERE b.code = ?
        LIMIT 1
    ");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode(['status' => true, 'product' => $product]);
    } else {
        echo json_encode(['status' => false, 'message' => 'ไม่พบสินค้าสำหรับบาร์โค้ดนี้']);
    }
}



if ($received_data->post == 'get_summary_range') {
    $range = $received_data->range ?? 'today';
    $start = '';
    $end = '';
    $today = date('Y-m-d');

    if ($range === 'today') {
        $start = $end = $today;
    } elseif ($range === 'week') {
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = $today;
    } elseif ($range === 'month') {
        $start = date('Y-m-01');
        $end = $today;
    } elseif ($range === 'custom') {
        $start = $received_data->start_date ?? $today;
        $end = $received_data->end_date ?? $today;
    }

    // 1. ยอดขายและต้นทุน
    $stmt = $connect->prepare("
        SELECT 
            SUM(sp.total) AS total_sale,
            SUM(sp.qty * p.price) AS total_cost
        FROM saleproducts sp
        JOIN products p ON sp.product_id = p.id
        WHERE DATE(sp.created_at) BETWEEN ? AND ?
    ");
    $stmt->execute([$start, $end]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_sale = (float)($result['total_sale'] ?? 0);
    $total_cost = (float)($result['total_cost'] ?? 0);
    $profit = $total_sale - $total_cost;

    // 2. มูลค่าสินค้าคงเหลือ
    $stmt = $connect->prepare("
        SELECT SUM(p.price * pw.unit) AS stock_value
        FROM pd_in_whs pw
        JOIN products p ON p.id = pw.prooduct_id
    ");
    $stmt->execute();
    $stock = $stmt->fetch(PDO::FETCH_ASSOC);
    $stock_value = (float)($stock['stock_value'] ?? 0);

    echo json_encode([
        'total_sale' => $total_sale,
        'total_cost' => $total_cost,
        'profit' => $profit,
        'stock_value' => $stock_value
    ]);
}

if ($received_data->post == 'get_sale_summary_by_week') {
    date_default_timezone_set('Asia/Bangkok'); // ตั้ง timezone ให้ถูก

    $weekMap = [
        1 => 'จันทร์',
        2 => 'อังคาร',
        3 => 'พุธ',
        4 => 'พฤหัสบดี',
        5 => 'ศุกร์',
        6 => 'เสาร์',
        7 => 'อาทิตย์'
    ];

    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

    $stmt = $connect->prepare("
        SELECT 
            DAYOFWEEK(created_at) AS weekday, 
            SUM(total) AS total
        FROM 
            saleproducts
        WHERE 
            DATE(created_at) BETWEEN ? AND ?
        GROUP BY 
            weekday
    ");
    $stmt->execute([$startOfWeek, $endOfWeek]);

    $data = array_fill(1, 7, 0);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $weekday = (int)$row['weekday'];
        if ($weekday == 1) $weekday = 7; // อาทิตย์ → 7
        else $weekday -= 1;              // จันทร์-เสาร์ → 1-6
        $data[$weekday] = (float)$row['total'];
    }

    $totalWeek = array_sum($data);

    $result = [];
    foreach ($weekMap as $day => $label) {
        $amount = $data[$day] ?? 0;
        $percent = $totalWeek > 0 ? round(($amount / $totalWeek) * 100, 2) : 0;
        $result[] = [
            'label' => $label,
            'total' => $amount,
            'percent' => $percent
        ];
    }

    echo json_encode(['status' => true, 'data' => $result, 'total' => $totalWeek]);
    exit;
}

if ($received_data->post == 'get_sale_summary_by_month') {
    date_default_timezone_set('Asia/Bangkok'); // ตั้ง timezone เช่นเดียวกัน

    $months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $year = date('Y');

    $stmt = $connect->prepare("
        SELECT 
            MONTH(created_at) AS month, 
            SUM(total) AS total
        FROM 
            saleproducts
        WHERE 
            YEAR(created_at) = ?
        GROUP BY 
            MONTH(created_at)
    ");
    $stmt->execute([$year]);

    $data = array_fill(1, 12, 0);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[(int)$row['month']] = (float)$row['total'];
    }

    $totalYear = array_sum($data);

    $result = [];
    foreach ($months as $i => $label) {
        $monthIndex = $i + 1;
        $amount = $data[$monthIndex] ?? 0;
        $percent = $totalYear > 0 ? round(($amount / $totalYear) * 100, 2) : 0;
        $result[] = [
            'label' => $label,
            'total' => $amount,
            'percent' => $percent
        ];
    }

    echo json_encode(['status' => true, 'data' => $result, 'total' => $totalYear]);
    exit;
}

if ($received_data->post == 'get_top_selling_products') {
    $limit = $received_data->limit ?? 10;

    $stmt = $connect->prepare("
        SELECT 
            p.id,
            p.name AS product_name,
            SUM(sp.qty) AS total_qty,
            SUM(sp.total) AS total_sale
        FROM 
            saleproducts sp
        JOIN 
            products p ON sp.product_id = p.id
        GROUP BY 
            sp.product_id
        ORDER BY 
            total_qty DESC,
    total_sale DESC
        LIMIT ?
    ");

    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    // $stmt->execute([$limit]);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
    exit;
}


if ($received_data->post == 'get_top_selling_products_id') {
    $limit = $received_data->limit ?? 10;
    $wareHouseId = $received_data->wareHouseId;
    $range = $received_data->range ?? 'today';
    $start_date = $end_date = null;

    switch ($range) {
        case 'today':
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
        case 'week':
            $start_date = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
            break;
        case 'month':
            $start_date = date('Y-m-01 00:00:00');
            $end_date = date('Y-m-t 23:59:59');
            break;
        case 'year':
            $currentYear = (int)date('Y');
            $start_year = $currentYear - 4;
            $start_date = "{$start_year}-01-01 00:00:00";
            $end_date = "{$currentYear}-12-31 23:59:59";
            break;
        case '5years':
            $start_date = date('Y-m-d', strtotime('-5 years')) . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
            break;
        case 'custom':
            if (!empty($received_data->start_date) && !empty($received_data->end_date)) {
                $start_date = $received_data->start_date . ' 00:00:00';
                $end_date = $received_data->end_date . ' 23:59:59';
            } else {
                echo json_encode(['status' => false, 'message' => 'กรุณาระบุช่วงวันที่']);
                exit;
            }
            break;
        default:
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
    }

    $stmt = $connect->prepare("
    SELECT 
        p.id,
        p.name AS product_name,
        MIN(sp.created_at) AS first_sale_date,
        SUM(sp.qty) AS total_qty,
        SUM(sp.total) AS total_sale
    FROM 
        saleproducts sp
    JOIN 
        products p ON sp.product_id = p.id
    WHERE
        sp.created_at BETWEEN ? AND ? 
        AND sp.warehouse_id = ?
    GROUP BY 
        sp.product_id
    ORDER BY 
        total_qty DESC, total_sale DESC
    LIMIT ?
");

    $stmt->bindValue(1, $start_date);
    $stmt->bindValue(2, $end_date);
    $stmt->bindValue(3, (int)$wareHouseId, PDO::PARAM_INT);
    $stmt->bindValue(4, (int)$limit, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
    exit;
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


if ($received_data->post == 'update_qyt') {
    $id = $received_data->id ?? null;
    $update_qyt = $received_data->update_qyt ?? null;

    if (!$id || $update_qyt === null) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
        exit;
    }

    try {
        $stmt = $connect->prepare("UPDATE sale_finish_ofday SET update_qyt = :update_qyt WHERE id = :id");
        $stmt->execute([
            ':update_qyt' => $update_qyt,
            ':id' => $id,
        ]);

        // ดึงข้อมูลล่าสุดกลับไปให้ด้วย (ถ้าต้องการ)
        $res = $connect->query("SELECT * FROM sale_finish_ofday ORDER BY id DESC");
        $data = $res->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => true, 'message' => 'อัปเดตเรียบร้อย', 'data' => $data]);
    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
    exit;
}



// report dashboard
// summary total

if ($received_data->post == 'get_dashboard_summary_total') {
    $range = $received_data->range ?? 'today';
    $start_date = $end_date = null;

    switch ($range) {
        case 'today':
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
        case 'week':
            $start_date = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
            break;
        case 'month':
            $start_date = date('Y-m-01 00:00:00');
            $end_date = date('Y-m-t 23:59:59');
            break;
        case 'year':
            $currentYear = (int)date('Y');
            $start_year = $currentYear - 4;
            $start_date = "{$start_year}-01-01 00:00:00";
            $end_date = "{$currentYear}-12-31 23:59:59";
            break;
        case '5years':
            $start_date = date('Y-m-d', strtotime('-5 years')) . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
            break;
        case 'custom':
            if (!empty($received_data->start_date) && !empty($received_data->end_date)) {
                $start_date = $received_data->start_date . ' 00:00:00';
                $end_date = $received_data->end_date . ' 23:59:59';
            } else {
                echo json_encode(['status' => false, 'message' => 'กรุณาระบุช่วงวันที่']);
                exit;
            }
            break;
        default:
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
    }

    // ดึงยอดขายจากฐานข้อมูล
    $stmt = $connect->prepare("
        SELECT 
            sp.created_at,
            DATE(sp.created_at) AS sale_date,
            HOUR(sp.created_at) AS hour,
            DAYOFWEEK(sp.created_at) AS weekday,
            YEAR(sp.created_at) AS year,
            MONTH(sp.created_at) AS month,
            SUM(sp.total) AS total_sale,
            SUM(sp.qty * p.price) AS total_cost,
            SUM(sp.total - (sp.qty * p.price)) AS profit
        FROM saleproducts sp
        JOIN products p ON sp.product_id = p.id
        WHERE sp.created_at BETWEEN ? AND ?
        GROUP BY sale_date, hour
        ORDER BY sale_date ASC
    ");
    $stmt->execute([$start_date, $end_date]);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงมูลค่าสินค้าคงคลัง
    $stmt2 = $connect->query("
        SELECT 
            SUM(w.unit * p.price) AS stock_value,
            COUNT(*) AS product_count
        FROM pd_in_whs w
        JOIN products p ON w.prooduct_id = p.id
    ");
    $stock = $stmt2->fetch(PDO::FETCH_ASSOC);

    $result = [];

    // วิเคราะห์ข้อมูลตามช่วงเวลา
    if ($range === 'today') {
        // รายชั่วโมง
        $map = [];
        foreach ($sales as $row) {
            $hour = str_pad($row['hour'], 2, '0', STR_PAD_LEFT) . ':00';
            $map[$hour] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        for ($i = 0; $i < 24; $i++) {
            $hourLabel = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $result[] = [
                'label' => $hourLabel,
                'total' => $map[$hourLabel]['total'] ?? 0,
                'profit' => $map[$hourLabel]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'week') {
        $weekMap = [1 => 'อาทิตย์', 2 => 'จันทร์', 3 => 'อังคาร', 4 => 'พุธ', 5 => 'พฤหัสบดี', 6 => 'ศุกร์', 7 => 'เสาร์'];
        $map = [];
        foreach ($sales as $row) {
            $weekday = $row['weekday'];
            $map[$weekday] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        foreach ([2, 3, 4, 5, 6, 7, 1] as $w) {
            $result[] = [
                'label' => $weekMap[$w],
                'total' => $map[$w]['total'] ?? 0,
                'profit' => $map[$w]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'month' || $range === 'custom') {
        // รายวัน
        $map = [];
        foreach ($sales as $row) {
            $day = date('Y-m-d', strtotime($row['sale_date']));
            $map[$day] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        $period = new DatePeriod(
            new DateTime(substr($start_date, 0, 10)),
            new DateInterval('P1D'),
            (new DateTime(substr($end_date, 0, 10)))->modify('+1 day')
        );
        $thaiMonths = [
            '01' => 'ม.ค.',
            '02' => 'ก.พ.',
            '03' => 'มี.ค.',
            '04' => 'เม.ย.',
            '05' => 'พ.ค.',
            '06' => 'มิ.ย.',
            '07' => 'ก.ค.',
            '08' => 'ส.ค.',
            '09' => 'ก.ย.',
            '10' => 'ต.ค.',
            '11' => 'พ.ย.',
            '12' => 'ธ.ค.'
        ];
        foreach ($period as $dt) {
            $d = $dt->format('Y-m-d');
            $day = $dt->format('d');
            $month = $dt->format('m');
            $year = (int)$dt->format('Y') + 543; // แปลงเป็น พ.ศ.

            $thaiLabel = "{$day} {$thaiMonths[$month]} " . substr($year, -2); // เช่น 01 ม.ค. 68

            $result[] = [
                'label' => $thaiLabel,
                'total' => $map[$d]['total'] ?? 0,
                'profit' => $map[$d]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'year' || $range === '5years') {
        // รายเดือนหรือรายปี
        $summary = [];

        foreach ($sales as $row) {
            $year_th = $row['year'] + 543;
            if ($range === 'year') {
                $key = $year_th . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT);
            } else {
                $key = (string)$year_th;
            }

            if (!isset($summary[$key])) {
                $summary[$key] = ['total' => 0, 'profit' => 0];
            }

            $summary[$key]['total'] += $row['total_sale'];
            $summary[$key]['profit'] += $row['profit'];
        }

        foreach ($summary as $label => $val) {
            $result[] = [
                'label' => $label,
                'total' => $val['total'],
                'profit' => $val['profit']
            ];
        }
    }

    // รวมยอดรวม
    $total_sale = array_sum(array_column($sales, 'total_sale'));
    $total_cost = array_sum(array_column($sales, 'total_cost'));
    $profit = array_sum(array_column($sales, 'profit'));

    echo json_encode([
        'status' => true,
        'summary' => $sales,
        'data' => $result,
        'stock_value' => round($stock['stock_value'] ?? 0, 2),
        'product_count' => (int)($stock['product_count'] ?? 0),
        'total_sale' => round($total_sale, 2),
        'total_cost' => round($total_cost, 2),
        'profit' => round($profit, 2)
    ]);
    exit;
}


if ($received_data->post == 'get_dashboard_summary_total_id') {
    $wareHouseId = $received_data->wareHouseId ?? 1;
    $range = $received_data->range ?? 'today';
    $start_date = $end_date = null;

    switch ($range) {
        case 'today':
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
        case 'week':
            $start_date = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
            break;
        case 'month':
            $start_date = date('Y-m-01 00:00:00');
            $end_date = date('Y-m-t 23:59:59');
            break;
        case 'year':
            $currentYear = (int)date('Y');
            $start_year = $currentYear - 4;
            $start_date = "{$start_year}-01-01 00:00:00";
            $end_date = "{$currentYear}-12-31 23:59:59";
            break;
        case '5years':
            $start_date = date('Y-m-d', strtotime('-5 years')) . ' 00:00:00';
            $end_date = date('Y-m-d') . ' 23:59:59';
            break;
        case 'custom':
            if (!empty($received_data->start_date) && !empty($received_data->end_date)) {
                $start_date = $received_data->start_date . ' 00:00:00';
                $end_date = $received_data->end_date . ' 23:59:59';
            } else {
                echo json_encode(['status' => false, 'message' => 'กรุณาระบุช่วงวันที่']);
                exit;
            }
            break;
        default:
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            break;
    }

    // ดึงยอดขายจากฐานข้อมูล
    $stmt = $connect->prepare("
        SELECT 
            sp.created_at,
            DATE(sp.created_at) AS sale_date,
            HOUR(sp.created_at) AS hour,
            DAYOFWEEK(sp.created_at) AS weekday,
            YEAR(sp.created_at) AS year,
            MONTH(sp.created_at) AS month,
            SUM(sp.total) AS total_sale,
            SUM(sp.qty * p.price) AS total_cost,
            SUM(sp.total - (sp.qty * p.price)) AS profit
        FROM saleproducts sp
        JOIN products p ON sp.product_id = p.id
        WHERE sp.created_at BETWEEN ? AND ? AND warehouse_id = ?
        GROUP BY sale_date, hour
        ORDER BY sale_date ASC
    ");
    $stmt->execute([$start_date, $end_date, $wareHouseId]);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงมูลค่าสินค้าคงคลัง
    $stmt2 = $connect->query("
        SELECT 
            SUM(w.unit * p.price) AS stock_value,
            COUNT(*) AS product_count
        FROM pd_in_whs w
        JOIN products p ON w.prooduct_id = p.id
    ");
    $stock = $stmt2->fetch(PDO::FETCH_ASSOC);

    $result = [];

    // วิเคราะห์ข้อมูลตามช่วงเวลา
    if ($range === 'today') {
        // รายชั่วโมง
        $map = [];
        foreach ($sales as $row) {
            $hour = str_pad($row['hour'], 2, '0', STR_PAD_LEFT) . ':00';
            $map[$hour] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        for ($i = 0; $i < 24; $i++) {
            $hourLabel = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            $result[] = [
                'label' => $hourLabel,
                'total' => $map[$hourLabel]['total'] ?? 0,
                'profit' => $map[$hourLabel]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'week') {
        $weekMap = [1 => 'อาทิตย์', 2 => 'จันทร์', 3 => 'อังคาร', 4 => 'พุธ', 5 => 'พฤหัสบดี', 6 => 'ศุกร์', 7 => 'เสาร์'];
        $map = [];
        foreach ($sales as $row) {
            $weekday = $row['weekday'];
            $map[$weekday] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        foreach ([2, 3, 4, 5, 6, 7, 1] as $w) {
            $result[] = [
                'label' => $weekMap[$w],
                'total' => $map[$w]['total'] ?? 0,
                'profit' => $map[$w]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'month' || $range === 'custom') {
        // รายวัน
        $map = [];
        foreach ($sales as $row) {
            $day = date('Y-m-d', strtotime($row['sale_date']));
            $map[$day] = [
                'total' => $row['total_sale'],
                'profit' => $row['profit']
            ];
        }

        $period = new DatePeriod(
            new DateTime(substr($start_date, 0, 10)),
            new DateInterval('P1D'),
            (new DateTime(substr($end_date, 0, 10)))->modify('+1 day')
        );
        $thaiMonths = [
            '01' => 'ม.ค.',
            '02' => 'ก.พ.',
            '03' => 'มี.ค.',
            '04' => 'เม.ย.',
            '05' => 'พ.ค.',
            '06' => 'มิ.ย.',
            '07' => 'ก.ค.',
            '08' => 'ส.ค.',
            '09' => 'ก.ย.',
            '10' => 'ต.ค.',
            '11' => 'พ.ย.',
            '12' => 'ธ.ค.'
        ];
        foreach ($period as $dt) {
            $d = $dt->format('Y-m-d');
            $day = $dt->format('d');
            $month = $dt->format('m');
            $year = (int)$dt->format('Y') + 543; // แปลงเป็น พ.ศ.

            $thaiLabel = "{$day} {$thaiMonths[$month]} " . substr($year, -2); // เช่น 01 ม.ค. 68

            $result[] = [
                'label' => $thaiLabel,
                'total' => $map[$d]['total'] ?? 0,
                'profit' => $map[$d]['profit'] ?? 0
            ];
        }
    } elseif ($range === 'year' || $range === '5years') {
        // รายเดือนหรือรายปี
        $summary = [];

        foreach ($sales as $row) {
            $year_th = $row['year'] + 543;
            if ($range === 'year') {
                $key = $year_th . '-' . str_pad($row['month'], 2, '0', STR_PAD_LEFT);
            } else {
                $key = (string)$year_th;
            }

            if (!isset($summary[$key])) {
                $summary[$key] = ['total' => 0, 'profit' => 0];
            }

            $summary[$key]['total'] += $row['total_sale'];
            $summary[$key]['profit'] += $row['profit'];
        }

        foreach ($summary as $label => $val) {
            $result[] = [
                'label' => $label,
                'total' => $val['total'],
                'profit' => $val['profit']
            ];
        }
    }

    // รวมยอดรวม
    $total_sale = array_sum(array_column($sales, 'total_sale'));
    $total_cost = array_sum(array_column($sales, 'total_cost'));
    $profit = array_sum(array_column($sales, 'profit'));

    echo json_encode([
        'status' => true,
        'summary' => $sales,
        'data' => $result,
        'stock_value' => round($stock['stock_value'] ?? 0, 2),
        'product_count' => (int)($stock['product_count'] ?? 0),
        'total_sale' => round($total_sale, 2),
        'total_cost' => round($total_cost, 2),
        'profit' => round($profit, 2)
    ]);
    exit;
}



// summary
if ($received_data->post == 'get_dashboard_summary') {
    $timeframe = $received_data->timeframe ?? 'day'; // day, week, month, year

    // คำนวณช่วงเวลา
    if ($timeframe == 'day') {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
    } elseif ($timeframe == 'week') {
        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $end = date('Y-m-d 23:59:59', strtotime('sunday this week'));
    } elseif ($timeframe == 'month') {
        $start = date('Y-m-01 00:00:00');
        $end = date('Y-m-t 23:59:59');
    } elseif ($timeframe == 'year') {
        $start = date('Y-01-01 00:00:00');
        $end = date('Y-12-31 23:59:59');
    }

    $stmt = $connect->prepare("
        SELECT 
            w.id,
            w.name AS warehouse_name,
            SUM(sp.total) AS total_sale,
            SUM(sp.qty * p.price) AS total_cost,
            SUM(sp.total - (sp.qty * p.price)) AS total_profit
        FROM 
            saleproducts sp
        JOIN 
            products p ON sp.product_id = p.id
        JOIN 
            warehouses w ON sp.warehouse_id = w.id
        WHERE 
            sp.created_at BETWEEN ? AND ?
        GROUP BY 
            sp.warehouse_id
    ");
    $stmt->execute([$start, $end]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'timeframe' => $timeframe,
        'start' => $start,
        'end' => $end,
        'data' => $data
    ]);
    exit;
}



if ($received_data->post == 'get_dashboard_summary_by_week') {
    $weekMap = [
        1 => 'จันทร์',
        2 => 'อังคาร',
        3 => 'พุธ',
        4 => 'พฤหัสบดี',
        5 => 'ศุกร์',
        6 => 'เสาร์',
        7 => 'อาทิตย์'
    ];

    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week 23:59:59'));

    $stmt = $connect->prepare("
        SELECT 
            DAYOFWEEK(created_at) AS weekday, 
            SUM(total) AS total
        FROM 
            saleproducts
        WHERE 
            DATE(created_at) BETWEEN ? AND ?
        GROUP BY 
            weekday
    ");
    $stmt->execute([$startOfWeek, $endOfWeek]);

    $rawData = array_fill(1, 7, 0);
    $totalWeek = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $day = (int)$row['weekday'];
        $rawData[$day] = (float)$row['total'];
        $totalWeek += $row['total'];
    }

    $result = [];
    foreach ($weekMap as $day => $label) {
        $amount = $rawData[$day] ?? 0;
        $percent = $totalWeek > 0 ? round(($amount / $totalWeek) * 100, 2) : 0;
        $result[] = [
            'label' => $label,
            'total' => $amount,
            'percent' => $percent
        ];
    }

    echo json_encode(['status' => true, 'data' => $result, 'total' => $totalWeek]);
    exit;
}
if ($received_data->post == 'get_dashboard_summary_by_month') {
    $months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $year = date('Y');

    $stmt = $connect->prepare("
        SELECT 
            MONTH(created_at) AS month, 
            SUM(total) AS total
        FROM 
            saleproducts
        WHERE 
            YEAR(created_at) = ?
        GROUP BY 
            MONTH(created_at)
    ");
    $stmt->execute([$year]);

    $rawData = array_fill(1, 12, 0);
    $totalYear = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthIndex = (int)$row['month'];
        $rawData[$monthIndex] = (float)$row['total'];
        $totalYear += $row['total'];
    }

    $result = [];
    foreach ($months as $i => $label) {
        $monthIndex = $i + 1;
        $amount = $rawData[$monthIndex] ?? 0;
        $percent = $totalYear > 0 ? round(($amount / $totalYear) * 100, 2) : 0;
        $result[] = [
            'label' => $label,
            'total' => $amount,
            'percent' => $percent
        ];
    }

    echo json_encode(['status' => true, 'data' => $result, 'total' => $totalYear]);
    exit;
}

// report day



if ($received_data->post == 'get_dashboard_summary_by_month') {
    $search = $_GET['keyword'] ?? ''; // รับคำค้นจาก query string เช่น ?keyword=น้ำปลา

    if ($search !== '') {
        $stmt = $pdo->prepare("
            SELECT ti.transfer_id, ti.product_id, ti.qty, p.name AS product_name, t.transfer_date
            FROM transfer_items ti
            JOIN products p ON p.id = ti.product_id
            JOIN transfers t ON t.id = ti.transfer_id
            WHERE p.name LIKE :search
            ORDER BY t.transfer_date DESC
        ");
        $stmt->execute([
            ':search' => "%$search%"
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


//employee
if ($received_data->post == 'add_employee') {
    $name = $received_data->name ?? '';
    $phone = $received_data->phone ?? '';
    $password = $received_data->password ?? '';
    $role_ids = $received_data->permissions ?? []; // array

    if (!$name || !$phone || !$password || empty($role_ids)) {
        echo json_encode(['status' => false, 'message' => 'กรอกข้อมูลไม่ครบ']);
        exit;
    }

    // hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // เริ่ม transaction
    $connect->beginTransaction();
    try {
        // insert employee
        $stmt = $connect->prepare("INSERT INTO employees (name, phone, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $phone, $hashed_password]);
        $employee_id = $connect->lastInsertId();

        // insert roles
        $stmt_role = $connect->prepare("INSERT INTO employee_permissions (employee_id, permission_id) VALUES (?, ?)");
        foreach ($role_ids as $role_id) {
            $stmt_role->execute([$employee_id, $role_id]);
        }

        $connect->commit();
        echo json_encode(['status' => true, 'message' => 'เพิ่มพนักงานเรียบร้อย']);
    } catch (Exception $e) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}

// API: ../api/index.php
if ($received_data->post == 'get_employee') {
    $stmt = $connect->query("
        SELECT 
            e.id, 
            e.name, 
            e.phone, 
            GROUP_CONCAT(r.name) AS permission,
            GROUP_CONCAT(el.role_id) AS role_ids
        FROM employees e
        LEFT JOIN employee_permissions er ON e.id = er.employee_id
        LEFT JOIN permissions r ON er.permission_id = r.id
        LEFT JOIN employee_roles el ON e.id = el.employee_id
        GROUP BY e.id
        ORDER BY e.id DESC
    ");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($employees as &$emp) {
        // แปลง role_ids เป็น array ของ int
        // $emp['roles'] = array_map('intval', explode(',', $emp['role_ids'] ?? ''));
        $emp['roles'] = !empty($emp['role_ids']) ? array_map('intval', explode(',', $emp['role_ids'])) : [];

        unset($emp['role_ids']); // ไม่ต้องส่ง field นี้กลับไป
    }

    echo json_encode(['status' => true, 'employees' => $employees]);
    exit;
}


//permission
if ($received_data->post == 'add_permission') {
    $name = $received_data->name ?? '';

    if (!$name) {
        echo json_encode(['status' => false, 'message' => 'กรุณากรอกชื่อสิทธิ์']);
        exit;
    }
    $stmt = $connect->prepare("SELECT COUNT(*) FROM permissions WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['status' => false, 'message' => 'สิทธิ์นี้มีอยู่แล้ว']);
        exit;
    }

    $stmt = $connect->prepare("INSERT INTO permissions (name) VALUES (?)");
    $success = $stmt->execute([$name]);

    if ($success) {
        echo json_encode(['status' => true, 'message' => 'เพิ่มสิทธิ์สำเร็จ']);
    } else {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }
}
if ($received_data->post == 'get_permission') {
    try {
        $stmt = $connect->prepare("SELECT id, name FROM permissions");
        $stmt->execute();
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => true,
            'permissions' => $permissions
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ]);
    }
}
if ($received_data->post == 'get_permission_emp') {
    $employee_id = $received_data->employee_id ?? null;

    if ($employee_id) {
        $stmt = $connect->prepare("
            SELECT permission_id 
            FROM employee_permissions 
            WHERE employee_id = ?
        ");
        $stmt->execute([$employee_id]);
        $permission_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode([
            'status' => true,
            'permissions' => array_map('intval', $permission_ids)
        ]);
    } else {
        echo json_encode(['status' => false, 'message' => 'ไม่พบ employee_id']);
    }
}

if ($received_data->post == 'add_employee_role') {
    $role_ids = $received_data->role ?? [];
    $employee_id = $received_data->employee_id ?? null;

    try {
        $connect->beginTransaction(); // เริ่ม transaction

        $stmt_role = $connect->prepare("INSERT INTO employee_roles (employee_id, role_id) VALUES (?, ?)");
        $stmt_clear = $connect->prepare("DELETE FROM employee_roles WHERE employee_id = ?");
        $stmt_clear->execute([$employee_id]);
        foreach ($role_ids as $role_id) {
            $stmt_role->execute([$employee_id, $role_id]);
        }

        $connect->commit(); // สำเร็จทั้งหมด


        echo json_encode(['status' => true, 'message' => 'เพิ่มสิทธิ์พนักงานเรียบร้อย']);
    } catch (Exception $e) {
        $connect->rollBack(); // ยกเลิกการเพิ่มทั้งหมด
        echo json_encode([
            'status' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ]);
    }
}


if ($received_data->post == 'get_role') {
    try {
        $stmt = $connect->prepare("SELECT id, role_name FROM roles");
        $stmt->execute();
        $role = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => true,
            'role' => $role
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ]);
    }
}
if ($received_data->post == 'add_role') {
    $name = $received_data->name ?? '';

    if (!$name) {
        echo json_encode(['status' => false, 'message' => 'กรุณากรอกชื่อสิทธิ์']);
        exit;
    }

    $stmt = $connect->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $success = $stmt->execute([$name]);

    if ($success) {
        echo json_encode(['status' => true, 'message' => 'เพิ่มสิทธิ์สำเร็จ']);
    } else {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด']);
    }
}


// ล้างตะกร้า (clear_cart)
if ($received_data->post == 'clear_cart') {
    $warehouse_id = $received_data->warehouse_id ?? null;

    if (!$warehouse_id) {
        echo json_encode(['status' => false, 'message' => 'warehouse_id หายไป']);
        exit;
    }

    try {
        $stmt = $connect->prepare("DELETE FROM cart WHERE warehouse_id = ? AND status = 'pending'");
        $stmt->execute([(int)$warehouse_id]);

        $deleted = $stmt->rowCount();
        echo json_encode(['status' => true, 'message' => "ล้างตะกร้าเรียบร้อย", 'deleted' => $deleted]);
    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
    exit;
}

// ลบสินค้าในตะกร้า (remove_item)
if ($received_data->post == 'remove_item') {
    $product_id = $received_data->product_id ?? null;
    // คำแนะนำ: ถ้ามี cart_id ใช้ cart_id ดีกว่า ปลอดภัยกว่า
    $cart_id = $received_data->cart_id ?? null;

    try {
        if ($cart_id) {
            $stmt = $connect->prepare("DELETE FROM cart WHERE id = ? AND status = 'pending'");
            $stmt->execute([(int)$cart_id]);
        } elseif ($product_id) {
            // ระวัง: ถ้าใช้ product_id จะลบทุกแถวที่มี product_id เดียวกัน (อาจจะลบของคนอื่นด้วย)
            $stmt = $connect->prepare("DELETE FROM cart WHERE product_id = ? AND status = 'pending'");
            $stmt->execute([(int)$product_id]);
        } else {
            echo json_encode(['status' => false, 'message' => 'ต้องส่ง product_id หรือ cart_id']);
            exit;
        }

        $deleted = $stmt->rowCount();
        echo json_encode(['status' => true, 'message' => 'ลบสินค้าแล้ว', 'deleted' => $deleted]);
    } catch (PDOException $e) {
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
    exit;
}

if ($received_data->post == 'update_qty') {
    $product_id = $received_data->product_id;
    $qty = $received_data->qty;
    
    echo json_encode(['status' => true, 'message' => 'อัปเดตจำนวนแล้ว']);
}