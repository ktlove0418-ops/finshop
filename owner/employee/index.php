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
    Category Products Dashboard
  </title>
  <!--     Fonts and icons   -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <!-- <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" /> -->
  <!-- Font Awesome Icons -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js "></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <script src="../../assets/js/vue/vue3.js"></script>
  <!-- axios -->
  <script src="../../assets/js/axios/axios.0.9.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .text-right {
      text-align: right;
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
            <div class="card-header pb-0">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                เพิ่มพนักงาน
              </button>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form @submit.prevent="submitForm">
                    <div class="modal-header">
                      <h5 class="modal-title">เพิ่มพนักงาน</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <h6 class="mb-4">ฟอร์มเพิ่มพนักงาน</h6>
                      <div class="mb-3">
                        <label class="form-label">ชื่อพนักงาน</label>
                        <input type="text" v-model="employee.name" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เงินเดือน</label>
                        <input type="text" v-model="employee.salary" class="form-control" required oninput="this.value = this.value.replace(/[^0-9_]/g, '');" >
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เบอร์โทร</label>
                        <input type="text" v-model="employee.phone" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">รหัสผ่าน</label>
                        <input type="password" v-model="employee.password" class="form-control" required>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label">แผนกพนักงาน</label>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="1" v-model="employee.permissions">
                          <label class="form-check-label">ขาย</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="2" v-model="employee.permissions">
                          <label class="form-check-label">คลัง</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" value="3" v-model="employee.permissions">
                          <label class="form-check-label">ขนส่ง</label>
                        </div>
                      </div>
                      <div v-if="responseMsg!=''">
                        <div class="alert alert-success mt-3" v-if="success">
                          {{responseMsg}}
                        </div>
                        <div class="alert alert-danger mt-3" v-if="!success">
                          {{responseMsg}}
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="submit" class="btn btn-primary">เพิ่มพนักงาน</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModalRole" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form @submit.prevent="submitFormRole">
                    <div class="modal-header">
                      <h5 class="modal-title">กำหนดสิทธิ</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">ชื่อพนักงาน {{employee.name}}</label>
                        <input type="text" readonly v-model="employee.permissions" class="form-control" required>
                      </div>

                      <div v-if="employee.permissions=='พนักงานขายสินค้า'">
                        <div class="card overflow-hidden border">
                          <div class="card-title bg-secondary text-white py-2 px-4">
                            <label class="form-label">สิทธิพนักงาน</label>
                          </div>
                          <div class="mb-3 py-2 px-4" v-if="saleRoles">
                            <div class="row">
                              <div class="col-md-6" v-for="(item, i) in saleRoles" :key="i">
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :value="item.id"
                                    v-model="selectedRoles" />
                                  <label class="form-check-label">{{ item.role_name }}</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div v-else-if="employee.permissions=='พนักงานคลังสินค้า'">
                        <div class="card overflow-hidden border">
                          <div class="card-title bg-secondary text-white py-2 px-4">
                            <label class="form-label">สิทธิพนักงาน</label>
                          </div>
                          <div class="mb-3 py-2 px-4" v-if="storeRoles">
                            <div class="row">
                              <div class="col-6" v-for="(item, i) in storeRoles" :key="i">
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :value="item.id"
                                    v-model="selectedRoles" />
                                  <label class="form-check-label">{{ item.role_name }}</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div v-else-if="employee.permissions=='พนักงานขนส่ง'">
                        <div class="card overflow-hidden border">
                          <div class="card-title bg-secondary text-white py-2 px-4">
                            <label class="form-label">สิทธิพนักงาน</label>
                          </div>
                          <div class="mb-3 py-2 px-4" v-if="truckRoles">
                            <div class="row">
                              <div class="col-md-6" v-for="(item, i) in truckRoles" :key="i">
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :value="item.id"
                                    v-model="selectedRoles" />

                                  <label class="form-check-label">{{ item.role_name }}</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div v-if="responseMsg!=''">
                        <div class="alert alert-success mt-3" v-if="success">
                          {{responseMsg}}
                        </div>
                        <div class="alert alert-danger mt-3" v-if="!success">
                          {{responseMsg}}
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="submit" class="btn btn-primary">เพิ่มสิทธิพนักงาน</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form @submit.prevent="saveEmployee">
                    <div class="modal-header">
                      <h5 class="modal-title">แก้ไขข้อมูลพนักงาน</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">ชื่อพนักงาน</label>
                        <input type="text" v-model="dataEmployeesEdit.name" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เงินเดือน</label>
                        <input type="text" v-model="dataEmployeesEdit.salary" oninput="this.value = this.value.replace(/[^0-9_]/g, '');" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เบอร์โทรพนักงาน</label>
                        <input type="text" v-model="dataEmployeesEdit.phone" class="form-control" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">รหัสผ่านใหม่</label>
                        <input type="text" v-model="dataEmployeesEdit.password" class="form-control" required>
                      </div>

                      <div v-if="responseMsg!=''">
                        <div class="alert alert-success mt-3" v-if="success">
                          {{responseMsg}}
                        </div>
                        <div class="alert alert-danger mt-3" v-if="!success">
                          {{responseMsg}}
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2 px-md-4">
              <div class="table-responsive p-4">
                <h6>รายการพนักงาน</h6>
                <table class="table align-items-center mb-0 rounded border text-left" style="max-width: 480px;">
                  <!-- <thead>
                    <tr>
                      <th>ชื่อ</th>
                      <th></th>
                    </tr>
                  </thead> -->
                  <tbody>
                    <!-- แผนกพนักงานคลังสินค้า -->
                    <tr>
                      <td colspan="2"><b>แผนก: พนักงานคลังสินค้า</b></td>
                    </tr>
                  <tbody v-for="emp in dataEmployees"
                    :key="'warehouse-' + emp.id">
                    <tr
                      style="background-color: #eee;"
                      v-if="emp.permissions === 'พนักงานคลังสินค้า'">
                      <td class="ps-4">
                        <b><svg xmlns="http://www.w3.org/2000/svg" style="border-radius: 50%;border: solid 1px #aaa;" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                          </svg> {{ emp.name }}</b></br>
                        <!-- https://feathericons.dev/?search=phone&iconset=feather -->
                        <!-- https://feathericons.dev/?search=smartphone&iconset=feather -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <rect height="20" rx="2" ry="2" width="14" x="5" y="2" />
                          <line x1="12" x2="12.01" y1="18" y2="18" />
                        </svg>

                        : {{ emp.phone }}
                      </td>
                      <td style="width: 100px;">
                        <button class="btn btn-info" @click="fetchRole(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalRole">กำหนดสิทธิ</button>
                        <button class="btn btn-primary ms-2" @click="fetchEdit(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">แก้ไข</button>
                      </td>
                    </tr>
                  </tbody>
                  <!-- แผนกพนักงานขายสินค้า -->
                  <tr>
                    <td colspan="2"><b>แผนก: พนักงานขายสินค้า</b></td>
                  </tr>
                  <tbody v-for="emp in dataEmployees"
                    :key="'sale-' + emp.id">
                    <tr
                      style="background-color: #eee;"
                      v-if="emp.permissions === 'พนักงานขายสินค้า'">
                      <td class="ps-4">
                        <b><svg xmlns="http://www.w3.org/2000/svg" style="border-radius: 50%;border: solid 1px #aaa;" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                          </svg> {{ emp.name }}</b></br>
                        <!-- https://feathericons.dev/?search=smartphone&iconset=feather -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <rect height="20" rx="2" ry="2" width="14" x="5" y="2" />
                          <line x1="12" x2="12.01" y1="18" y2="18" />
                        </svg>

                        : {{ emp.phone }}
                      </td>
                      <td style="width: 100px;">
                        <button class="btn btn-info" @click="fetchRole(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalRole">กำหนดสิทธิ</button>
                        <button class="btn btn-primary ms-2" @click="fetchEdit(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">แก้ไข</button>
                      </td>
                    </tr>
                  </tbody>
                  <!-- แผนกพนักงานขนส่ง -->
                  <tr>
                    <td colspan="2"><b>แผนก: พนักงานขนส่ง</b></td>
                  </tr>
                  <tbody v-for="emp in dataEmployees"
                    :key="'sale-' + emp.id">
                    <tr
                      style="background-color: #eee;"
                      v-if="emp.permissions === 'พนักงานขนส่ง'">
                      <td class="ps-4">
                        <b><!-- https://feathericons.dev/?search=user&iconset=feather -->
                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" style="border-radius: 50%;border: solid 1px #aaa;" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                          </svg>
                          {{ emp.name }}</b></br>
                        <!-- https://feathericons.dev/?search=smartphone&iconset=feather -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <rect height="20" rx="2" ry="2" width="14" x="5" y="2" />
                          <line x1="12" x2="12.01" y1="18" y2="18" />
                        </svg>

                        : {{ emp.phone }}
                      </td>
                      <td style="width: 100px;">
                        <button class="btn btn-info" @click="fetchRole(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalRole">กำหนดสิทธิ</button>
                        <button class="btn btn-primary ms-2" @click="fetchEdit(emp)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">แก้ไข</button>
                      </td>
                    </tr>
                  </tbody>
                  <!-- กรณีไม่มีข้อมูล -->
                  <tr v-if="dataEmployees.length < 1">
                    <td colspan="5">
                      <div class="d-flex align-items-top justify-content-center px-2 py-1 w-100 text-muted" style="min-height: 200px;">
                        - ไม่พบข้อมูล -
                      </div>
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
    dayjs.locale('th');
    const {
      createApp
    } = Vue;
    createApp({
      data() {
        return {
          auth: "",
          cateNameError: false,
          cateName: "",
          noted: "",
          dataEmployees: [],
          dataEmployeesEdit: '',
          saleRoles: [],
          truckRoles: [],
          storeRoles: [],
          selectedRoles: [],
          dataRole: [],
          employee: {
            name: '',
            phone: '',
            password: '',
            salary:'',
            permissions: []
          },
          success: false,
          responseMsg: ''
        };
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
          this.fetchEmployees();
        }
      },
      methods: {

        formatThaiDate(dateStr) {
          return dayjs(dateStr).format("D MMM YY เวลา HH:mm");
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
        fetchEdit(emp) {
          this.dataEmployeesEdit = emp;
        },
        saveEmployee() {
          axios.post('../../api/', {
              post: 'save_editemployee',
              data: this.dataEmployeesEdit
            })
            .then(res => {
              if (res.data.status) {
                this.success = true;
                this.responseMsg = res.data.message;
                this.fetchEmployees();
                setTimeout(() => {
                  this.success = false;
                  this.responseMsg = ''
                }, 4000);
              } else if (res.data.status) {
                this.success = true;
                this.responseMsg = res.data.message;
                setTimeout(() => {
                  this.success = false;
                }, 10000);
              }
            });
        },
        fetchRole(emp) {
          this.employee = emp;
          this.selectedRoles = [];

          axios.post('../../api/', {
              post: 'get_role',
              emp_id: emp.id
            })
            .then(res => {
              if (res.data.status) {
                this.saleRoles = res.data.roles_sale;
                this.truckRoles = res.data.roles_truck;
                this.storeRoles = res.data.roles_store;

                // แปลง emp.roles เป็น array ของ id ที่ติ๊ก
                if (Array.isArray(emp.roles)) {
                  this.selectedRoles = [...emp.roles];
                } else if (typeof emp.roles === 'string') {
                  const roleNames = emp.roles.split(',').map(r => r.trim());
                  this.selectedRoles = res.data.selected_store
                    .filter(r => roleNames.includes(r.role_name))
                    .map(r => r.id);
                }
              } else {
                console.log('โหลดข้อมูลไม่สำเร็จ');
              }
            });
        },
        submitFormRole() {
          axios.post('../../api/', {
              post: 'save_role',
              employee_id: this.employee.id,
              roles: this.selectedRoles // <<-- ใช้ selectedRoles เท่านั้น
            })
            .then(res => {
              if (res.data.status) {
                this.success = true;
                this.responseMsg = 'บันทึกสำเร็จ';
              } else {
                this.success = false;
                this.responseMsg = 'บันทึกไม่สำเร็จ';
              }
            });
        },
        fetchEmployees() {
          axios.post('../../api/', {
              post: 'get_employee'
            })
            .then(res => {
              if (res.data.status) {
                this.dataEmployees = res.data.employees;
              } else {
                console.log('โหลดข้อมูลไม่สำเร็จ');
              }
            })
            .catch(err => {
              console.error(err);
              console.log('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            });
        },
        async submitForm() {
          const postData = {
            post: 'add_employee',
            name: this.employee.name,
            phone: this.employee.phone,
            password: this.employee.password,
            salary: this.employee.salary,
            permissions: this.employee.permissions
          };

          axios.post('../../api/', postData)
            .then(res => {
              this.responseMsg = res.data.message;
              if (res.data.status) {
                // ล้างฟอร์มหากบันทึกสำเร็จ
                this.success = true;
                this.fetchEmployees();
                this.employee = {
                  name: '',
                  phone: '',
                  password: '',
                  permissions: []
                };
                setTimeout(() => {
                  this.success = false;
                  this.responseMsg = ''
                }, 4000);
              } else if (res.data.status) {
                this.success = true;
                this.responseMsg = res.data.message;
                setTimeout(() => {
                  this.success = false;
                }, 10000);
              }
            })
            .catch(err => {
              this.responseMsg = 'เกิดข้อผิดพลาด';
              console.error(err);
            });
        }
      },
    }).mount("#app");
  </script>
</body>

</html>