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
  Font Awesome Icons -->

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

    @media (min-width: 481px) {
      .trn-r {
        transform: rotate(0deg);
      }
    }

    @media (max-width: 480px) {
      .trn-r {
        transform: rotate(90deg);
      }
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
        <div class="col-12" v-if="dataRoles && dataRoles.some(r => r.id === 13)">
          <div class="card mb-4">
            <div id="app" class="container py-5">
              <div
                v-if="isEditing"
                class="alert alert-warning d-flex justify-content-between align-items-center mb-3">
                <div>
                  กำลังแก้ไขเอกสารโอนเลขที่
                  <strong>{{ editingDocumentNo }}</strong>
                </div>
                <div>
                  <button class="btn btn-sm btn-secondary me-2" @click="cancelEdit">
                    ยกเลิกแก้ไข
                  </button>
                </div>
              </div>

              <h2 class="mb-4">โอนสินค้า</h2>

              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <!-- <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">โอนเข้า</button> -->
                  <!-- <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">นำออก</button> -->
                </div>
              </nav>
              <div class="tab-content px-4" id="nav-tabContent">
                <div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                  <div class="my-3">

                  </div>
                  <!-- เลือกคลังต้นทาง และปลายทาง -->
                  <!-- เลือกคลังต้นทาง / ปลายทาง -->
                  <div class="row g-3">
                    <div class="col-md-5">
                      <label>คลังต้นทาง</label>
                      <select v-model="fromWarehouse" @change="loadProducts(fromWarehouse)" class="form-control">
                        <option value="">-- เลือกคลังต้นทาง --</option>
                        <option v-for="w in warehouses" :value="w.id" :key="w.id">{{ w.name }}</option>
                      </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                      <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-box-arrow-right mt-4 trn-r" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                      </svg>
                    </div>

                    <div class="col-md-5">
                      <label>คลังปลายทาง</label>
                      <select v-model="toWarehouse" @change="loadProductsT(toWarehouse)" class="form-control">
                        <option value="">-- เลือกคลังปลายทาง --</option>
                        <option v-for="w in warehouses" :value="w.id" :key="w.id" :disabled="w.id === fromWarehouse">{{ w.name }}</option>
                      </select>
                    </div>
                  </div>

                  <!-- รายการโอน -->
                  <div v-if="fromWarehouse && toWarehouse">
                    <div class="row my-3">
                      <div v-if="products.length" class="col-9">
                        <select v-model="selectedToAdd" class="p-2 w-100 rounded border" @change="addItem">
                          <option value="">-- เลือกสินค้า --</option>
                          <option v-for="p in products" :value="p" :key="p.product_id">
                            {{ p.product_name }} (คงเหลือ {{ p.unit }})
                          </option>
                        </select>
                        <!-- <button class="btn btn-sm btn-primary" @click="addItem" :disabled="!selectedToAdd">เพิ่ม</button> -->
                      </div>
                      <div v-if="products.length" class="col-2">
                        <button class="btn btn-sm btn-warning mb-0" @click="addAllMax">MAX</button>
                      </div>
                    </div>
                    <div class="table-responsive" style="min-height: 280px;">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>สินค้า</th>
                            <th>จำนวนที่มี (ต้นทาง)</th> <!-- แก้หัวตาราง -->
                            <th>จำนวนที่มี (ปลายทาง)</th> <!-- แก้หัวตาราง -->
                            <th>จำนวนโอน</th>
                            <th>ลบ</th>
                            
                          </tr>
                        </thead>
                        <tbody v-for="(item,i) in transferList" :key="i">
                          <tr>
                            <td>{{ item.product_name }}</td>
                            <!-- หา product ที่ตรงกับ item.product_id -->
                            <td>
                              <span v-if="getProductById(item.product_id)">
                                {{ getProductById(item.product_id).unit }}
                                <span v-if="getProductById(item.product_id).unit > getProductById(item.product_id).max"> เกิน Max</span>
                              </span>
                              <span v-else>0</span>
                            </td>

                            <td>
                              {{ item.unit }}
                              <span v-if="item.unit > item.max"> เกิน Max</span>
                            </td>

                            <td>
                              <input class="border rounded p-2" style="width: 80px;" type="number"
                                v-model.number="item.qty" :min="1" :max="item.max" />
                              <span v-if="item.qty < 0" class="text-danger"> ขาด</span>
                            </td>

                            <td>
                              <button class="btn btn-sm btn-danger" @click="remove(item)">×</button>
                            </td>
                            
                          </tr>
                        </tbody>

                      </table>


                    </div>

                    <hr />
                    <button
                      class="btn btn-success me-3"
                      @click="submitTransfer"
                      :disabled="!transferList.length">
                      {{ isEditing ? 'บันทึกการแก้ไข' : 'ส่งรายการโอน' }}
                    </button>
                    <!-- <button class="btn btn-success me-3" @click="submitTransfer" :disabled="!transferList.length">ส่งรายการโอน</button> -->
                    <!-- <button class="btn btn-primary" @click="showWhf=true">ดูสินค้าต้นทาง</button> -->
                  </div>
                </div>
                <!--  -->
              </div>
            </div>
          </div>
        </div>
        <div class="col-12" v-if="dataRoles && dataRoles.some(r => r.id === 12)">
          <div class="card mb-4">
            <div class="card-body ">
              <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title">ประวัติการโอนสินค้า</h5>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                  <div class="d-flex flex-wrap gap-2 align-items-center">
                    <!-- ค้นหาชื่อ/เลขเอกสาร/คลัง -->
                    <div class="input-group mb-3" style="max-width: 320px;">
                      <span class="input-group-text text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                      </span>
                      <input
                        type="text"
                        class="form-control"
                        placeholder="ค้นหาชื่อ/เลขเอกสาร/คลัง..."
                        v-model="searchKeyword"
                        @input="fetchTransferDocuments" />
                    </div>

                    <!-- ช่วงวันที่ -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                      <input type="date" class="form-control" v-model="startDate" @change="fetchTransferDocuments" />
                      <span class="mx-1">—</span>
                      <input type="date" class="form-control" v-model="endDate" @change="fetchTransferDocuments" />
                    </div>

                    <!-- เลือกเดือน (type=month) -->
                    <div class="mb-3">
                      <input type="month" class="form-control" v-model="monthFilter" @change="applyMonthToRange" />
                    </div>

                    <!-- ปุ่มล้างตัวกรอง -->
                    <div class="mb-3">
                      <button class="btn btn-outline-secondary" @click="clearDateFilters">ล้างตัวกรองวันที่</button>
                    </div>
                  </div>

                  <!-- <div class="input-group mb-3 d-none">
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
                  </div> -->
                </div>
              </div>

              <div class="table-responsive" style="min-height: 280px;">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>วันที่</th>
                      <th>เลขที่เอกสาร</th>
                      <th>จากคลัง</th>
                      <th>ไปยังคลัง</th>
                      <!-- <th>สินค้า</th>
                      <th>จำนวน</th> -->
                      <th>ดูเอกสาร</th>
                      <th v-if="dataRoles && dataRoles.some(r => r.id === 14)">แก้ไข</th>
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
                      <!-- <td>{{ doc.product_name }}</td>
                      <td>{{ doc.qty }}</td> -->
                      <td>
                        <a href="#" class="btn btn-sm btn-primary" @click.prevent="openDocModal(doc.file_name)">เปิด</a>
                      </td>
                      <td v-if="dataRoles && dataRoles.some(r => r.id === 14)">
                              <button class="btn btn-sm btn-warning" @click="startEdit(doc)">
                                แก้ไข
                              </button>
                            </td>
                    </tr>
                  </tbody>
                </table>

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
                :src="'../../api/' + docUrl"
                style="width: 100%; height: 100%; border: none;"
                ref="docFrame"></iframe>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button class="btn btn-success" @click="printDoc">พิมพ์</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modalTransferSuccess" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">โอนสินค้าสำเร็จ</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p><b>จาก:</b> {{ transferResult.from_name }}</p>
              <p><b>ไปยัง:</b> {{ transferResult.to_name }}</p>
              <!-- <p><b>สินค้า:</b> {{ transferResult.product_name }}</p>
              <p><b>จำนวน:</b> {{ transferResult.unit }}</p> -->
              <hr>
              <a :href="'../../api/' + transferResult.doc_url" target="_blank" class="btn btn-outline-primary">
                พิมพ์เอกสาร
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="exampleModalDel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ลบรายการคลัง</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>ต้องการลบ <b id="text"></b></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="button" class="btn btn-danger" @click="saveDeleteWarehouse(warehouseForm.id)">ลบ</button>
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
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/th.js"></script>
  <script type="module">
    const {
      createApp
    } = Vue;
    createApp({
      data() {
        return {
          auth: "",
          hidId: '',
          modalDel: false,
          edit: false,
          del: false,
          showWhf: false,
          docUrl: null,
          text: 'เพิ่มคลังสินค้าใหม่',
          textName: '',
          name: '',
          dataCategory: '',
          editData: '',
          image_url: '',
          warehouseForm: {
            post: 'warehouse',
            name: '',
            location: '',
            id: ''
          },
          searchKeyword: '',
          transferResults: [],
          filteredProducts: [],
          image: null,
          currentPage: 1,
          perPage: 10,
          transferDocuments: [],
          dataRoles:'',
          warehouses: [],
          fromWarehouse: '',
          toWarehouse: '',
          products: [],
          productsT: [],
          productsInWh: [],
          transferResult: '',
          selectedProduct: '',
          selectedProduct2: '',
          transferQty: '',
          productsInFromWh: [], // สินค้าทั้งหมดในคลังต้นทาง
          productsInToWh: [], // สินค้าทั้งหมดในคลังปลายทาง
          filteredFromWh: [], // หลังค้นหา
          filteredToWh: [],
          transferList: [], // สินค้าในคลังต้นทาง
          toStock: {}, // เก็บคงเหลือของคลังปลายทางตาม product_id    // รายการที่จะโอน
          selectedToAdd: null,
          startDate: '',
          endDate: '',
          monthFilter: '', // ค่าแบบ '2025-10'
          isEditing: false,
          editingDocumentId: null,
          editingDocumentNo: '',
        };
      },
      computed: {
        filteredDocuments() {
          // เริ่มจากรายการทั้งหมด
          let rows = this.transferDocuments.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

          // กรองด้วยคีย์เวิร์ด (ถ้ามี)
          const kw = this.searchKeyword.trim().toLowerCase();
          if (kw) {
            rows = rows.filter(doc => {
              return (
                (doc.document_no || '').toLowerCase().includes(kw) ||
                (doc.from_name || '').toLowerCase().includes(kw) ||
                (doc.to_name || '').toLowerCase().includes(kw)
              );
            });
          }

          // กรองด้วยช่วงวันที่ (ถ้ามี)
          if (this.startDate || this.endDate) {
            const start = this.startDate ? dayjs(this.startDate).startOf('day') : null;
            const end = this.endDate ? dayjs(this.endDate).endOf('day') : null;

            rows = rows.filter(doc => {
              const d = dayjs(doc.created_at);
              if (start && d.isBefore(start)) return false;
              if (end && d.isAfter(end)) return false;
              return true;
            });
          }

          return rows;
        },



        filteredProductInWh() {
          if (!this.selectedProduct) return [];
          const matched = this.productsInWh.filter(p => p.id === this.selectedProduct.id);
          return matched.length ? matched : null; // ถ้าไม่มีเลย return null
        },
        // canTransfer() {
        //   return this.fromWarehouse && this.toWarehouse && this.selectedProduct && this.transferQty > 0;
        // },
        selectedCategoryName() {
          const selected = this.warehouses.find(cat => cat.name === this.name);
          return selected ? selected.name : 'ยังไม่ได้เลือก';
        },

        totalPages() {
          return Math.ceil(this.transferDocuments.length / this.perPage);
        },
        paginatedDocuments() {
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.transferDocuments.slice(start, end);
        }
      },
      async mounted() {
        const res = await axios.post('../../api/tranferController.php', {
          post: 'get_warehouses'
        })
        this.warehouses = res.data.data
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
          this.fetchTransferDocuments();
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
        startEdit(doc) {
          // เข้าโหมดแก้ไข
          this.isEditing = true;
          this.editingDocumentId = doc.id;
          this.editingDocumentNo = doc.document_no;

          // เซ็ตคลังต้นทาง/ปลายทางจากเอกสาร (สมมติ backend ส่ง from_id,to_id มาด้วย)
          this.fromWarehouse = doc.from_id;
          this.toWarehouse = doc.to_id;

          // โหลดสินค้าในคลังให้พร้อมก่อน
          Promise.all([
              this.loadProducts(this.fromWarehouse),
              this.loadProductsT(this.toWarehouse),
            ])
            .then(async () => {
              // เรียก API เอารายการสินค้าของเอกสารมาเติมใน transferList
              // *** ต้องไปเขียน API ฝั่ง PHP ให้รองรับ post: 'get_transfer_document_detail' ***
              const res = await axios.post('../../api/', {
                post: 'get_transfer_document_detail',
                id: doc.id,
              });

              if (res.data.status) {
                // สมมติ backend ส่ง items = [{product_id, product_name, qty, price}]
                this.transferList = res.data.items.map((item) => {
                  const toStockItem = this.getToStock(item.product_id); // สินค้าในคลังปลายทางปัจจุบัน
                  return {
                    product_id: item.product_id,
                    product_name: item.product_name,
                    // คงเหลือปลายทางตอนนี้
                    unit: toStockItem ? toStockItem.unit : 0,
                    max: toStockItem ? toStockItem.max : 999999,
                    qty: item.qty,
                    price: item.price ?? 0,
                  };
                });
              } else {
                alert('โหลดรายละเอียดเอกสารไม่สำเร็จ');
              }
            })
            .catch((err) => {
              console.error(err);
              alert('เกิดข้อผิดพลาดในการโหลดข้อมูลสำหรับแก้ไข');
            });
        },

        cancelEdit() {
          // กลับไปโหมดสร้างใหม่
          this.isEditing = false;
          this.editingDocumentId = null;
          this.editingDocumentNo = '';
          this.fromWarehouse = '';
          this.toWarehouse = '';
          this.transferList = [];
        },

        applyMonthToRange() {
          if (!this.monthFilter) return;
          const d = dayjs(this.monthFilter + '-01');
          this.startDate = d.startOf('month').format('YYYY-MM-DD');
          this.endDate = d.endOf('month').format('YYYY-MM-DD');
          this.currentPage = 1;
          this.fetchTransferDocuments();
        },
        clearDateFilters() {
          this.startDate = '';
          this.endDate = '';
          this.monthFilter = '';
          this.currentPage = 1;
        },

        getProductById(id) {
          return this.products.find(product => product.product_id === id);
        },

        async loadProducts(w) {
          if (!this.fromWarehouse) return
          const res = await axios.post('../../api/tranferController.php', {
            post: 'get_products_in_wh',
            wh: w
          })
          this.products = res.data.data // [{product_id, product_name, unit, max}]
          this.transferList = []
        },
        async loadProductsT(w) {

          if (!this.fromWarehouse) return
          const res = await axios.post('../../api/tranferController.php', {
            post: 'get_products_in_wh',
            wh: w
          })
          this.productsT = res.data.data // [{product_id, product_name, unit, max}]
          this.transferList = []
          this.toStock = {};
          for (const item of this.productsT) {
            this.toStock[item.product_id] = item;
          }
        },
        addItem() {
          if (this.transferList.some(i => i.product_id === this.selectedToAdd.product_id)) return
          this.transferList.push({
            ...this.selectedToAdd,
            qty: 1
          })
          this.selectedToAdd = null
        },
        remove(item) {
          this.transferList = this.transferList.filter(i => i !== item)
        },
        getToStock(productId) {
          return this.toStock[productId] || [];
        },
        addAllMax() {
          this.transferList = []; // ล้างรายการเดิมก่อน (หรือลบเฉพาะที่ซ้ำ)

          this.productsT.forEach(p => {
            this.transferList.push({
              product_id: p.product_id,
              product_name: p.product_name,
              unit: p.unit,
              qty: p.max - p.unit, // 👈 ใช้ยอดสูงสุดของคลังปลายทาง
              max: p.max // อาจเก็บ max ไว้ด้วย สำหรับควบคุม <input>
            });
          });
          console.log('push transferList', this.transferList);
        },
        addAllMax2() {
          this.transferList = [];

          for (const product of this.products) {
            const toQty = this.getToStock(product.product_id);
            const maxQty = Math.max(product.unit - toQty, 0);

            if (maxQty > 0) {
              this.transferList.push({
                product_id: product.product_id,
                product_name: product.product_name,
                qty: maxQty,
                max: product.unit,
              });
            }
          }
        },
        changePage(page) {
          if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
          }
        },


        async submitTransfer() {
  // กันเคสลืมเลือกคลัง / ไม่มีรายการ
  if (!this.fromWarehouse || !this.toWarehouse) {
    alert('กรุณาเลือกคลังต้นทางและปลายทาง');
    return;
  }

  if (!this.transferList.length) {
    alert('กรุณาเลือกรายการสินค้าอย่างน้อย 1 รายการ');
    return;
  }

  // เตรียม payload พื้นฐาน
  const payload = {
    from: this.fromWarehouse,
    to: this.toWarehouse,
    items: this.transferList.map(i => ({
      product_id: i.product_id,
      qty: i.qty,
      // กัน null/undefined
      price: i.price ?? 0
    }))
  };

  // แยกโหมด สร้างใหม่ vs แก้ไขเอกสาร
  if (this.isEditing && this.editingDocumentId) {
    payload.post = 'update_transfer_document';
    payload.document_id = this.editingDocumentId;
  } else {
    payload.post = 'transfer_product';
  }

  try {
    const res = await axios.post('../../api/', payload);

    if (res.data.status) {
      // เก็บผลลัพธ์ไว้ใช้แสดงใน modal
      this.transferResult = res.data;

      // ข้อความแจ้งเตือน
      if (this.isEditing && this.editingDocumentId) {
        alert('อัปเดตการโอนสินค้าสำเร็จ');
      } else {
        alert('โอนสินค้าสำเร็จ');
      }

      // เคลียร์ค่าบางตัว
      this.transferQty = '';
      this.selectedProduct = '';

      // โหลดข้อมูลใหม่
      // (อันนี้ใช้ตามโค้ดเดิมของคุณเลย ถ้ามีเปลี่ยนชื่อฟังก์ชันก็ปรับเอา)
      this.getProducts();
      this.getProductss();
      this.fetchTransferDocuments();

      // แสดง modal เฉพาะตอน "โอนใหม่"
      if (!this.isEditing) {
        const modal = new bootstrap.Modal(
          document.getElementById('modalTransferSuccess')
        );
        modal.show();
      }

      // เคลียร์ฟอร์มบนจอ
      this.fromWarehouse = '';
      this.toWarehouse = '';
      this.products = [];
      this.transferList = [];

      // ถ้าเป็นโหมดแก้ไข ให้ปิดโหมดแก้ไข
      if (this.isEditing) {
        this.isEditing = false;
        this.editingDocumentId = null;
        this.editingDocumentNo = '';
      }
    } else {
      alert('ดำเนินการล้มเหลว: ' + (res.data.message || 'ไม่ทราบสาเหตุ'));
    }
  } catch (err) {
    console.error(err);
    alert('เกิดข้อผิดพลาดระหว่างบันทึกข้อมูล');
  }
},
        getToStock(productId) {
          return this.toStock[productId] || [];
        },
        searchTransfer() {
          if (this.searchKeyword.trim().length > 2) {
            axios.post('../../api/', {
              post: 'searchTransfer',
              keyword: this.searchKeyword
            }).then(res => {
              // this.transferResults = res.data.data;
              this.transferDocuments = res.data.data;
            }).catch(err => {
              console.error(err);
            });
          } else {
            // this.transferResults = [];
            this.fetchTransferDocuments();
          }
        },
        searchWarehouses() {
          const keyword = this.searchKeyword.toLowerCase().trim();

          // สมมุติว่าคุณมี productsInFromWh และ productsInToWh เป็นรายการสินค้าในคลังต้นทางและปลายทาง
          this.filteredFromWh = this.productsInFromWh.filter(p =>
            p.product_name.toLowerCase().includes(keyword)
          );

          this.filteredToWh = this.productsInToWh.filter(p =>
            p.product_name.toLowerCase().includes(keyword)
          );
        },
        openDocModal(filePath) {
          this.docUrl = filePath;

          // แสดง modal ด้วย Bootstrap 5
          const modal = new bootstrap.Modal(document.getElementById('docModal'));
          modal.show();
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
        async fetchTransferDocuments() {
          try {
            const payload = {
              post: 'get_transfer_documents',
              // ส่งเฉพาะที่มีค่า เพื่อลดภาระฝั่งเซิร์ฟเวอร์
              keyword: this.searchKeyword?.trim() || undefined,
              start_date: this.startDate || undefined,
              end_date: this.endDate || undefined,
              month: this.monthFilter || undefined,
              page: this.currentPage, // เริ่มที่ 1
              per_page: this.perPage
            };

            const res = await axios.post('../../api/', payload);

            if (res.data?.status) {
              this.transferDocuments = res.data.documents || [];
              this.total = res.data.total ?? this.transferDocuments.length;
            } else {
              this.transferDocuments = [];
              this.total = 0;
            }
          } catch (err) {
            console.error('load transfer documents failed', err);
            this.transferDocuments = [];
            this.total = 0;
            alert('โหลดประวัติการโอนล้มเหลว');
          }
        },
        formatDate(dateStr) {
          return new Date(dateStr).toLocaleString('th-TH', {
            dateStyle: 'short',
            timeStyle: 'short'
          });
        },
        searchProduct() {
          if (this.searchKeyword.length > 0) {
            axios.post('../../api/', {
              post: 'searchWarehouses',
              keyword: this.searchKeyword
            }).then(res => {
              this.warehouses = res.data.data;
            }).catch(err => {
              console.error(err);
            });
          } else {
            this.loadWarehouses();
          }
        },
        formatThaiDate(datetime) {
          const d = dayjs(datetime).locale('th');
          const buddhistYear = d.year() + 543;
          const shortYear = buddhistYear.toString().slice(-2); // ดึง 2 ตัวท้าย

          return `${d.format('DD MMM')} ${shortYear} ${d.format('HH:mm')}`;
        },
        submitTransferLast() {
          // console.log(this.canTransfer);
          // if (!this.canTransfer) return alert("กรอกข้อมูลให้ครบก่อนโอน");

          axios.post('../../api/', {
            post: 'transfer_product',
            from: this.fromWarehouse,
            to: this.toWarehouse,
            product_id: this.selectedProduct.id,
            unit: this.transferQty
          }).then(res => {
            if (res.data.status) {
              // alert("โอนสำเร็จ ✅");
              this.transferResult = res.data;
              this.transferQty = '';
              this.selectedProduct = '';
              this.getProducts();
              this.getProductss();
              this.fetchTransferDocuments();
              const modal = new bootstrap.Modal(document.getElementById('modalTransferSuccess'));
              modal.show();
            } else {
              alert("❌ โอนล้มเหลว: " + res.data.message);
            }
          });
        },
        showToast(txt, icn) {
          Swal.fire({
            toast: true,
            position: "top-end", // ตำแหน่งของ Toast
            icon: icn, // ไอคอน (success, error, warning, info, question)
            title: txt, // ข้อความหลัก
            showConfirmButton: false, // ไม่แสดงปุ่มยืนยัน
            timer: 3000, // ระยะเวลาแสดง (ms)
            timerProgressBar: true, // แสดงแถบเวลา
          });
        },
        goToWarehouse(id, name) {
          window.location = 'warehouse/?id=' + id + '&warehousename=' + name;
        },
        loadWarehouses() {
          axios.post('../../api/', {
              post: 'get_warehouses_fproduct'
            })
            .then(res => this.warehouses = res.data.data)
            .catch(err => alert('โหลดคลังสินค้าล้มเหลว'));
        },
        addWarehouse() {
          if (this.warehouseForm.name == '') {
            this.showToast('กรุณากรอกชื่อคลัง', 'error');
            return false;
          }
          if (this.warehouseForm.location == '') {
            this.showToast('กรุณากรอกที่ตั้งคลัง', 'error');
            return false;
          }
          axios.post('../../api/', this.warehouseForm)
            .then(() => {
              this.loadWarehouses();
              this.warehouseForm = {
                post: 'warehouse',
                name: '',
                location: '',
                id: ''
              };
              this.text = 'เพิ่มคลังสินค้าใหม่';
            })
            .catch(() => this.showToast('เพิ่มคลังเรียบร้อย', 'success'));
        },
        async getProducts() {
          try {
            const response = await axios.post('../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: this.fromWarehouse
            });
            if (response.data.status) {
              this.products = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        async getProductss() {
          console.log(this.fromWarehouse);
          try {
            const response = await axios.post('../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: this.toWarehouse
            });
            if (response.data.status) {
              this.productsInWh = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        saveEditWarehouse() {
          if (this.warehouseForm.name == '') {
            this.showToast('กรุณากรอกชื่อคลัง', 'error');
            return false;
          }
          axios.post('../../api/', this.warehouseForm)
            .then((res) => {
              if (res.data.status) {
                this.showToast('แก้เรียบร้อย', 'success');
              }
              this.loadWarehouses();
              this.edit = false;
              this.text = 'เพิ่มคลังสินค้าใหม่';
              this.warehouseForm = {
                post: 'warehouse',
                name: '',
                location: '',
                id: ''
              };
            })
            .catch(() => this.showToast('แก้เรียบร้อย', 'success'));
        },
        clearWarehouse() {
          this.loadWarehouses();
          this.edit = false;
          this.text = 'เพิ่มคลังสินค้าใหม่';
          this.warehouseForm = {
            post: '',
            name: '',
            location: ''
          };
        },
        editWarehouse(item) {
          this.warehouseForm = item;
          this.warehouseForm.post = 'editwarehouse';
          this.edit = true;
          this.text = 'แก้ไขคลังสินค้า';
        },
        deleteWarehouse(item) {
          this.warehouseForm = item;
          document.getElementById('text').innerHTML = this.warehouseForm.name;
          this.del = true;
        },
        saveDeleteWarehouse(id) {
          console.log('id', id);
          axios.post('../../api/', {
              id: id,
              post: 'deleteWarehouse'
            })
            .then(() => {
              this.showToast('ลบเรียบร้อย', 'success');
              setTimeout(() => {
                window.location.reload();
              }, 3000);
            })
            .catch(() => alert('ลบคลังไม่สำเร็จ'));
          this.del = false;
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