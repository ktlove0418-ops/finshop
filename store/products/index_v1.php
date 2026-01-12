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
  <style>
    .active a.page-link {
      color: #fff;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <?php include('../layout/navbar.html'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4" id="app">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4" v-if="dataRoles && dataRoles.some(r => r.id === 8)">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">
              <button class="btn btn-primary mb-0" @click="getType" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                เพิ่มสินค้า
              </button>
              <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group mb-3">
                  <span class="input-group-text text-body">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                  </span>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="ค้นหาชื่อสินค้า..."
                    v-model="searchKeyword"
                    @input="searchProduct">
                </div>

                <!-- แสดงรายการสินค้า -->
                <ul class="list-group" v-if="filteredProducts.length">
                  <li class="list-group-item" v-for="(item, i) in filteredProducts" :key="i">
                    {{ item.name }} - {{ item.price }} บาท
                  </li>
                </ul>

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
                        <img :src="'../'+formData.image" alt="รูปสินค้า" style="max-height: 80px; height: auto;">
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

            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0" style="min-height: 280px;">
                <table class="table table-responsive align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รูป</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รายละเอียด</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อสินค้า</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">หมวดหมู่</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาทุน</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาขาย</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" v-if="dataRoles && dataRoles.some(r => r.id === 9)">จัดการ</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ผู้ดูแล</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in paginatedProducts" :key="index">
                      <td class="d-flex justify-content-start">
                        <img :src="'../../uploads/' + item.image" alt="image" class="mx-2" style="max-height: 60px;">
                      </td>
                      <td>
                        <div
                          v-html="item.description"
                          class="text-xs text-secondary mb-0"
                          style="text-align: left; white-space: pre-wrap; word-break: break-word;min-width: 140px;"></div>
                      </td>
                      <td>{{ item.product_name }} </td>
                      <td>{{ item.category_name }}</td>
                      <td>{{ formatPrice(item.price) }} บาท</td>
                      <td>
                        {{formatPrice(item.quantity) }}บาท
                      </td>
                      <td class="align-middle pt-3"  v-if="dataRoles && dataRoles.some(r => r.id === 9)">
                        <button class="btn btn-sm px-2 mx-1 btn-secondary" @click="genModal(item)" data-bs-toggle="modal" data-bs-target="#exampleModalGen">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                            <path d="M2 2h2v2H2z" />
                            <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                            <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                            <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                          </svg>
                        </button>
                        <button class="btn btn-sm px-2 mx-1 btn-info" @click="editProducts(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                          </svg>
                        </button>
                        <button class="btn btn-sm px-2 mx-1 btn-danger" @click="delModal(item)" data-bs-toggle="modal" data-bs-target="#exampleModalDel">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                          </svg>
                        </button>
                      </td>
                      <td class="text-xs">
                        <span v-html="item.person"></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
      <div class="modal fade" id="exampleModalGen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">บาร์โค๊ดสินค้า <strong>{{ editData.product_name }} {{ formatPrice(editData.quantity) }} บาท</strong></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
              <div v-if="editData.barcode_image">
                <img :src="'../../api/'+editData.barcode_image" alt="Barcode" class="img-fluid" />
                <!-- ซ่อนไว้สำหรับสั่งพิมพ์ -->
                <div id="printArea" style="display: none;">
                  <div style="text-align: center;">
                    <img :src="'../../api/'+editData.barcode_image" alt="Barcode" style="max-width: 100%;" />
                  </div>
                </div>
              </div>
              <div v-else>
                <i>
                  <!-- https://feathericons.dev/?search=clock&iconset=feather -->
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                  </svg>
                  กำลังโหลดข้อมูล..</i>
              </div>
              <!-- <b>{{editData.product_name}} {{formatPrice(editData.price)}}</b> -->
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

      <div class="d-none row mt-4">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Projects table</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Budget</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Completion</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm rounded-circle me-2" alt="spotify">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Spotify</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$2,500</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">working</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">60%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-invision.svg" class="avatar avatar-sm rounded-circle me-2" alt="invision">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Invision</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$5,000</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">done</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">100%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-jira.svg" class="avatar avatar-sm rounded-circle me-2" alt="jira">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Jira</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$3,400</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">canceled</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">30%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-danger" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="30" style="width: 30%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-slack.svg" class="avatar avatar-sm rounded-circle me-2" alt="slack">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Slack</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$1,000</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">canceled</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">0%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-webdev.svg" class="avatar avatar-sm rounded-circle me-2" alt="webdev">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Webdev</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$14,000</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">working</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">80%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="80" style="width: 80%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img src="../../assets/img/small-logos/logo-xd.svg" class="avatar avatar-sm rounded-circle me-2" alt="xd">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">Adobe XD</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">$2,300</p>
                      </td>
                      <td>
                        <span class="text-xs font-weight-bold">done</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">100%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar bg-gradient-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle">
                        <button class="btn btn-link text-secondary mb-0" aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
          whId: '',
          modalDel: false,
          usersError: false,
          passError: false,
          textError: "",
          dataCategory: '',
          dataWarehouses: '',
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
          dataRoles:'',
          employee:''
        };
      },
      computed: {

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

      },
      mounted() {

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
          this.getProducts();
          this.loadWarehouses();
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
        genModal(item) {
          this.hidId = item.id;
          this.editData = item;
          axios.post('../../api/gen_barcode.php', {
              product_id: item.id,
              product_name: item.product_name
            })
            .then(res => {
              if (res.data.status) {
                this.editData.barcode_image = res.data.image_url;
              } else {
                alert("เกิดข้อผิดพลาดในการสร้างบาร์โค้ด");
              }
            });
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
              post: 'get_products',
            });
            if (response.data.status) {
              this.products = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
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
            .then(res => this.dataWarehouses = res.data.data)
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
            !this.image && this.formData.image==''
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