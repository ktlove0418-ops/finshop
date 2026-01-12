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
        <div class="container my-4">
  <div class="card shadow-lg rounded-3">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0 text-white">จัดการสิทธิพนักงาน</h5>
    </div>
    <div class="card-body">
      <form @submit.prevent="savePermissionRoles">

        <!-- เลือกแผนก -->
        <div class="mb-3">
          <label class="form-label">เลือกแผนก</label>
          <select v-model="selectedPermission" class="form-select" @change="fetchRolesByPermission">
            <option value="" disabled>-- กรุณาเลือกแผนก --</option>
            <option v-for="p in permissions" :key="p.id" :value="p.id">
              {{ p.name }}
            </option>
          </select>
        </div>

        <!-- เลือกสิทธิ -->
        <div v-if="roles.length > 0" class="mb-3">
          <label class="form-label">เลือกสิทธิของแผนก</label>
          <div class="row">
            <div class="col-6" v-for="(r, i) in roles" :key="i">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" :value="r.id" v-model="selectedRoles">
                <label class="form-check-label">{{ r.role_name }}</label>
              </div>
            </div>
          </div>
        </div>

        <!-- ข้อความตอบกลับ -->
        <div v-if="responseMsg" class="mt-3">
          <div class="alert" :class="success ? 'alert-success' : 'alert-danger'">
            {{ responseMsg }}
          </div>
        </div>

        <!-- ปุ่ม -->
        <div class="mt-3">
          <button type="submit" class="btn btn-success" :disabled="!selectedPermission">
            บันทึกสิทธิ
          </button>
        </div>
      </form>
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
          dataPermissions: [],
          dataRole: [],
          permissionName: '',
          message: '',
          status: false,
          success: false,
          responseMsg: '',
          permissions: [],        // แผนก
    roles: [],              // สิทธิ์ของแผนกที่เลือก
    selectedPermission: "", // แผนกที่เลือก
    selectedRoles: [],      // สิทธิที่เลือก
    responseMsg: "",
    success: false
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
          this.fetchPermissions();
          this.fetchPermission();
          this.fetchRole();
        }
      },
      methods: {
        async fetchPermissions() {
          const res = await axios.post("../../api/", { post: "get_permissions" });
          this.permissions = res.data;
        },
        async fetchRolesByPermission() {
          const res = await axios.post("../../api/", { 
            post: "get_roles_by_permission", 
            permission_id: this.selectedPermission 
          });
          this.roles = res.data.roles;
          this.selectedRoles = res.data.selected; // เอาสิทธิเดิมมาเช็คให้เลย
        },
        async savePermissionRoles() {
          try {
            const res = await axios.post("../../api/", {
              post: "save_permission_roles",
              permission_id: this.selectedPermission,
              roles: this.selectedRoles
            });

            this.responseMsg = res.data.message;
            this.success = res.data.status;
          } catch (err) {
            this.responseMsg = "ไม่สามารถบันทึกข้อมูลได้";
            this.success = false;
          }
        },
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