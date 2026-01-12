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
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Sale Products Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->

  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <script src="../assets/js/vue/vue3.js"></script>
  <!-- axios -->
  <script src="../assets/js/axios/axios.0.9.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    window.location = "./transfer";
  </script>
  <style>
    .active a.page-link {
      color: #fff;
    }

    label,
    .form-label {
      margin-bottom: 0rem;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('./layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <?php include('./layout/navbar.html'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4" id="app">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">
              <div id="whName"></div>
              <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="form-group mb-3">
                  <label for="searchDate">ค้นหาวันที่:</label>
                  <input
                    type="date"
                    class="form-control"
                    id="searchDate"
                    v-model="searchDate"
                    @change="searchTransferByDate" />
                </div>

                <div class="input-group mb-3 mt-4 ms-3">
                  <label for="searchDate" style="position: absolute; top:-20px;white-space: nowrap;">เลือกคลังปลายทาง:</label>

                  <span class="input-group-text text-body" style="height: 40px;">
                    <h1><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                        <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                      </svg></h1>
                  </span>
                  <select class="form-select" v-model="warehouseId" style="height: 40px;" @change="searchWarehouse">
                    <option value="">เลือกคลังสินค้า</option>
                    <option value="">เลือกทั้งหมด</option>
                    <option v-for="(item, i) in dataWarehouses" :key="i" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                </div>
                <div class="input-group mb-3 mt-4 ms-3">
                  <!-- <label for="">ค้นหาชื่อสินค้า:</label> -->
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
                    @input="searchTransfer" />
                </div>
              </div>
            </div>
            <!-- <div class="card-body px-0 pt-0 pb-2" v-if="currentWh==''">
              <div class="row p-4 row align-items-center justify-content-center">
                <div class="col-12 col-md-8 text-center">
                  <h4>
                    <p>เลือกสาขา?</p>
                  </h4>
                  <div class="row mt-5">
                    <div class="col-4 col-md-3" v-for="(item, i) in dataWarehouses">
                      <button class="btn" @click="selecthWarehouse(item)">
                        <h1><svg xmlns="http://www.w3.org/2000/svg" width="68" height="68" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                          </svg></h1>
                        {{item.name}}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="card-body px-2 pt-0 pb-2">
              <div class="col-12">
                <div class="card mb-4">
                  <div class="card-body ">
                    <div class="d-flex align-items-center justify-content-between">
                      <div>
                        <h6>ข้อมูลการโอนสินค้า</h6>
                      </div>
                      <div>
                        <button class="btn btn-pramary" @click.prevent="openProductListModal()">ดูรายการสินค้า</button>
                      </div>
                    </div>
                    <!-- DESKTOP MODE -->
<div class="table-responsive d-none d-md-block" style="min-height: 280px;" v-if="dataRoles && dataRoles.some(r => r.id == 17)">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>วันที่</th>
        <th>เลขที่เอกสาร</th>
        <th>จากคลัง</th>
        <th>ไปยังคลัง</th>
        <th style="text-align: center;">สถานะการขนส่ง</th>
        <th>ดูเอกสาร</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="doc in paginatedDocuments" :key="doc.id">
        <td>{{ formatThaiDate(doc.created_at) }}</td>
        <td class="d-flex justify-content-start align-items-center">
          <div style="width: 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text me-2" viewBox="0 0 16 16">
              <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
              <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
            </svg>
          </div>
          <small>{{ doc.document_no }}</small>
        </td>
        <td>{{ doc.from_name }}</td>
        <td>{{ doc.to_name }}</td>
        <td style="text-align: center;" :class="doc.delivered!='wait' ? 'text-success' : 'text-warning'">
          {{ doc.delivered!='wait' ? 'ส่งแล้ว' : 'รอส่ง' }}
        </td>
        <td>
          <a href="#" class="btn btn-sm btn-primary" @click.prevent="openDocModal(doc)">
            เปิด
          </a>
        </td>
      </tr>
      <tr v-if="transferDocuments && transferDocuments.length < 1">
        <td colspan="7" class="w-200 text-center">- ไม่พบข้อมูล -</td>
      </tr>
    </tbody>
  </table>
</div>

<!-- MOBILE MODE -->
<div class="d-block d-md-none" v-if="dataRoles && dataRoles.some(r => r.id == 17)">
  <div v-for="doc in paginatedDocuments" :key="doc.id" class="card mb-3 shadow-sm">
    <div class="card-body">
      <h6 class="card-title mb-1">
        📄 {{ doc.document_no }}
      </h6>
      <p class="mb-1"><strong>วันที่:</strong> {{ formatThaiDate(doc.created_at) }}</p>
      <p class="mb-1"><strong>จาก:</strong> {{ doc.from_name }}</p>
      <p class="mb-1"><strong>ถึง:</strong> {{ doc.to_name }}</p>
      <p class="mb-2">
        <strong>สถานะ:</strong>
        <span :class="doc.delivered!='wait' ? 'text-success fw-bold' : 'text-warning fw-bold'">
          {{ doc.delivered!='wait' ? 'ส่งแล้ว' : 'รอส่ง' }}
        </span>
      </p>
      <!-- Overlay -->
      <div v-if="doc.delivered!='wait'"
                      class="position-absolute top-50 start-50 text-success fw-bold fs-4"
                      style="opacity:0.8; transform: rotate(-15deg);white-space: nowrap;">
                      ✅ ส่งแล้ว
                    </div>
      <a href="#" class="btn btn-sm btn-primary" @click.prevent="openDocModal(doc)">
        เปิดเอกสาร
      </a>
    </div>
  </div>

  <div v-if="transferDocuments && transferDocuments.length < 1" class="text-center py-3">
    - ไม่พบข้อมูล -
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
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="exampleModalReceipts" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ใบเสร็จชำระเงิน</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <iframe
                v-if="dataReceipt && dataReceipt.file_path"
                :src="'../../' + dataReceipt.file_path"
                ref="docFrame"
                style="width: 100%; height: 400px; border: none;"></iframe>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="button" class="btn btn-primary" @click="printDoc()">
                <!-- https://feathericons.dev/?search=printer&iconset=feather -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                  <polyline points="6 9 6 2 18 2 18 9" />
                  <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                  <rect height="8" width="12" x="6" y="14" />
                </svg>
                พิมพ์ใบเสร็จ
              </button>
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
                <img :src="'../api/'+editData.barcode_image" alt="Barcode" class="img-fluid" />
                <!-- ซ่อนไว้สำหรับสั่งพิมพ์ -->
                <div id="printArea" style="display: none;">
                  <div style="text-align: center;">
                    <img :src="'../api/'+editData.barcode_image" alt="Barcode" style="max-width: 100%;" />
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

      <!-- Modal แสดงเอกสาร -->
      <div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ดูเอกสารการโอนสินค้า</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh;">
              <iframe
                v-if="docUrl"
                :src="'../api/' + docUrl.file_name"
                style="width: 100%; height: 100%; border: none;"
                ref="docFrame"></iframe>
            </div>
            <div class="modal-footer justify-content-between">
              <div v-if="docUrl && dataRoles && dataRoles.some(r => r.id == 18)">
                <button type="button" class="btn " v-if="docUrl.delivered=='delivered'" disabled>✔️ จัดส่งแล้ว</button>
                <button type="button" class="btn btn-success" v-else @click="saveDelivered">✔️ จัดส่ง</button>
              </div>
              <div>
                <button class="btn btn-secondary me-3" data-bs-dismiss="modal">ปิด</button>
                <button class="btn btn-primary" @click="printDoc">พิมพ์</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal แสดงรายการสินค้า -->
      <div class="modal fade" id="productListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ดูเอกสารการโอนสินค้า</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 80vh; overflow-y: scroll;">
              <div>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ไปยังคลัง</th>
                      <th>สินค้า</th>
                      <th style="text-align: right;">จำนวน</th>
                    </tr>
                  </thead>
                  <tbody v-for="(warehouse, i) in productsList" :key="i">
                    <tr>
                      <td>{{warehouse.warehouse_name}}<br> {{warehouse.document_no}}</td>
                      <td>
                        <template v-for="(product, j) in warehouse.products" :key="j">
                    <tr>
                      <td>{{ product.product_name }}</td>
                    </tr>
                    </template>
                    </td>
                    <td>
                      <template v-for="(product, j) in warehouse.products" :key="j">
                        <tr>
                          <td style="text-align: right;">x{{ product.qty }}</td>
                        </tr>
                      </template>
                    </td>
                    </tr>
                    <tr style="background-color: #ccc;">
                      <td colspan="2" style="text-align: right;">
                        รวมจำนวน
                      </td>
                      <td style="background-color: #999;color:#fff">{{warehouse.total_qty}}</td>
                    </tr>
                  </tbody>
                </table>

              </div>
            </div>
            <div class="modal-footer">
              <div>
                <button class="btn btn-secondary me-3" data-bs-dismiss="modal">ปิด</button>
                <button class="btn btn-primary" @click="printDocAll">พิมพ์</button>
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
                </script> Soft by <img src="../assets/img/lgg.png" width="20" alt="logo">ɢɪɢᴀᴊᴜ ꜱᴛᴜᴅɪᴏ.
              </div>
            </div>

          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
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
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
  <script type="module" src="https://unpkg.com/vue@3/dist/vue.esm-browser.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/th.js"></script>
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
          dataWarehousesSelect: '',
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
          searchStartDate: '',
          searchEndDate: '',
          searchDate: this.getTodayDate(), // ตั้ง default เป็นวันนี้
          searchKeyword: '',
          filteredProducts: [],
          image: null,
          currentWh: '',
          products: [],
          currentPage: 1,
          perPage: 10,
          dataReceipt: '',
          receipts: [],
          transferDocuments: [],
          productsList: [],
          docUrl: null,
          dataRoles:[]
        };
      },
      computed: {

        selectedCategoryName() {
          const selected = this.dataCategory.find(cat => cat.id === this.formData.category_id);
          return selected ? selected.cate_name : 'ยังไม่ได้เลือก';
        },

        totalPages() {
          return Array.isArray(this.transferDocuments) ?
            Math.ceil(this.transferDocuments.length / this.perPage) :
            0;
        },
        paginatedDocuments() {
          if (!Array.isArray(this.transferDocuments)) return [];
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.transferDocuments.slice(start, end);
        }
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
          if (profile.data.position == 'owner') {
            window.location = "../" + profile.redirect;
          }
          this.loadWarehouses();
          this.getEmployeeRoles(profile.data.id);
        }
      },
      methods: {
        goBack() {
          this.currentWh = '';
        },
        selecthWarehouse(item) {
          this.currentWh = item.id;
          this.fetchTransferDocuments();
          document.getElementById("whName").innerHTML = 'ขนส่งสินค้า / ' + item.name;
        },
        loadWarehouses() {
          axios.post('../api/', {
              post: 'get_warehouses'
            })
            .then(res => this.dataWarehouses = res.data.data)
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
        },
        async searchWarehouse() {
          this.currentWh = this.warehouseId;
          this.fetchTransferDocuments();
        },
        async fetchTransferDocuments() {
          try {
            const res = await axios.post('../api/', {
              post: 'get_transfer_documents_id',
              wareHouseId: this.currentWh,
              range: this.rangeType
            });
            if (res.data.status) {
              this.transferDocuments = res.data.documents;
            }
          } catch (err) {
            alert('โหลดประวัติการโอนล้มเหลว');
          }
        },
        async saveDelivered() {
          try {
            const res = await axios.post('../api/', {
              post: 'save_delivered',
              wareHouseId: this.currentWh,
              transfer_id: this.docUrl.id,
              delivered_by: 'ขนส่ง',
              status: 'delivered'
            });
            if (res.data.status) {
              this.transferDocuments = res.data.documents;
            }
          } catch (err) {
            alert('โหลดประวัติการโอนล้มเหลว');
          }
        },
        openDocModal(doc) {
          this.docUrl = doc;

          // แสดง modal ด้วย Bootstrap 5
          const modal = new bootstrap.Modal(document.getElementById('docModal'));
          modal.show();
        },
        openProductListModal() {
          this.getAllList();
          // แสดง modal ด้วย Bootstrap 5
          const modal = new bootstrap.Modal(document.getElementById('productListModal'));
          modal.show();
        },
        getAllList() {

          axios.post('../api/', {
            post: 'get_all_products',
            date: this.searchDate,
            wareHouseId: this.currentWh
          }).then(res => {
            this.productsList = res.data.productsList;
          }).catch(err => {
            console.error(err);
          });

        },
        toggleWarehouse(id) {

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
        
        searchTransfer() {
          if (this.searchKeyword.trim().length > 2 || this.searchStartDate || this.searchEndDate) {
            axios.post('../api/', {
              post: 'searchTransfer_id',
              keyword: this.searchKeyword,
              wareHouseId: this.currentWh,
              startDate: this.searchStartDate,
              endDate: this.searchEndDate,
            }).then(res => {
              this.transferDocuments = res.data.data;
            }).catch(err => {
              console.error(err);
            });
          } else {
            this.transferDocuments = [];
          }
        },
        getTodayDate() {
          const today = new Date();
          const yyyy = today.getFullYear();
          const mm = String(today.getMonth() + 1).padStart(2, '0');
          const dd = String(today.getDate()).padStart(2, '0');
          return `${yyyy}-${mm}-${dd}`;
        },
        searchTransferByDate() {
          axios.post('../api/', {
            post: 'searchTransferByDate',
            date: this.searchDate,
            wareHouseId: this.currentWh,
          }).then(res => {
            this.transferDocuments = res.data.data;
          }).catch(err => {
            console.error(err);
          });
        },
        handlePrint(item) {
          this.dataReceipt = item;
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
            axios.post('../api/', {
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

        async loadTopSellingProducts() {
          const payload = {
            post: 'get_today_selling_products_id',
            wareHouseId: this.currentWh,
            range: this.rangeType
          };

          try {
            const res = await axios.post('../api/', payload)
            if (res.data.status) {
              this.products = res.data.data
            } else {
              alert('โหลดข้อมูลยอดขายไม่สำเร็จ')
            }
          } catch (err) {
            console.error('เกิดข้อผิดพลาดในการโหลดข้อมูล:', err)
          }
        },
        async getReceipts() {
          try {
            const res = await axios.post('../api/', {
              post: 'get_receipts'
            });
            if (res.data.status) {
              this.receipts = res.data.receipts;
            }
          } catch (err) {
            console.error('ไม่สามารถโหลดใบเสร็จได้', err);
          }
        },
        formatPrice2(num) {
          return parseFloat(num).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          });
        },
        formatPrice(price) {
          return Number(price).toLocaleString(); // ใส่ comma (,)
        },
        getType: async function() {
          try {
            const url = '../api/';
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
          axios.post('../api/', {
              post: 'get_warehouses_fproduct',
            })
            .then(res => this.dataWarehouses = res.data.data)
            .catch(err => alert('โหลดคลังสินค้าล้มเหลว'));
        },
        handleFileUpload(event) {
          this.image = event.target.files[0];
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
        printDoc() {
          const iframe = this.$refs.docFrame;
          if (iframe && iframe.contentWindow) {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
          } else {
            alert('ไม่สามารถพิมพ์เอกสารได้');
          }
        },
        printDocAll() {
    const modalBody = document.querySelector('#productListModal .modal-body');

    if (!modalBody) {
      alert('ไม่พบเนื้อหาสำหรับพิมพ์');
      return;
    }

    const printWindow = window.open('', '', 'width=900,height=600');
    printWindow.document.write(`
      <html>
        <head>
          <title>พิมพ์เอกสารการโอนสินค้า</title>
          <style>
            body {
              font-family: Arial, sans-serif;
              padding: 20px;
            }
            table {
              width: 100%;
              border-collapse: collapse;
              margin-bottom: 20px;
            }
            th, td {
              border: 1px solid #000;
              padding: 8px;
              text-align: left;
            }
            th {
              background-color: #f0f0f0;
            }
            tr.total-row td {
              background-color: #999;
              color: #fff;
              font-weight: bold;
            }
          </style>
        </head>
        <body>
          ${modalBody.innerHTML}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  },
        delData: async function() {
          try {
            const url = '../api/';
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
        async getEmployeeRoles(empId) {
          const res = await axios.post("../api/", {
            post: "get_employee_roles",
            employee_id: empId
          });
          if (res.data.status) {
            this.employee = res.data.employee;
            this.dataRoles = res.data.roles;
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