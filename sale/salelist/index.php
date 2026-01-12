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
    Sale Products Dashboard
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

    .bg-dang {
      background-color: #eed3d3;
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
        <div class="col-12 card d-flex align-items-center justify-content-between mb-3 p-5 text-center" v-if="dataRoles && !dataRoles.some(r => r.id === 3)"> {{'กรุณาขอสิทธิเข้าใช้งาน!'}}</div>
        <div class="col-12" v-if="dataRoles && dataRoles.some(r => r.id === 3)">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">
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

            <div class="row mx-2 justify-content-center" v-if="dataRoles && dataRoles.some(r => r.id === 3)">
              <!-- ✅ Desktop: Table -->
              <div class="table-responsive px-2 d-none d-sm-none d-md-block justify-content-center w-100" style="min-height: 280px;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>📄 เลขที่</th>
                      <th>วันที่</th>
                      <th>คลังสินค้า</th>
                      <th>รวมเงิน</th>
                      <th>ผู้ขาย</th>
                      <th>ตัวเลือก</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(r, i) in paginatedReceipts" :key="i" :class="{'bg-dang text-white':r.status=='canceled'}">
                      <td>{{ r.receipt_code }}</td>
                      <td>{{ formatThaiDate(r.created_at) }}</td>
                      <td>{{ r.warehouse_name }}</td>
                      <td>{{ formatPrice2(r.total) }} บาท</td>
                      <td v-html="r.persons"></td>
                      <td>
                        <button class="btn btn-sm btn-primary" @click="handlePrint(r)" data-bs-toggle="modal" data-bs-target="#exampleModalReceipts">
                          เปิดใบเสร็จ
                        </button>
                        <div v-if="r.status" class="btn text-danger text-center ms-3"> {{ 'ยกเลิกแล้ว' }}</div>
                        <button v-else class="btn btn-sm btn-danger ms-3" @click="cancelReceipt(r.id)">
                          ยกเลิกรายการ
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- ✅ Mobile: Card -->
              <div class="row d-flex d-sm-flex d-md-none d-lg-none justify-content-center" style="width: 100%;">
                <h3>รายการขายสินค้า</h3>
                <div v-for="(r, i) in paginatedReceipts" :key="i" class="col-6 mb-3 p-0 overflow-hidden">
                  <div class="card mb-3 ms-0 shadow-sm px-2 ms-1" :class="{ 'bg-light text-muted': r.status === 'canceled' }">
                    <h6 class="card-title">
                      📄 {{ r.receipt_code }}
                    </h6>
                    <p class="mb-1"><b>วันที่:</b> {{ formatThaiDate(r.created_at) }}</p>
                    <p class="mb-1"><b>คลังสินค้า:</b> {{ r.warehouse_name }}</p>
                    <p class="mb-1"><b>รวมเงิน:</b> {{ formatPrice2(r.total) }} บาท</p>
                    <p class="mb-2"><b>ผู้ขาย:</b> <span v-html="r.persons"></span></p>
                    <!-- Overlay -->
                    <div v-if="r.status === 'canceled'"
                      class="position-absolute top-50 start-50 text-danger fw-bold fs-4"
                      style="opacity:0.8; transform: rotate(-15deg);white-space: nowrap;margin-left: -50px;">
                      ❌ ยกเลิกแล้ว
                    </div>
                    <div class="d-flex flex-wrap">
                      <button class="btn btn-sm btn-primary me-2 mb-2"
                        @click="handlePrint(r)"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModalReceipts">
                        เปิดใบเสร็จ
                      </button>

                      <span v-if="r.status" class="text-danger fw-bold align-self-center m-2">
                        {{ 'ยกเลิกแล้ว' }}
                      </span>

                      <button v-else class="btn btn-sm btn-danger mb-2" @click="cancelReceipt(r.id)">
                        ยกเลิกรายการ
                      </button>
                    </div>
                  </div>
                </div>
              </div>

            </div>


          </div>
        </div>
        <!-- Pagination -->
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
          dataReceipt: '',
          receipts: [],
          dataRoles: '',
          employee: ''
        };
      },
      computed: {

        selectedCategoryName() {
          const selected = this.dataCategory.find(cat => cat.id === this.formData.category_id);
          return selected ? selected.cate_name : 'ยังไม่ได้เลือก';
        },

        totalPages() {
          return Math.ceil(this.receipts.length / this.perPage);
        },
        paginatedReceipts() {
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.receipts.slice(start, end);
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
          this.loadWarehouses();
          this.getReceipts();
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
        handlePrint(item) {
          this.dataReceipt = item;
        },
        async cancelReceipt(receiptId) {
          if (!confirm("คุณต้องการยกเลิกใบเสร็จนี้หรือไม่?")) return;

          try {
            const res = await axios.post('../../api/', {
              post: 'cancel_receipt',
              receipt_id: receiptId,
              reason: 'ลูกค้ายกเลิกสินค้า'
            });

            if (res.data.status) {
              this.showToast(res.data.message, 'success');
              this.getReceipts(); // โหลดข้อมูลใหม่

            } else {
              this.showToast(res.data.message, 'error');
            }
          } catch (err) {
            console.error(err);
            this.showToast("เกิดข้อผิดพลาด", 'error');
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
          // return dayjs(dateStr).format("HH:mm");
          return dayjs(dateStr).format("D MMMM YYYY เวลา HH:mm");
        },

        showToast(txt, icn) {
          Swal.fire({
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
        async getReceipts() {
          try {
            const res = await axios.post('../../api/', {
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