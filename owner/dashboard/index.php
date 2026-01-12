<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 dashboard (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by dashboard

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link
    rel="apple-touch-icon"
    sizes="76x76"
    href="../../assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png" />
  <title>finshop Dashboard</title>
  <!--     Fonts and icons     -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
    rel="stylesheet" />
  <!-- CSS Files -->
  <link
    id="pagestyle"
    href="../../assets/css/argon-dashboard.css?v=2.1.0"
    rel="stylesheet" />
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js "></script> -->
  <script src="../../assets/js/vue/vue3.js"></script>
  <!-- axios -->
  <script src="../../assets/js/axios/axios.0.9.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav
      class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
      id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol
            class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-white" href="javascript:;">OWNER</a>
            </li>
            <li
              class="breadcrumb-item text-sm text-white active"
              aria-current="page">
              Dashboard
            </li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">
            Dashboard /ภาพรวม
          </h6>
        </nav>
        <div
          class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4"
          id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              
            </div>
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a
                href="javascript:;"
                class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">OWNER</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a
                href="javascript:;"
                class="nav-link text-white p-0"
                id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i
                  class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a
                href="javascript:;"
                class="nav-link text-white p-0"
                id="dropdownMenuButton"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4"
                aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a
                    class="dropdown-item border-radius-md"
                    href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img
                          src="../../assets/img/team-2.jpg"
                          class="avatar avatar-sm me-3" />
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span>
                          from Laur
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 minutes ago
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="mb-2">
                  <a
                    class="dropdown-item border-radius-md"
                    href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img
                          src="../../assets/img/small-logos/logo-spotify.svg"
                          class="avatar avatar-sm bg-gradient-dark me-3" />
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by
                          Travis Scott
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 day
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a
                    class="dropdown-item border-radius-md"
                    href="javascript:;">
                    <div class="d-flex py-1">
                      <div
                        class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                        <svg
                          width="12px"
                          height="12px"
                          viewBox="0 0 43 36"
                          version="1.1"
                          xmlns="http://www.w3.org/2000/svg"
                          xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g
                            stroke="none"
                            stroke-width="1"
                            fill="none"
                            fill-rule="evenodd">
                            <g
                              transform="translate(-2169.000000, -745.000000)"
                              fill="#FFFFFF"
                              fill-rule="nonzero">
                              <g
                                transform="translate(1716.000000, 291.000000)">
                                <g
                                  transform="translate(453.000000, 454.000000)">
                                  <path
                                    class="color-background"
                                    d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                    opacity="0.593633743"></path>
                                  <path
                                    class="color-background"
                                    d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          Payment successfully completed
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          2 days
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4" id="app">

      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                      ยอดขาย
                    </p>
                    <h5 class="font-weight-bolder">฿{{ formatPrice(summaryData.total_sale) }}</h5>
                    <p class="mb-0 text-nowrap">
                      <!-- <span class="text-success text-sm font-weight-bolder">+55%</span> -->
                      ยอดขายทุกสาขา
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div
                    class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                      <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                      <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                      <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                      เงินต้นทุน
                    </p>
                    <h5 class="font-weight-bolder">฿{{ formatPrice(summaryData.total_cost) }}</h5>
                    <p class="mb-0 text-nowrap">
                      <!-- <span class="text-success text-sm font-weight-bolder">+3%</span> -->
                      ราคาต้นทุนสินค้า
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div
                    class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i
                      class="ni ni-world text-lg opacity-10"
                      aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                      กำไร
                    </p>
                    <h5 class="font-weight-bolder">+฿{{ formatPrice(summaryData.profit) }}</h5>
                    <p class="mb-0 text-nowrap">
                      <!-- <span class="text-danger text-sm font-weight-bolder">-2%</span> -->
                      ยอดขาย หักต้นทุนแล้ว
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div
                    class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i
                      class="ni ni-paper-diploma text-lg opacity-10"
                      aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                      ทุนสินค้าคงเหลือ
                    </p>
                    <h5 class="font-weight-bolder">฿{{ formatPrice(summaryData.stock_value) }}</h5>
                    <p class="mb-0 text-nowrap ">
                      สินค้า
                      <span class="text-success text-sm font-weight-bolder">5</span>
                      รายการ
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div
                    class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i
                      class="ni ni-cart text-lg opacity-10"
                      aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-12 mb-lg-0 mb-4">
          <div class="card p-3">
            <div class="row mb-3">
              <div class="col-md-3">
                <select v-model="rangeType" class="form-select" @change="fetchSummaryData">
                  <option value="today">วันนี้</option>
                  <option value="week">สัปดาห์นี้</option>
                  <option value="month">เดือนนี้</option>
                  <option value="year">รายปี</option>
                  <option value="custom">กำหนดเอง</option>
                </select>
              </div>
              <div class="col-md-4" v-if="rangeType === 'custom'">
                <input type="date" v-model="customStart" class="form-control" />
              </div>
              <div class="col-md-4" v-if="rangeType === 'custom'">
                <input type="date" v-model="customEnd" class="form-control" />
              </div>
              <div class="col-md-1" v-if="rangeType === 'custom'">
                <button class="btn btn-primary" @click="fetchSummaryData">ดูผล</button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent  d-flex justify-content-between">
              <div>
                <h6 class="text-capitalize">Sales overview</h6>
                <p class="text-sm mb-0">
                  <i class="fa fa-arrow-up text-success"></i>
                  <!-- <span class="font-weight-bold">4% more</span> in 2021 -->
                </p>
              </div>
              
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas
                  id="chart-line"
                  class="chart-canvas"
                  height="300"></canvas>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row mt-4">
        <div class="col-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">

                <div>
                  <h6 class="mb-2">Sales Summary</h6>
                </div>
                <div>
                  <select class="border rounded p-1" v-model="selectedTimeframe" @change="getDashboardSummary(selectedTimeframe)">
                    <option value="day" selected>วันนี้</option>
                    <option value="week">สัปดาห์นี้</option>
                    <option value="month">เดือนนี้</option>
                    <option value="year">ปีนี้</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="table-responsive px-2">

              <table class="table align-items-center">
                <thead>
                  <tr>
                    <th>คลังสินค้า</th>
                    <th style="text-align: right;">ยอดขาย</th>
                    <th style="text-align: right;">ต้นทุน</th>
                    <th style="text-align: right;">กำไร</th>
                    <th style="text-align: right;"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="row in dashboardData" :key="row.warehouse_name">
                    <td>{{ row.warehouse_name }}</td>
                    <td style="text-align: right;">{{ formatPrice(row.total_sale) }}</td>
                    <td style="text-align: right;">{{ formatPrice(row.total_cost) }}</td>
                    <td style="text-align: right;">{{ formatPrice(row.total_profit) }}</td>
                    <td style="text-align: right;">
                      <button class="btn btn-sm btm-primary" @click="goWarsHouse(row)">ดูข้อมูล</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
      <footer class="footer pt-3">
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
  <script src="../../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
    gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
    gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");
    new Chart(ctx1, {
      type: "line",
      data: {
        labels: [
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ],
        datasets: [{
          label: "Mobile apps",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke1,
          borderWidth: 3,
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6,
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        interaction: {
          intersect: false,
          mode: "index",
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
            },
            ticks: {
              display: true,
              padding: 10,
              color: "#fbfbfb",
              font: {
                size: 11,
                family: "Open Sans",
                style: "normal",
                lineHeight: 2,
              },
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5],
            },
            ticks: {
              display: true,
              color: "#ccc",
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: "normal",
                lineHeight: 2,
              },
            },
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf("Win") > -1;
    if (win && document.querySelector("#sidenav-scrollbar")) {
      var options = {
        damping: "0.5",
      };
      Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/@1/.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@1/locale/th.js"></script>
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
        const today = new Date();
        const todayStr = today.toISOString().substr(0, 10);
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().substr(0, 10);
        return {
          auth: "",
          dashboardData: [],
          dashboardTimeframe: '',
          rangeType: 'today',
          customStart: todayStr,
          customEnd: tomorrowStr,
          persent: 0,
          selectedTimeframe:'',
          summaryData: {
            total_sale: 0,
            total_cost: 0,
            profit: 0,
            stock_value: 0,
            product_count: 0
          },
          weeklyChart: null,
          weekChartData: [],
          chartInstance: null,
          labelsChart:'',
          totalsChart:'',
          profitChart:'',
          warehouses:''
        };
      },

      mounted() {
        const profile = localStorage.getItem("Fin-Profile");

        if (!profile || profile === "undefined") {
          // ตรวจสอบว่าค่าเป็น null, undefined หรือ "undefined"

          window.location = "../../";
        } else {
          let porson = JSON.parse(profile)
          if (porson.data.position != 'owner') {
            window.location = "../../" + porson.redirect;
          }
          try {
            const parsedProfile = JSON.parse(profile); // แปลง JSON เฉพาะเมื่อค่าถูกต้อง
            this.userProfile = {
              fname: parsedProfile.fname,
              lname: parsedProfile.lname,
              uid: parsedProfile.uid,
              phone: parsedProfile.phone,
              credit: parsedProfile.credit,
            };
            this.loadWarehouses();
            this.fetchSummaryData();
            this.getDashboardSummary();
          } catch (error) {
            console.error("Invalid JSON in Profile:", error);
            // window.location = "login";
          }
        }
      },
      methods: {
        getBack() {
          window.location = "../top";
        },
        goWarsHouse(row) {
          window.location = "./warehouse/?id="+row.id+"&warehousename="+row.warehouse_name;
        },
        formatThaiDate(dateStr) {
          return dayjs(dateStr).format("HH:mm");
          // return dayjs(dateStr).format("D MMMM YYYY เวลา HH:mm");
        },
        formatPrice(price) {
          return Number(price).toLocaleString(); // ใส่ comma (,)
        },
        loadWarehouses() {
          axios.post('../../api/', {
              post: 'get_warehouses'
            })
            .then(res => this.warehouses = res.data.data)
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
        },
        async fetchSummaryData() {
          const payload = {
            post: 'get_dashboard_summary_total',
            range: this.rangeType
          };

          if (this.rangeType === 'custom') {
            if (!this.customStart || !this.customEnd) return console.log("กรุณาระบุช่วงวันที่ให้ครบ");
            payload.start_date = this.customStart;
            payload.end_date = this.customEnd;
          }

          try {
            const  res = await axios.post('../../api/', payload);
            if(res.data.status){
              this.summaryData = res.data;

                this.labelsChart = res.data.data.map(d => d.label);
                this.totalsChart = res.data.data.map(d => d.total ? parseFloat(d.total) : 0);
                this.profitChart = res.data.data.map(d => d.profit ? parseFloat(d.profit) : 0);
                this.renderChart();
              // if (this.rangeType === 'today') {
              //   this.getWeeklySummary();
              // }else if (this.rangeType === 'week') {
              //   this.getWeeklySummary();
              // }else if (this.rangeType === 'month') {
              //   this.getMonthlySummary();
              // }else if (this.rangeType === 'year') {
              //   this.getMonthlySummary();
              // }
            }
          } catch (err) {
            console.error(err);
          }
        },
       
        async getDashboardSummary(timeframe = 'month') {
          const res = await axios.post('../../api/', {
            post: 'get_dashboard_summary',
            timeframe: timeframe
          });

          if (res.data.status) {
            this.dashboardData = res.data.data;
            this.dashboardTimeframe = res.data.timeframe;
          }
        },
        async getWeeklySummary() {
          const res = await axios.post('../../api/', {
            post: 'get_sale_summary_by_week'
          });
          if (res.data.status) {
            this.labelsChart = this.summaryData.data.map(d => d.label);
            this.totalsChart = this.summaryData.data.map(d => d.total ? parseFloat(d.total) : 0);
            this.profitChart = this.summaryData.data.map(d => d.profit ? parseFloat(d.profit) : 0);
            console.log('totalsChart',this.totalsChart);
            console.log('profitChart',this.profitChart);
            const labels = res.data.data.map(d => d.label);
            const totals = res.data.data.map(d => d.total);
            this.renderChart();
          }
        },
        async getMonthlySummary() {
          const res = await axios.post('../../api/', {
            post: 'get_sale_summary_by_month'
          });
          if (res.data.status) {
            this.labelsChart = res.data.data.map(d => d.label);
            this.totalsChart = res.data.data.map(d => d.total ? parseFloat(d.total) : 0);
            this.profitChart = res.data.data.map(d => d.profit ? parseFloat(d.profit) : 0);
            const labels = res.data.data.map(d => d.label);
            const totals = res.data.data.map(d => d.total);
            this.renderChart();
          }
        },
        
        renderChart() {
          if (this.chartInstance) this.chartInstance.destroy();
          const ctx = document.getElementById('chart-line');
          this.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
              labels: this.labelsChart,
              datasets: [
                {
                  label: 'ยอดขายรวม',
                  data: this.totalsChart,
                  borderColor: 'rgb(54,162,235)',
                  backgroundColor: 'rgba(54,162,235,0.2)',
                  tension: 0.4,
                  fill: true,
                },
                {
                  label: 'กำไร',
                  data: this.profitChart,
                  borderColor: 'rgb(255,99,132)',
                  backgroundColor: 'rgba(255,99,132,0.2)',
                  tension: 0.4,
                  fill: true,
                }
              ]
            },
            options: {
              responsive: true,
              scales: { y: { beginAtZero: true } }
            }
          });
        }
      },
    }).mount("#app");
  </script>
</body>

</html>