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
    .card .badge {
    border-radius: 999px;
    padding: .35rem .6rem;
  }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <?php include('../layout/navbar.html'); ?>
    <div id="app" class="container py-4">
      <h3 class="mb-3">จัดการเงินเดือนพนักงาน</h3>
      <div v-if="selectedEmployee" class="card p-3 mb-3 shadow-sm">
        <div class="row">
          <div class="col-md-6">
            <div><strong>ชื่อ:</strong> {{ selectedEmployee.name }}</div>
            <div v-if="selectedEmployee.position"><strong>ตำแหน่ง:</strong> {{ selectedEmployee.position }}</div>
            <div v-if="selectedEmployee.phone"><strong>โทร:</strong> {{ selectedEmployee.phone }}</div>
          </div>
          <div class="col-md-6">
            <div v-if="selectedEmployee.base_salary != null">
              <strong>เงินเดือนพื้นฐาน:</strong> {{ Number(selectedEmployee.base_salary).toLocaleString() }} บาท
            </div>
            <div v-if="selectedEmployee.default_shift">
              <strong>กะปกติ:</strong> {{ selectedEmployee.default_shift == 1 ? 'กะเช้า' : 'กะดึก' }}
            </div>
          </div>
        </div>
      </div>
      <div class="card p-3 mb-4 shadow-sm">
        <div class="mb-3" v-if="dataEmployees">
          <label class="form-label">เลือกพนักงาน</label>
          <select class="form-select" v-model="form.employee_id" @change="selectedEmp">
            <option disabled value="">-- เลือกพนักงาน --</option>
            <option v-for="emp in dataEmployees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
          </select>
        </div>

        <div class="mb-3" v-if="form">
          <label class="form-label">เลือกกะทำงาน</label>
          <select class="form-select" v-model="form.shift">
            <option value="1">กะเช้า (09:00 - 21:00)</option>
            <option value="2">กะดึก (21:00 - 09:00)</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">เดือน</label>
          <input type="month" class="form-control" v-model="form.month">
          <small class="text-muted d-block mt-1">
            {{ thaiMonthLabel }}
          </small>
        </div>

        <div class="row" v-if="form">
          <div class="col-md-6 mb-3">
            <label class="form-label">เงินเดือน (บาท)</label>
            <input type="number" class="form-control" v-model="form.salary" min="0">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">โบนัส (บาท)</label>
            <input type="number" class="form-control" v-model="form.bonus" min="0">
          </div>
        </div>

        <button class="btn btn-primary" @click="saveSalaryToAPI">บันทึกข้อมูล</button>
      </div>

      <h5 class="mb-2">รายการเงินเดือนทั้งหมด</h5>
      <div class="d-md-none">
        <div v-if="salaryList.length === 0" class="text-center text-muted py-3">
          - ไม่มีข้อมูล -
        </div>

        <div v-for="item in salaryList" :key="item.id" class="card shadow-sm mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <h6 class="mb-1">{{ item.employee_name }}</h6>
                <small class="text-muted">{{ formatThaiMonth(item.month) }}</small>
              </div>
              <span
                class="badge"
                :class="item.shift == 1 ? 'bg-success' : 'bg-dark'"
              >
                {{ item.shift == 1 ? 'กะเช้า' : 'กะดึก' }}
              </span>
            </div>

            <div class="row g-2">
              <div class="col-6 px-0">
                <div class="text-muted border-bottom">เงินเดือน</div>
                <div class="text-muted border-bottom">โบนัส</div>
              </div>
              <div class="col-6 text-end px-0">
                <div class="fw-semibold border-bottom">{{ formatCurrency(item.salary) }}</div>
                <div class="fw-semibold border-bottom">{{ formatCurrency(item.bonus) }}</div>
              </div>
            </div>

            <hr class="my-2">

            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted">รวมทั้งหมด</div>
              <div class="fw-bold h6 mb-0">{{ formatCurrency(item.total) }}</div>
            </div>
          </div>
        </div>
      </div>

      <table class="table table-bordered table-striped align-middle d-none d-md-table">
        <thead class="table-secondary">
          <tr>
            <th>ชื่อพนักงาน</th>
            <th>กะ</th>
            <th>เดือน</th>
            <th>เงินเดือน (บาท)</th>
            <th>โบนัส (บาท)</th>
            <th>รวมทั้งหมด (บาท)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in salaryList" :key="item.id">
            <td>{{ item.employee_name }}</td>
            <td>{{ item.shift == 1 ? 'กะเช้า' : 'กะดึก' }}</td>
            <td>{{ formatThaiMonth(item.month) }}</td>
            <td>{{ parseFloat(item.salary).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") }}</td>
            <td>{{ parseFloat(item.bonus).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") }}</td>
            <td>{{ parseFloat(item.total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") }}</td>
          </tr>
          <tr v-if="salaryList.length < 1">
            <td colspan="6" class="text-center text-muted py-3">- ไม่มีข้อมูล -</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
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
  <script>
    const {
      createApp
    } = Vue;

    createApp({
      data() {
        const now = new Date();
        const yyyy = now.getFullYear();
        const mm = String(now.getMonth() + 1).padStart(2, '0'); // 01..12
        const currentMonth = `${yyyy}-${mm}`;

        return {
          salaryList: [],
          dataEmployees: [],
          dataEndMonth: [],
          form: {
            id: "",
            shift: "1",
            month: currentMonth,
            salary: 0,
            bonus: 0
          }
        };
      },
      computed: {
        thaiMonthLabel() {
          return this.formatThaiMonth(this.form?.month);
        }
      },
      watch: {
        'form.id'(val) {
          if (!val) return;
          const emp = this.dataEmployees.find(e => String(e.id) === String(val));
          if (!emp) return;
          Object.assign(this.form, {
            salary: Number(emp.base_salary ?? emp.salary ?? 0),
            shift: String(emp.default_shift ?? this.form.shift ?? '1'),
            month: this.form.month || new Date().toISOString().slice(0, 7)
          });
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
          this.fetchEmployees();
          this.fetchEndMonth();
        }
      },
      methods: {
        selectedEmp() {
          const emp = this.dataEmployees.find(e => String(e.id) === String(this.form.employee_id));
          if (!emp) return;

          Object.assign(this.form, {
            salary: Number(emp.base_salary ?? emp.salary ?? 0),
            shift: String(emp.default_shift ?? this.form.shift ?? '1'),
            month: this.form.month || new Date().toISOString().slice(0, 7)
          });
          this.selectedEmployee = emp;
        },

        formatCurrency(val) {
          const num = Number(val ?? 0);
          return num.toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        saveSalary() {
          if (!this.form.employee_id || !this.form.month) {
            alert("กรุณากรอกข้อมูลให้ครบถ้วน");
            return;
          }

          const emp = this.selectedEmployee;
          if (!emp) {
            alert("ไม่พบข้อมูลพนักงาน");
            return;
          }

          const record = {
            id: Date.now(),
            name: emp.name,
            shift: this.form.shift,
            month: this.form.month,
            salary: Number(this.form.salary),
            bonus: Number(this.form.bonus)
          };

          this.salaryList.push(record);
          this.form.month = "";
          this.form.salary = 0;
          this.form.bonus = 0;
          alert("บันทึกสำเร็จ");
        },

        /** ✅ ฟังก์ชันใหม่ — บันทึกลงฐานข้อมูลผ่าน /api/ */

        saveSalaryToAPI() {
          if (!this.form.employee_id || !this.form.month) {
            Swal.fire({
              icon: 'warning',
              title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
              timer: 2000,
              showConfirmButton: false
            });
            return;
          }

          const emp = this.selectedEmployee;
          if (!emp) {
            Swal.fire({
              icon: 'error',
              title: 'ไม่พบข้อมูลพนักงาน',
              timer: 2000,
              showConfirmButton: false
            });
            return;
          }

          const payload = {
            post: "save_salary", // ตัวระบุ action สำหรับ PHP
            employee_id: this.form.employee_id,
            shift: this.form.shift,
            month: this.form.month,
            salary: this.form.salary,
            bonus: this.form.bonus,
            total: Number(this.form.salary) + Number(this.form.bonus)
          };

          axios.post("../../api/", payload)
            .then(res => {
              if (res.data.status) {
                Swal.fire({
                  icon: 'success',
                  title: 'บันทึกข้อมูลสำเร็จ',
                  timer: 1500,
                  showConfirmButton: false
                });
               
                
                this.fetchEndMonth();
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'บันทึกไม่สำเร็จ',
                  text: res.data.message || 'โปรดลองอีกครั้ง',
                });
              }
            })
            .catch(err => {
              console.error(err);
              Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
              });
            });
        },
        formatThaiMonth(input) {
          if (!input) return '-';

          // รับได้ทั้ง 'YYYY-MM' หรือ 'YYYY-MM-DD'
          const [y, m] = String(input).split('-');
          if (!y || !m) return input;

          // สร้างวันที่เป็นวันแรกของเดือน
          const d = new Date(Number(y), Number(m) - 1, 1);

          // ---------- ตัวเลือก A: แสดงเดือน+ปี พ.ศ. ----------
          const beYear = d.getFullYear() + 543;
          const thMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
          ];
          return `${thMonths[d.getMonth()]} ${beYear}`;

          // ---------- ตัวเลือก B (ถ้าอยากใช้ Intl): ----------
          // return new Intl.DateTimeFormat('th-TH', { month: 'long', year: 'numeric' }).format(d);
          // หมายเหตุ: Intl แบบ th-TH จะให้ปี พ.ศ. อยู่แล้ว
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
        fetchEndMonth() {
          axios.post('../../api/', {
              post: 'get_endmont'
            })
            .then(res => {
              if (res.data.status) {
                this.salaryList = res.data.data;
              } else {
                console.log('โหลดข้อมูลไม่สำเร็จ');
              }
            })
            .catch(err => {
              console.error(err);
              console.log('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            });
        },
        formatThaiMonth(input) {
    if (!input) return '-';
    // รับทั้ง 'YYYY-MM' หรือ 'YYYY-MM-DD'
    const [y, m] = String(input).split('-');
    if (!y || !m) return input;

    // สร้าง Date เป็นวันแรกของเดือนนั้น (ใช้เวลาท้องถิ่นผู้ใช้)
    const d = new Date(Number(y), Number(m) - 1, 1);

    // ใช้ Intl ให้ได้เดือนภาษาไทย + ปี พ.ศ.
    return new Intl.DateTimeFormat('th-TH', {
      month: 'long',
      year: 'numeric'
    }).format(d); // ตัวอย่าง: "ตุลาคม 2568"
  }
      }
    }).mount("#app");
  </script>
</body>

</html>