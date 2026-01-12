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
                            <button class="btn btn-primary mb-0" @click="getProducts" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                </svg>
                                เพิ่ม
                            </button>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2 px-md-4" style="min-height: 300px;">

                            <div class="container my-3">
                                <h4 class="mb-3"></h4>

                                <div v-for="product in dataPromotion" :key="product.product_id" class="card mb-3 shadow-sm">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div>
                                            <img :src="'../../uploads/' + product.image" alt="" style="height:40px; width:40px; object-fit:cover; border-radius:5px; margin-right:10px;">
                                            <strong>{{ product.product_name }}</strong>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-sm mt-3" @click="handleEdit(product)" data-bs-toggle="modal" data-bs-target="#exampleEditModal">แก้ไข</button>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div v-for="warehouse in product.warehouses" :key="warehouse.warehouse_id" class="mb-3">
                                            <h6 class="text-primary">
                                                <i class="bi bi-house-door"></i> {{ warehouse.warehouse_name }}
                                            </h6>

                                            <table class="table table-sm table-bordered text-center align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>จำนวนขั้นต่ำ</th>
                                                        <th>ราคา/ส่วนลด</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(step, idx) in warehouse.steps" :key="idx">
                                                        <td>{{ step.min_quantity }}</td>
                                                        <td>
                                                            <span v-if="step.price < 0" class="text-success">
                                                                ลด {{ Math.abs(step.price) }}
                                                            </span>
                                                            <span v-else>
                                                                {{ step.price }}
                                                            </span>
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
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">จัดการเรทส่ง</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-1">
                                <label for="exampleFormControlInput1" class="form-label mb-0">เลือกสินค้า *</label>

                                <div class="d-flex justify-content-start flex-wrap">
                                    <div
                                        class="m-1 text-center"
                                        v-for="(item, index) in products"
                                        :key="index"
                                        @click="toggleProduct(item.id)"
                                        style="cursor: pointer;">
                                        <img
                                            :src="'../../uploads/' + item.image"
                                            :alt="item.name"
                                            :class="formData.product_ids.includes(item.id) ? 'border border-success' : ''"
                                            class="mx-2"
                                            style="height: 40px; border-radius: 5px;" />
                                        <div style="font-size: 10px;">{{ item.name }}</div>
                                    </div>
                                </div>
                                <div v-if="formData.product_ids.length" class="mt-2">
                                    <label class="form-label">สินค้าที่เลือก:</label><br>
                                    <span
                                        v-for="id in formData.product_ids"
                                        :key="id"
                                        class="badge bg-success text-light me-1 mb-1">
                                        {{ getProductName(id) }}
                                        <button
                                            @click="removeProduct(id)"
                                            class="btn-close btn-close-white btn-sm ms-1"
                                            style="float: none;"
                                            aria-label="ลบ"></button>
                                    </span>
                                </div>

                            </div>

                            <label class="form-label">เลือกคลังสินค้า:</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button
                                    v-for="(item, i) in dataWarehouses"
                                    :key="item.id"
                                    class="btn btn-xs small mb-1"
                                    :class="formData.warehouses_id.includes(item.id) ? 'btn-success' : 'btn-outline-primary'"
                                    @click="toggleWarehouse(item.id)">
                                    {{ item.name }}
                                </button>
                            </div>

                            <!-- แสดงรายการคลังที่เลือก -->
                            <div v-if="formData.warehouses_id.length" class="mt-2">
                                <label class="form-label">คลังสินค้าที่เลือก:</label><br>
                                <span
                                    v-for="id in formData.warehouses_id"
                                    :key="id"
                                    class="badge bg-info text-dark me-1 mb-1">
                                    {{ getWarehouseName(id) }}
                                    <button @click="removeWarehouse(id)" class="btn-close btn-close-white btn-sm ms-1" style="float: none;" aria-label="ลบ"></button>
                                </span>
                            </div>


                            <div class="row">
                                <!--<div class="col-6 mb-1">
                                    <label for="exampleFormControlInput1" class="form-label mb-0">ราคา *</label>
                                    <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาสินค้า..">
                                </div>
                                <div class="col-6 mb-1">
                                    <label for="exampleFormControlInput1" class="form-label mb-0">จำนวน *</label>
                                    <input type="number" class="form-control" v-model="formData.quantity" placeholder="จำนวนรับเข้า">
                                </div>-->
                                <div class="mb-1">
                                    <label class="form-label">ตั้งเรทราคาแบบขั้นบันได:</label>

                                    <div v-for="(step, index) in formData.steps" :key="index" class="row g-1 align-items-center mb-1">
                                        <div class="col-5">
                                            <input
                                                type="number"
                                                class="form-control"
                                                v-model.number="step.quantity"
                                                placeholder="จำนวนขั้นต่ำ"
                                                min="1" />
                                        </div>
                                        <div class="col-5">
                                            <input
                                                type="number"
                                                class="form-control"
                                                v-model.number="step.price"
                                                placeholder="ราคาต่อหน่วย"
                                                min="0" />
                                        </div>
                                        <div class="col-2 text-end">
                                            <button class="btn btn-danger btn-sm" @click="removeStep(index)" v-if="formData.steps.length > 1">−</button>
                                        </div>
                                    </div>

                                    <button class="btn btn-outline-primary btn-sm mt-2" @click="addStep">+ เพิ่มเรท</button>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-primary" @click="submitForm">บันทึกข้อมูล</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit -->

            <div class="modal fade" id="exampleEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">แก้ไขเรทส่ง <span class="text-primary">{{dataEdit.product_name}}</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-2">
                                
                            </div>
                            <!-- แสดงรายการคลังที่เลือก -->
                            <div v-for="warehouse in dataEdit.warehouses" :key="warehouse.warehouse_id" class="mb-3">
                                <h6 class="text-primary">
                                    <i class="bi bi-house-door"></i> {{ warehouse.warehouse_name }}
                                </h6>

                                <table class="table table-sm table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>จำนวนขั้นต่ำ</th>
                                            <th>ราคา/ส่วนลด</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(step, idx) in warehouse.steps" :key="idx">
                                            <td>
                                            <input type="number" class="form-control text-center" v-model="step.min_quantity" placeholder="จำนวนขั้นต่ำ...">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control text-center" v-model="step.price" placeholder="ราคา...">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-primary" @click="editForm">บันทึกข้อมูล</button>
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
                    products: [],
                    dataPromotion: [],
                    message: '',
                    status: false,
                    success: false,
                    responseMsg: '',
                    image_url: '',
                    formData: {
                        name: '',
                        price: '',
                        quantity: '',
                        description: '',
                        warehouses_id: [],
                        product_ids: [], // ✅ เพิ่มสำหรับสินค้าที่เลือก
                        steps: [ // ✅ สำหรับเรทราคาแบบขั้น
                            {
                                quantity: '',
                                price: ''
                            }
                        ]
                    },
                    searchKeyword: '',
                    filteredProducts: [],
                    dataEdit: [],
                    image: null,
                    products: [],
                    dataWarehouses: [],
                    currentPage: 1,
                    perPage: 10,
                };
            },
            computed: {

                selectedCategoryName() {
                    const selected = this.dataCategory.find(cat => cat.id === this.formData.category_id);
                    return selected ? selected.cate_name : 'ยังไม่ได้เลือก';
                },

                totalPages() {
                    return Math.ceil(this.dataPromotion.length / this.perPage);
                },
                paginatedProducts() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return this.dataPromotion.slice(start, end);
                },

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
                    this.fetchPromo();
                }
            },
            methods: {
                handleEdit(item) {
                    this.dataEdit = item;
                },
                formatThaiDate(dateStr) {
                    return dayjs(dateStr).format("D MMM YY เวลา HH:mm");
                },
                editForm() {
                    fetch("../../api/index.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            post: "update_promo",
                            product_id: this.dataEdit.product_id,
                            warehouses: this.dataEdit.warehouses
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                    if (res.status) {
                        alert(res.message);
                        // โหลดข้อมูลใหม่
                        this.loadPromotions();
                        // ปิด modal
                        bootstrap.Modal.getInstance(document.getElementById('yourModalId')).hide();
                    } else {
                        alert(res.message);
                    }
                    });
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
                addStep() {
                    this.formData.steps.push({
                        quantity: '',
                        price: ''
                    });
                },
                removeStep(index) {
                    this.formData.steps.splice(index, 1);
                },
                async submitForm() {
                    if (!this.formData.product_ids.length || !this.formData.warehouses_id.length || !this.formData.steps.length) {
                        alert("กรุณาเลือกสินค้า, คลัง และระบุเรทราคา/จำนวนอย่างน้อย 1 ขั้น");
                        return;
                    }

                    try {
                        const res = await axios.post('../../api/', {
                            post: 'save_promo',
                            product_ids: this.formData.product_ids,
                            warehouses_id: this.formData.warehouses_id,
                            steps: this.formData.steps
                        });

                        if (res.data.status) {
                            alert(res.data.message);
                            this.fetchPromo();
                            // รีเซ็ตฟอร์มหรือปิด modal
                            this.resetForm();
                            const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                            if (modal) modal.hide();
                        } else {
                            alert("เกิดข้อผิดพลาด: " + res.data.message);
                        }

                    } catch (error) {
                        console.error("API error:", error);
                        alert("ไม่สามารถบันทึกข้อมูลได้");
                    }
                },

                resetForm() {
                    this.formData.product_ids = [];
                    this.formData.warehouses_id = [];
                    this.formData.steps = [];
                    this.formData.price = '';
                    this.formData.quantity = '';
                },
                toggleProduct(productId) {
                    const index = this.formData.product_ids.indexOf(productId);
                    if (index === -1) {
                        this.formData.product_ids.push(productId);
                    } else {
                        this.formData.product_ids.splice(index, 1);
                    }
                },
                removeProduct(productId) {
                    this.formData.product_ids = this.formData.product_ids.filter(id => id !== productId);
                },
                getProductName(id) {
                    const product = this.products.find(p => p.id === id);
                    return product ? product.product_name : 'ไม่พบสินค้า';
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
                removeWarehouse(id) {
                    const index = this.formData.warehouses_id.indexOf(id);
                    if (index !== -1) {
                        this.formData.warehouses_id.splice(index, 1);
                    }
                },
                async getProducts() {
                    try {
                        const response = await axios.post('../../api/', {
                            post: 'get_products',
                        });
                        if (response.data.status) {
                            this.products = response.data.products;
                            this.loadWarehouses();
                        }
                    } catch (error) {
                        console.error('Error fetching products:', error);
                    }
                },
                getWarehouseName(id) {
                    const warehouse = this.dataWarehouses.find(w => w.id === id);
                    return warehouse ? warehouse.name : 'ไม่พบ';
                },
                loadWarehouses() {
                    axios.post('../../api/', {
                            post: 'get_warehouses_fproduct',
                        })
                        .then(res => this.dataWarehouses = res.data.data)
                        .catch(err => alert('โหลดคลังสินค้าล้มเหลว'));
                },
                fetchPromo() {
                    axios.post('../../api/', {
                            post: 'get_promo'
                        })
                        .then(res => {
                            if (res.data.status) {
                                this.dataPromotion = res.data.promotions;
                            } else {
                                console.log('โหลดข้อมูลไม่สำเร็จ');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            console.log('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                        });
                },
            },
        }).mount("#app");
    </script>
</body>

</html>