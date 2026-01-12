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
  <!--     Fonts and icons     -->
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
          <div class="card mb-4 px-2 px-md-4">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-6">
                  <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    เพิ่มแผนก
                  </button>
                </div>
                <div class="col-6">
                  <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModalRole">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                    เพิ่มสิทธิ
                  </button>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form @submit.prevent="submitPermission">
                    <div class="modal-header">
                      <h5 class="modal-title">เพิ่มแผนก (Permission)</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="container mt-4">


                        <div class="mb-3">
                          <label class="form-label">ชื่อแผนก</label>
                          <input v-model="permissionName" type="text" class="form-control" required />
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
                    <div class="modal-footer d-flex justify-content-between">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <div>
                        <button type="reset" class="btn btn-secondary me-2">ล้าง</button>
                        <button type="submit" class="btn btn-primary">เพิ่มแผนก</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModalRole" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form @submit.prevent="submitRole">
                    <div class="modal-header">
                      <h5 class="modal-title">เพิ่มสิทธิ์ (Role)</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="container mt-4">


                        <div class="mb-3">
                          <label class="form-label">ชื่อสิทธิ์</label>
                          <input v-model="permissionName" type="text" class="form-control" required />
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
                    <div class="modal-footer d-flex justify-content-between">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      <div>
                        <button type="reset" class="btn btn-secondary me-2">ล้าง</button>
                        <button type="submit" class="btn btn-primary">เพิ่มสิทธิ์</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="card-body px-0 pt-0 pb-2 px-md-4">
                  <div class="table-responsive p-0">
                    <h6>รายการแผนก</h6>
                    <table class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th>ชื่อ</th>
                          <!-- <th>เบอร์โทร</th>
                      <th>สิทธิ์</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="emp in dataPermissions" :key="emp.id">
                          <td>{{ emp.name }}</td>
                        </tr>
                        <tr v-if="dataPermissions.length<1">
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
              <div class="col-md-6">
                <div class="card-body px-0 pt-0 pb-2 px-md-4">
                  <div class="table-responsive p-0">
                    <h6>สิทธิพนักงาน</h6>
                    <table class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th>ชื่อ</th>
                          <!-- <th>เบอร์โทร</th>
                      <th>สิทธิ์</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="emp in dataRole" :key="emp.id">
                          <td>{{ emp.role_name }}</td>
                        </tr>
                        <tr v-if="dataRole.length<1">
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
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Argon Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default" onclick="sidebarType(this)">Dark</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="d-flex my-3">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <div class="mt-2 mb-5 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/argon-dashboard">Free Download</a>
        <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/license/argon-dashboard">View documentation</a>
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/argon-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/argon-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Argon%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fargon-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/argon-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
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
          dataPermissions: [],
          dataRole: [],
          permissionName: '',
          message: '',
          status: false,
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
          this.fetchPermission();
          this.fetchRole();
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
        fetchPermission() {
          axios.post('../../api/', {
              post: 'get_permission'
            })
            .then(res => {
              if (res.data.status) {
                this.dataPermissions = res.data.permissions;
              } else {
                console.log('โหลดข้อมูลไม่สำเร็จ');
              }
            })
            .catch(err => {
              console.error(err);
              console.log('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            });
        },
        async submitPermission() {
          try {
            const response = await axios.post('../../api/', {
              post: 'add_permission',
              name: this.permissionName
            })

            this.responseMsg = response.data.message;
            this.success = response.data.status;
            this.permissionName = '';
            this.fetchPermission();
            setTimeout(() => {
              this.success = false;
              this.responseMsg = '';
            }, 4000);

          } catch (err) {
            this.responseMsg = 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
            this.success = false
          }
        },
        fetchRole() {
          axios.post('../../api/', {
              post: 'get_role'
            })
            .then(res => {
              if (res.data.status) {
                this.dataRole = res.data.role;
              } else {
                console.log('โหลดข้อมูลไม่สำเร็จ');
              }
            })
            .catch(err => {
              console.error(err);
              console.log('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            });
        },
        async submitRole() {
          try {
            const response = await axios.post('../../api/', {
              post: 'add_role',
              name: this.permissionName
            })

            this.responseMsg = response.data.message;
            this.success = response.data.status;
            this.permissionName = '';
            this.fetchRole();
            setTimeout(() => {
              this.success = false;
              this.responseMsg = '';
            }, 4000);

          } catch (err) {
            this.responseMsg = 'เกิดข้อผิดพลาดในการเชื่อมต่อ'
            this.success = false
          }
        }
      },
    }).mount("#app");
  </script>
</body>

</html>