<?php
require_once './connect.php'; // เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json');

$postdata = json_decode(file_get_contents("php://input"));
$product_id = $postdata->product_id ?? null;

if (!$product_id) {
    echo json_encode(['status' => false, 'message' => 'ไม่พบ product_id']);
    exit;
}

// 1. ตรวจสอบว่ามี barcode เดิมอยู่หรือไม่
$stmt = $connect->prepare("SELECT * FROM barcodes WHERE product_id = ?");
$stmt->execute([$product_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // ถ้ามีอยู่แล้ว ส่งกลับข้อมูลเก่า
    echo json_encode([
        'status' => true,
        'barcode' => $existing['code'],
        'image_url' => $existing['image_path'],
        'message' => 'ใช้บาร์โค้ดที่มีอยู่แล้ว'
    ]);
    exit;
}

// 2. ถ้าไม่มี สร้างใหม่
$barcode = rand(100000000000, 999999999999);
$filename = 'barcode_' . $barcode . '.png';
$dir = __DIR__ . '/uploads/barcodes/';
$savePath = $dir . $filename;
$image_url = 'uploads/barcodes/' . $filename;

// สร้างโฟลเดอร์ถ้ายังไม่มี
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

// ดาวน์โหลดภาพจาก API bwip-js
$barcodeUrl = "https://bwipjs-api.metafloor.com/?bcid=code128&text=$barcode&scale=3&height=10&includetext";
$barcodeImage = file_get_contents($barcodeUrl);

if ($barcodeImage === false) {
    echo json_encode(['status' => false, 'message' => 'ดึงภาพบาร์โค้ดไม่สำเร็จ']);
    exit;
}

$success = file_put_contents($savePath, $barcodeImage);
if (!$success) {
    echo json_encode(['status' => false, 'message' => '❌ เกิดข้อผิดพลาดในการบันทึกภาพ']);
    exit;
}

// บันทึกลงฐานข้อมูล
$stmt = $connect->prepare("INSERT INTO barcodes (product_id, code, image_path) VALUES (?, ?, ?)");
$stmt->execute([$product_id, $barcode, $image_url]);

echo json_encode([
    'status' => true,
    'barcode' => $barcode,
    'image_url' => $image_url,
    'message' => 'สร้างบาร์โค้ดใหม่สำเร็จ'
]);
