<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>
    Logs Products Dashboard
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
              <h2 class="mb-3">📊 รายงานประวิติการทำงาน</h2>
              <!-- Filter -->
              <div class="row mb-3">
                <div class="col-md-3 my-1">
                  <input type="date" v-model="filterDate" class="form-control">
                </div>
                <div class="col-md-3 my-1">
                  <input type="text" v-model="filterWarehouse" class="form-control" placeholder="รหัสสาขา (เช่น 1)">
                </div>
                <div class="col-md-3 my-1">
                  <input type="text" v-model="keyword" class="form-control" placeholder="ชื่อพนักงาน">
                </div>
                <div class="col-md-3 my-1">
                  <button class="btn btn-primary" @click="loadSummary">โหลดข้อมูล</button>
                </div>
              </div>
              <!-- Summary Table -->
              <div 
  v-for="summary in summaries" 
  :key="summary.warehouse_id" 
  class="mb-4 p-4 border rounded shadow-sm bg-white"
>

  <!-- Header ชื่อสาขา -->
  <div class="mb-2">
    <h4 class="fw-bold mb-1">
      <i class="bi bi-shop me-2"></i> {{ summary.name }}
    </h4>
    <h6 class="text-muted">
     [ <span v-html="summary.person"></span> ]
      <small class="ms-2">วันที่ {{ formatDate(summary.created_at) }}</small>
    </h6>
  </div>

  <!-- รายละเอียดสรุปยอด -->
  <div class="small mb-3 p-2 rounded bg-light border">
    <span class="me-3">
      <i class="bi bi-geo-alt"></i> สาขา: <b>{{ summary.warehouse_id }}</b>
    </span>

    <span class="me-3 text-danger">
      <i class="bi bi-tags"></i> ส่วนลด: 
      <b>{{ formatPrice(summary.discount) }}</b> บาท
    </span>

    <span class="me-3">
      <i class="bi bi-credit-card"></i>
      จ่ายด้วย: <b>{{ summary.payment_method }}</b>
    </span>

    <span class="me-3">
      <i class="bi bi-wallet2"></i>
      รับเงิน: <b>{{ formatPrice(summary.received) }}</b> บาท
    </span>

    <span class="text-danger">
      <i class="bi bi-cash-coin"></i>
      ทอนเงิน: <b>{{ formatPrice(summary.change_amount) }}</b> บาท
    </span>
  </div>

  <!-- ตารางรายการสินค้า -->
  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th width="40%">สินค้า</th>
          <th class="text-center">จำนวนขาย</th>
          <th class="text-end">ยอดขายรวม</th>
          <th class="text-center">วันที่ขาย</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in summary.products" :key="item.id">

          <!-- คอลัมน์ชื่อสินค้า -->
          <td>
            <img 
              :src="item.image" 
              width="50"
              class="me-2 rounded" 
              v-if="item.image"
            >
            {{ item.name }}
          </td>

          <td class="text-center">
            <span class="badge bg-primary rounded-pill px-3 py-2">
              {{ item.sale_qty }}
            </span>
          </td>

          <td class="text-end text-success fw-bold">
            {{ formatPrice(item.total_sale) }}
          </td>

          <td class="text-center">
            <small class="text-secondary">
              {{ item.date }}
            </small>
          </td>

        </tr>
      </tbody>

    </table>
  </div>

</div>

            </div>
            <div class="card-body px-0 pt-0 pb-2 px-md-4" style="height: 300px;">
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                <script>
                  document.write(new Date().getFullYear())
                </script>© Soft by <img src="../../assets/img/lgg.png" width="20" alt="logo">ɢɪɢᴀᴊᴜ ꜱᴛᴜᴅɪᴏ.
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
          responseMsg: '',
          summaries: [],
          filterDate: '',
          filterWarehouse: '',
          keyword:''
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
          this.loadSummary();
        }
      },
      methods: {
    async loadSummary() {
      try {
        const payload = {
          post: "logs",
        };
        if (this.filterDate) payload.date = this.filterDate;
        if (this.filterWarehouse) payload.warehouse_id = this.filterWarehouse;
        if (this.keyword) payload.keyword = this.keyword;

        const res = await axios.post("../../api/", payload);
        if (res.data.status) {
          this.summaries = res.data.data;
        } else {
          alert("ไม่พบข้อมูล");
        }
      } catch (err) {
        console.error(err);
        alert("โหลดข้อมูลไม่สำเร็จ");
      }
    },

    formatDate(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleDateString("th-TH", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit"
      });
    },
    formatPrice(num) {
      return Number(num).toLocaleString("th-TH", { minimumFractionDigits: 2 });
    }
  },
    }).mount("#app");
  </script>
</body>

</html>