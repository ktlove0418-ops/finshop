<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
include('connect.php');

$received_data = json_decode(file_get_contents("php://input"));
$data = array();
@session_start();

$version = 0.1;
$post = $received_data->post ?? $_POST['post'];
$get = $received_data->get;
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
                if ($dataLogin['position'] === 'owner') {
                    $redirect = 'owner';
                } else if ($dataLogin['position'] === 'store') {
                    $redirect = 'store';
                } else if ($dataLogin['position'] === 'sale') {
                    $redirect = 'sale';
                } else if ($dataLogin['position'] === 'truck') {
                    $redirect = 'truck';
                }
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

        $query = "SELECT e.*,ep.permission_id FROM employees e
        JOIN employee_permissions ep ON ep.employee_id = e.id
        WHERE e.phone = :username ORDER BY e.id DESC";
        $statement = $connect->prepare($query);
        $statement->bindParam(':username', $received_data->username);
        $statement->execute();
        $dataLogin = $statement->fetch(PDO::FETCH_ASSOC);
        $data = array();

        if ($dataLogin) {
            $hash_b = $dataLogin['password'];
            $pass_w = password_verify($received_data->password, $hash_b);

            if ($pass_w) {
                $redirect = '';
                // print_r($dataLogin['permission_id']); exit;
                if ($dataLogin['permission_id'] == 2) {
                    $redirect = 'store';
                } else if ($dataLogin['permission_id'] == 1) {
                    $redirect = 'sale';
                } else if ($dataLogin['permission_id'] == 3) {
                    $redirect = 'truck';
                }
                @session_start();
                $_SESSION['fin_position'] = $redirect;
                $_SESSION['fin_username'] = $dataLogin['name'];

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
        } else {
            $data = array(
                'msg' => 'ชื่อเข้าใช้งานไม่ถูกต้อง',
                'code' => 203,
                'status' => false,
                'start_date' => date('Y-m-d H:i:s')
            );
        }
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
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// print_r($post);
if ($post == 'get_warehouses_fproduct') {
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
    $stock_qty = $_POST['stock_qty'];
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
        $unit = $stock_qty;
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
    $stock_qty = $_POST['stock_qty'];
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
            $unit = $stock_qty;  // หรือกำหนดเป็น 0 แล้วแต่ลอจิก
            $max = 999;
            $insertStmt->execute([$whId, $product_id, $unit, $price, $max]);
        }
    }
    echo json_encode(['status' => true]);
}

/*
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
*/

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
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN barcodes b ON p.id = b.product_id
            WHERE 
                REPLACE(REPLACE(REPLACE(p.warehouses_id, ' ', ''), '\"', ''), '[', '') 
                LIKE ?
            ORDER BY p.created_at DESC";

    $search = '%' . $warehouses_id . '%';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($products); exit;
    // 🔎 สรุปจำนวนสินค้าจากตาราง pd_in_whs
    $sql_summary = "SELECT COUNT(*) AS total_products,SUM(CASE WHEN unit < 50 AND unit > 0 THEN 1 ELSE 0 END) AS low_stock,SUM(CASE WHEN unit = 0 THEN 1 ELSE 0 END) AS out_of_stock FROM pd_in_whs WHERE warehouses_id = :warehouses_id AND prooduct_id = :pid";
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

if ($received_data->post === 'upsert_product_in_wh') {

    $warehouses_id = (int)($received_data->warehouses_id ?? 0);
    $product_id    = (int)($received_data->product_id ?? 0);
    $delta_unit    = (int)($received_data->delta_unit ?? 0);
  
    $new_price = isset($received_data->price) ? (int)$received_data->price : null;
    $new_max   = isset($received_data->max)   ? (int)$received_data->max   : null;
  
    $user_id   = $received_data->user_id ?? null;
    $user_role = $received_data->user_role ?? null;
    $note      = $received_data->note ?? null;
  
    if ($warehouses_id <= 0 || $product_id <= 0 || $delta_unit === 0) {
      echo json_encode(['status' => false, 'message' => 'missing warehouses_id/product_id/delta_unit']);
      exit;
    }
  
    try {
      $connect->beginTransaction();
  
      // 1) ตรวจว่ามีสินค้าแม่จริงไหม
      $chk = $connect->prepare("SELECT id FROM products WHERE id = ? LIMIT 1");
      $chk->execute([$product_id]);
      if (!$chk->fetch()) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'product not found in master products']);
        exit;
      }
  
      // 2) ล็อกแถวใน pd_in_whs ถ้ามีอยู่
      $stmt = $connect->prepare("
        SELECT id, unit, price, max
        FROM pd_in_whs
        WHERE warehouses_id = ? AND prooduct_id = ?
        LIMIT 1
        FOR UPDATE
      ");
      $stmt->execute([$warehouses_id, $product_id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (!$row) {
        // 2.1 ยังไม่มีในคลังนี้ => INSERT ใหม่
        $unit = max(0, $delta_unit); // ถ้ารับค่าเป็นลบตอนยังไม่มี แนะนำกันไว้
        $price = $new_price ?? 0;
        $max = $new_max ?? 999;
  
        $ins = $connect->prepare("
          INSERT INTO pd_in_whs (warehouses_id, prooduct_id, unit, price, max, created_at)
          VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $ins->execute([$warehouses_id, $product_id, $unit, $price, $max]);
  
        $pd_in_whs_id = (int)$connect->lastInsertId();
  
        // log: สร้างสินค้าเข้าคลัง
        $lg = $connect->prepare("
          INSERT INTO pd_in_wh_logs
          (pd_in_whs_id, warehouses_id, action_type, old_unit, new_unit, diff_unit, old_price, new_price, old_max, new_max, note, user_id, user_role, created_at)
          VALUES
          (?, ?, 'CREATE_IN_WH', 0, ?, ?, NULL, ?, NULL, ?, ?, ?, ?, NOW())
        ");
        $lg->execute([
          $pd_in_whs_id,
          $warehouses_id,
          $unit,
          $unit,               // diff = unit
          $price,
          $max,
          $note,
          $user_id,
          $user_role
        ]);
  
        $connect->commit();
        echo json_encode(['status' => true, 'pd_in_whs_id' => $pd_in_whs_id, 'mode' => 'insert']);
        exit;
      }
  
      // 3) มีอยู่แล้ว => UPDATE (ไม่ซ้ำ)
      $pd_in_whs_id = (int)$row['id'];
      $old_unit  = (int)$row['unit'];
      $old_price = (int)$row['price'];
      $old_max   = (int)$row['max'];
  
      $new_unit = $old_unit + $delta_unit;
      if ($new_unit < 0) $new_unit = 0;
  
      $final_price = ($new_price !== null) ? $new_price : $old_price;
      $final_max   = ($new_max !== null) ? $new_max : $old_max;
  
      $upd = $connect->prepare("
        UPDATE pd_in_whs
        SET unit = ?, price = ?, max = ?
        WHERE id = ? AND warehouses_id = ?
      ");
      $upd->execute([$new_unit, $final_price, $final_max, $pd_in_whs_id, $warehouses_id]);
  
      // 4) log เฉพาะที่เปลี่ยน
      $insertLog = $connect->prepare("
        INSERT INTO pd_in_wh_logs
        (pd_in_whs_id, warehouses_id, action_type, old_unit, new_unit, diff_unit, old_price, new_price, old_max, new_max, note, user_id, user_role, created_at)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
      ");
  
      if ($new_unit !== $old_unit) {
        $diff = $new_unit - $old_unit;
        $action = ($diff > 0) ? 'INCREASE_UNIT' : 'DECREASE_UNIT';
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, $action, $old_unit, $new_unit, $diff, null, null, null, null, $note, $user_id, $user_role]);
      }
      if ($final_price !== $old_price) {
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, 'UPDATE_PRICE', null, null, null, $old_price, $final_price, null, null, $note, $user_id, $user_role]);
      }
      if ($final_max !== $old_max) {
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, 'SET_MAX', null, null, null, null, null, $old_max, $final_max, $note, $user_id, $user_role]);
      }
  
      $connect->commit();
      echo json_encode(['status' => true, 'pd_in_whs_id' => $pd_in_whs_id, 'mode' => 'update']);
      exit;
  
    } catch (Exception $e) {
      if ($connect->inTransaction()) $connect->rollBack();
      echo json_encode(['status' => false, 'message' => $e->getMessage()]);
      exit;
    }
  }
  if ($received_data->post === 'get_products_master') {
    $stmt = $connect->prepare("
      SELECT p.id, p.name AS product_name, p.category_id, c.cate_name AS category_name, p.image, p.description, p.price, p.quantity
      FROM products p
      LEFT JOIN categories c ON p.category_id = c.id
      ORDER BY p.id DESC
    ");
    $stmt->execute();
  
    echo json_encode(['status' => true, 'products' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
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
        $html_items .= $index++ . ": $product_name | จำนวน: $qty หน่วย<br>";

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
            <b>รายการ:สินค้า</b><br>
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
}


//ดึงรายการเอกสารทั้งหมด
if ($received_data->post == 'get_transfer_documents') {
    // รับพารามิเตอร์
    $keyword    = isset($received_data->keyword) ? trim($received_data->keyword) : '';
    $startDate  = isset($received_data->start_date) ? trim($received_data->start_date) : '';
    $endDate    = isset($received_data->end_date) ? trim($received_data->end_date) : '';
    $month      = isset($received_data->month) ? trim($received_data->month) : '';
    $page       = isset($received_data->page) ? (int)$received_data->page : 0;        // 1-based
    $perPage    = isset($received_data->per_page) ? (int)$received_data->per_page : 0; // 0 = ไม่แบ่งหน้า

    // แปลง month -> ช่วงวันที่
    if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
        // วันแรกของเดือน
        $startDate = $month . '-01';
        // วันสุดท้ายของเดือน (ใช้ PHP คำนวณ)
        $dt = DateTime::createFromFormat('Y-m-d', $startDate);
        if ($dt !== false) {
            $endDate = $dt->format('Y-m-t'); // last day of month
        }
    }

    // สร้าง WHERE เงื่อนไขแบบไดนามิก
    $where = [];
    $params = [];

    if ($keyword !== '') {
        $where[] = "(d.document_no LIKE :kw 
                  OR p.name LIKE :kw 
                  OR fw.name LIKE :kw 
                  OR tw.name LIKE :kw)";
        $params[':kw'] = '%' . $keyword . '%';
    }

    // กรองวันที่: ใช้ created_at แบบ datetime
    if ($startDate !== '') {
        // เริ่มต้นวัน
        $where[] = "d.created_at >= :startDate";
        $params[':startDate'] = $startDate . ' 00:00:00';
    }
    if ($endDate !== '') {
        // ปลายวัน
        $where[] = "d.created_at <= :endDate";
        $params[':endDate'] = $endDate . ' 23:59:59';
    }

    // ประกอบ SQL
    $baseSql = "
        FROM transfer_documents d
        JOIN products p   ON d.product_id = p.id
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
    ";

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // นับ total (เพื่อ pagination)
    $countSql = "SELECT COUNT(*) AS total " . $baseSql . ' ' . $whereSql;
    $stmtCount = $connect->prepare($countSql);
    foreach ($params as $k => $v) $stmtCount->bindValue($k, $v);
    $stmtCount->execute();
    $total = (int)$stmtCount->fetchColumn();

    // ดึงรายการจริง
    $selectSql = "SELECT d.*, 
                         p.name AS product_name, 
                         fw.name AS from_name, 
                         tw.name AS to_name
                  " . $baseSql . ' ' . $whereSql . "
                  ORDER BY d.created_at DESC
    ";

    // แบ่งหน้า (optional)
    if ($perPage > 0 && $page > 0) {
        $offset = ($page - 1) * $perPage;
        $selectSql .= " LIMIT :limit OFFSET :offset";
    }

    $stmt = $connect->prepare($selectSql);
    // bind ค่าปกติ
    foreach ($params as $k => $v) $stmt->bindValue($k, $v);

    // bind limit/offset เป็น int
    if ($perPage > 0 && $page > 0) {
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'    => true,
        'documents' => $data,
        'total'     => $total,
        'page'      => $page,
        'per_page'  => $perPage
    ]);
}

// ==============================
// ดึงรายละเอียดเอกสาร + รายการสินค้า
// ==============================
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// ==============================
// ดึงรายละเอียดเอกสาร + รายการสินค้า (ไม่มี transfer_document_items)
// ==============================
if ($received_data->post == 'get_transfer_document_detail') {

    if (!isset($received_data->id)) {
        echo json_encode([
            'status'  => false,
            'message' => 'ไม่พบรหัสเอกสาร (id)'
        ]);
        exit;
    }

    $docId = (int)$received_data->id;

    // 1) ดึง header (ใช้ id แถวใดก็ได้ในเอกสาร)
    $sqlHeader = "
        SELECT d.*,
               fw.name AS from_name,
               tw.name AS to_name
        FROM transfer_documents d
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
        WHERE d.id = :id
        LIMIT 1
    ";
    $stmtH = $connect->prepare($sqlHeader);
    $stmtH->bindValue(':id', $docId, PDO::PARAM_INT);
    $stmtH->execute();
    $header = $stmtH->fetch(PDO::FETCH_ASSOC);

    if (!$header) {
        echo json_encode([
            'status'  => false,
            'message' => 'ไม่พบเอกสารที่ต้องการแก้ไข'
        ]);
        exit;
    }

    $doc_no        = $header['document_no'];
    $fromWh        = (int)$header['from_warehouse'];
    $toWh          = (int)$header['to_warehouse'];

    // 2) ดึงรายการสินค้า ทั้งหมดของเอกสารนี้ (ใช้ doc_no เดียวกัน)
    $sqlItems = "
        SELECT d.id,
               d.product_id,
               d.qty,
               p.name AS product_name
        FROM transfer_documents d
        JOIN products p ON d.product_id = p.id
        WHERE d.document_no = :doc_no
        ORDER BY d.id ASC
    ";
    $stmtI = $connect->prepare($sqlItems);
    $stmtI->bindValue(':doc_no', $doc_no);
    $stmtI->execute();
    $items = $stmtI->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status'   => true,
        'document' => [
            'id'             => $header['id'],             // id แถวที่ใช้เป็นตัวแทน
            'document_no'    => $header['document_no'],
            'from_warehouse' => $fromWh,
            'to_warehouse'   => $toWh,
            'from_name'      => $header['from_name'],
            'to_name'        => $header['to_name'],
            'file_name'      => $header['file_name'],
            'created_at'     => $header['created_at'],
        ],
        'items'    => $items
    ]);
}

// =========================
// อัปเดตเอกสารโอนสินค้า
// =========================
if ($received_data->post == 'update_transfer_document') {

    $documentId = isset($received_data->document_id) ? (int)$received_data->document_id : 0;
    $from       = $received_data->from ?? null;
    $to         = $received_data->to ?? null;
    $items      = $received_data->items ?? [];

    if (!$documentId || !$from || !$to || !is_array($items) || empty($items)) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    try {
        $connect->beginTransaction();

        // --- 1. หาเอกสารเดิมจาก id เพื่อเอา doc_no, from_warehouse, to_warehouse, file_name ---
        $stmt = $connect->prepare("SELECT * FROM transfer_documents WHERE id = ?");
        $stmt->execute([$documentId]);
        $docRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$docRow) {
            $connect->rollBack();
            echo json_encode(['status' => false, 'message' => 'ไม่พบเอกสารที่ต้องการแก้ไข']);
            exit;
        }

        $doc_no        = $docRow['document_no'];
        $old_from      = $docRow['from_warehouse'];
        $old_to        = $docRow['to_warehouse'];
        $filename      = $docRow['file_name']; // path HTML เดิม
        $transfer_date = date("d/m/Y H:i");    // วันที่อัปเดต

        // --- 2. ดึงทุกรายการสินค้าเดิมของเอกสารนี้ (ใช้ doc_no เดียวกัน) ---
        $stmt = $connect->prepare("SELECT * FROM transfer_documents WHERE document_no = ?");
        $stmt->execute([$doc_no]);
        $oldItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // --- 3. ย้อนสต็อกจากรายการเดิม (คืนของให้คลังเก่า) ---
        foreach ($oldItems as $old) {
            $product_id = (int)$old['product_id'];
            $qty        = (int)$old['qty'];

            if ($qty <= 0 || !$product_id) continue;

            // 3.1 เพิ่มกลับเข้า old_from
            $stmt = $connect->prepare("UPDATE pd_in_whs 
                                       SET unit = unit + ? 
                                       WHERE warehouses_id = ? AND prooduct_id = ?");
            $stmt->execute([$qty, $old_from, $product_id]);

            // 3.2 ตัดออกจาก old_to
            $stmt = $connect->prepare("SELECT unit FROM pd_in_whs 
                                       WHERE warehouses_id = ? AND prooduct_id = ?");
            $stmt->execute([$old_to, $product_id]);
            $stockTo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$stockTo || $stockTo['unit'] < $qty) {
                // สต็อกปลายทางไม่พอสำหรับย้อนกลับ แสดงว่า data เพี้ยน
                $connect->rollBack();
                echo json_encode(['status' => false, 'message' => "ไม่สามารถย้อนสต็อกเก่าของสินค้า ID: $product_id ได้ (สต็อกปลายทางไม่เพียงพอ)"]);
                exit;
            }

            $stmt = $connect->prepare("UPDATE pd_in_whs 
                                       SET unit = unit - ? 
                                       WHERE warehouses_id = ? AND prooduct_id = ?");
            $stmt->execute([$qty, $old_to, $product_id]);
        }

        // --- 4. ลบรายการเก่าใน transfer_documents ของ doc_no นี้ทิ้ง ---
        $stmt = $connect->prepare("DELETE FROM transfer_documents WHERE document_no = ?");
        $stmt->execute([$doc_no]);

        // --- 5. ดึงชื่อคลังใหม่ (จาก / ไป) ---
        $from_name = $connect->prepare("SELECT name FROM warehouses WHERE id=?");
        $from_name->execute([$from]);
        $from_name = $from_name->fetchColumn();

        $to_name = $connect->prepare("SELECT name FROM warehouses WHERE id=?");
        $to_name->execute([$to]);
        $to_name = $to_name->fetchColumn();

        // --- 6. ทำรายการโอนใหม่ตาม items ที่ส่งมา ---
        $html_items = "";
        $index = 1;

        foreach ($items as $item) {
            $product_id = $item->product_id ?? null;
            $qty        = (int)($item->qty ?? 0);

            if (!$product_id || $qty <= 0) continue;

            // 6.1 ตรวจสอบสินค้าในต้นทางใหม่
            $stmt = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$from, $product_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row || $row['unit'] < $qty) {
                $connect->rollBack();
                echo json_encode(['status' => false, 'message' => "สินค้า ID: $product_id ในคลังต้นทางไม่เพียงพอ"]);
                exit;
            }

            // 6.2 หักจากต้นทางใหม่
            $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = unit - ? WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$qty, $from, $product_id]);

            // 6.3 เพิ่มเข้าคลังปลายทางใหม่
            $stmt = $connect->prepare("SELECT * FROM pd_in_whs WHERE warehouses_id=? AND prooduct_id=?");
            $stmt->execute([$to, $product_id]);

            if ($stmt->rowCount()) {
                $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = unit + ? WHERE warehouses_id=? AND prooduct_id=?");
                $stmt->execute([$qty, $to, $product_id]);
            } else {
                $price      = $row['price'];
                $max        = 999;
                $created_at = date('Y-m-d H:i:s');
                $stmt = $connect->prepare("INSERT INTO pd_in_whs (warehouses_id, prooduct_id, unit, price, max, created_at) 
                                          VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$to, $product_id, $qty, $price, $max, $created_at]);
            }

            // 6.4 อัปเดต warehouses_id ใน products (ให้รู้ว่ามีอยู่ในคลังปลายทางนี้แล้ว)
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

            // 6.5 สร้าง HTML list
            $stmt = $connect->prepare("SELECT name FROM products WHERE id=?");
            $stmt->execute([$product_id]);
            $product_name = $stmt->fetchColumn();
            $html_items .= $index++ . ": $product_name | จำนวน: $qty หน่วย<br>";

            // 6.6 บันทึกเอกสารการโอนแต่ละรายการใหม่ (ใช้ doc_no เดิม, file_name เดิม)
            $stmt = $connect->prepare("INSERT INTO transfer_documents 
                (document_no, file_name, from_warehouse, to_warehouse, product_id, qty) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$doc_no, $filename, $from, $to, $product_id, $qty]);
        }

        // ถ้าไม่เหลือรายการเลย ก็ revert กลับ
        if ($html_items === "") {
            $connect->rollBack();
            echo json_encode(['status' => false, 'message' => 'ไม่มีรายการสินค้าใหม่ให้บันทึก']);
            exit;
        }

        // --- 7. เขียน HTML ทับไฟล์เดิม ---
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
                <h2>ใบโอนสินค้าออก (แก้ไข)</h2>
                เลขที่เอกสาร: $doc_no<br>
                แก้ไขล่าสุด: $transfer_date<br>
                จากคลัง: $from_name → ไปยัง: $to_name<br><br>
                <hr>
                <b>รายการ:สินค้า</b><br>
                $html_items
            </body>
            </html>
        ";

        // ใช้ชื่อไฟล์เดิม ถ้ายังไม่มีโฟลเดอร์ก็สร้างให้เหมือนเดิม
        if (!file_exists('uploads/transfer_docs')) mkdir('uploads/transfer_docs', 0777, true);
        file_put_contents($filename, $html);

        $connect->commit();

        echo json_encode([
            'status'    => true,
            'message'   => 'อัปเดตการโอนสินค้าสำเร็จ',
            'doc_url'   => $filename,
            'doc_no'    => $doc_no,
            'from_name' => $from_name,
            'to_name'   => $to_name
        ]);
    } catch (Exception $e) {
        if ($connect->inTransaction()) {
            $connect->rollBack();
        }
        echo json_encode([
            'status'  => false,
            'message' => 'เกิดข้อผิดพลาดระหว่างอัปเดตเอกสาร: ' . $e->getMessage()
        ]);
    }
}
if ($received_data->post == 'add_to_cart') {
    session_start();

    $pos  = $_SESSION['fin_position'] ?? '';
    $user = $_SESSION['fin_username'] ?? '';
    $user_id = trim($pos . ' ' . $user);
    if ($user_id === '') $user_id = 'unknown';

    $product_id   = (int)($received_data->product_id ?? 0);
    $warehouse_id = (int)($received_data->warehouse_id ?? 0);
    $qty          = (int)($received_data->qty ?? 1);

    if ($product_id <= 0 || $warehouse_id <= 0 || $qty < 1) {
        echo json_encode(['status' => false, 'message' => 'ข้อมูลไม่ครบ']);
        exit;
    }

    // 1) เช็ค stock ในคลัง
    $stmt = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ? LIMIT 1");
    $stmt->execute([$warehouse_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบข้อมูลในคลัง']);
        exit;
    }

    $availableUnit = (int)$row['unit'];
    if ($availableUnit < $qty) {
        echo json_encode(['status' => false, 'message' => 'สินค้ามีจำนวนไม่เพียงพอ']);
        exit;
    }

    // 2) มีใน cart ไหม
    $stmt = $connect->prepare("SELECT id, qty FROM cart WHERE product_id = ? AND warehouse_id = ? AND status = 'pending' LIMIT 1");
    $stmt->execute([$product_id, $warehouse_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $stmt = $connect->prepare("UPDATE cart SET qty = qty + ? WHERE id = ?");
        $stmt->execute([$qty, $existing['id']]);
    } else {
        $stmt = $connect->prepare("INSERT INTO cart (user_id, product_id, warehouse_id, qty, status, created_at)
                                   VALUES (?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([$user_id, $product_id, $warehouse_id, $qty]);
    }

    // 3) ดึง cart กลับ
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

    echo json_encode(['status' => true, 'cart' => $cart]);
    exit;
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
    $cart = array_map(function ($row) {
        $row['id']          = (int) $row['id'];
        $row['product_id']  = (int) $row['product_id'];
        $row['product_name']  = (string) $row['product_name'];
        $row['image']  = (string) $row['image'];
        $row['price']       = isset($row['price']) ? (float) $row['price'] : 0.0;
        $row['qty']         = isset($row['qty']) ? (int) $row['qty'] : 0;
        $row['total_price'] = isset($row['total_price']) ? (float) $row['total_price'] : 0.0;
        return $row;
    }, $cart);
    echo json_encode([
        'status' => true,
        'cart' => $cart
    ]);
}

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

    // บังคับให้เป็น number
    $cart = array_map(function ($row) {
        $row['id']          = (int) $row['id'];
        $row['product_id']  = (int) $row['product_id'];
        $row['product_name']  = (string) $row['product_name'];
        $row['image']  = (string) $row['image'];
        $row['price']       = isset($row['price']) ? (float) $row['price'] : 0.0;
        $row['qty']         = isset($row['qty']) ? (int) $row['qty'] : 0;
        $row['total_price'] = isset($row['total_price']) ? (float) $row['total_price'] : 0.0;
        return $row;
    }, $cart);

    echo json_encode([
        'status' => true,
        'cart'   => $cart,
    ], JSON_UNESCAPED_UNICODE);
}


if ($received_data->post == 'generate_receipt_html') {
    $cart = $received_data->cart ?? [];
    $total = (float)($received_data->total ?? 0);
    $received = (float)($received_data->received ?? 0);
    $warehouse_name = $received_data->warehouse_name ?? '';
    $cash = $received_data->cash ?? '';
    $change = $received - $total;
    $warehouse_id = $received_data->warehouse_id ?? 'unknown';

    @session_start();
    $person = '<b>' . ($_SESSION['fin_position'] ?? '') . '</b> ' . ($_SESSION['fin_username'] ?? '');

    if (empty($cart)) {
        echo json_encode(['status' => false, 'message' => 'ไม่มีข้อมูลสินค้า']);
        exit;
    }

    // 🔹 สร้างเลขที่ใบเสร็จ
    $receipt_id = 'RC' . date('YmdHis');
    $file_name = $receipt_id . '.html';
    $folder_path = __DIR__ . '/../receipts/';
    if (!file_exists($folder_path)) {
        mkdir($folder_path, 0777, true);
    }

    // 🔹 สร้าง HTML
    $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>ใบเสร็จ</title>';
    $html .= '<style>body{font-family:sans-serif;padding:20px;border: solid 1px #ccc;}table{width:100%;border-collapse:collapse;}td,th{padding:5px;text-align:left;}</style>';
    $html .= '</head><body>';
    $html .= "<h2 style='text-align: right;'>🧾 ใบเสร็จรับเงิน</h2>";
    $html .= "<div style='text-align: right;'>เลขที่: $receipt_id</div>";
    $html .= "<div style='text-align: right;'>วันที่: " . date('Y-m-d H:i:s') . "</div>";
    $html .= "<p>สาขา: $warehouse_name</p>";

    $html .= "<table><thead>
                <tr>
                    <th>สินค้า</th>
                    <th style='text-align:right'>จำนวน</th>
                    <th style='text-align:right'>ราคา/หน่วย</th>
                    <th style='text-align:right'>ส่วนลด/หน่วย</th>
                    <th style='text-align:right'>ราคาสุทธิ/หน่วย</th>
                    <th style='text-align:right'>รวม</th>
                </tr>
              </thead><tbody>";

    foreach ($cart as $item) {
        $product = htmlspecialchars($item->product_name);
        $qty = (int)$item->qty;
        $price_per_unit = number_format((float)$item->price, 2);
        $discount_per_unit = number_format((float)$item->discount_per_unit, 2);
        $final_price_per_unit = number_format((float)$item->final_price_per_unit, 2);
        $total_item = number_format((float)$item->total_price_item, 2);

        $html .= "<tr>
                    <td>$product</td>
                    <td style='text-align:right'>x $qty</td>
                    <td style='text-align:right'>$price_per_unit</td>
                    <td style='text-align:right'>$discount_per_unit</td>
                    <td style='text-align:right'>$final_price_per_unit</td>
                    <td style='text-align:right'>$total_item</td>
                  </tr>";
    }

    $html .= "</tbody><tfoot>";
    $html .= "<tr style='border-top: solid 8px #e3e0e0;'><td colspan='5'><b>รวมทั้งหมด</b></td><td style='text-align:right'>" . number_format($total, 2) . "</td></tr>";
    $html .= "<tr><td colspan='5'><b>เงินที่รับมา</b></td><td style='text-align:right'>" . $cash . ' ' . number_format($received, 2) . "</td></tr>";
    $html .= "<tr><td colspan='5'><b>เงินทอน</b></td><td style='text-align:right'>" . number_format($change, 2) . "</td></tr>";
    $html .= "</tfoot></table>";
    $html .= "<p style='text-align: right;'><b>Emp</b>: " . $person . "</p>";
    $html .= '</body></html>';

    // 🔹 บันทึกไฟล์ HTML
    file_put_contents($folder_path . $file_name, $html);

    // 🔹 บันทึกลง DB
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
    $receipt_db_id = $connect->lastInsertId();

    // 🔹 บันทึกรายการสินค้า
    $stmt_item = $connect->prepare("
        INSERT INTO receipt_items (receipt_id, product_id, product_name, qty, price, discount_per_unit, final_price_per_unit, total, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    foreach ($cart as $item) {
        $stmt_item->execute([
            $receipt_db_id,
            $item->product_id,
            $item->product_name,
            $item->qty,
            $item->price,
            $item->discount_per_unit,
            $item->final_price_per_unit,
            $item->total_price_item
        ]);
    }

    // 🔹 ลบจาก cart และบันทึกขาย + อัปเดตสต๊อก
    $stmt_insert_sale = $connect->prepare("
        INSERT INTO saleproducts (warehouse_id, product_id, qty, price, total, receipt_id, person, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt_get_unit = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?");
    $stmt_update_unit = $connect->prepare("UPDATE pd_in_whs SET unit = ? WHERE warehouses_id = ? AND prooduct_id = ?");

    foreach ($cart as $item) {
        $total_item = (float)$item->total_price_item;
        $stmt_insert_sale->execute([
            $warehouse_id,
            $item->product_id,
            $item->qty,
            $item->final_price_per_unit,
            $total_item,
            $receipt_db_id,
            $person
        ]);

        // อัปเดตสต๊อก
        $stmt_get_unit->execute([$warehouse_id, $item->product_id]);
        $row = $stmt_get_unit->fetch(PDO::FETCH_ASSOC);
        $current_unit = (int)($row['unit'] ?? 0);
        $new_unit = max($current_unit - $item->qty, 0);
        $stmt_update_unit->execute([$new_unit, $warehouse_id, $item->product_id]);
    }

    // ลบสินค้าที่ขายแล้วออกจากตะกร้า
    $stmt_delete_cart = $connect->prepare("DELETE FROM cart WHERE warehouse_id = ? AND status = 'pending'");
    $stmt_delete_cart->execute([$warehouse_id]);

    echo json_encode([
        'status' => true,
        'message' => 'สร้างใบเสร็จและบันทึกข้อมูลสำเร็จ',
        'file_name' => $file_name,
        'receipt_url' => 'receipts/' . $file_name
    ]);
}


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($received_data->post == 'cancel_receipt') {
    $receipt_id = $received_data->receipt_id ?? null; // id ในตาราง receipts
    $reason = $received_data->reason ?? 'ไม่ระบุสาเหตุ';

    if (!$receipt_id) {
        echo json_encode(['status' => false, 'message' => 'ไม่พบรหัสใบเสร็จ']);
        exit;
    }

    @session_start();
    $person = '<b>' . ($_SESSION['fin_position'] ?? '') . '</b> ' . ($_SESSION['fin_username'] ?? '');

    try {
        $connect->beginTransaction();

        // 1) ดึงข้อมูลใบเสร็จ
        $stmt = $connect->prepare("SELECT * FROM receipts WHERE id = ? ");
        $stmt->execute([$receipt_id]);
        $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$receipt) {
            echo json_encode(['status' => false, 'message' => 'ไม่พบใบเสร็จหรือถูกยกเลิกไปแล้ว']);
            exit;
        }

        // 2) ดึงสินค้าที่ขายไป
        $stmt_items = $connect->prepare("SELECT * FROM receipt_items WHERE receipt_id = ?");
        $stmt_items->execute([$receipt_id]);
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // 3) คืนสต๊อก
        $stmt_get_unit = $connect->prepare("SELECT unit FROM pd_in_whs WHERE warehouses_id = ? AND prooduct_id = ?");
        $stmt_update_unit = $connect->prepare("UPDATE pd_in_whs SET unit = ? WHERE warehouses_id = ? AND prooduct_id = ?");

        foreach ($items as $item) {
            $stmt_get_unit->execute([$receipt['warehouse_id'], $item['product_id']]);
            $row = $stmt_get_unit->fetch(PDO::FETCH_ASSOC);
            $current_unit = (int)($row['unit'] ?? 0);
            $new_unit = $current_unit + (int)$item['qty'];

            $stmt_update_unit->execute([$new_unit, $receipt['warehouse_id'], $item['product_id']]);
        }

        // 4) อัปเดตสถานะใบเสร็จ
        $stmt_cancel = $connect->prepare("
            UPDATE receipts 
            SET status = 'canceled', created_at = NOW()
            WHERE id = ?
        ");
        $stmt_cancel->execute([$receipt_id]);

        // 5) ลบ/mark รายการขาย (ถ้าต้องการเก็บ log ให้ UPDATE แทน DELETE)
        $stmt_sale = $connect->prepare("UPDATE saleproducts SET status = 'canceled' WHERE receipt_id = ?");
        $stmt_sale->execute([$receipt_id]);

        $connect->commit();

        echo json_encode([
            'status' => true,
            'message' => 'ยกเลิกใบเสร็จเรียบร้อย',
            'receipt_id' => $receipt_id
        ]);
    } catch (Exception $e) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
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
if ($received_data->post == 'save_products_in_wh') {

    $pd_in_whs_id = (int)($received_data->pd_in_whs_id ?? 0);
    $warehouses_id = (int)($received_data->warehouses_id ?? 0);
  
    $new_unit = (int)($received_data->unit ?? 0);
    $new_price = (int)($received_data->price ?? 0);
    $new_max = (int)($received_data->max ?? 0);
  
    $user_id = $received_data->user_id ?? null;
    $user_role = $received_data->user_role ?? null;
  
    if ($pd_in_whs_id <= 0 || $warehouses_id <= 0) {
      echo json_encode(['status' => false, 'message' => 'missing pd_in_whs_id/warehouses_id']);
      exit;
    }
  
    try {
      $connect->beginTransaction();
  
      // 1) อ่านค่าเก่าก่อน เพื่อทำ diff + log
      $stmt = $connect->prepare("SELECT unit, price, max FROM pd_in_whs WHERE id = ? AND warehouses_id = ? LIMIT 1");
      $stmt->execute([$pd_in_whs_id, $warehouses_id]);
      $old = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (!$old) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'row not found']);
        exit;
      }
  
      $old_unit = (int)$old['unit'];
      $old_price = (int)$old['price'];
      $old_max = (int)$old['max'];
  
      // 2) UPDATE ให้ชัวร์ (และเช็ค rowCount)
      $stmt = $connect->prepare("UPDATE pd_in_whs SET unit = ?, price = ?, max = ? WHERE id = ? AND warehouses_id = ?");
      $stmt->execute([$new_unit, $new_price, $new_max, $pd_in_whs_id, $warehouses_id]);
  
      if ($stmt->rowCount() < 1 && ($old_unit !== $new_unit || $old_price !== $new_price || $old_max !== $new_max)) {
        // ถ้า rowCount = 0 แต่ค่าจริงเปลี่ยน แปลว่ามีปัญหาเงื่อนไข WHERE
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'update failed (wrong id/warehouse?)']);
        exit;
      }
  
      // 3) INSERT LOG เฉพาะ field ที่เปลี่ยน
      // ต้องมีตาราง pd_in_wh_logs (เดี๋ยวข้อ 3 ให้ SQL)
      $insertLog = $connect->prepare("
        INSERT INTO pd_in_wh_logs
        (pd_in_whs_id, warehouses_id, action_type, old_unit, new_unit, diff_unit, old_price, new_price, old_max, new_max, user_id, user_role, created_at)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
      ");
  
      // unit diff
      if ($old_unit !== $new_unit) {
        $diff = $new_unit - $old_unit;
        $action = ($diff > 0) ? 'INCREASE_UNIT' : 'DECREASE_UNIT';
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, $action, $old_unit, $new_unit, $diff, null, null, null, null, $user_id, $user_role]);
      }
  
      // price change
      if ($old_price !== $new_price) {
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, 'UPDATE_PRICE', null, null, null, $old_price, $new_price, null, null, $user_id, $user_role]);
      }
  
      // max change
      if ($old_max !== $new_max) {
        $insertLog->execute([$pd_in_whs_id, $warehouses_id, 'SET_MAX', null, null, null, null, null, $old_max, $new_max, $user_id, $user_role]);
      }
  
      $connect->commit();
  
      echo json_encode(['status' => true]);
      exit;
  
    } catch (Exception $e) {
      if ($connect->inTransaction()) $connect->rollBack();
      echo json_encode(['status' => false, 'message' => $e->getMessage()]);
      exit;
    }
  }
  
if ($received_data->post == 'get_pd_in_wh_logs') {

  $pd_in_whs_id = (int)($received_data->pd_in_whs_id ?? 0);

  $stmt = $connect->prepare("
    SELECT *
    FROM pd_in_wh_logs
    WHERE pd_in_whs_id = ?
    ORDER BY id DESC
    LIMIT 200
  ");
  $stmt->execute([$pd_in_whs_id]);

  echo json_encode([
    'status' => true,
    'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
  ]);
  exit;
}
if ($received_data->post == 'stock_move_pd_in_wh') {

    $pd_in_whs_id  = (int)($received_data->pd_in_whs_id ?? 0);
    $warehouses_id = (int)($received_data->warehouses_id ?? 0);
  
    // +รับเข้า, -จ่ายออก
    $delta_unit = (int)($received_data->delta_unit ?? 0);
  
    $user_id   = $received_data->user_id ?? null;
    $user_role = $received_data->user_role ?? null;
    $note      = $received_data->note ?? null;
  
    if ($pd_in_whs_id <= 0 || $warehouses_id <= 0 || $delta_unit == 0) {
      echo json_encode(['status' => false, 'message' => 'missing pd_in_whs_id/warehouses_id or delta_unit=0']);
      exit;
    }
  
    try {
      $connect->beginTransaction();
  
      // 1) อ่านค่าเก่า (ล็อคแถวกันคนแก้พร้อมกัน)
      $stmt = $connect->prepare("
        SELECT unit
        FROM pd_in_whs
        WHERE id = ? AND warehouses_id = ?
        LIMIT 1
        FOR UPDATE
      ");
      $stmt->execute([$pd_in_whs_id, $warehouses_id]);
      $old = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if (!$old) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'row not found']);
        exit;
      }
  
      $old_unit = (int)$old['unit'];
      $new_unit = $old_unit + $delta_unit;
  
      // กันติดลบ
      if ($new_unit < 0) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'stock not enough']);
        exit;
      }
  
      // 2) update unit แบบ atomic
      $stmt = $connect->prepare("
        UPDATE pd_in_whs
        SET unit = ?
        WHERE id = ? AND warehouses_id = ?
      ");
      $stmt->execute([$new_unit, $pd_in_whs_id, $warehouses_id]);
  
      // 3) log 1 แถว/1 เหตุการณ์
      $action = ($delta_unit > 0) ? 'STOCK_IN' : 'STOCK_OUT';
  
      $insertLog = $connect->prepare("
        INSERT INTO pd_in_wh_logs
        (pd_in_whs_id, warehouses_id, action_type,
         old_unit, new_unit, diff_unit,
         note, user_id, user_role, created_at)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
      ");
      $insertLog->execute([
        $pd_in_whs_id, $warehouses_id, $action,
        $old_unit, $new_unit, $delta_unit,
        $note, $user_id, $user_role
      ]);
  
      $connect->commit();
      echo json_encode(['status' => true, 'old_unit' => $old_unit, 'new_unit' => $new_unit]);
      exit;
  
    } catch (Exception $e) {
      if ($connect->inTransaction()) $connect->rollBack();
      echo json_encode(['status' => false, 'message' => $e->getMessage()]);
      exit;
    }
  }
  

if ($received_data->post == 'get_stock_logs') {

    $warehouses_id = (int)($received_data->warehouses_id ?? 0);
    $product_id    = (int)($received_data->product_id ?? 0);
    $limit         = (int)($received_data->limit ?? 50);

    if ($warehouses_id <= 0) {
        echo json_encode(['status' => false, 'message' => 'warehouses_id required']);
        exit;
    }

    $sql = "
      SELECT id, warehouses_id, product_id, pw_id, action,
             before_unit, after_unit, delta_unit,
             before_max, after_max,
             before_price, after_price,
             actor_uid, actor_name, actor_role,
             note, created_at
      FROM product_stock_logs
      WHERE warehouses_id = ?
    ";
    $params = [$warehouses_id];

    if ($product_id > 0) {
        $sql .= " AND product_id = ? ";
        $params[] = $product_id;
    }

    $sql .= " ORDER BY created_at DESC LIMIT ? ";
    $params[] = $limit;

    $stmt = $connect->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => true, 'data' => $rows]);
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
if ($received_data->post == 'get_today_selling_products_id') {
    $wareHouseId = $received_data->wareHouseId;
    $range = 'today';
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
            p.image AS product_img,
            sp.person,
            sp.created_at,
            pd.unit AS total_unit,
            MIN(sp.created_at) AS first_sale_date,
            SUM(sp.qty) AS total_qty,
            SUM(sp.total) AS total_sale
        FROM 
            saleproducts sp
        JOIN 
            products p ON sp.product_id = p.id
        JOIN 
            pd_in_whs pd ON sp.product_id = pd.prooduct_id
        WHERE
            sp.created_at BETWEEN ? AND ? 
            AND sp.warehouse_id = ?
        GROUP BY 
            sp.product_id
        ORDER BY 
            total_qty DESC, total_sale DESC
    ");

    $stmt->bindValue(1, $start_date);
    $stmt->bindValue(2, $end_date);
    $stmt->bindValue(3, (int)$wareHouseId, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'data' => $result
    ]);
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
    $salary = $received_data->salary ?? '';
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
        $stmt = $connect->prepare("INSERT INTO employees (name, phone, password, salary) VALUES (?, ?, ?,?)");
        $stmt->execute([$name, $phone, $hashed_password, $salary]);
        $employee_id = $connect->lastInsertId();

        // insert roles
        $stmt_role = $connect->prepare("INSERT INTO employee_permissions (employee_id, permission_id) VALUES (?, ?)");
        // foreach ($role_ids as $role_id) {
            $stmt_role->execute([$employee_id, $role_ids]);
        // }

        $connect->commit();
        echo json_encode(['status' => true, 'message' => 'เพิ่มพนักงานเรียบร้อย']);
    } catch (Exception $e) {
        $connect->rollBack();
        echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
}

// update employee
if ($received_data->post == "save_editemployee") {
    @session_start();
    $person = '<b>' . $_SESSION['fin_position'] . '</b> ' . $_SESSION['fin_username'];

    try {
        if (!empty($received_data->data->password)) {
            // hash password จากข้อมูลที่ส่งมา
            $hashed_password = password_hash($received_data->data->password, PASSWORD_DEFAULT);

            $data_ = array(
                ':id'        => $received_data->data->id,
                ':name'      => $received_data->data->name,
                ':phone'     => $received_data->data->phone,
                ':password'  => $hashed_password,
                ':salary'  => (int)$received_data->data->salary,
                ':created_at' => date('Y-m-d H:i:s')
            );

            $sql = "UPDATE employees 
                    SET name=:name, phone=:phone, password=:password, created_at=:created_at , salary=:salary
                    WHERE id=:id";
        } else {
            $data_ = array(
                ':id'        => $received_data->data->id,
                ':name'      => $received_data->data->name,
                ':phone'     => $received_data->data->phone,
                ':salary'  => (int) $received_data->data->salary,
                ':created_at' => date('Y-m-d H:i:s')
            );

            $sql = "UPDATE employees 
                    SET name=:name, phone=:phone, created_at=:created_at , salary=:salary
                    WHERE id=:id";
        }

        $statement = $connect->prepare($sql);
        $statement->execute($data_);

        $data = array(
            'status'  => true,
            'message' => 'Update successfully'
        );
    } catch (Exception $e) {
        $data = array(
            'status'  => false,
            'message' => 'Error: ' . $e->getMessage()
        );
    }

    echo json_encode($data);
}


// API: ../api/index.php
if ($received_data->post == 'get_employee') {
    $stmt = $connect->query("
    SELECT 
        e.*, 
        GROUP_CONCAT(DISTINCT r.name) AS permissions,
        GROUP_CONCAT(DISTINCT el.role_id) AS role_ids
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
  
    if (!$employee_id) {
      echo json_encode(['status' => false, 'message' => 'ไม่พบ employee_id']);
      exit;
    }
  
    if (!is_array($role_ids)) $role_ids = [$role_ids];
  
    try {
      $connect->beginTransaction();
  
      $stmt_clear = $connect->prepare("DELETE FROM employee_roles WHERE employee_id = ?");
      $stmt_clear->execute([$employee_id]);
  
      $stmt_role = $connect->prepare("INSERT INTO employee_roles (employee_id, role_id) VALUES (?, ?)");
  
      foreach ($role_ids as $role_id) {
        $stmt_role->execute([$employee_id, $role_id]);
      }
  
      $connect->commit();
      echo json_encode(['status' => true, 'message' => 'เพิ่มสิทธิ์พนักงานเรียบร้อย']);
      exit;
  
    } catch (Exception $e) {
      if ($connect->inTransaction()) $connect->rollBack();
      echo json_encode(['status' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
      exit;
    }
  }
  

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($received_data->post == 'get_role') {
    $emp_id = $received_data->emp_id;

    // ดึง role ของ employee
    $stmt2 = $connect->prepare("SELECT role_id FROM employee_roles WHERE employee_id = ?");
    $stmt2->execute([$emp_id]);
    $empRoles = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'role_id');

    $departments = [
        'sale' => 1,   // พนักงานขาย
        'store' => 2,  // พนักงานคลัง
        'truck' => 3   // พนักงานขนส่ง
    ];

    $result = [
        "status" => true,
        "employee_roles" => $empRoles
    ];

    foreach ($departments as $key => $permission_id) {
        // role ทั้งหมดของแผนก
        $stmt = $connect->prepare("
            SELECT r.id, r.role_name 
            FROM roles r 
            JOIN permission_role pr ON pr.role_id = r.id
            WHERE pr.permission_id = ?");
        $stmt->execute([$permission_id]);
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // role ที่ employee มี (เช็คจาก empRoles)
        $selected = array_column(
            array_filter($roles, fn($r) => in_array($r['id'], $empRoles)),
            'id'
        );

        // เก็บลง array ตามแผนก
        $result["roles_$key"] = $roles;        // สิทธิทั้งหมดของแผนก
        $result["selected_$key"] = $selected;  // สิทธิที่ employee มี
    }

    echo json_encode($result);
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
        $item = (array) $item;

        $product_id = $item['product_id'];
        $price = $item['price'];
        $qty = $item['qty'];

        // ดึงโปรโมชั่นทั้งหมดของสินค้า
        $stmt = $connect->prepare("
            SELECT pp.*, p.image
            FROM product_promotions pp
            JOIN products p ON pp.product_id = p.id
            WHERE pp.product_id = ?
              AND pp.warehouse_id = ?
        ");
        $stmt->execute([$product_id, $warehouse_id]);
        $promo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $image = $promo[0]['image'];
        $price_per_unit = $price; // ราคาปกติ
        $discount = 0;

        if ($promo) {
            foreach ($promo as $tier) {

                if ($qty >= 10 and $qty < 20 and $tier['min_quantity'] >= 10 and $tier['min_quantity'] < 20) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 20 and $qty < 30 and $tier['min_quantity'] >= 20 and $tier['min_quantity'] < 30) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 30 and $qty < 40 and $tier['min_quantity'] >= 30 and $tier['min_quantity'] < 40) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 40 and $qty < 50 and $tier['min_quantity'] >= 40 and $tier['min_quantity'] < 50) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 50 and $qty < 60 and $tier['min_quantity'] >= 50 and $tier['min_quantity'] < 60) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 60 and $qty < 70 and $tier['min_quantity'] >= 60 and $tier['min_quantity'] < 70) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 70 and $qty < 80 and $tier['min_quantity'] >= 70 and $tier['min_quantity'] < 80) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 80 and $qty < 90 and $tier['min_quantity'] >= 80 and $tier['min_quantity'] < 90) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= 90 and $qty < 100 and $tier['min_quantity'] >= 90 and $tier['min_quantity'] < 100) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                } else if ($qty >= $tier['min_quantity']) {
                    $price_per_unit = $price + $tier['price'];
                    $discount = $tier['price'];
                }
            }
        }

        $final_price_per_unit = max(0, $price_per_unit);
        $total_price_item = $final_price_per_unit * $qty;
        $total += $total_price_item;

        $newCart[] = [
            'product_id'            => (int) $product_id,
            'product_name'          => $item['product_name'],
            'qty'                   => (int) $qty,
            'image'                 => $image, // อันนี้น่าจะไม่ใช่ตัวเลข เลยแนะนำเก็บเป็น string
            'price'                 => (float) $price,
            'price_per_unit'        => (float) $price_per_unit,
            'discount_per_unit'     => (float) $discount,
            'final_price_per_unit'  => (float) $final_price_per_unit,
            'total_price_item'      => (float) $total_price_item

        ];
    }

    echo json_encode([
        'status' => true,
        'cart'   => $newCart,
        'total'  => $total
    ]);
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
            LEFT JOIN barcodes b ON p.id = b.product_id
            WHERE REPLACE(REPLACE(REPLACE(p.warehouses_id, ' ', ''), '\"', ''), '[', '') LIKE ?";

    $search = '%' . $warehouses_id . '%';
    $stmt = $connect->prepare($sql);
    $stmt->execute([$search]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    $total_products = 0;
    $total_sale_out = 0;
    $low_stock = 0;
    $out_of_stock = 0;

    foreach ($products as $row) {
        // ข้อมูลคลังสินค้า
        $sql_pdwh = "SELECT * FROM pd_in_whs
                     WHERE warehouses_id = ? AND prooduct_id = ?";
        $stmt_pdwh = $connect->prepare($sql_pdwh);
        $stmt_pdwh->execute([$warehouses_id, $row['id']]);
        $pd_data = $stmt_pdwh->fetch(PDO::FETCH_ASSOC);

        // นับสต๊อก
        if ($pd_data) {
            $total_products++;
            if ($pd_data['unit'] == 0) {
                $out_of_stock++;
            } elseif ($pd_data['unit'] < 50) {
                $low_stock++;
            }
        }

        // ยอดขายวันนี้
        $sql_s = "SELECT SUM(total) AS total_sale, SUM(qty) AS sale_qty
                  FROM saleproducts 
                  WHERE warehouse_id = ? AND product_id = ? 
                  AND created_at BETWEEN ? AND ?";
        $stmt_s = $connect->prepare($sql_s);
        $stmt_s->execute([$warehouses_id, $row['id'], $start_date, $end_date]);
        $s_data = $stmt_s->fetch(PDO::FETCH_ASSOC);

        $sale_total = (int)($s_data['total_sale'] ?? 0);
        $sale_qty   = (int)($s_data['sale_qty'] ?? 0);
        $total_sale_out += $sale_total;


        $sql_re = "
            SELECT
                r.*,
                w.name AS warehouse_name,
                (
                    SELECT GROUP_CONCAT(DISTINCT sp.person SEPARATOR ', ')
                    FROM saleproducts sp
                    WHERE sp.warehouse_id = r.warehouse_id
                ) AS persons
            FROM receipts r
            JOIN warehouses w ON r.warehouse_id = w.id
            WHERE r.warehouse_id = ?
              AND r.created_at BETWEEN ? AND ?
              AND EXISTS (
                  SELECT 1 FROM receipt_items ri
                  WHERE ri.receipt_id = r.id
                    AND ri.product_id = ?
              )
            ORDER BY r.created_at DESC
        ";

        $stmt_re = $connect->prepare($sql_re);
        $stmt_re->execute([$warehouses_id, $start_date, $end_date, $row['id']]);
        $receipts = $stmt_re->fetchAll(PDO::FETCH_ASSOC);

        $discount_per_unit = 0; // กำหนดค่าเริ่มต้น

        foreach ($receipts as $receipt) {
            $stmt_item = $connect->prepare("
                SELECT
                    id,
                    receipt_id,
                    product_id,
                    qty,
                    price,
                    discount_per_unit,
                    (qty * price) AS line_total
                FROM receipt_items
                WHERE receipt_id = ?
                AND product_id = ?
            ");
            $stmt_item->execute([$receipt['id'], $row['id']]);
            $items = $stmt_item->fetchAll(PDO::FETCH_ASSOC);

            foreach ($items as $i) {
                // รวมส่วนลดทั้งหมด (discount ต่อชิ้น * จำนวนชิ้น)
                $discount_per_unit += abs($i['discount_per_unit']);
            }
        }
        $discount_total = 0;

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
            'qty_total'     => number_format($sale_qty + $pd_data['unit']) ?? 0,
            'pw_id'          => $pd_data['id'] ?? null,
            'discount_total' => $discount_per_unit
        ];
        $discount_total = $discount_per_unit += $discount_per_unit;
    }

    echo json_encode([
        'products' => $data,
        'discount_total' => $discount_total,
        'summary' => [
            'total_sale_out' => $total_sale_out,
            'total_products' => $total_products,
            'low_stock'      => $low_stock,
            'out_of_stock'   => $out_of_stock,
        ],
        'status' => true,
        'date' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
}


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ($received_data->post == 'get_finish_ofday_id') {
    $warehouses_id = $received_data->warehouses_id;
    $start_date = date('Y-m-d 00:00:00');
    $end_date = date('Y-m-d 23:59:59');
    $sql = " SELECT 
            sp.created_at,sp.qty,
            p.*,
            DATE(sp.created_at) AS sale_date,
            SUM(sp.total) AS total_sale,
            SUM(sp.qty * p.price) AS total_cost,
            SUM(sp.total - (sp.qty * p.price)) AS profit
        FROM saleproducts sp
        JOIN products p ON sp.product_id = p.id
        WHERE warehouse_id = ? AND sp.created_at BETWEEN ? AND ? 
        ORDER BY sp.product_id ASC
        ";
    $stmt_summary = $connect->prepare($sql);
    $stmt_summary->execute([$warehouses_id, $start_date, $end_date]);
    $sale = $stmt_summary->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode([
        'products' => $sale,
        'status'   => true,
        'date'     => date('Y-m-d H:i:s'),
    ], JSON_UNESCAPED_UNICODE);
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



if ($received_data->post == 'save_summary') {
    $warehouse_ids  = $received_data->warehouse_id; // คาดว่าเป็น array
    if (is_string($warehouse_ids)) {
        $warehouse_ids = json_decode($warehouse_ids, true); // แปลงจาก JSON string เป็น array
    }

    $discount       = $received_data->discount ?? 0;
    $cash_counted   = $received_data->cash_counted ?? 0;
    $cash_received  = $received_data->cash_received ?? 0;
    $transfer       = $received_data->transfer ?? 0;
    $products       = $received_data->products ?? [];

    $saved_summaries = [];

    // foreach ($warehouse_ids as $warehouse_id) {
    // บันทึก summary รายวัน
    $stmt = $connect->prepare("
            INSERT INTO sale_of_day_summary 
            (warehouse_id, discount, cash_counted, cash_received, transfer, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
    $stmt->execute([
        $warehouse_ids,
        $discount,
        $cash_counted,
        $cash_received,
        $transfer
    ]);

    $summary_id = $connect->lastInsertId();

    // บันทึกสินค้าแต่ละรายการ
    $stmt_item = $connect->prepare("
            INSERT INTO sale_of_day_items (summary_id, product_id, sale_qty, total_sale, remain)
            VALUES (?, ?, ?, ?, ?)
        ");
    foreach ($products as $p) {
        $stmt_item->execute([
            $summary_id,
            $p->id,
            $p->sale_qty,
            $p->total_sale,
            $p->remain
        ]);
    }

    $saved_summaries[] = $summary_id;
    // }

    echo json_encode([
        'status' => true,
        'message' => 'บันทึกสรุปรายวันสำเร็จ',
        'summary_ids' => $saved_summaries
    ]);
}


if ($received_data->post == "get_summary") {
    $warehouse_id = $received_data->warehouse_id ?? null;
    $date = $received_data->date ?? null; // เลือกวันที่ที่ต้องการ เช่น '2025-08-28'

    $params = [];
    $conditions = [];

    // ถ้ามี warehouse_id
    if (!empty($warehouse_id)) {
        $conditions[] = "s.warehouse_id = ?";
        $params[] = $warehouse_id;
    }

    // ถ้ามี date
    if (!empty($date)) {
        $conditions[] = "DATE(s.created_at) = ?";
        $params[] = $date;
    }

    $where = "";
    if (count($conditions) > 0) {
        $where = "WHERE " . implode(" AND ", $conditions);
    }

    // ดึง summary
    $sql = "
        SELECT 
            s.*
        FROM sale_of_day_summary s
        $where
        ORDER BY s.created_at DESC
    ";
    $stmt = $connect->prepare($sql);
    $stmt->execute($params);
    $summaries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึง items ของแต่ละ summary
    $result = [];
    foreach ($summaries as $s) {
        $stmt_items = $connect->prepare("
            SELECT 
                i.*,p.name as product_name
            FROM sale_of_day_items i
            LEFT JOIN products p ON i.product_id = p.id
            WHERE i.summary_id = ?
        ");
        $stmt_items->execute([$s['id']]);
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        $s['items'] = $items;
        $result[] = $s;
    }

    echo json_encode([
        "status" => true,
        "data" => $result
    ]);
}


if ($received_data->post == "assign_employee_roles") {
    $employee_id = intval($received_data->employee_id);
    $roles = $received_data->roles; // array ของ role_id

    if (!$employee_id || !is_array($roles)) {
        echo json_encode([
            "status" => false,
            "message" => "ข้อมูลไม่ถูกต้อง"
        ]);
        exit;
    }

    try {
        // ลบสิทธิเดิมก่อน (เพื่อป้องกันซ้ำซ้อน)
        $del = $connect->prepare("DELETE FROM employee_roles WHERE employee_id = ?");
        $del->execute([$employee_id]);

        // บันทึกสิทธิใหม่
        $stmt = $connect->prepare("INSERT INTO employee_roles (employee_id, role_id) VALUES (?, ?)");
        foreach ($roles as $role_id) {
            $stmt->execute([$employee_id, intval($role_id)]);
        }

        echo json_encode([
            "status" => true,
            "message" => "บันทึกสิทธิพนักงานเรียบร้อยแล้ว"
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => false,
            "message" => "ไม่สามารถบันทึกสิทธิได้: " . $e->getMessage()
        ]);
    }
    exit;
}

// ✅ ดึงแผนก
if ($received_data->post == "get_permissions") {
    $stmt = $connect->prepare("SELECT * FROM permissions");
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ✅ ดึงสิทธิของแผนก + สิทธิที่เลือกไว้แล้ว
if ($received_data->post == "get_roles_by_permission") {
    $permission_id = intval($received_data->permission_id);

    // role ทั้งหมด
    $stmt = $connect->prepare("
        SELECT r.id, r.role_name 
        FROM roles r 
        JOIN permission_role pr ON pr.role_id = r.id
        WHERE pr.permission_id = ?");
    $stmt->execute([$permission_id]);
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // role ที่เลือกไว้แล้ว
    $stmt2 = $connect->prepare("SELECT role_id FROM permission_role WHERE permission_id = ?");
    $stmt2->execute([$permission_id]);
    $selected = array_column($stmt2->fetchAll(PDO::FETCH_ASSOC), 'role_id');

    echo json_encode(["roles" => $roles, "selected" => $selected]);
    exit;
}

// ✅ บันทึกสิทธิของแผนก
if ($received_data->post == "save_permission_roles") {
    $permission_id = intval($received_data->permission_id);
    $roles = $received_data->roles;

    try {
        $connect->beginTransaction();

        // ลบเก่า
        $del = $connect->prepare("DELETE FROM permission_role WHERE permission_id = ?");
        $del->execute([$permission_id]);

        // เพิ่มใหม่
        $ins = $connect->prepare("INSERT INTO permission_role (permission_id, role_id) VALUES (?, ?)");
        foreach ($roles as $role_id) {
            $ins->execute([$permission_id, intval($role_id)]);
        }

        $connect->commit();

        echo json_encode([
            "status" => true,
            "message" => "บันทึกสิทธิของแผนกเรียบร้อยแล้ว"
        ]);
    } catch (Exception $e) {
        $connect->rollBack();
        echo json_encode([
            "status" => false,
            "message" => "เกิดข้อผิดพลาด: " . $e->getMessage()
        ]);
    }
    exit;
}

if ($received_data->post == "get_employee_roles") {
    $employee_id = intval($received_data->employee_id);

    // หาว่า employee คนนี้อยู่แผนกอะไร
    $stmt = $connect->prepare("
        SELECT e.id, e.name, p.permission_id, rl.role_name as permission_name
        FROM employees e
        JOIN employee_roles er ON e.id = er.employee_id
        JOIN employee_permissions p ON p.employee_id = e.id
        JOIN permission_role pr ON pr.permission_id = p.permission_id
        JOIN roles rl ON rl.id = pr.role_id
        WHERE e.id = ?
    ");
    $stmt->execute([$employee_id]);
    $emp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$emp) {
        echo json_encode([
            "status" => false,
            "roles" => [
                ["id" => 0, "role_name" => "ยังไม่มีการกำหนดสิทธิ"]
            ],
            "message" => "ไม่พบพนักงาน"
        ]);
        exit;
    }

    // เอาสิทธิจาก mapping แผนก
    $stmt2 = $connect->prepare("
        SELECT r.*
        FROM roles r
        JOIN employee_roles pr ON pr.role_id = r.id
        WHERE pr.employee_id = ?
    ");
    $stmt2->execute([$emp['id']]);
    $roles = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // 🔥 แปลง id ให้เป็น integer
    foreach ($roles as &$role) {
        $role['id'] = intval($role['id']);
    }

    echo json_encode([
        "status"   => true,
        "employee" => $emp,
        "roles"    => $roles
    ]);
    exit;
}



//ดึงรายการเอกสารทั้งหมด
// if ($received_data->post == 'get_transfer_documents') {
//     $stmt = $connect->prepare("
//         SELECT d.*, 
//                p.name AS product_name, 
//                fw.name AS from_name, 
//                tw.name AS to_name
//         FROM transfer_documents d
//         JOIN products p ON d.product_id = p.id
//         JOIN warehouses fw ON d.from_warehouse = fw.id
//         JOIN warehouses tw ON d.to_warehouse = tw.id
//         ORDER BY d.created_at DESC
//     ");
//     $stmt->execute();
//     $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     echo json_encode(['status' => true, 'documents' => $data]);
// }
//ดึงรายการเอกสาร เฉพาะสาขา
if ($received_data->post == 'get_transfer_documents_id') {
    $warehouse_id = $received_data->wareHouseId ?? null; // ถ้าไม่ส่งมาก็เป็น null
    $start_date = date('Y-m-d 00:00:00');
    $end_date   = date('Y-m-d 23:59:59');

    if ($warehouse_id) {
        // กรณีเลือก warehouse เฉพาะ
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
              AND DATE(d.created_at) = :start_date
            GROUP BY document_no 
            ORDER BY d.created_at DESC
        ");
        $stmt->bindParam(':warehouse_id', $warehouse_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    } else {
        // กรณีไม่ส่ง warehouse → ดึงทุก warehouse
        $stmt = $connect->prepare("
            SELECT d.*, 
                p.name AS product_name, 
                fw.name AS from_name, 
                tw.name AS to_name 
            FROM transfer_documents d
            JOIN products p ON d.product_id = p.id
            JOIN warehouses fw ON d.from_warehouse = fw.id
            JOIN warehouses tw ON d.to_warehouse = tw.id
            WHERE DATE(d.created_at) = :start_date
            GROUP BY document_no 
            ORDER BY d.created_at DESC
        ");
        $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    if ($data) {
        foreach ($data as $row) {
            // ดึงสถานะ delivery
            $stmt_dr = $connect->prepare("
                SELECT * 
                FROM delivery_records 
                WHERE transfer_id = :whid 
                  AND delivered_at >= :delivered_at
            ");
            $stmt_dr->execute([
                ':whid' => $row['id'],
                ':delivered_at' => $start_date
            ]);
            $dr_result = $stmt_dr->fetch(PDO::FETCH_ASSOC);

            $result[] = [
                'created_at'     => $row['created_at'],
                'delivered_at'   => $dr_result['delivered_at'] ?? null,
                'delivered_by'   => $dr_result['delivered_by'] ?? null,
                'document_no'    => $row['document_no'],
                'file_name'      => $row['file_name'],
                'from_name'      => $row['from_name'],
                'from_warehouse' => $row['from_warehouse'],
                'id'             => $row['id'],
                'product_id'     => $row['product_id'],
                'product_name'   => $row['product_name'],
                'qty'            => $row['qty'],
                'to_name'        => $row['to_name'],
                'to_warehouse'   => $row['to_warehouse'],
                'delivered'      => $dr_result['status'] ?? 'wait'
            ];
        }
    }

    echo json_encode(['status' => true, 'documents' => $result]);
}


if ($received_data->post == 'searchTransferByDate') {
    $date = $received_data->date;
    $warehouse_id = $received_data->wareHouseId ?? null;

    // base query
    $sql = "
        SELECT d.*, 
               p.name AS product_name, 
               fw.name AS from_name, 
               tw.name AS to_name
        FROM transfer_documents d
        JOIN products p ON d.product_id = p.id
        JOIN warehouses fw ON d.from_warehouse = fw.id
        JOIN warehouses tw ON d.to_warehouse = tw.id
        WHERE DATE(d.created_at) = :search_date
    ";

    // ถ้ามี warehouse_id ให้ใส่เงื่อนไขเพิ่ม
    if (!empty($warehouse_id)) {
        $sql .= " AND d.from_warehouse = :warehouse_id";
    }

    $sql .= " ORDER BY d.created_at DESC";

    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':search_date', $date);

    if (!empty($warehouse_id)) {
        $stmt->bindParam(':warehouse_id', $warehouse_id);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    if ($data) {
        foreach ($data as $row) {
            // 🔄 ดึงข้อมูลคลังของสินค้านี้
            $stmt_dr = $connect->prepare("
                SELECT * 
                FROM delivery_records 
                WHERE transfer_id = :whid 
                  AND delivered_at >= :delivered_at
            ");
            $stmt_dr->execute([
                ':whid' => $row['id'],
                ':delivered_at' => $date
            ]);
            $dr_result = $stmt_dr->fetch(PDO::FETCH_ASSOC);

            $result[] = [
                'created_at'  =>  $row['created_at'],
                'delivered_at' => $dr_result['delivered_at'] ?? null,
                'delivered_by' => $dr_result['delivered_by'] ?? null,
                'document_no' => $row['document_no'],
                'file_name'   => $row['file_name'],
                'from_name'   => $row['from_name'],
                'from_warehouse'  => $row['from_warehouse'],
                'id'  => $row['id'],
                'product_id'  => $row['product_id'],
                'product_name' => $row['product_name'],
                'qty' => $row['qty'],
                'to_name' => $row['to_name'],
                'to_warehouse' => $row['to_warehouse'],
                'delivered' => $dr_result['status'] ?? 'wait'
            ];
        }
    }

    echo json_encode(['status' => true, 'data' => $result]);
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

if ($received_data->post == "save_role") {
    $employee_id = intval($received_data->employee_id);
    $roles = $received_data->roles; // [1,5,7]

    // ลบของเดิมก่อน
    $stmt = $connect->prepare("DELETE FROM employee_roles WHERE employee_id = ?");
    $stmt->execute([$employee_id]);

    // เพิ่มสิทธิใหม่
    $stmt = $connect->prepare("INSERT INTO employee_roles (employee_id, role_id) VALUES (?, ?)");
    foreach ($roles as $rid) {
        $stmt->execute([$employee_id, $rid]);
    }

    echo json_encode(["status" => true, "message" => "อัปเดตสิทธิพนักงานสำเร็จ"]);
    exit;
}


if ($received_data->post == "update_type") {
    $id = $received_data->id;
    $cateName = $received_data->cateName;
    $noted = $received_data->noted;

    if ($cateName == "") {
        echo json_encode(["status" => false, "message" => "กรุณากรอกชื่อประเภทสินค้า!"]);
        exit;
    }

    $sql = "UPDATE categories SET cate_name = :cateName, noted = :noted WHERE id = :id";
    $stmt = $connect->prepare($sql);
    $result = $stmt->execute([
        ":cateName" => $cateName,
        ":noted" => $noted,
        ":id" => $id
    ]);

    if ($result) {
        echo json_encode(["status" => true, "message" => "อัปเดตข้อมูลสำเร็จ"]);
    } else {
        echo json_encode(["status" => false, "message" => "อัปเดตไม่สำเร็จ"]);
    }
}

if ($received_data->post == "delete_type") {
    $id = $received_data->id;

    if (!$id) {
        echo json_encode(["status" => false, "message" => "ไม่พบข้อมูลที่ต้องการลบ"]);
        exit;
    }

    $sql = "DELETE FROM categories WHERE id = :id";
    $stmt = $connect->prepare($sql);
    $result = $stmt->execute([":id" => $id]);

    if ($result) {
        echo json_encode(["status" => true, "message" => "ลบข้อมูลสำเร็จ"]);
    } else {
        echo json_encode(["status" => false, "message" => "ลบไม่สำเร็จ"]);
    }
}


// if ($received_data->post == 'save_salary') {
//     $employee_id = $received_data->employee_id;
//     $shift = $received_data->shift;
//     $work_date = $received_data->work_date;
//     $hours_worked = $received_data->hours_worked;
//     $salary_per_hour = $received_data->salary_per_hour;

//     $stmt = $connect->prepare("
//         INSERT INTO employee_salaries (employee_id, shift, work_date, hours_worked, salary_per_hour)
//         VALUES (?, ?, ?, ?, ?)
//     ");
//     $stmt->execute([$employee_id, $shift, $work_date, $hours_worked, $salary_per_hour]);

//     echo json_encode(['status' => true]);
// }

if ($received_data->post == 'get_salaries') {
    $stmt = $connect->query("
        SELECT es.*, e.name
        FROM employee_salaries es
        JOIN employees e ON es.employee_id = e.id
        ORDER BY es.work_date DESC
    ");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}


$action = $_GET['action'] ?? '';

if ($action === 'getEmployees') {
    $query = $connect->query("SELECT id, name FROM employees ORDER BY name ASC");
    $data = $query->fetchAll(MYSQLI_ASSOC);
    echo json_encode($data);
    exit;
}

if ($action === 'getSalaries') {
    $query = $connect->query("
        SELECT s.id, e.name AS employee_name, s.month, s.salary, s.bonus 
        FROM salaries s
        JOIN employees e ON s.employee_id = e.id
        ORDER BY s.month DESC
    ");
    $data = $query->fetchAll(MYSQLI_ASSOC);
    echo json_encode($data);
    exit;
}

// if ($action === 'saveSalary') {
//     $data = json_decode(file_get_contents("php://input"), true);
//     $emp_id = (int)$data['employee_id'];
//     $month = $connect->real_escape_string($data['month']);
//     $salary = (float)$data['salary'];
//     $bonus = (float)$data['bonus'];

//     $stmt = $connect->prepare("INSERT INTO salaries (employee_id, month, salary, bonus) VALUES (?, ?, ?, ?)");
//     $stmt->bind_param("isdd", $emp_id, $month, $salary, $bonus);
//     $stmt->execute();

//     echo json_encode(["status" => "success"]);
//     exit;
// }



if ($post === 'save_salary') {
    // แนะนำ: เปิด try/catch เพื่อเห็น error ชัดเจน
    try {
        // แปลง/ตรวจค่าให้ชัดเจน
        $employee_id = (int)($received_data->employee_id ?? 0);
        $shift       = (int)($received_data->shift ?? 0);
        $month       = trim($received_data->month ?? '');
        $salary      = (int)($received_data->salary ?? 0);
        $bonus       = (int)($received_data->bonus ?? 0);

        if (!$employee_id || !$shift || $month === '') {
            echo json_encode(['status' => false, 'message' => 'missing fields']);
            exit;
        }

        // ❌ อย่าใส่ total ถ้าเป็น Generated Column
        $sql_post = "INSERT INTO salaries (employee_id, shift, month, salary, bonus, created_at)
                     VALUES (:employee_id, :shift, :month, :salary, :bonus, :created_at)";

        $data_ = [
            ':employee_id' => $employee_id,
            ':shift'       => $shift,
            ':month'       => $month,                       // รูปแบบ YYYY-MM
            ':salary'      => $salary,
            ':bonus'       => $bonus,
            ':created_at'  => date('Y-m-d H:i:s')
        ];

        $statement = $connect->prepare($sql_post);
        $result = $statement->execute($data_);

        if ($result) {
            echo json_encode(['status' => true, 'message' => 'บันทึกสำเร็จ']);
        } else {
            echo json_encode(['status' => false, 'message' => 'บันทึกไม่สำเร็จ']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => false, 'message' => 'SQL error', 'error' => $e->getMessage()]);
    }
    exit;
}

if ($post === 'get_employee') {
    $sql = "SELECT id, name, position, phone, base_salary, default_shift FROM employees";
    $res = $connect->query($sql);
    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
    echo json_encode(['status' => true, 'employees' => $rows]);
    exit;
}

if ($post === 'get_endmont') { // ชื่อ action ตามที่ใช้
    // ให้เวลาตรงกับไทย (แล้วแต่เครื่องเซิร์ฟเวอร์)
    date_default_timezone_set('Asia/Bangkok');

    $currentMonth = date('Y-m'); // รูปแบบ YYYY-MM

    $sql = "SELECT s.*, e.name AS employee_name
            FROM salaries s 
            LEFT JOIN employees e ON e.id = s.employee_id
            WHERE s.month = :m
            ORDER BY s.id DESC";

    $stmt = $connect->prepare($sql);
    $stmt->execute([':m' => $currentMonth]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);  // ต้อง fetch ก่อนค่อย encode

    echo json_encode([
        'status' => true,
        'month'  => $currentMonth,
        'data'   => $rows
    ]);
    exit;
}



// Logs
if ($received_data->post == 'logs') {
    $search = $received_data->keyword ?? '';
    $range  = $received_data->range ?? 'today';
    $date   = $received_data->date ?? '';
    $start  = '';
    $end    = '';
    $today  = date('Y-m-d');

    // จัดการช่วงวันที่
    if ($range === 'today') {
        $date  = $date ?: $today;             // ถ้าไม่ส่งมา ใช้วันนี้
        $start = $date . ' 00:00:00';
        $end   = $date . ' 23:59:59';
    } elseif ($range === 'week') {
        $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $end   = $today . ' 23:59:59';
    } elseif ($range === 'month') {
        $start = date('Y-m-01 00:00:00');
        $end   = $today . ' 23:59:59';
    } elseif ($range === 'custom') {
        $start = ($received_data->start_date ?? $today) . ' 00:00:00';
        $end   = ($received_data->end_date   ?? $today) . ' 23:59:59';
    }

    // 1. ยอดขายและต้นทุน
    $stmt = $connect->prepare("
    SELECT 
        sp.id AS sale_id,
        sp.warehouse_id,
        sp.product_id,
        sp.qty,
        sp.total,
        sp.created_at,
        sp.person,
        p.name AS product_name,
        w.name AS warehouse_name,
        w.location,
        w.isActive,
        re.payment_method,
        re.received,
        re.change_amount,
        d.discount
    FROM saleproducts sp
    JOIN products   p  ON p.id = sp.product_id
    JOIN warehouses w  ON w.id = sp.warehouse_id
    JOIN receipts   re ON sp.receipt_id = re.id
    LEFT JOIN (
        SELECT 
            receipt_id,
            SUM(discount_per_unit) AS discount
        FROM receipt_items
        GROUP BY receipt_id
    ) d ON d.receipt_id = re.id
    WHERE sp.created_at BETWEEN ? AND ?
    ORDER BY sp.warehouse_id, sp.id
");
    $stmt->execute([$start, $end]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $warehouses = [];

    foreach ($rows as $r) {
        $wid = $r['warehouse_id'];

        // ถ้ายังไม่มี warehouse นี้ ให้สร้างก่อน
        if (!isset($warehouses[$wid])) {
            $warehouses[$wid] = [
                "warehouse_id"   => $wid,
                "person"           => $r['person'],
                "name"           => $r['warehouse_name'],
                "location"       => $r['location'],
                "isActive"       => $r['isActive'],
                "created_at"     => $r["created_at"],
                "payment_method" => $r["payment_method"],
                "received"       => $r["received"],       // รับมา
                "change_amount"  => $r["change_amount"],  // เงินทอน
                "discount"       => $r["discount"],       // ส่วนลด (ของใบเสร็จนี้)
                "products"       => []
            ];
        }

        // เพิ่มสินค้าเข้าไปใน warehouse นั้น
        $warehouses[$wid]["products"][] = [
            "id"         => $r["product_id"],
            "name"       => $r["product_name"],
            "sale_qty"   => $r["qty"],
            "total_sale" => $r["total"],
            "date"       => $r["created_at"]
        ];
    }

    // reset index ให้เป็น array ธรรมดา
    $final = array_values($warehouses);

    echo json_encode([
        "status" => true,
        "data"   => $final
    ]);
}
// unit
if ($old['unit'] != $received_data->unit) {
    $diff = $received_data->unit - $old['unit'];

    $stmt = $connect->prepare("
      INSERT INTO pd_in_whs_logs
      (pd_in_whs_id, warehouses_id, product_id, action_type,
       old_unit, new_unit, diff_unit,
       user_id, user_role)
      VALUES (?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $received_data->pd_in_whs_id,
        $received_data->warehouses_id,
        $received_data->product_id,
        $diff > 0 ? 'INCREASE_UNIT' : 'DECREASE_UNIT',
        $old['unit'],
        $received_data->unit,
        abs($diff),
        $received_data->user_id,
        $received_data->user_role
    ]);
}

// max
if ($old['max'] != $received_data->max) {
    $stmt = $connect->prepare("
      INSERT INTO pd_in_whs_logs
      (pd_in_whs_id, warehouses_id, product_id, action_type,
       old_max, new_max,
       user_id, user_role)
      VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $received_data->pd_in_whs_id,
        $received_data->warehouses_id,
        $received_data->product_id,
        'SET_MAX',
        $old['max'],
        $received_data->max,
        $received_data->user_id,
        $received_data->user_role
    ]);
}

// price
if ($old['price'] != $received_data->price) {
    $stmt = $connect->prepare("
      INSERT INTO pd_in_whs_logs
      (pd_in_whs_id, warehouses_id, product_id, action_type,
       old_price, new_price,
       user_id, user_role)
      VALUES (?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([
        $received_data->pd_in_whs_id,
        $received_data->warehouses_id,
        $received_data->product_id,
        'UPDATE_PRICE',
        $old['price'],
        $received_data->price,
        $received_data->user_id,
        $received_data->user_role
    ]);
}
  

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
