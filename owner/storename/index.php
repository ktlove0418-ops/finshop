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
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">

              <!-- ฟอร์มเพิ่มคลังสินค้า -->
              <div class="card mb-4">
                <div class="card-body">
                  <h5 class="card-title">{{text}}</h5>
                  <div class="row g-2">
                    <div class="col-md-6">
                      <input v-model="warehouseForm.name" type="text" class="form-control" placeholder="ชื่อคลังสินค้า">
                    </div>
                    <div class="col-md-6">
                      <input v-model="warehouseForm.location" type="text" class="form-control" placeholder="ที่ตั้ง">
                    </div>
                    <div class="col-md-12" v-if="edit">
                      <button class="btn btn-warning" @click="saveEditWarehouse">บันทึกการแก้ไข</button>
                      <button class="btn btn-border ms-3" @click="clearWarehouse">เลิกทำ</button>
                    </div>
                    <div class="col-md-12" v-else>
                      <button class="btn btn-success" @click="addWarehouse">บันทึกคลัง</button>
                      <button class="btn btn-border ms-3" @click="clearWarehouse">เลิกทำ</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-body ">
              <div class="table-responsive" style="min-height: 280px;">

                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title">รายการคลังสินค้า</h5>
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
                  </div>
                </div>
                <!-- ===== TABLE (แสดงเฉพาะ md ขึ้นไป) ===== -->
<div class="table-responsive d-none d-md-block">
  <table class="table table-bordered align-middle">
    <thead>
      <tr>
        <th>ชื่อคลัง</th>
        <th>ที่ตั้ง</th>
        <th>แจ้งเตือน</th>
        <th>จัดการ</th>
        <th>ผู้ดูแล</th>
      </tr>
    </thead>

    <!-- แนะนำให้ย้าย v-for มาไว้ที่ <tr> จะชัดกว่า -->
    <tbody>
      <tr v-for="item in paginatedProducts" :key="item.id">
        <td class="px-4"><b class="text-dark">{{ item.name }}</b></td>
        <td>{{ item.location }}</td>
        <td>
          <button class="btn btn-success btn-xs me-1 py-1 px-2" @click="goToWarehouse(item.id,item.name)">
            <p class="m-0 p-0">{{item.total_products}}</p> สินค้า
          </button>
          <button class="btn btn-warning btn-xs me-1 p-1" @click="goToWarehouse(item.id,item.name)">
            <p class="m-0 p-0">{{item.low_stock}}</p> ใกล้หมด
          </button>
          <button class="btn btn-secondary btn-xs me-1 p-1" @click="goToWarehouse(item.id,item.name)">
            <p class="m-0 p-0">{{item.out_of_stock}}</p> หมดแล้ว
          </button>
        </td>
        <td style="width: 200px;">
          <button class="btn btn-info btn-sm me-3" @click="goToWarehouse(item.id,item.name)">
            <!-- icon list -->
            <!-- ... SVG เดิม ... -->
            ดูข้อมูล
          </button>
          <button class="btn btn-primary btn-sm me-3" @click="editWarehouse(item)">
            <!-- icon edit -->
            <!-- ... SVG เดิม ... -->
            แก้ไข
          </button>
          <button class="btn btn-danger btn-sm"
                  @click="deleteWarehouse(item)"
                  data-bs-toggle="modal" data-bs-target="#exampleModalDel">
            <!-- icon trash -->
            <!-- ... SVG เดิม ... -->
            ลบ
          </button>
        </td>
        <td>
          <div v-html="item.person"></div>
          <small class="text-muted text-sm">{{ formatThaiDate(item.created_at) }}</small>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<!-- ===== CARDS (แสดงเฉพาะจอเล็ก) d-md-none ===== -->
<div class="d-md-none">
  <div v-for="item in paginatedProducts" :key="item.id" class="card mb-3 shadow-sm">
    <div class="card-body py-3">
      <!-- Header: ชื่อคลัง + ผู้ดูแล/วันที่ -->
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="mb-1">{{ item.name }}</h6>
          <div class="text-muted small">{{ item.location }}</div>
        </div>
        <div class="text-end small">
          <div v-html="item.person"></div>
          <div class="text-muted">{{ formatThaiDate(item.created_at) }}</div>
        </div>
      </div>

      <hr class="my-2">

      <!-- แจ้งเตือน: ปุ่มสถิติ -->
      <div class="d-flex gap-2">
        <button class="btn btn-success btn-sm flex-fill" @click="goToWarehouse(item.id,item.name)">
          <span class="fw-bold">{{ item.total_products }}</span> สินค้า
        </button>
        <button class="btn btn-warning btn-sm flex-fill" @click="goToWarehouse(item.id,item.name)">
          <span class="fw-bold">{{ item.low_stock }}</span> ใกล้หมด
        </button>
        <button class="btn btn-secondary btn-sm flex-fill" @click="goToWarehouse(item.id,item.name)">
          <span class="fw-bold">{{ item.out_of_stock }}</span> หมดแล้ว
        </button>
      </div>

      <!-- Action buttons -->
      <div class="d-flex justify-content-end gap-2 mt-3">
        <button class="btn btn-outline-info btn-sm" @click="goToWarehouse(item.id,item.name)">
          <!-- icon list -->
          <!-- ... SVG เดิม ... -->
          ดูข้อมูล
        </button>
        <button class="btn btn-info btn-sm" @click="editWarehouse(item)">
          <!-- icon edit -->
          <!-- ... SVG เดิม ... -->
          แก้ไข
        </button>
        <button class="btn btn-danger btn-sm"
                @click="deleteWarehouse(item)"
                data-bs-toggle="modal" data-bs-target="#exampleModalDel">
          <!-- icon trash -->
          <!-- ... SVG เดิม ... -->
          ลบ
        </button>
      </div>
    </div>
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
      <div class="table-responsive px-4 mt-5" style="min-height: 280px;">
        <h5 class="card-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill me-2" viewBox="0 0 16 16">
            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
          </svg>
          รายการคลังสินค้าที่ลบ
        </h5>
        <table class="table table-bordered">
          <thead>
            <tr class="bg-secondary">
              <th class=" text-white">ชื่อคลัง</th>
              <th class=" text-white">ที่ตั้ง</th>
              <th class=" text-white">วันที่</th>
              <th class=" text-white">ผู้ดูแล</th>
            </tr>
          </thead>
          <tbody v-for="item in warehouses" :key="item.id">
            <tr v-if="item.isActive == 44">
              <td class="px-4">{{ item.name }}</td>
              <td>{{ item.location }}</td>
              <td style="width: 200px;">
                {{ formatThaiDate(item.created_at) }}
              </td>
              <td v-html="item.person"></td>
            </tr>
          </tbody>
        </table>
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
          warehouses: [],
          searchKeyword: '',
          filteredProducts: [],
          image: null,
          products: [],
          currentPage: 1,
          perPage: 10,
          intervalId: null,
        };
      },
      computed: {
        selectedCategoryName() {
          const selected = this.warehouses.find(cat => cat.name === this.name);
          return selected ? selected.name : 'ยังไม่ได้เลือก';
        },

        totalPages() {
          return Math.ceil(this.warehouses.length / this.perPage);
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.warehouses.slice(start, end);
        }
      },
      mounted() {
        const profile = localStorage.getItem("Fin-Profile");

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
          let porson = JSON.parse(profile)
          if (porson.data.position != 'owner') {
            window.location = "../../" + porson.redirect;
          }
          this.loadWarehouses();
          this.intervalId = setInterval(() => {
            this.loadWarehouses();
          }, 12000); // ทุก 8 วินาที
        }
      },
      beforeUnmount() {
        // ล้าง interval ตอน component ถูกถอด
        clearInterval(this.intervalId);
      },
      methods: {

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

          return `${d.format('DD MMM YY')}` //${shortYear} เวลา ${d.format('HH:mm')}`;
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
              post: 'get_warehouses'
            })
            .then(res => this.warehouses = res.data.data)
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
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