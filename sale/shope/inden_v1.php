<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>
    Products Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->

  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <script src="../../assets/js/vue/vue3.js"></script>
  <!-- axios -->
  <script src="../../assets/js/axios/axios.0.9.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- <script src="../../assets/js/qrcode.min.js"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/vue@3"></script> -->
  <style>
    .active a.page-link {
      color: #fff;
    }

    .card.card-profile-bottom {
      margin-top: 5rem;
    }

    .text-left {
      text-align: left;
    }

    .table thead th {
      padding: 0;
    }

    .text-right {
      text-align: right;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
    <span class="mask bg-primary opacity-6"></span>
  </div>
  <?php include('../layout/sitebar.html') ?>

  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <?php include('../layout/navbar.html'); ?>
    <!-- End Navbar -->
    <div id="app">
      <div class="card shadow-lg mx-4 card-profile-bottom">
        <!-- <div class="card-body p-3">
          <div class="row gx-4">
            <div class="col-auto">
              <div class="avatar avatar-xl position-relative">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="34" height="34" class="main-grid-item-icon" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <circle cx="9" cy="21" r="1"></circle>
                  <circle cx="20" cy="21" r="1"></circle>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
              </div>
            </div>
            <div class="col-auto my-auto">
              <div class="h-100">
                <input type="text" v-model="barcodeId" @keyup.enter="searchByBarcode" placeholder="สแกนหรือพิมพ์บาร์โค้ด..." class="form-control">
              </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                      <i class="ni ni-app"></i>
                      <span class="ms-2">App</span>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false" tabindex="-1">
                      <i class="ni ni-email-83"></i>
                      <span class="ms-2">Messages</span>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false" tabindex="-1">
                      <i class="ni ni-settings-gear-65"></i>
                      <span class="ms-2">Settings</span>
                    </a>
                  </li>
                  <div class="moving-tab position-absolute nav-link" style="padding: 0px; width: 86px; transform: translate3d(0px, 0px, 0px); transition: 0.5s;" aria-selected="false" tabindex="-1" role="tab"><a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">-</a></div>
                </ul>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12 card d-flex align-items-center justify-content-between mb-3 p-5 text-center" v-if="!hasRole1"> {{'กรุณาขอสิทธิเข้าใช้งาน!'}}</div>
          <div class="col-12" v-else>
            <div class="card mb-4">

              <div class="card-header pb-0 mb-3">
                <!-- <button class="btn btn-primary mb-0" @click="getType" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                  </svg>
                  เพิ่มสินค้า
                </button> -->
                <div class="col-12 col-md-6">
                  <h4 class="px-2 px-md-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                      <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                    </svg>
                    {{whName}}
                  </h4>
                </div>
                <div class="col-12 col-md-6 ms-md-auto pe-md-3 d-flex align-items-center">
                  <div class="input-group mb-3 me-3">
                    <span class="input-group-text text-body" style="height: 40px;">
                      <h1><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                          <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                        </svg></h1>
                    </span>
                    <select class="form-select" v-model="warehouseId" style="height: 40px;" @change="searchWarehouse">
                      <option value="">เลือกคลังสินค้า</option>
                      <option v-for="(item, i) in dataWarehouses" :key="i" :value="item.id">
                        {{ item.name }}
                      </option>
                    </select>
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-text text-body">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                      </svg>
                    </span>
                    <input
                      type="text"
                      class="form-control"
                      placeholder="ค้นหาชื่อสินค้า ..."
                      v-model="searchKeyword"
                      @input="searchProduct">
                  </div>
                </div>
              </div>
              <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">เพิ่มสินค้า</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-1">
                        <label for="exampleFormControlInput1" class="form-label mb-0">ประเภทสินค้า *</label>
                        <!-- หมวดหมู่ -->
                        <select class="form-select" v-model="formData.category_id">
                          <option value="">เลือกหมวดหมู่สินค้า</option>
                          <option v-for="(item,i) in dataCategory" :key="i" :value="item.id">
                            {{ item.cate_name }}
                          </option>
                        </select>
                      </div>

                      <label class="form-label">เลือกคลังสินค้า:</label>
                      <div class="d-flex flex-wrap gap-2">
                        <button
                          v-for="(item, i) in dataWarehouses"
                          :key="item.id"
                          class="btn mb-1"
                          :class="formData.warehouses_id.includes(item.id) ? 'btn-success' : 'btn-outline-primary'"
                          @click="toggleWarehouse(item.id)">
                          {{ item.name }}
                        </button>
                      </div>
                      <!-- แสดงรายการคลังที่เลือก -->
                      <div v-if="formData.warehouses_id.length" class="mt-2">
                        <label class="form-label">คลังสินค้าที่เลือก:</label><br>
                        <span
                          v-for="id in formData.warehouses_id"
                          :key="id"
                          class="badge bg-info text-dark me-1 mb-1">
                          {{ getWarehouseName(id) }}
                          <button @click="removeWarehouse(id)" class="btn-close btn-close-white btn-sm ms-1" style="float: none;" aria-label="ลบ"></button>
                        </span>
                      </div>
                      <div class="mb-1">
                        <label for="exampleFormControlInput1" class="form-label mb-0">ชื่อสินค้า *</label>
                        <!-- ชื่อสินค้า -->
                        <input type="text" class="form-control" v-model="formData.name" placeholder="ชื่อสินค้า หรือยี่ห้อ...">
                      </div>
                      <div class="row">
                        <div class="col-6 mb-1">
                          <label for="exampleFormControlInput1" class="form-label mb-0">ราคาทุน *</label>
                          <!-- ราคา -->
                          <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาสินค้า..">
                        </div>
                        <div class="col-6 mb-1">
                          <label for="exampleFormControlInput1" class="form-label mb-0">ราคาขาย *</label>
                          <!-- จำนวน -->
                          <input type="number" class="form-control" v-model="formData.quantity" placeholder="จำนวนรับเข้า">
                        </div>
                      </div>
                      <div class="mb-1">
                        <label for="exampleFormControlTextarea1" class="form-label mb-0">รูปภาพ *</label>
                        <!-- รูปภาพ -->
                        <input type="file" class="form-control" @change="handleFileUpload" ref="imageInput">
                      </div>
                      <div class="mb-1">
                        <label for="exampleFormControlTextarea1" class="form-label mb-0">รายละเอียด</label>
                        <!-- รายละเอียด -->
                        <textarea class="form-control" v-model="formData.description" rows="3"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="button" class="btn btn-primary" @click="submitForm">บันทึกข้อมูล</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Edit -->
              <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">แก้ไขสินค้า</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <!-- ประเภทสินค้า -->
                      <div class="mb-2">
                        <label class="form-label mb-0">ประเภทสินค้า *</label>
                        <select class="form-select" v-model="formData.category_id">
                          <option value="">เลือกหมวดหมู่สินค้า</option>
                          <option v-for="(item, i) in dataCategory" :key="i" :value="item.id">
                            {{ item.cate_name }}
                          </option>
                        </select>
                      </div>
                      <div class="mb-2">
                        <label class="form-label">เลือกคลังสินค้า:</label>
                        <div class="d-flex flex-wrap gap-2">
                          <button
                            v-for="(item, i) in dataWarehouses"
                            :key="item.id"
                            class="btn mb-1"
                            :class="formData.warehouses_id.includes(item.id) ? 'btn-success' : 'btn-outline-primary'"
                            @click="toggleWarehouse(item.id)">
                            {{ item.name }}
                          </button>
                        </div>
                      </div>

                      <!-- แสดงรายการคลังที่เลือก -->
                      <div v-if="formData.warehouses_id.length" class="mt-2">
                        <label class="form-label">คลังสินค้าที่เลือก:</label><br>
                        <span
                          v-for="id in formData.warehouses_id"
                          :key="id"
                          class="badge bg-info text-dark me-1">
                          {{ getWarehouseName(id) }}
                          <button @click="removeWarehouse(id)" class="btn-close btn-close-white btn-sm ms-1" style="float: none;" aria-label="ลบ"></button>
                        </span>
                      </div>

                      <!-- ชื่อสินค้า -->
                      <div class="mb-2">
                        <label class="form-label mb-0">ชื่อสินค้า *</label>
                        <input type="text" class="form-control" v-model="formData.product_name" placeholder="ชื่อสินค้า หรือยี่ห้อ...">
                      </div>

                      <!-- ราคา และ จำนวน -->
                      <div class="row">
                        <div class="col-6 mb-2">
                          <label class="form-label mb-0">ราคาทุน *</label>
                          <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาสินค้า...">
                        </div>
                        <div class="col-6 mb-2">
                          <label class="form-label mb-0">ราคาขาย *</label>
                          <input type="number" class="form-control" v-model="formData.quantity" placeholder="จำนวนรับเข้า">
                        </div>
                      </div>

                      <!-- รูปภาพ -->
                      <div class="mb-2">
                        <label class="form-label mb-0">รูปภาพ *</label>
                        <input type="file" class="form-control" @change="handleFileUploads" ref="imageInput">
                        <div v-if="image_url" class="mt-2">
                          <img :src="image_url" alt="รูปสินค้า" style="max-height: 80px; height: auto;">
                        </div>
                        <div v-else class="mt-2">
                          <img :src="'../../'+formData.image" alt="รูปสินค้า" style="max-height: 80px; height: auto;">
                        </div>
                      </div>

                      <!-- รายละเอียด -->
                      <div class="mb-2">
                        <label class="form-label mb-0">รายละเอียด</label>
                        <textarea class="form-control" v-model="formData.description" rows="3"></textarea>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="button" class="btn btn-primary" @click="editForm">บันทึกข้อมูล</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body px-0 pt-0 pb-2" v-if="whName">

                <div class="row ">
                  <!-- สรุปยอด -->

                  <div class="col-12 col-md-8 text-center d-block d-sm-block d-md-none d-lg-none">
                    <div class="mt-3 px-4 d-flex align-items-center justify-content-between text-white">
                     <div class="d-flex align-items-center justify-content-between">
                      <div class="text-white px-3 bg-primary rounded py-2">
                        {{ totalQty }}
                      </div>
                      <button class="btn btn-sm btn-warning ms-2 mb-0 px-2" @click="clearCart">ล้าง</button>
                     </div>
                      <div class="w-50 bg-dark px-3">
                        <h2><strong class="text-right text-white">{{ formatPrice(totalPrice) }}</strong></h2>
                      </div>
                    </div>
                    <div class="row p-4">
                      <div class="col-4 col-md-3 col-lg-3 mb-3" v-for="(item, index) in paginatedProducts" :key="index">
                        <div class="text-white ms-2 px-0 text-nowarp bg-primary rounded" style="    margin-top: 10px;position: absolute;
                            z-index: 99;
                            width: 40px;
                          " v-if="returnQty(item)>=1" @click="editQty(item)"   
                          data-bs-toggle="modal" data-bs-target="#exampleModalQty"
                        >
                          {{ returnQty(item) }}
                        </div>
                        <div class="card h-100 shadow-sm" @click="saleProducts(item)">
                          <img :src="'../../uploads/' + item.image" class="card-img-top" alt="image" style="max-height: 180px; object-fit: contain;" />
                          <div class="card-body p-1 p-md-2 text-center">
                            <div class="card-title mb-0">{{ item.product_name }} {{ formatPrice(item.quantity) }}฿</div>
                            <!-- <p class="card-text text-muted">{{ item.category_name }}</p> -->
                            <!-- <button class="btn d-none" id="barcode" data-bs-toggle="modal" data-bs-target="#exampleModalCalculat"></button> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-xxl btn-success w-50 px-0 " @click="handleCheckout" v-if="totalQty > 0" style="border-radius:20px;position: fixed; bottom: 95px;right: 23%;" data-bs-toggle="modal" data-bs-target="#exampleModalCalculat">
                      <h1>ยืนยันการขาย</h1>
                    </button>
                  </div>
                  <div class="col-12 col-md-8 text-center d-none d-sm-none d-md-block d-lg-block">
                    <div class="row p-4">
                      <div class="col-4 col-md-3 col-lg-3 mb-3" v-for="(item, index) in paginatedProducts" :key="index">
                        <div class="card h-100 shadow-sm" @click="saleProducts(item)">
                          <img :src="'../../uploads/' + item.image" class="card-img-top" alt="image" style="max-height: 180px; object-fit: contain;" />
                          <div class="card-body p-1 p-md-2 text-center">
                            <div class="card-title mb-0">{{ item.product_name }} {{ formatPrice(item.quantity) }}฿</div>
                            <!-- <p class="card-text text-muted">{{ item.category_name }}</p> -->
                            <!-- <button class="btn d-none" id="barcode" data-bs-toggle="modal" data-bs-target="#exampleModalCalculat"></button> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 text-center border-left  d-none d-sm-none d-md-block d-lg-block">
                    <!-- 🛒 ตะกร้าสินค้า -->
                    <div class="card mt-4">
                      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">รายการขาย</h5>
                        <button class="btn btn-sm btn-warning" @click="clearCart">ล้างรายการ</button>
                      </div>
                      <div class="card-body p-2" v-if="cart.length > 0">
                        <table class="table table-bordered table-sm mb-2">
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in cart" :key="index">
                              <td>
                                <img :src="'../../uploads/' + item.image" class="card-img" alt="image" style="max-height: 50px; object-fit: contain;" />
                              </td>
                              <td>
                                <div class="text-left">
                                  {{ item.product_name }} ฿{{ formatPrice(item.price) }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="d-flex justify-content-between align-items-start">
                                    x <input type="text" oninput="this.value = this.value.replace(/[^0-9_]/g, '');" class="form-control form-control-sm mx-1" v-model.number="item.qty" min="1" style="width:50px" />
                                  </div>
                                  <div>
                                    <button class="rounded p-2" style="line-height: 0.8;" @click="updateQty(item, item.qty + 1)"> +</button>
                                    <button class="rounded p-2" style="line-height: 0.8;" @click="updateQty(item, item.qty > 1 ? item.qty - 1 : 1)"> -</button>
                                    <button class="btn btn-sm btn-danger p-2 ms-2 mb-0" @click="removeItem(index)">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                      </svg>
                                    </button>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <h5 class="text-end px-3 text-nowrap">💰 รวมทั้งหมด: {{ formatPrice(totalPrice) }} บาท</h5>
                        <div class="text-end px-3">
                          <button class="btn btn-success" @click="handleCheckout" data-bs-toggle="modal" data-bs-target="#exampleModalCalculat">ยืนยันการขาย</button>
                        </div>
                      </div>
                      <div class="card-body text-center text-muted" v-else>
                        ไม่มีรายการสินค้าในตะกร้า
                      </div>
                    </div>
                  </div>
                </div>
                <!-- checkout -->
              </div>
              <div class="card-body px-0 pt-0 pb-2" v-else>
                <div class="row p-4 row align-items-center justify-content-center">
                  <div class="col-12 col-md-8 text-center">
                    เลือกสาขา?
                    <div class="row">
                      <div class="col-4 col-md-3" v-for="(item, i) in dataWarehouses">
                        <button class="btn" @click="selecthWarehouse(item.id)">
                          <h1><svg xmlns="http://www.w3.org/2000/svg" width="68" height="68" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                              <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                            </svg>
                          </h1>
                          {{item.name}}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <nav aria-label="Page navigation mt-0">
            <ul class="pagination">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">«</a>
              </li>
              <li
                v-for="page in totalPages"
                :key="page"
                class="page-item"
                :class="{ active: currentPage === page }">
                <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
              </li>

              <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">»</a>
              </li>
            </ul>
          </nav>
        </div>

        <div class="modal fade" id="exampleModalDel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">ลบรายการสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>ต้องการลบ <b>{{editData.product_name}}</b> ราคาขาย {{editData.quantity}}</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" @click="delData()">บันทึกข้อมูล</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="exampleModalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">บาร์โค๊ดสินค้า <strong>{{ iDetail.product_name }} {{ formatPrice(iDetail.quantity) }} บาท</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <div class=" h-100 " v-if="!isSale">
                  <img :src="'../'+iDetail.image" class="card-img-top" alt="image" style="max-height: 180px; object-fit: contain;" />

                  <div class="p-2 text-center">
                    <h5 class="card-title mb-0">{{ iDetail.product_name }} {{ formatPrice(iDetail.quantity) }}฿</h5>
                    <!-- <p class="card-text text-muted">{{ iDetail.category_name }}</p> -->
                    <div class="text-center">
                      <span class="text-wrap badge bg-secondary d-none">ทุน: {{ formatPrice(iDetail.price) }}฿</span>
                      <div class="text-wrap text-muted mt-1 mx-2">ขายแล้ว: {{ formatPrice(iDetail.unit) }}</div>
                      <div class="text-wrap text-muted mt-1">เหลือ: {{ formatPrice(iDetail.uint) }}</div>
                      <div v-if="iDetail.image_barcodes">
                        <img :src="'../../api/'+iDetail.image_barcodes" width="200" alt="">
                      </div>
                      <div v-else>
                        <img :src="'../../api/'+editData.image_barcodes" width="200" alt="">
                        <button class="btn btn-primary" @click="genBarcode">สร้างบาร์โค๊ด</button>
                      </div>
                      <div id="printArea" class="d-none">
                        <div style="text-align: center;">
                          <img :src="'../../api/'+iDetail.image_barcodes" width="200" alt="Barcode" style="max-width: 100%;" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="card-footer p-2">
                    <div class="row px-1 px-md-4">
                      <div class="col-12 col-md-6 text-center">
                        <button class="btn btn-danger" @click="delModal(iDetail)">
                          <i class="bi bi-trash-fill"></i>
                          คืน
                        </button>
                      </div>
                      <div class="col-12 col-md-6 text-center">
                        <button class="btn btn-md btn-info" @click="saleProducts(iDetail)">
                          <i class="bi bi-pencil-square"></i>
                          ขาย
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <img :src="'../'+iDetail.image" class="card-img-top" alt="image" style="max-height: 50px; object-fit: contain;" />
                  <b class="card-title mb-0">{{ iDetail.product_name }} {{ formatPrice(iDetail.quantity) }}฿</b> x {{quantityUnit}} <button class="rounded p-2" @click="addqQantityUnit"> +</button>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success" @click="printBarcode">
                  <!-- https://feathericons.dev/?search=printer&iconset=feather -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect height="8" width="12" x="6" y="14" />
                  </svg>
                  พิมพ์บาร์โค้ด</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="exampleModalCalculat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">ขายสินค้า <strong>{{ iDetail }} {{ formatPrice(totalPr) }} บาท</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <div v-if="checkout" class="row ">
                  <div class="col-12 text-center px-2">
                    <!-- เลือกประเภทการชำระเงิน -->
                    <div class="form-group mt-3">
                      <label>ประเภทการชำระเงิน</label>
                      <select v-model="paymentType" class="form-control">
                        <option value="">-- เลือก --</option>
                        <option value="เงินสด">เงินสด</option>
                        <option value="โอนเงิน">โอนเงิน</option>
                        <option value="พร้อมเพย์">พร้อมเพย์</option>
                        <!-- <option value="บัตรเครดิต">บัตรเครดิต</option> -->
                      </select>
                    </div>

                    <!-- ถ้าเลือกเงินสด ให้แสดงรายการเหรียญและธนบัตร -->
                    <div v-if="paymentType === 'เงินสด'" class="mt-3">
                      <label>ลูกค้าจ่ายด้วย</label>
                      <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-md-3 mx-md-2 col-5 mb-2" v-for="(value, i) in cashBreakdown" :key="i">
                          <label class="form-label">{{ i }} บาท</label>
                          <img v-if="i==1" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==5" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==10" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==20" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==50" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==100" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==500" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <img v-if="i==1000" :src="'../../assets/img/money/'+i+'.png'" height="50px" alt="">
                          <input
                            type="number"
                            min="0"
                            class="form-control"
                            placeholder="จำนวน"
                            v-model.number="cashBreakdown[i]" />
                        </div>
                      </div>
                      <div class="mt-3 d-flex align-items-center justify-content-between">
                        <strong class="text-left">รวมที่ลูกค้าจ่ายมา:</strong>
                        <strong class="text-right">{{ totalCashFromBreakdown }} บาท</strong>
                      </div>
                    </div>
                    <div v-if="paymentType === 'โอนเงิน' || paymentType === 'พร้อมเพย์'" class="mt-3">
                      <input v-model="amount" placeholder="ระบุยอดเงิน" />
                      <button @click="generateQR">สร้าง QR</button>
                      <div v-if="qrDataUrl">
                        <h4 class="my-2">แสกน QR</h4>
                        <img :src="qrDataUrl" alt="qr" style="max-width: 300px;" />
                      </div>
                    </div>
                    <!-- สรุปยอด -->
                    <div class="mt-3 px-2 d-flex align-items-center justify-content-between bg-dark text-white">
                      <strong class="text-left">ยอดรวมสินค้า:</strong>
                      <strong class="text-right">{{ formatPrice(totalPr) }} บาท</strong>
                    </div>
                    <div class="mt-3 px-2 d-flex align-items-center justify-content-between">
                      <strong class="text-left">เงินทอน:</strong>
                      <strong class="text-right px-2 bg-secondary text-white" v-if="totalCashFromBreakdown">{{ (totalCashFromBreakdown - totalPr).toFixed(2) }} บาท</strong>
                      <strong class="text-right px-2 bg-secondary text-white" v-else>0 บาท</strong>
                    </div>

                    <!-- ปุ่มยืนยัน -->
                    <button class="btn btn-success mt-3" @click="handleCheckout" :disabled="paymentType === ''">ยืนยันการชำระเงิน</button>
                  </div>
                </div>
                <!-- Checkout UI -->
                <div v-if="checkoutCf">
                  <h4>ใบเสร็จชำระเงิน</h4>
                  <div id="receipt" class="px-4">
                    <p>🧾 วันที่: {{ new Date().toLocaleString() }}</p>
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th style="text-align:left">สินค้า</th>
                          <th style="width:50px">จำนวน</th>
                          <th style="width:50px">ราคา</th>
                          <th style="width:50px">ส่วนลด</th>
                          <th style="width:50px">เหลือ</th>
                          <th style="width:50px">รวม</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in cart" :key="item.id">
                          <td class="text-left">{{ item.product_name }}</td>
                          <td>{{ item.qty }}</td>
                          <td>{{ formatPrice(item.price) }}</td>
                          <td style="color:#888">{{ formatPrice(item.discount_per_unit) }}</td>
                          <td>{{ formatPrice(item.price_per_unit) }}</td>
                          <td class="text-right">{{ formatPrice(item.total_price_item) }}</td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">รวมทั้งหมด:</td>
                          <td class="text-right">{{ formatPrice(totalPr) }}</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">เงินที่รับมา:</td>
                          <td class="text-right" v-if="totalCashFromBreakdown">{{ (totalCashFromBreakdown) }} </td>
                          <td v-else class="text-right">0</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">เงินทอน:</td>
                          <td class="text-right" v-if="totalCashFromBreakdown">{{ (totalCashFromBreakdown - totalPr) }} </td>
                          <td v-else class="text-right">0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div>
                      <button class="btn btn-secondary me-2 px-2" @click="checkPromotion">🔢 คิดเงิน</button>
                    </div>
                    <div>
                      <button class="btn btn-success me-2" @click="confirmSale">✅ บันทึกการขาย</button>
                      <button class="btn btn-secondary" @click="printReceipt">🖨️ ปริ้นใบเสร็จ</button>
                    </div>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="exampleModalQty" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body text-center">
                <div class="row ">
                  <div class="col-12 text-center px-2">
                    <!-- ถ้าเลือกเงินสด ให้แสดงรายการเหรียญและธนบัตร -->
                    <div class="mt-3 text-left">
                     <h3>แก้ไขจำนวน</h3>
                      <div class="row d-flex align-items-center justify-content-between">
                        <div class="col-md-3 mx-md-2 col-5 mb-2">
                          <input
                            type="number"
                            min="0"
                            class="form-control"
                            placeholder="จำนวน"
                            v-model.number="thisItem" />

                        </div>
                        <div class="col-md-3 mx-md-2 col-5 mb-2">
                        <button class="btn btn-success mt-3" @click="handleQty">ยืนยัน</button>
                        </div>
                      </div>
                    </div>
                    <!-- ปุ่มยืนยัน -->
                  </div>
                </div>
                <!-- Checkout UI -->
                <div v-if="checkoutCf">
                  <h4>ใบเสร็จชำระเงิน</h4>
                  <div id="receipt" class="px-4">
                    <p>🧾 วันที่: {{ new Date().toLocaleString() }}</p>
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th style="text-align:left">สินค้า</th>
                          <th style="width:50px">จำนวน</th>
                          <th style="width:50px">ราคา</th>
                          <th style="width:50px">ส่วนลด</th>
                          <th style="width:50px">เหลือ</th>
                          <th style="width:50px">รวม</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in cart" :key="item.id">
                          <td class="text-left">{{ item.product_name }}</td>
                          <td>{{ item.qty }}</td>
                          <td>{{ formatPrice(item.price) }}</td>
                          <td style="color:#888">{{ formatPrice(item.discount_per_unit) }}</td>
                          <td>{{ formatPrice(item.price_per_unit) }}</td>
                          <td class="text-right">{{ formatPrice(item.total_price_item) }}</td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">รวมทั้งหมด:</td>
                          <td class="text-right">{{ formatPrice(totalPr) }}</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">เงินที่รับมา:</td>
                          <td class="text-right" v-if="totalCashFromBreakdown">{{ (totalCashFromBreakdown) }} </td>
                          <td v-else class="text-right">0</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-left">เงินทอน:</td>
                          <td class="text-right" v-if="totalCashFromBreakdown">{{ (totalCashFromBreakdown - totalPr) }} </td>
                          <td v-else class="text-right">0</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div>
                      <button class="btn btn-secondary me-2 px-2" @click="checkPromotion">🔢 คิดเงิน</button>
                    </div>
                    <div>
                      <button class="btn btn-success me-2" @click="confirmSale">✅ บันทึกการขาย</button>
                      <button class="btn btn-secondary" @click="printReceipt">🖨️ ปริ้นใบเสร็จ</button>
                    </div>
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              </div>
            </div>
          </div>
        </div>
        <footer class="footer pt-3  ">
          <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
              <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                  ©
                  <script>
                    document.write(new Date().getFullYear())
                  </script> Soft by <img src="../../assets/img/lgg.png" width="20" alt="logo">ɢɪɢᴀᴊᴜ ꜱᴛᴜᴅɪᴏ.
                </div>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
  <script type="module" src="https://unpkg.com/vue@3/dist/vue.esm-browser.js"></script>
  <script>
    // เอา path ปัจจุบันจาก URL (ไม่รวม domain)
    const currentPath = window.location.pathname;

    // เลือกทุกลิงก์ใน sidebar
    const navLinks = document.querySelectorAll('.sidenav .nav-link');

    navLinks.forEach(link => {
      // สร้าง element เพื่อเช็ก pathname ของลิงก์
      const linkPath = new URL(link.href).pathname;

      // ถ้า pathname ตรงกับ path ปัจจุบัน ให้เพิ่ม class active
      if (currentPath === linkPath) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
  </script>
  <script type="module">
    const {
      createApp
    } = Vue;
    createApp({
      data() {
        return {
          auth: "",
          hidId: '',
          warehouseId: '',
          barcodeId: '',
          selectedProduct: null,
          iDetail: '',
          quantityUnit: 1,
          whName: '',
          modalDel: false,
          isSale: false,
          checkout: false,
          checkoutCf: false,
          usersError: false,
          passError: false,
          textError: "",
          dataCategory: '',
          dataWarehouses: '',
          barcode_image: '',
          totalPr: 0,
          editData: {
            barcode_image: ''
          },
          image_url: '',
          formData: {
            category_id: '',
            warehouses_id: [],
            name: '',
            price: '',
            quantity: '',
            description: ''
          },
          searchKeyword: '',
          filteredProducts: [],
          image: null,
          products: [],
          currentPage: 1,
          perPage: 10,
          cart: [],
          paymentType: '',
          cashBreakdown: {
            1000: 0,
            500: 0,
            100: 0,
            50: 0,
            20: 0,
            10: 0,
            5: 0,
            1: 0
          },
          amount: '0',
          qrDataUrl: '',
          dataRoles: '',
          employee: '',
          clickCount: 0,
          editingProduct: null, 
          thisItem: 0
        }
      },

      watch: {
        cart: {
          handler(newVal) {
            newVal.forEach(item => {
              if (item.qty < 1) item.qty = 0;
            });
          },
          deep: true
        }
      },
      computed: {
        totalQty() {
    return (this.cart || []).reduce((sum, r) => sum + (Number(r.qty) || 0), 0);
  },
        hasRole1() {
          return this.dataRoles && this.dataRoles.some(r => r.id === 1);
        },
        totalCashFromBreakdown() {
          return Object.entries(this.cashBreakdown).reduce((total, [denom, count]) => {
            return total + parseInt(denom) * (count || 0);
          }, 0);
        },
        selectedCategoryName() {
          const selected = this.dataCategory.find(cat => cat.id === this.formData.category_id);
          return selected ? selected.cate_name : 'ยังไม่ได้เลือก';
        },

        totalPages() {
          return Math.ceil(this.products.length / this.perPage);
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.products.slice(start, end);
        },
        totalPrice() {
          if (this.cart) {
            return this.cart.reduce((sum, item) => sum + item.price * item.qty, 0);
          }
        }
      },
      mounted() {
        // const urlParams = new URLSearchParams(window.location.search);
        // this.warehouseId = urlParams.get('id'); // ค่าจากพารามิเตอร์ ?id= ใน URL
        // this.whName = urlParams.get('warehousename'); // ค่าจากพารามิเตอร์ ?id= ใน URL


        const profile = JSON.parse(localStorage.getItem("Fin-Profile"));

        if (!profile || profile === "undefined") {
          // ตรวจสอบว่าค่าเป็น null, undefined หรือ "undefined"
          Swal.fire({
            toast: true,
            position: "top-end", // ตำแหน่งของ Toast
            icon: "warning", //ไอคอน (success, error, warning, info, question)
            title: "session die !", // ข้อความหลัก
            showConfirmButton: false, // ไม่แสดงปุ่มยืนยัน
            timer: 3000, // ระยะเวลาแสดง (ms)
            timerProgressBar: true, // แสดงแถบเวลา
          });
          window.location = "../";
        } else {
          this.getEmployeeRoles(profile.data.id);
          this.loadWarehouses();
          this.generateQR();
        }
      },
      methods: {
        async getEmployeeRoles(empId) {
          const res = await axios.post("../../api/", {
            post: "get_employee_roles",
            employee_id: empId
          });
          if (res.data.status) {
            this.employee = res.data.employee;
            this.dataRoles = res.data.roles;
          }
        },
        // ล้างสินค้าทั้งหมดในตะกร้า
        clearCart() {
          if (confirm("คุณต้องการล้างรายการทั้งหมดหรือไม่?")) {
            axios.post('../../api/', {
                post: 'clear_cart',
                warehouse_id: this.warehouseId
              })
              .then(res => {
                if (res.data.status) {
                  this.cart = [];
                }
              })
              .catch(err => console.error(err));
          }
        },
        checkPromotion() {
          this.checkout = true;
          this.checkoutCf = false;
          // console.log(this.cart);
          // console.log('totalPrice',this.totalPrice);
          axios.post('../../api/', {
              post: 'check_promotion',
              cart: this.cart,
              warehouse_id: this.warehouseId
            })
            .then(res => {
              if (res.data.status) {
                this.cart = res.data.cart;
                this.totalPr = res.data.total
              }
            })
            .catch(err => console.error(err));
        },
        // ลบสินค้ารายการเดียว
        removeItem(index) {
          const item = this.cart[index];
          if (confirm(`คุณต้องการลบสินค้า "${item.product_name}" หรือไม่?`)) {
            axios.post('../../api/', {
                post: 'remove_item',
                product_id: item.product_id
              })
              .then(res => {
                if (res.data.status) {
                  this.cart.splice(index, 1);
                }
              })
              .catch(err => console.error(err));
          }
        },

        // อัปเดตจำนวนสินค้า
        // updateQty(item, newQty) {
        //   if (newQty < 1) return;
        //   item.qty = newQty;
        //   axios.post('../../api/', {
        //     post: 'update_qty',
        //     product_id: item.product_id,
        //     qty: newQty
        //   }).catch(err => console.error(err));
        // },
        async searchByBarcode() {
          if (!this.barcode) return;

          try {
            const response = await axios.post('../../api/', {
              post: 'get_product_by_id',
              id: this.barcode // สมมุติว่า barcode คือรหัสสินค้า (id)
            });

            if (response.data.status && response.data.product) {
              this.selectedProduct = response.data.product;

              // เปิด Modal
              const modal = new bootstrap.Modal(document.getElementById('productModal'));
              modal.show();
            } else {
              alert('ไม่พบสินค้า');
            }
          } catch (err) {
            console.error('เกิดข้อผิดพลาด:', err);
            alert('เกิดข้อผิดพลาดในการค้นหา');
          }

          this.barcode = ''; // clear หลังสแกน
        },
        buildPromptpayPayload(mobile, amount) {
          const formatMobile = mobile.replace(/[^0-9]/g, '');
          const id = '0066' + formatMobile.slice(1); // ลบ 0 นำหน้า แล้วต่อด้วยรหัสประเทศไทย

          const payload = [
            '000201', // Payload format
            '010212', // Point of Initiation method
            '29370016A0000006770101110113' + id, // Merchant Account Info
            '5802TH', // Country
            '5303764', // Currency (764 = THB)
            `540${(amount.length + 2)}${amount}`, // Amount
            '6304' // CRC (จะคำนวณทีหลัง)
          ].join('');

          return payload + this.calculateCRC(payload);
        },

        handleCheckout(payload) {
          this.checkPromotion();
          this.checkoutCf = true;
          this.checkout = false;
          if (this.paymentType == 'โอน') {
            console.log('');
          }
        },
        returnQty(item) {
          const found = this.cart?.find(c => c.product_id === item.id);
          return found ? found.qty : 0;
        },
        editQty(item) {
          this.editingProduct = item;               // จำสินค้าที่กำลังจะแก้จำนวน
          this.thisItem = this.returnQty(item) || 1; // เติมค่าเริ่มต้นเป็นจำนวนในตะกร้าปัจจุบัน
        },
        calculateCRC(payload) {
          let crc = 0xFFFF;

          for (let i = 0; i < payload.length; i++) {
            crc ^= payload.charCodeAt(i) << 8;
            for (let j = 0; j < 8; j++) {
              if ((crc & 0x8000) !== 0) {
                crc = (crc << 1) ^ 0x1021;
              } else {
                crc <<= 1;
              }
            }
          }
          crc &= 0xFFFF;
          return crc.toString(16).toUpperCase().padStart(4, '0');
        },
        generateQR() {
          const mobile = '0653035491'; // เปลี่ยนเป็นเบอร์ PromptPay จริง
          const payload = this.buildPromptpayPayload(mobile, parseFloat(this.amount).toFixed(2));
          QRCode.toDataURL(payload, {
            errorCorrectionLevel: 'H'
          }, (err, url) => {
            if (err) return console.error(err);
            this.qrDataUrl = url;
          });
        },
        // generateQR() {
        //   this.amount = this.totalPrice;
        //   console.log('amount',this.amount);
        //   console.log('totalPrice',this.totalPrice);
        //   const promptpayId = '0653035491'; // ใส่เลข PromptPay หรือบัตร ปชช.
        //   const payload = `00020101021129370016A00000067701011101130066${promptpayId}5802TH53037646304${parseFloat(this.amount).toFixed(2)}6304`;

        //   QRCode.toDataURL(payload, { errorCorrectionLevel: 'H' }, (err, url) => {
        //     if (err) return console.error(err);
        //     this.qrDataUrl = url;
        //   });
        // },
        formatPrice(value) {
          return Number(value).toLocaleString('th-TH', {
            minimumFractionDigits: 2
          });
        },
        detailProduct(item) {
          console.log('item', item);
          this.iDetail = item;
        },
        addqQantityUnit(u) {
          u++;
        },
        minusqQantityUnit(u) {
          if (u > 0) u--;
        },
        toggleWarehouse(id) {
          // console.log('ค่า warehouses_id ปัจจุบัน:', this.formData.warehouses_id);
          // console.log('type:', typeof this.formData.warehouses_id);
          // console.log('isArray:', Array.isArray(this.formData.warehouses_id));

          if (!Array.isArray(this.formData.warehouses_id)) {
            this.formData.warehouses_id = [];
          }

          const index = this.formData.warehouses_id.indexOf(id);
          if (index === -1) {
            this.formData.warehouses_id.push(id);
          } else {
            this.formData.warehouses_id.splice(index, 1);
          }
        },
        removeWarehouse(id) {
          const index = this.formData.warehouses_id.indexOf(id);
          if (index !== -1) {
            this.formData.warehouses_id.splice(index, 1);
          }
        },
        getWarehouseName(id) {
          const warehouse = this.dataWarehouses.find(w => w.id === id);
          return warehouse ? warehouse.name : 'ไม่พบ';
        },
        handleFileUploads(e) {
          const file = e.target.files[0];
          if (file) {
            this.image = file;
            this.image_url = URL.createObjectURL(file);
          }
        },
        getWarehouseNameById(id) {
          const found = this.dataWarehouses.find(w => w.id === id);
          return found ? found.name : 'ไม่พบชื่อคลัง';
        },
        selecthWarehouse(id) {
          this.warehouseId = id;
          if (this.warehouseId) {
            this.searchWarehouse();
            this.getCart();
          }
        },
        async confirmSale() {
          let receivedMoney = 0;
          if (this.totalCashFromBreakdown) {
            receivedMoney = this.totalCashFromBreakdown;
          }
          await axios.post('../../api/', {
            post: 'generate_receipt_html',
            cart: this.cart,
            warehouse_name: this.whName,
            cash: this.paymentType,
            warehouse_id: this.warehouseId,
            total: this.totalPr,
            received: receivedMoney
          }).then(res => {
            if (res.data.status) {
              this.getCart();
              alert("ขายสินค้าเรียบร้อย");
              const modalElement = document.getElementById('exampleModalCalculat');
              const modalInstance = bootstrap.Modal.getInstance(modalElement); // ดึง instance ที่เปิดอยู่
              modalInstance.hide();
              // window.open(res.data.receipt_url, '_blank');
            }
          });
        },
        async searchWarehouse() {
          // หา object ของคลังจาก id ที่เลือก
          const selectedWh = this.dataWarehouses.find(w => String(w.id) === String(this.warehouseId));
          this.whName = selectedWh ? selectedWh.name : ''
          if (this.warehouseId) {
            try {
              const response = await axios.post('../../api/', {
                post: 'get_products_in_wh',
                warehouses_id: this.warehouseId
              });
              if (response.data.status) {
                this.products = response.data.products;
                // อัปเดตจำนวนสินค้านั้น
                this.paginatedProducts = this.paginatedProducts.map(product => {
                  const found = this.cart.items.find(c => c.product_id === product.id);
                  return {
                    ...product,
                    qty: found ? found.qty : 0
                  };
                });
              }
            } catch (error) {
              console.error('Error fetching products:', error);
            }
          }
          this.getCart();
        },
        searchProduct() {
          if (this.searchKeyword.length > 1) {
            axios.post('../../api/', {
              post: 'search_products',
              keyword: this.searchKeyword
            }).then(res => {
              this.products = res.data.products;
            }).catch(err => {
              console.error(err);
            });
          } else {
            this.getProducts();
          }
        },
        async saleProducts(item) {
          this.clickCount++;
          item.clickCount++;
          console.log('ดู', this.paginatedProducts);
          try {
            const response = await axios.post("../../api/", {
              post: "add_to_cart",
              product_id: item.id,
              warehouse_id: this.warehouseId,
              qty: 1,
              price: item.price,
            });

            if (response.data.status) {
              this.cart = response.data.cart;

              // อัปเดตจำนวนรวม
              this.clickCount++;

              // อัปเดตราคารวม (สมมติ backend ส่ง totalPrice มา)
              this.totalPrice = this.cart.totalPrice;
            } else {
              console.log("เกิดข้อผิดพลาด:", response.data.message);
            }
          } catch (error) {
            console.error('Error adding to cart:', error);
            //alert('ไม่สามารถเชื่อมต่อกับระบบได้');
          }
        },
        async handleQty() {
          if (!this.editingProduct) {
            alert('ไม่พบสินค้าที่ต้องการแก้จำนวน');
            return;
          }

          const newQty = Number(this.thisItem);
          if (Number.isNaN(newQty) || newQty < 0) {
            alert('กรุณาระบุจำนวนให้ถูกต้อง');
            return;
          }

          // หา index ของสินค้าที่กำลังแก้ในตะกร้า
          const index = this.cart.findIndex(
            (c) => String(c.product_id) === String(this.editingProduct.id)
          );

          if (newQty === 0) {
            if (index === -1) {
              alert('ไม่พบสินค้าในตะกร้า');
              return;
            }
            // ใช้ฟังก์ชัน removeItem(index) ตามที่ขอ
            this.removeItem(index);

            // ปิดโมดัล + เคลียร์สถานะชั่วคราว
            const modalEl = document.getElementById('exampleModalQty');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            this.editingProduct = null;
            this.thisItem = 0;
            return;
          }

          // กรณีอัปเดตเป็นจำนวน > 0
          try {
            const res = await axios.post('../../api/', {
              post: 'update_cart',
              warehouse_id: this.warehouseId,
              product_id: this.editingProduct.id,
              qty: newQty,
            });

            if (res.data?.status) {
              this.cart = res.data.cart;

              const modalEl = document.getElementById('exampleModalQty');
              const modal = bootstrap.Modal.getInstance(modalEl);
              if (modal) modal.hide();

              this.editingProduct = null;
              this.thisItem = 0;
            } else {
              alert(res.data?.message || 'อัปเดตจำนวนไม่สำเร็จ');
            }
          } catch (err) {
            console.error('Error updating cart:', err);
            alert('เกิดข้อผิดพลาดในการอัปเดตจำนวน');
          }
        },
        async getCart() {
          try {
            const response = await axios.post('../../api/', {
              post: 'get_cart',
              warehouse_id: this.warehouseId
            });

            if (response.data.status) {
              this.cart = response.data.cart;
              this.amount = this.formatPrice(totalPrice);
            } else {
              console.warn('ไม่พบข้อมูลในตะกร้าจากคลังนี้');
            }
          } catch (error) {
            console.error('เกิดข้อผิดพลาดในการโหลดตะกร้า:', error);
          }
        },
        genBarcode() {
          this.hidId = this.iDetail.id;
          this.editData = this.iDetail;
          axios.post('../../api/gen_barcode.php', {
              product_id: this.iDetail.id,
              product_name: this.iDetail.product_name
            })
            .then(res => {
              if (res.data.status) {
                this.editData.barcode_image = res.data.image_url;
              } else {
                alert("เกิดข้อผิดพลาดในการสร้างบาร์โค้ด");
              }
            });
        },
        printBarcode() {
          const printContent = document.getElementById('printArea').innerHTML;
          const win = window.open('', '', 'width=600,height=400');
          win.document.write(`
              <html>
                <head>
                  <title>พิมพ์บาร์โค้ด</title>
                  <style>
                    body { font-family: Arial, sans-serif; text-align: center; }
                    img { max-width: 100%; height: auto; }
                  </style>
                </head>
                <body onload="window.print(); window.close();">
                  ${printContent}
                </body>
              </html>
            `);
          win.document.close();
        },
        delModal(item) {
          this.hidId = item.id;
          this.editData = item;
        },
        changePage(page) {
          if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
          }
        },
        formatThaiDate(dateStr) {
          return dayjs(dateStr).format("HH:mm");
          // return dayjs(dateStr).format("D MMMM YYYY เวลา HH:mm");
        },

        showToast(txt, icn) {
          wal.fire({
            toast: true,
            position: "top-end", // ตำแหน่งของ Toast
            icon: icn, // ไอคอน (success, error, warning, info, question)
            title: txt, // ข้อความหลัก
            showConfirmButton: false, // ไม่แสดงปุ่มยืนยัน
            timer: 5000, // ระยะเวลาแสดง (ms)
            timerProgressBar: true, // แสดงแถบเวลา
          });
        },
        editProducts(item) {
          this.formData = item;
          this.formData.warehouses_id = item.warehouses_id
            .split(',')
            .map(id => parseInt(id)); // แปลงเป็น array
          this.getType();
        },
        async getProducts() {
          try {
            const response = await axios.post('../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: this.warehouseId
            });
            if (response.data.status) {
              this.products = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        async updateCart() {
          try {
            axios.post('../../api/', {
              post: 'update_cart_bulk',
              warehouse_id: this.warehouseId,
              items: this.cart
            });
            if (response.data.status) {
              this.cart = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        async updateQty(item, newQty) {
          item.qty = newQty;

          await axios.post('../../api/', {
              post: 'update_cart',
              warehouse_id: this.warehouseId,
              product_id: item.product_id,
              qty: item.qty
            })
            .then(res => {
              if (!res.data.status) {
                alert(res.data.message);
                return false;
              }
              this.cart = res.data.cart;
              this.totalPrice();
            })
            .catch(err => {
              console.error('เกิดข้อผิดพลาด:', err);
            });
        },
        // removeItem(index) {
        //   // ส่งลบรายการใน API ด้วยก็ได้
        //   this.cart.splice(index, 1);
        // },
        formatPrice(price) {
          return Number(price).toLocaleString(); // ใส่ comma (,)
        },
        getType: async function() {
          try {
            const url = '../../api/';
            const config = {
              headers: {
                'Content-Type': 'application/json'
              }
            };

            const response = await axios.post(url, {
              post: 'get_type'
            }, config);

            // ตรวจสอบ response และอัปเดต dataVdo
            if (response.data && response.data.data) {
              this.dataCategory = response.data.data;
              this.cateName = '';
            } else {
              console.log("ไม่พบข้อมูลประเภทสินค้า");
            }
          } catch (error) {
            console.error('เกิดข้อผิดพลาด:', error);
          }
        },
        loadWarehouses() {
          axios.post('../../api/', {
              post: 'get_warehouses_fproduct',
            })
            .then(res => {
              this.dataWarehouses = res.data.data

            })
            .catch(err => alert('โหลดคลังสินค้าล้มเหลว'));
        },
        handleFileUpload(event) {
          this.image = event.target.files[0];
        },
        async submitForm() {
          // Validation
          if (
            !this.formData.category_id ||
            !this.formData.name ||
            !this.formData.price ||
            !this.formData.quantity ||
            !this.formData.warehouses_id ||
            !this.image
          ) {
            Swal.fire("กรอกข้อมูลให้ครบถ้วน", "", "warning");
            return;
          }
          const profile = JSON.parse(localStorage.getItem("Fin-Profile"));
          const payload = new FormData();
          payload.append("post", "save_product");
          payload.append("category_id", this.formData.category_id);
          payload.append("name", this.formData.name);
          payload.append("price", this.formData.price);
          payload.append("quantity", this.formData.quantity);
          payload.append("description", this.formData.description);
          payload.append("image", this.image);
          payload.append("position", profile.data.position);
          payload.append("username", profile.data.username);
          payload.append("warehouses_id", this.formData.warehouses_id);

          try {
            const response = await axios.post("../../api/", payload, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            });

            if (response.data.status) {
              Swal.fire("บันทึกสำเร็จ", "", "success");
              setTimeout(() => {
                window.location.reload();
              }, 5000);
              // // รีเซตฟอร์มหรือโหลดใหม่
              // this.getProducts();
              // this.formData = {
              //   category_id: '',
              //   name: '',
              //   price: '',
              //   quantity: '',
              //   description: ''
              // };
            } else {
              Swal.fire("เกิดข้อผิดพลาด", response.data.message, "error");
            }
          } catch (error) {
            console.error(error);
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        },
        async editForm() {
          console.log('formData', this.formData);
          // Validation
          this.image
          if (
            !this.formData.category_id ||
            !this.formData.product_name ||
            !this.formData.id ||
            !this.formData.price ||
            !this.formData.quantity ||
            !this.formData.warehouses_id ||
            !this.image && this.formData.image == ''
          ) {
            Swal.fire("กรอกข้อมูลให้ครบถ้วน", "", "warning");
            return;
          }
          const profile = JSON.parse(localStorage.getItem("Fin-Profile"));
          const payload = new FormData();
          payload.append("post", "save_edit_product");
          payload.append("category_id", this.formData.category_id);
          payload.append("name", this.formData.product_name);
          payload.append("price", this.formData.price);
          payload.append("product_id", this.formData.id);
          payload.append("quantity", this.formData.quantity);
          payload.append("description", this.formData.description);
          payload.append("warehouses_id", this.formData.warehouses_id);
          payload.append("image", this.image);
          payload.append("position", profile.data.position);
          payload.append("username", profile.data.username);

          try {
            const response = await axios.post("../../api/", payload, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            });

            if (response.data.status) {
              Swal.fire("บันทึกสำเร็จ", "", "success");
              // รีเซตฟอร์มหรือโหลดใหม่
              this.getProducts();
              this.formData = {
                category_id: '',
                name: '',
                price: '',
                quantity: '',
                description: ''
              };
              this.image_url = '';
            } else {
              Swal.fire("เกิดข้อผิดพลาด", response.data.message, "error");
            }
          } catch (error) {
            console.error(error);
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        },
        async checkout() {
          const cash_received = this.paymentType === 'เงินสด' ? this.totalCashFromBreakdown : this.cashReceived;

          if (!this.paymentType || cash_received < this.totalPr) {
            alert('กรุณาระบุข้อมูลให้ครบถ้วน');
            return;
          }

          try {
            const response = await axios.post('../../api/', {
              post: 'checkout',
              warehouse_id: this.warehouseId,
              items: this.cart.map(item => ({
                product_id: item.id,
                qty: item.qty,
                price: item.price
              })),
              total_price: this.totalPr,
              cash_received: cash_received,
              payment_type: this.paymentType,
              breakdown: this.paymentType === 'เงินสด' ? this.cashBreakdown : {}
            });

            if (response.data.status) {
              alert('ชำระเงินสำเร็จ');
              this.cart = [];
              this.cashBreakdown = {
                1000: 0,
                500: 0,
                100: 0,
                50: 0,
                20: 0,
                10: 0,
                5: 0,
                1: 0
              };
              this.paymentType = '';
              this.loadCart();
            } else {
              alert(response.data.message || 'เกิดข้อผิดพลาด');
            }
          } catch (err) {
            console.error(err);
            alert('ไม่สามารถทำรายการได้');
          }
        },
        delData: async function() {
          try {
            const url = '../../api/';
            const config = {
              headers: {
                'Content-Type': 'application/json'
              }
            }
            await axios.post(url, {
              post: 'del_product_id',
              id: this.hidId,
            }, config).then(function(response) {
              if (response.data.status) {
                Swal.fire("ลบสำเร็จ", "", "success");
                this.getProducts();
              }
              Swal.fire("ลบสำเร็จ", "", "success");
              this.getProducts();
            });
          } catch (error) {
            console.log('error', error);
          }
        },
        logOut() {
          localStorage.removeItem("Profile");
          this.auth = "";
          setTimeout(() => {
            window.location = "../login";
          }, 800);
        },
      },
    }).mount("#app");
  </script>
</body>

</html>