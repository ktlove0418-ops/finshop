<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>Products Dashboard</title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link id="pagestyle" href="../../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />

  <script src="../../assets/js/vue/vue3.js"></script>
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
    <?php include('../layout/navbar.html'); ?>

    <div class="container-fluid py-4" id="app">
      <div class="row">
        <div class="col-12">

          <div class="card mb-4" v-if="dataRoles && dataRoles.some(r => r.id === 8)">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">

              <!-- เพิ่มสินค้าใหม่ -->
              <button class="btn btn-primary mb-0" @click="openCreateModal" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                เพิ่มสินค้าใหม่
              </button>

              <!-- เพิ่มสินค้าเข้าคลังใหญ่ -->
              <button class="btn btn-success mb-0" @click="openStockInModal" data-bs-toggle="modal" data-bs-target="#modalStockIn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                </svg>
                เพิ่มสินค้าเข้าคลัง (คลังใหญ่)
              </button>

              <!-- Search -->
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

            <!-- ===========================
                 MODAL: เพิ่มสินค้าใหม่
                 =========================== -->
            <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">เพิ่มสินค้าใหม่ (เข้าคลังใหญ่ทันที)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="mb-1">
                      <label class="form-label mb-0">ประเภทสินค้า *</label>
                      <select class="form-select" v-model="formData.category_id">
                        <option value="">เลือกหมวดหมู่สินค้า</option>
                        <option v-for="(item,i) in dataCategory" :key="i" :value="item.id">
                          {{ item.cate_name }}
                        </option>
                      </select>
                    </div>

                    <div class="alert alert-info py-2 mt-2" v-if="mainWarehouseId">
                      ระบบจะเพิ่มเข้าคลัง: <b>{{ mainWarehouseName }}</b> (บังคับ)
                    </div>
                    <div class="alert alert-warning py-2 mt-2" v-else>
                      ไม่พบ “คลังใหญ่” (กรุณาตั้งชื่อคลังให้มีคำว่า <b>คลังใหญ่</b>)
                    </div>

                    <label class="form-label mb-0">จำนวนสินค้าเริ่มต้น *</label>
                    <div class="input-group">
                      <button class="btn btn-outline-secondary" type="button" @click="formData.stock_qty > 0 ? formData.stock_qty-- : 0">-</button>
                      <input type="number" class="form-control text-center p-2" v-model.number="formData.stock_qty" min="0" placeholder="จำนวนคงเหลือในสต๊อก">
                      <button class="btn btn-outline-secondary" type="button" @click="formData.stock_qty++">+</button>
                    </div>

                    <div class="mb-1 mt-2">
                      <label class="form-label mb-0">ชื่อสินค้า *</label>
                      <input type="text" class="form-control" v-model="formData.name" placeholder="ชื่อสินค้า หรือยี่ห้อ...">
                    </div>

                    <div class="row">
                      <div class="col-6 mb-1">
                        <label class="form-label mb-0">ราคาทุน *</label>
                        <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาทุน..">
                      </div>
                      <div class="col-6 mb-1">
                        <label class="form-label mb-0">ราคาขาย *</label>
                        <input type="number" class="form-control" v-model="formData.quantity" placeholder="ราคาขาย..">
                      </div>
                    </div>

                    <div class="mb-1">
                      <label class="form-label mb-0">รูปภาพ *</label>
                      <input type="file" class="form-control" @change="handleFileUpload">
                    </div>

                    <div class="mb-1">
                      <label class="form-label mb-0">รายละเอียด</label>
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

            <!-- ===========================
                 MODAL: เพิ่มสินค้าเข้าคลังใหญ่
                 Flow: เลือกหมวด -> แสดงสินค้าในหมวด -> กรอกจำนวน -> บันทึก #
            =========================== -->
            <div class="modal fade" id="modalStockIn" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">เพิ่มสินค้าเข้าคลังใหญ่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <div class="alert alert-info py-2" v-if="mainWarehouseId">
                      คลังปลายทาง (บังคับ): <b>{{ mainWarehouseName }}</b>
                    </div>
                    <div class="alert alert-warning py-2" v-else>
                      ไม่พบ “คลังใหญ่” (กรุณาตั้งชื่อคลังให้มีคำว่า <b>คลังใหญ่</b>)
                    </div>

                    <div class="mb-2">
                      <label class="form-label mb-0">เลือกหมวดสินค้า *</label>
                      <select class="form-select" v-model="stockIn.category_id">
                        <option value="">เลือกหมวด</option>
                        <option v-for="(c,i) in dataCategory" :key="i" :value="c.id">{{ c.cate_name }}</option>
                      </select>
                    </div>

                    <div class="mb-2">
                      <label class="form-label mb-0">ค้นหาสินค้าในหมวด</label>
                      <input class="form-control" v-model="stockIn.keyword" placeholder="พิมพ์ชื่อสินค้าเพื่อค้นหา...">
                    </div>

                    <div class="mb-2">
                      <label class="form-label mb-0">เลือกสินค้า *</label>
                      <select class="form-select " v-model="stockIn.product_id">
                        <option value="">เลือกสินค้า</option>
                        <option v-for="p in filteredMasterProducts" :key="p.id" :value="p.id">
                          {{ p.product_name }}
                        </option>
                      </select>

                    </div>

                    <div class="mb-2">
                      <label class="form-label mb-0">จำนวนรับเข้า *</label>
                      <input type="number" class="form-control" v-model.number="stockIn.qty" min="1" placeholder="เช่น 10">
                    </div>

                    <div class="mb-2">
                      <label class="form-label mb-0">หมายเหตุ (ถ้ามี)</label>
                      <input class="form-control" v-model="stockIn.note" placeholder="เช่น รับเข้าจาก supplier">
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-success" @click="submitStockIn">บันทึกเข้าคลังใหญ่</button>
                  </div>

                </div>
              </div>
            </div>

            <!-- ===========================
                 TABLE
                 =========================== -->
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0" style="min-height: 280px;">
                <table class="table table-responsive align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รูป</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รายละเอียด</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อสินค้า</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">หมวดหมู่</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาทุน</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาขาย</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">คงเหลือ</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ผู้ดูแล</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in paginatedProducts" :key="index">
                      <td class="d-flex justify-content-start">
                        <img :src="'../../uploads/' + item.image" alt="image" class="mx-2" style="max-height: 60px;">
                      </td>
                      <td>
                        <div v-html="item.description" class="text-xs text-secondary mb-0"
                          style="text-align: left; white-space: pre-wrap; word-break: break-word;min-width: 140px;"></div>
                      </td>
                      <td>{{ item.product_name }}</td>
                      <td>{{ item.category_name }}</td>
                      <td>{{ formatPrice(item.price) }} บาท</td>
                      <td>{{ formatPrice(item.quantity) }} บาท</td>
                      <td><b>{{ item.unit }}</b></td>
                      <td class="text-xs"><span v-html="item.person"></span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

        <nav aria-label="Page navigation mt-0">
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">«</a>
            </li>
            <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
              <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">»</a>
            </li>
          </ul>
        </nav>

      </div>

      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>
                Soft by <img src="../../assets/img/lgg.png" width="20" alt="logo">ɢɪɢᴀᴊᴜ ꜱᴛᴜᴅɪᴏ.
              </div>
            </div>
          </div>
        </div>
      </footer>

    </div>
  </main>

  <!-- Core JS -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

  <script>
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidenav .nav-link');
    navLinks.forEach(link => {
      const linkPath = new URL(link.href).pathname;
      if (currentPath === linkPath) link.classList.add('active');
      else link.classList.remove('active');
    });
  </script>

  <!-- ===========================
       VUE
       =========================== -->
  <script>
    const {
      createApp
    } = Vue;

    createApp({
      data() {
        return {
          dataCategory: [],
          dataWarehouses: [],
          mainWarehouseId: null,
          masterProducts: [],
          dataRoles: [],
          employee: null,

          // ใช้รายการสินค้าจาก get_products_in_wh (คลังใหญ่)
          products: [],

          currentPage: 1,
          perPage: 10,

          searchKeyword: '',
          image: null,

          // เพิ่มสินค้าใหม่
          formData: {
            category_id: '',
            warehouses_id: [],
            name: '',
            price: '',
            quantity: '',
            stock_qty: 0,
            description: ''
          },

          // เพิ่มสินค้าเข้าคลังใหญ่
          stockIn: {
            category_id: '',
            keyword: '',
            pw_id: '',
            qty: 1,
            note: ''
          }
        };
      },

      computed: {
        filteredMasterProducts() {
          const catId = Number(this.stockIn.category_id || 0);
          const kw = (this.stockIn.keyword || '').trim().toLowerCase();

          let list = this.masterProducts || [];

          if (catId) list = list.filter(p => Number(p.category_id) === catId);
          if (kw) list = list.filter(p => String(p.product_name || '').toLowerCase().includes(kw));

          return list;
        },
        totalPages() {
          return Math.ceil(this.products.length / this.perPage) || 1;
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.perPage;
          return this.products.slice(start, start + this.perPage);
        },

        mainWarehouseName() {
          const w = (this.dataWarehouses || []).find(x => x.id === this.mainWarehouseId);
          return w ? w.name : 'คลังใหญ่';
        },

        // รายการสินค้าใน modal stock-in: กรองตามหมวด + keyword
        stockInProducts() {
          const catId = Number(this.stockIn.category_id || 0);
          const kw = (this.stockIn.keyword || '').trim().toLowerCase();

          let list = (this.products || []).filter(p => p.pw_id); // ต้องมี pw_id เท่านั้นถึงจะรับเข้าได้

          if (catId) {
            list = list.filter(p => Number(p.category_id) === catId);
          }
          if (kw) {
            list = list.filter(p => String(p.product_name || '').toLowerCase().includes(kw));
          }

          list.sort((a, b) => String(a.product_name || '').localeCompare(String(b.product_name || '')));
          return list;
        }
      },

      async mounted() {
        const profileRaw = localStorage.getItem("Fin-Profile");
        if (!profileRaw) {
          Swal.fire("Session หมดอายุ", "", "warning");
          window.location = "../";
          return;
        }

        const profile = JSON.parse(profileRaw);

        await this.getEmployeeRoles(profile.data.id);
        await this.getType();
        await this.loadWarehouses();
        await this.loadMasterProducts();
        // โหลดสินค้า “ในคลังใหญ่” ด้วย endpoint ที่คุณให้มา
        if (this.mainWarehouseId) {
          await this.getProductsInMainWarehouse();
        }
      },

      methods: {
        async getEmployeeRoles(empId) {
          try {
            const res = await axios.post("../../api/", {
              post: "get_employee_roles",
              employee_id: empId
            });
            if (res.data.status) {
              this.employee = res.data.employee;
              this.dataRoles = res.data.roles || [];
            }
          } catch (e) {}
        },

        async getType() {
          try {
            const res = await axios.post('../../api/', {
              post: 'get_type'
            });
            this.dataCategory = res.data.data || [];
          } catch (e) {
            this.dataCategory = [];
          }
        },

        async loadWarehouses() {
          try {
            const res = await axios.post('../../api/', {
              post: 'get_warehouses_fproduct'
            });
            this.dataWarehouses = res.data.data || [];

            const main = this.dataWarehouses.find(w => (w.name || '').includes('คลังใหญ่'));
            this.mainWarehouseId = main ? main.id : null;

            if (this.mainWarehouseId) {
              this.formData.warehouses_id = [this.mainWarehouseId];
            }
          } catch (e) {
            this.dataWarehouses = [];
            this.mainWarehouseId = null;
          }
        },

        async getProductsInMainWarehouse() {
          try {
            const res = await axios.post('../../api/', {
              post: 'get_products',
              warehouses_id: this.mainWarehouseId
            });

            if (res.data.status) {
              this.products = res.data.products || [];
              this.currentPage = 1;
            }
          } catch (e) {
            this.products = [];
          }
        },

        searchProduct() {
          // สำหรับหน้านี้เราโหลด “สินค้าในคลังใหญ่” มาแล้ว
          // ถ้าจะ search แบบ server ก็ทำได้ แต่ตอนนี้ทำเป็น client filter ง่าย ๆ
          // หากอยากให้ server search จริง ให้ทำ endpoint search_in_wh อีกตัว
        },

        openCreateModal() {
          this.formData = {
            category_id: '',
            warehouses_id: this.mainWarehouseId ? [this.mainWarehouseId] : [],
            name: '',
            price: '',
            quantity: '',
            stock_qty: 0,
            description: ''
          };
          this.image = null;
        },

        openStockInModal() {
          this.stockIn = {
            category_id: '',
            keyword: '',
            pw_id: '',
            qty: 1,
            note: ''
          };
        },

        handleFileUpload(e) {
          this.image = e.target.files[0];
        },

        formatPrice(v) {
          return Number(v || 0).toLocaleString();
        },

        changePage(page) {
          if (page >= 1 && page <= this.totalPages) this.currentPage = page;
        },

        async submitForm() {
          if (
            !this.mainWarehouseId ||
            !this.formData.category_id ||
            !this.formData.name ||
            !this.formData.price ||
            !this.formData.quantity ||
            this.formData.stock_qty === null || this.formData.stock_qty === '' ||
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
          payload.append("stock_qty", this.formData.stock_qty);
          payload.append("description", this.formData.description || '');
          payload.append("image", this.image);

          // บังคับคลังใหญ่
          payload.append("warehouses_id", this.mainWarehouseId);

          payload.append("position", profile.data.position);
          payload.append("username", profile.data.username);

          try {
            const res = await axios.post("../../api/", payload, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            });

            if (res.data.status) {
              Swal.fire("บันทึกสำเร็จ", "", "success");
              setTimeout(() => window.location.reload(), 800);
            } else {
              Swal.fire("เกิดข้อผิดพลาด", res.data.message || "", "error");
            }
          } catch (e) {
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        },
        async loadMasterProducts() {
          const res = await axios.post("../../api/", {
            post: "get_products_master"
          });
          if (res.data.status) {
            this.masterProducts = res.data.products || [];
          }
        },
        async submitStockIn() {
          if (!this.mainWarehouseId) {
            Swal.fire("ไม่พบคลังใหญ่", "กรุณาตั้งชื่อคลังให้มีคำว่า 'คลังใหญ่'", "warning");
            return;
          }

          // if (!this.stockIn.category_id || !this.stockIn.pw_id || !this.stockIn.qty || this.stockIn.qty < 1) {
          //   Swal.fire("กรอกข้อมูลให้ครบถ้วน", "", "warning");
          //   return;
          // }

          const profile = JSON.parse(localStorage.getItem("Fin-Profile"));

          try {
            const res = await axios.post("../../api/", {
              post: "upsert_product_in_wh", // หรือ stock_move_pd_in_wh ถ้าคุณยังใช้ตัวเดิม
              warehouses_id: this.mainWarehouseId, // บังคับคลังใหญ่
              product_id: this.stockIn.product_id, // เลือกจากสินค้าแม่ (products)
              delta_unit: this.stockIn.qty, // จำนวนที่รับเข้า
              note: "เพิ่มสินค้าเข้าคลังใหญ่", // ✅ เพิ่มตรงนี้
              user_id: profile.data.id,
              user_role: profile.data.position

            });

            if (res.data.status) {
              Swal.fire("รับเข้าสำเร็จ", "", "success");
              // โหลดข้อมูลใหม่ให้เห็น unit อัปเดต
              await this.getProductsInMainWarehouse();
              // reset form
              this.openStockInModal();
            } else {
              Swal.fire("ไม่สำเร็จ", res.data.message || "", "error");
            }
          } catch (e) {
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        }
      }
    }).mount("#app");
  </script>

</body>

</html>