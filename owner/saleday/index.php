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

    table tr td,
    table tr td input {
      text-align: center;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <?php include('../layout/navbar.html'); ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4" id="app">
      <div class="row">
      <!-- <div class="col-12 card d-flex align-items-center justify-content-between mb-3 p-5 text-center" v-if="dataRoles && !dataRoles.some(r => r.id === 24)"> {{'กรุณาขอสิทธิเข้าใช้งาน!'}}</div> -->
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">
              <div id="whName"></div>
              <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group mb-3">
                  <span class="input-group-text text-body">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                  </span>
                  <select class="form-select me-3" v-model="warehouseId" style="height: 40px;" @change="searchWarehouse">
                    <option value="">เลือกคลังสินค้า</option>
                    <option v-for="(item, i) in dataWarehouses" :key="i" :value="item.id">
                      {{ item.name }}
                    </option>
                  </select>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="ค้นหาชื่อสินค้า..."
                    v-model="searchKeyword"
                    @input="searchProduct">
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" v-if="currentWh==''">
              <div class="row p-4 row align-items-center justify-content-center">
                <div class="col-12 col-md-8 text-center">
                  <h4>
                    <p>เลือกสาขา?</p>
                  </h4>
                  <div class="row">
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
            </div>
            <div class="card-body px-0 pt-0 pb-2" v-else>
              <div class="table-responsive px-md-2 border-bottom" style="min-height: 280px;">
                <table class="table table-responsive align-items-center mb-0">
                  <thead>
                    <tr>
                      <th>เวลา</th>
                      <th>สินค้า</th>
                      <th></th>
                      <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">รวมยอดเงิน(บาท)</th>
                      <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">ยอดทั้งหมด</th>
                      <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">จำนวนขาย</th>
                      <th>ยอดนับคงเหลือ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(r, i) in paginatedProducts" :key="i">
                      <!-- วันที่ -->
                      <td class="text-center" style="vertical-align: middle;">
                        {{ formatThaiDate(r.created_at) }}
                      </td>

                      <!-- รูปสินค้า -->
                      <td class="text-center" style="vertical-align: middle;">
                        <img :src="'../' + r.image" width="55" class="rounded shadow-sm" alt="">
                      </td>

                      <!-- ชื่อสินค้า -->
                      <td style="vertical-align: middle; font-weight: 500;text-align:left">
                        {{ r.product_name }}
                      </td>

                      <!-- ราคารวม -->
                      <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell text-end" style="vertical-align: middle;">
                        <span v-if="r.total_sale > 0">{{ formatPrice2(r.total_sale) }}</span>
                        <span v-else>0.00</span>
                      </td>

                      <!-- ยอดยกมา -->
                      <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell text-center" style="vertical-align: middle;">
                        <span>{{ r.qty_total }}</span>
                      </td>

                      <!-- จำนวนขาย -->
                      <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell text-center fw-bold" style="vertical-align: middle;">

                        <span>{{ r.sale_qty }}</span>
                      </td>

                      <!-- กรอกจำนวนใหม่ -->
                      <td class="text-center" style="vertical-align: middle;">
                        <div class="d-flex justify-content-center">
                        <input
                            :placeholder="r.unit || ''"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            type="text"
                            v-model="r.update_qyt"
                            class="form-control text-center"
                            style="width: 90px; font-weight: 500;"
                            @input="recalculateRow(r)"
                            @keydown.enter.prevent="recalculateRow(r)"
                          />


                          <!-- ปุ่มคำนวณใหม่ 
                          <button class="btn btn-primary mt-2" @click="reCalculateAll">
                            คำนวณใหม่
                          </button>-->
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row px-2 px-md-4 mb-4">
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
                  <div class="form-group w-50">
                    <label for="">คูปองส่วนลด</label>
                    <input type="text" class="form-control" v-model="discountTotal" oninput="this.value = this.value.replace(/[^0-9_]/g, '');">
                  </div>
                  <div class="form-group w-50 px-2">
                    <label for="">เงินสดนับได้</label>
                    <input type="text" class="form-control"  v-model="cashCounted">
                  </div>
                </div>
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
                  <div class="form-group w-50">
                    <label for="">ยอดเงินสด</label>
                    <input type="text" class="form-control" v-model="cashReceived">
                  </div>
                  <div class="form-group w-50 px-2">
                    <label for="">เงินโอน</label>
                    <input type="text" class="form-control"  v-model="transferAmount">
                  </div>
                  <div class="form-group mt-5">
                    <button class="btn btn-success" @click="saveSummary">บันทึก</button>
                  </div>
                </div>
                <div class="col-12 d-flex align-items-center justify-content-end">
                  <div class="mx-2 p-2" v-html="products[0]?.person"></div>
                  <div class="mx-2 p-2">ยอดรวมสุทธิ:</div>
                  <div class="mx-2 p-2 bg-dark w-20" style="min-width: 150px;">
                    <h3 class="text-white text-end">{{ formatPrice2(totalSaleOut) }}</h3>
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
          warehouseId: '',
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
          currentWh: '',
          products: [],
          qty: [],
          currentPage: 1,
          perPage: 10,
          dataReceipt: '',
          receipts: [],
          summary: 0,
          discountTotal: 0,
          carryingOver: [],
          products: [], // จาก API get_products_in_wh_sof
          discountTotal: 0,
          cashCounted: 0,
          cashReceived: 0,
          transferAmount: 0,
          dataRoles:'',
          employee:''
        };
      },
      computed: {
        totalSaleOut() {
          return this.products.reduce((sum, p) => sum + (Number(p.total_sale) || 0), 0)
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
          // this.getEmployeeRoles(profile.data.id);
          this.loadWarehouses();
        }
      },
      methods: {
        toNumber(val) {
    // แปลงเป็นตัวเลข ป้องกัน NaN และรองรับค่าที่มีคอมมา/ช่องว่าง
    return Number(String(val ?? 0).toString().replace(/[^\d.-]/g, "")) || 0;
  },
  ensureStartQty(row) {
    // กำหนดจำนวนเริ่มต้นของรอบไว้ครั้งเดียวต่อแถว
    if (row._start_qty == null) {
      const qtyTotal = this.toNumber(row.qty_total);
      const unit = this.toNumber(row.unit);
      const sale = this.toNumber(row.sale_qty);
      row._start_qty = qtyTotal > 0 ? qtyTotal : (unit + sale);
    }
  },

  recalculateRow(row) {
    this.ensureStartQty(row);

    const start = this.toNumber(row._start_qty);
    const remainNow = Math.max(0, this.toNumber(row.update_qyt)); // คงเหลือที่นับได้ตอนนี้
    let newSold = start - remainNow;
    if (newSold < 0) newSold = 0;

    row.sale_qty = newSold;
    row.total_sale = newSold * this.toNumber(row.price);
    // ไม่ต้องทำอะไรเพิ่ม: totalSaleOut เป็น computed จะอัปเดตอัตโนมัติ
  },

  reCalculateAll() {
    this.products.forEach(p => this.recalculateRow(p));
  },

  // ปรับ getProducts ให้ตั้งค่าเริ่มต้นของแต่ละแถวด้วย
  async getProducts() {
    try {
      const response = await axios.post('../../api/', {
        post: 'get_products_in_wh_sof',
        warehouses_id: this.currentWh,
      });

      if (response.data.status) {
        const toNumber = this.toNumber;
        this.products = (response.data.products || []).map(p => {
          const qtyTotal = toNumber(p.qty_total);
          const unit = toNumber(p.unit);
          const sold = toNumber(p.sale_qty);
          const startQty = qtyTotal > 0 ? qtyTotal : (unit + sold);

          return {
            ...p,
            _start_qty: startQty,                // เก็บจำนวนเริ่มต้นของรอบ
            update_qyt: p.unit ?? 0,            // ค่าเริ่มต้นในช่อง “ยอดคงเหลือ”
            sale_qty: sold,                     // ค่าที่มาจาก API
            total_sale: sold * toNumber(p.price)
          };
        });

        this.discountTotal = response.data.discount_total;
        this.summary = response.data.summary;

        // ถ้าต้องการคำนวณใหม่ทั้งหมดทันทีจากค่า update_qyt ปัจจุบัน
        this.reCalculateAll();
      }
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  },
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
        findCarry(id) {
          return this.carryingOver.find(c => c.id === id) || {
            unit: 0,
            sale_qty: 0,
            qty: 0,
            last_qty: 0,
          };
        },
        // handleUpdateQty(row) {
        //   const carry = this.findCarry(row.id);

        //   if (carry && row.update_qyt !== '') {
        //     const newQty = parseFloat(row.update_qyt);

        //     // ตรวจสอบว่ามี unit และ sale_qty
        //     const unit = parseFloat(carry.unit) || 0;
        //     const saleQty = parseFloat(carry.sale_qty) || 0;

        //     const currentTotal = unit - saleQty;
        //     const diff = newQty - currentTotal;

        //     // อัปเดตค่า qty ใหม่ (หรืออัปเดตตาม logic ที่คุณต้องการ)
        //     carry.qty = newQty;

        //     console.log(`carry.unit: ${unit}`);
        //     console.log(`carry.sale_qty: ${saleQty}`);
        //     console.log(`รับค่า: ${row.update_qyt}`);
        //     console.log(`ยกมา (unit + sale_qty): ${currentTotal}`);
        //     console.log(`ต่างจากเดิม: ${diff}`);
        //   } else {
        //     console.warn(`ไม่พบข้อมูล carry สำหรับ id: ${row.id}`);
        //   }
        // },

        async searchWarehouse() {
          // หา object ของคลังจาก id ที่เลือก
          const selectedWh = this.dataWarehouses.find(w => String(w.id) === String(this.warehouseId));
          this.whName = selectedWh ? selectedWh.name : ''
          if (this.warehouseId) {
            this.currentWh = this.warehouseId;
            // this.loadTopSellingProducts();
            document.getElementById("whName").innerHTML = 'สรุปยอดขาย / ' + selectedWh.name;
            try {
              const response = await axios.post('../../api/', {
                post: 'get_products_in_wh',
                warehouses_id: this.warehouseId
              });
              if (response.data.status) {
                this.products = response.data.products;
                this.getProducts();
            this.getFinishOfday();
            
              }
            } catch (error) {
              console.error('Error fetching products:', error);
            }
          }
          
        },
        goBack() {
          this.currentWh = '';
        },
        selecthWarehouse(item) {
          this.currentWh = item.id;
          // this.loadTopSellingProducts();
          this.getProducts();
          this.getFinishOfday();
          document.getElementById("whName").innerHTML = 'สรุปยอดขาย / ' + item.name;
        },
        loadWarehouses() {
          axios.post('../../api/', {
              post: 'get_warehouses'
            })
            .then(res => this.dataWarehouses = res.data.data)
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
        },
        getFinishOfday() {
          axios.post('../../api/', {
              post: 'get_finish_ofday_id',
              warehouses_id: this.currentWh,
            })
            .then(res => {
              this.carryingOver = res.data.products;
            })
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
        },
        //     formatThaiDate(dateStr) {
        //   return new Date(dateStr).toLocaleString('th-TH')
        // },
        // เมื่อแก้ไขจำนวนคงเหลือ
        recalculateRow(r) {
          let newRemain = Number(r.update_qyt || 0)
          let oldRemain = Number(r.unit || 0)

          if (!r._original_sale_qty) {
            r._original_sale_qty = Number(r.sale_qty || 0)
          }

          let newSale = (oldRemain + r._original_sale_qty) - newRemain
          if (newSale < 0) newSale = 0

          r.sale_qty = newSale
          r.total_sale = newSale * Number(r.price || 0)

          // ✅ อัปเดตยอดรวมสุทธิใหม่
          this.summary.total_sale_out = this.products.reduce((sum, p) => sum + (Number(p.total_sale) || 0), 0)
        },
        // ฟังก์ชันกดบันทึก
        saveSummary() {
          const payload = {
            warehouse_id:this.currentWh,
            discount: this.discountTotal,
            cash_counted: this.cashCounted,
            cash_received: this.cashReceived,
            transfer: this.transferAmount,
            products: this.products.map(p => ({
              id: p.id,
              sale_qty: p.sale_qty,
              total_sale: p.total_sale,
              remain: p.update_qyt || p.unit
            }))
          }

          fetch('../../api/', {
              method: 'POST',
              body: JSON.stringify({
                post: 'save_summary',
                ...payload
              })
            })
            .then(res => res.json())
            .then(res => {
              if (res.status) {
                alert('บันทึกเรียบร้อย')
              } else {
                alert('เกิดข้อผิดพลาด: ' + res.message)
              }
            })
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
        async getProducts() {
          try {
            const response = await axios.post('../../api/', {
              post: 'get_products_in_wh_sof',
              warehouses_id: this.currentWh,
            });
            if (response.data.status) {
              this.products = response.data.products;
              this.discountTotal = response.data.discount_total;
              this.summary = response.data.summary;
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