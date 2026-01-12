<?php
// สมมุติว่าได้ข้อมูลจากการโอนมา
$from_name = "คลัง A";
$to_name = "คลัง B";
$transfer_date = date("d/m/Y H:i");
$product_name = "สินค้า A";
$unit = 10;
$doc_no = "TRF" . date("YmdHis"); // หมายเลขเอกสาร
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เอกสารโอนสินค้า</title>
  <style>
    body {
      font-family: "TH SarabunPSK", sans-serif;
      margin: 30px;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .doc-title {
      font-size: 22pt;
      font-weight: bold;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    td, th {
      border: 1px solid #000;
      padding: 8px;
      font-size: 16pt;
    }
    .section {
      margin-top: 40px;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="doc-title">ใบโอนสินค้าออก</div>
    <div>เลขที่เอกสาร: <?= $doc_no ?></div>
    <div>วันที่: <?= $transfer_date ?></div>
  </div>

  <div>
    <strong>จากคลัง:</strong> <?= $from_name ?><br>
    <strong>ไปยังคลัง:</strong> <?= $to_name ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>ลำดับ</th>
        <th>ชื่อสินค้า</th>
        <th>จำนวน</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><?= $product_name ?></td>
        <td><?= $unit ?></td>
      </tr>
    </tbody>
  </table>

  <div class="section">
    <strong>ผู้โอน:</strong> .......................................
    <br><br>
    <strong>วันที่:</strong> ..................
  </div>

  <hr style="margin: 60px 0; border-top: 2px dashed #000;">

  <div class="header">
    <div class="doc-title">ใบรับสินค้าเข้า</div>
    <div>เลขที่เอกสาร: <?= $doc_no ?></div>
    <div>วันที่: <?= $transfer_date ?></div>
  </div>

  <div>
    <strong>จากคลัง:</strong> <?= $from_name ?><br>
    <strong>ไปยังคลัง:</strong> <?= $to_name ?>
  </div>

  <table>
    <thead>
      <tr>
        <th>ลำดับ</th>
        <th>ชื่อสินค้า</th>
        <th>จำนวน</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><?= $product_name ?></td>
        <td><?= $unit ?></td>
      </tr>
    </tbody>
  </table>

  <div class="section">
    <strong>ผู้รับ:</strong> .......................................
    <br><br>
    <strong>วันที่:</strong> ..................
  </div>

</body>
</html>
