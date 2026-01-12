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

  <!-- Libs -->
  <script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

  <style>
    .active a.page-link {
      color: #fff;
    }

    .card.card-profile-bottom {
      margin-top: 5rem;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    .table thead th {
      padding: 0;
    }

    /* แถบจำนวน + ปุ่มลบบนการ์ดสินค้า */
    .badge-qty {
      position: absolute;
      top: 8px;
      left: 8px;
      z-index: 5;
      background: #5469d4;
      color: #fff;
      border-radius: 12px;
      padding: 4px 8px;
      font-weight: 700;
      font-size: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
    }

    .btn-remove-on-card {
      position: absolute;
      top: 8px;
      right: 8px;
      z-index: 6;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #dc3545;
      color: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
    }

    .btn-remove-on-card:hover {
      opacity: .9;
    }

    .product-card {
      position: relative;
    }

    /* ช่องสรุปยอดให้แตะง่ายบนจอเล็ก */
    .summary-line {
      min-height: 44px;
    }

    /* ปรับ tile ของธนบัตร/เหรียญให้จัดวางสวยบนมือถือ */
    .denom-tile img {
      max-height: 48px;
      object-fit: contain;
    }

    /* ให้ modal body มีระยะขอบบนมือถือ */
    @media (max-width: 576px) {
      .modal-body {
        padding: 12px;
      }

      .checkout-wrap .btn-lg {
        padding-top: .8rem;
        padding-bottom: .8rem;
      }
    }

    /* ลดขนาดตัวอักษรหัวตารางบนจอเล็กให้พอดี */
    @media (max-width: 420px) {
      .table thead th {
        font-size: 12px;
      }

      .table td,
      .table th {
        padding: .4rem .5rem;
      }
    }

    .modal-cash .modal-content {
      border-radius: 18px;
    }

    .border-bottom-soft {
      border-bottom: 1px solid rgba(0, 0, 0, .06);
    }

    .paytab {
      opacity: .5;
    }

    .paytab.active {
      opacity: 1;
    }

    .paytab .icon {
      font-size: 1.2rem;
      line-height: 1;
    }

    .paytab .small {
      font-size: .75rem;
    }

    .display-amount {
      font-size: 2.25rem;
      font-weight: 700;
    }

    .keypad-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: .5rem;
    }

    .keypad-grid .key {
      border: 0;
      background: #f6f7f9;
      border-radius: 12px;
      padding: .85rem 0;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .keypad-grid .key-quick {
      color: #0d6efd;
      font-size: 1rem;
      font-weight: 700;
      background: #eef5ff;
    }

    .keypad-grid .key-fill {
      background: #e9f7ee;
      color: #198754;
      font-weight: 700;
    }

    @media (max-width: 576px) {
      .modal-cash .modal-dialog {
        width: 100%;
        margin: .5rem;
      }

      .display-amount {
        font-size: 2rem;
      }

      .keypad-grid .key {
        padding: .75rem 0;
      }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="position-absolute w-100 min-height-300 top-0" style="background-image:url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y:50%;">
    <span class="mask bg-primary opacity-6"></span>
  </div>

  <?php include('../layout/sitebar.html') ?>

  <main class="main-content position-relative border-radius-lg ">
    <?php include('../layout/navbar.html'); ?>

    <div id="app">
      <div class="card shadow-lg mx-4 card-profile-bottom"></div>

      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12 card d-flex align-items-center justify-content-between mb-3 p-5 text-center" v-if="!hasRole1"> {{'กรุณาขอสิทธิเข้าใช้งาน!'}}</div>
          <div class="col-12" v-else>
            <div class="card mb-4">

              <div class="card-header pb-0 mb-3">
                <div class="row g-2 g-md-3 align-items-end">
                  <div class="col-12 col-md-6">
                    <h4 class="px-2 px-md-4 d-flex align-items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                        <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5"></path>
                      </svg>
                      {{ whName || 'เลือกคลังสินค้า' }}
                    </h4>
                  </div>
                  <div class="col-12 col-md-6 ms-md-auto pe-md-3 d-flex align-items-center">
                    <div class="input-group me-3" style="height:40px;">
                      <span class="input-group-text text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shop-window" viewBox="0 0 16 16">
                          <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5" />
                        </svg>
                      </span>
                      <select class="form-select" v-model="warehouseId" @change="onChangeWarehouse">
                        <option value="">เลือกคลังสินค้า</option>
                        <option v-for="(item, i) in dataWarehouses" :key="i" :value="item.id">{{ item.name }}</option>
                      </select>
                    </div>
                    <div class="input-group">
                      <span class="input-group-text text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                      </span>
                      <input type="text" class="form-control" placeholder="ค้นหาชื่อสินค้า ..." v-model="searchKeyword" @input="searchProduct">
                    </div>
                  </div>
                </div>
              </div>

              <!-- ========== BODY: มีทั้ง mobile และ desktop ========== -->
              <div class="card-body px-0 pt-0 pb-2" v-if="whName">
                <div class="row">
                  <!-- Mobile: แสดง badge + ปุ่มลบบนการ์ด -->
                  <div class="col-12 col-md-8 text-center d-block d-sm-block d-md-none d-lg-none">
                    <div class="mt-3 px-4 d-flex align-items-center justify-content-between text-white">
                      <div class="d-flex align-items-center">
                        <div class="text-white px-3 bg-primary rounded py-2">{{ totalQty }}</div>
                        <button class="btn btn-sm btn-warning ms-2 mb-0 px-2" @click="clearCart" :disabled="cart.length===0">ล้าง</button>
                      </div>
                      <div class="bg-dark px-3">
                        <h2><strong class="text-right text-white">{{ formatMoney(totalPrice) }}</strong></h2>
                      </div>
                    </div>

                    <div class="row p-4">
                      <div class="col-4 col-md-3 mb-3" v-for="(item, index) in paginatedProducts" :key="index">
                        <div class="card h-100 shadow-sm product-card" @click="saleProducts(item)">
                          <!-- Badge จำนวน -->
                          <div class="badge-qty" v-if="returnQty(item) > 0" @click.stop="openQtyModal(item)">
                            {{ returnQty(item) }}
                          </div>
                          <!-- ปุ่มลบบนการ์ด -->
                          <button class="btn-remove-on-card" v-if="returnQty(item) > 0" @click.stop="removeItem2(item)">
                            <!-- https://feathericons.dev/?search=trash2&iconset=feather -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                              <polyline points="3 6 5 6 21 6" />
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                              <line x1="10" x2="10" y1="11" y2="17" />
                              <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>

                          </button>

                          <img :src="'../../uploads/' + item.image" class="card-img-top" alt="image" style="max-height: 180px; object-fit: contain;" />
                          <div class="card-body p-1 p-md-2 text-center">
                            <div class="card-title mb-0 mt-3">{{ item.product_name }} {{ formatMoney(item.quantity) }}฿</div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <button class="btn btn-xxl btn-success w-auto px-4"
                      @click="handleCheckout"
                      v-if="totalQty > 0"
                      style="border-radius:20px;position: fixed; bottom: 95px; right: 23%;z-index: 3;"
                      data-bs-toggle="modal" data-bs-target="#exampleModalCalculat">
                      <h1>ยืนยันการขาย</h1>
                    </button>
                  </div>

                  <!-- Desktop -->
                  <div class="col-12 col-md-8 text-center d-none d-sm-none d-md-block d-lg-block">
                    <div class="row p-4">
                      <div class="col-3 col-lg-3 mb-3" v-for="(item, index) in paginatedProducts" :key="index">
                        <div class="card h-100 shadow-sm product-card" @click="saleProducts(item)">
                          <div class="badge-qty" v-if="returnQty(item) > 0" @click.stop="openQtyModal(item)">
                            {{ returnQty(item) }}
                          </div>
                          <button class="btn-remove-on-card" v-if="returnQty(item) > 0" @click.stop="removeFromCartByProduct(item.id)"><!-- https://feathericons.dev/?search=trash2&iconset=feather -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                              <polyline points="3 6 5 6 21 6" />
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                              <line x1="10" x2="10" y1="11" y2="17" />
                              <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                          </button>

                          <img :src="'../../uploads/' + item.image" class="card-img-top" alt="image" style="max-height: 180px; object-fit: contain;" />
                          <div class="card-body p-1 p-md-2 text-center">
                            <div class="card-title mb-0 mt-3">{{ item.product_name }} {{ formatMoney(item.quantity) }}฿</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Cart (Desktop) -->
                  <div class="col-12 col-md-4 ">
                    <div class="card mt-4">
                      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">รายการขาย</h5>
                        <button class="btn btn-sm btn-warning" @click="clearCart" :disabled="cart.length===0">ล้างรายการ</button>
                      </div>
                      <div class="card-body p-2" v-if="cart.length > 0">
                        <table class="table table-bordered table-sm mb-2">
                          <tbody>
                            <tr v-for="(item, index) in cart" :key="index">
                              <td style="width:58px;">
                                <img :src="'../../uploads/' + item.image" alt="image" style="max-height:50px; object-fit:contain;" />
                              </td>
                              <td>
                                <div class="text-left">
                                  {{ item.product_name }} ฿{{ formatMoney(item.price) }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="d-flex align-items-center">
                                    x <input type="text"
                                      class="form-control form-control-sm mx-1"
                                      oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                      v-model.number="item.qty"
                                      min="1"
                                      style="width:60px" />
                                  </div>
                                  <div>
                                    <button class="rounded p-2" style="line-height:0.8;" @click="updateQty(item, item.qty + 1)">+</button>
                                    <button class="rounded p-2" style="line-height:0.8;" @click="updateQty(item, item.qty > 1 ? item.qty - 1 : 1)">-</button>
                                    <button class="btn btn-sm btn-danger p-2 ms-2 mb-0" @click.stop="removeItem(index)"><!-- https://feathericons.dev/?search=trash2&iconset=feather -->
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                      </svg>
                                    </button>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <h5 class="text-end px-3 text-nowrap">💰 รวมทั้งหมด: {{ formatMoney(totalPrice) }} บาท</h5>
                        <div class="text-end px-3 d-none d-sm-none d-md-block d-lg-block">
                          <button class="btn btn-success" @click="handleCheckout" data-bs-toggle="modal" data-bs-target="#exampleModalCalculat">ยืนยันการขาย</button>
                        </div>
                      </div>
                      <div class="card-body text-center text-muted" v-else>
                        ไม่มีรายการสินค้าในตะกร้า
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- เลือกสาขา -->
              <div class="card-body px-0 pt-0 pb-2" v-else>
                <div class="row p-4 align-items-center justify-content-center">
                  <div class="col-12 col-md-8 text-center">
                    เลือกสาขา?
                    <div class="row">
                      <div class="col-4 col-md-3" v-for="(item, i) in dataWarehouses" :key="item.id">
                        <button class="btn" @click="selectWarehouse(item.id)">
                          <h1>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop-window" viewBox="0 0 16 16">
                              <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                          </h1>
                          {{ item.name }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
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
          </div>
        </div>

        <!-- โมดัลต่างๆ (เดิม) : ลบ/รายละเอียด/เช็คเอาต์/แก้จำนวน -->
        <!-- ... (คงโครงเดิมของคุณ) ... -->

        <!-- Modal แก้จำนวนแบบเร็ว -->
        <div class="modal fade" id="exampleModalQty" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body text-center">
                <div class="row">
                  <div class="col-12 text-center px-2">
                    <h3>แก้ไขจำนวน</h3>
                    <div class="row d-flex align-items-center justify-content-center">
                      <div class="col-8 col-md-4 mb-2">
                        <input type="number" min="0" class="form-control" placeholder="จำนวน" v-model.number="thisItem" />
                      </div>
                      <div class="col-12">
                        <button class="btn btn-success mt-2" @click="handleQty">ยืนยัน</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ใบเสร็จ (เดิม) -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Checkout (เดิมทั้งหมดของคุณ) -->
        <!-- ====== BEGIN: Checkout Modal ====== -->
        <div class="modal fade" id="exampleModalCalculat" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">ขายสินค้า <strong>{{ iDetail }} {{ formatMoney(totalPr) }} บาท</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <!-- ชำระเงิน -->
                <div v-if="checkout" class="checkout-wrap">
                  <div class="row g-3">
                    <div class="col-12">
                      <!-- <label class="form-label">ประเภทการชำระเงิน</label>
                      <select v-model="paymentType" class="form-control">
                        <option value="">-- เลือก --</option>
                        <option value="เงินสด">เงินสด</option>
                        <option value="โอนเงิน">โอนเงิน</option>
                        <option value="พร้อมเพย์">พร้อมเพย์</option>
                      </select> -->
                      <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-0 pb-0">
                          <div class="w-100 text-center">
                            <div class="text-success fw-bold" style="font-size:1.25rem;">
                              ฿ {{ formatMoney(totalPr) }}
                            </div>
                            <div class="small text-muted">จำนวนที่ต้องชำระ</div>
                          </div>
                          <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- แท็บ (แสดงเฉยๆ ให้เหมือนภาพ; โฟกัสที่เงินสด) -->
                        <div class="px-3 pt-2 pb-1 border-bottom-soft d-flex justify-content-around text-center">
                          <div class="paytab" :class="{ active: paymentType === 'เงินสด' }" @click="paymentType = 'เงินสด'">
                            <div class="icon">💵</div>
                            <div class="small">เงินสด</div>
                          </div>

                          <div class="paytab" :class="{ active: paymentType === 'โอนเงิน' }" @click="paymentType = 'โอนเงิน'">
                            <div class="icon">🏦</div>
                            <div class="small">โอนเงิน</div>
                          </div>

                          <div class="paytab" :class="{ active: paymentType === 'พร้อมเพย์' }" @click="paymentType = 'พร้อมเพย์'">
                            <div class="icon">📱</div>
                            <div class="small">พร้อมเพย์</div>
                          </div>
                        </div>


                        <div class="modal-body pt-2" v-if="paymentType === 'เงินสด'">
                          <!-- ช่องแสดงจำนวนที่ลูกค้าจ่าย -->
                          <div class="display-amount text-end px-2"> {{ keypadDisplay }} </div>

                          <!-- แป้นพิมพ์ -->
                          <div class="keypad-grid mt-2">
                            <button class="key" @click="pressKey('7')">7</button>
                            <button class="key" @click="pressKey('8')">8</button>
                            <button class="key" @click="pressKey('9')">9</button>
                            <button class="key key-quick" @click="fillQuick(1000)">฿ 1000</button>

                            <button class="key" @click="pressKey('4')">4</button>
                            <button class="key" @click="pressKey('5')">5</button>
                            <button class="key" @click="pressKey('6')">6</button>
                            <button class="key key-quick" @click="fillQuick(500)">฿ 500</button>

                            <button class="key" @click="pressKey('1')">1</button>
                            <button class="key" @click="pressKey('2')">2</button>
                            <button class="key" @click="pressKey('3')">3</button>
                            <button class="key key-quick" @click="fillQuick(100)">฿ 100</button>

                            <button class="key" @click="pressKey('.')">.</button>
                            <button class="key" @click="pressKey('0')">0</button>
                            <button class="key" @click="backspace()">
                              <span class="bi bi-backspace"></span>⌫
                            </button>
                            <button class="key key-fill" @click="fillExact">เต็ม</button>
                          </div>

                          <!-- แถวล่าง: ล้าง / ยืนยัน -->
                          <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-light" @click="clearKeypad">ล้าง</button>
                            <button class="btn btn-primary" @click="confirmCash">ตกลง</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- เงินสด 
                    <div v-if="paymentType === 'เงินสด'" class="col-12">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <div class="small text-muted">จำนวนที่ต้องชำระ</div>
                          <div class="fs-5 text-success fw-bold">฿ {{ formatMoney(totalPr) }}</div>
                        </div>
                      </div>

                      <div class="mt-2 d-flex justify-content-between">
                        <div>ลูกค้าจ่ายมา</div>
                        <div class="fw-semibold">฿ {{ formatMoney(cashReceived || 0) }}</div>
                      </div>
                      <div class="mt-1 d-flex justify-content-between">
                        <div>เงินทอน</div>
                        <div class="badge bg-secondary fs-6">
                          ฿ {{ formatMoney(Math.max((cashReceived || 0) - totalPr, 0)) }}
                        </div>
                      </div>
                    </div>
                    -->

                    <!-- โอน/พร้อมเพย์ -->
                    <div class="col-12" v-if="paymentType === 'โอนเงิน' || paymentType === 'พร้อมเพย์'">
                      <div class="row g-2 align-items-start">
                        <div class="col-12 col-sm-6">
                          <label class="form-label">ระบุยอดเงิน</label>
                          <div class="input-group">
                            <input v-model="amount" type="number" class="form-control" placeholder="0.00" />
                            <span class="input-group-text">บาท</span>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 d-grid">
                          <label class="form-label d-none d-sm-block">&nbsp;</label>
                          <button class="btn btn-primary" @click="generateQR">สร้าง QR</button>
                        </div>
                        <div class="col-12 text-center" v-if="qrDataUrl">
                          <h6 class="my-2">สแกน QR</h6>
                          <img :src="qrDataUrl" alt="qr" class="img-fluid" style="max-width:320px;" />
                        </div>
                      </div>
                    </div>

                    <!-- สรุปยอด -->
                    <div class="col-12">
                      <div class="summary-line bg-dark text-white d-flex justify-content-between align-items-center rounded-3 px-3 py-2">
                        <strong>ยอดรวมสินค้า</strong>
                        <strong class="fs-5">{{ formatMoney(totalPr) }} บาท</strong>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="summary-line d-flex justify-content-between align-items-center rounded-3 px-3 py-2 border">
                        <strong>เงินทอน</strong>
                        <strong class="badge bg-secondary fs-6">
                          {{ (totalCashFromBreakdown ? (totalCashFromBreakdown - totalPr).toFixed(2) : '0.00') }} บาท
                        </strong>
                      </div>
                    </div>

                    <div class="col-12 d-grid">
                      <button class="btn btn-success btn-lg" @click="handleCheckout" :disabled="paymentType === ''">
                        ยืนยันการชำระเงิน
                      </button>
                    </div>
                  </div>
                </div>

                <!-- ใบเสร็จ -->
                <div v-if="checkoutCf" class="receipt-wrap">
                  <h5 class="text-center mb-3">ใบเสร็จชำระเงิน</h5>
                  <div id="receipt" class="px-2 px-sm-4">
                    <p class="mb-2">🧾 วันที่: {{ new Date().toLocaleString() }}</p>

                    <!-- ตารางเลื่อนในจอเล็ก -->
                    <div class="table-responsive">
                      <table class="table table-sm align-middle">
                        <thead class="small text-nowrap">
                          <tr>
                            <th class="text-start">สินค้า</th>
                            <th class="text-end">จำนวน</th>
                            <th class="text-end">ราคา</th>
                            <th class="text-end">ส่วนลด</th>
                            <th class="text-end">เหลือ</th>
                            <th class="text-end">รวม</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in cart" :key="item.id">
                            <td class="text-start">{{ item.product_name }}</td>
                            <td class="text-end">{{ item.qty }}</td>
                            <td class="text-end">{{ formatMoney(item.price) }}</td>
                            <td class="text-end" style="color:#888">{{ formatMoney(item.discount_per_unit) }}</td>
                            <td class="text-end">{{ formatMoney(item.price_per_unit) }}</td>
                            <td class="text-end">{{ formatMoney(item.total_price_item) }}</td>
                          </tr>
                        </tbody>
                        <tbody class="fw-semibold">
                          <tr>
                            <td colspan="4"></td>
                            <td class="text-start">รวมทั้งหมด:</td>
                            <td class="text-end">{{ formatMoney(totalPr) }}</td>
                          </tr>
                          <tr>
                            <td colspan="4"></td>
                            <td class="text-start">เงินที่รับมา:</td>
                            <td class="text-end">{{ formatMoney(totalCashFromBreakdown || 0) }}</td>
                          </tr>
                          <tr>
                            <td colspan="4"></td>
                            <td class="text-start">เงินทอน:</td>
                            <td class="text-end">
                              {{ formatMoney((totalCashFromBreakdown - totalPr) || 0) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <!-- ปุ่ม: เต็มความกว้างบนมือถือ -->
                  <div class="d-grid gap-2 d-sm-flex justify-content-sm-between mt-3">
                    <button class="btn btn-secondary px-3" @click="checkPromotion">🔢 คิดเงิน</button>
                    <div class="d-grid gap-2 d-sm-flex">
                      <button class="btn btn-success" @click="confirmSale">✅ บันทึกการขาย</button>
                      <button class="btn btn-outline-secondary" @click="printReceipt">🖨️ ปริ้นใบเสร็จ</button>
                    </div>
                  </div>
                </div>

              </div>

              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button></div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="cashKeypadModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-sm modal-cash">
            <div class="modal-content border-0 shadow-lg">
              <div class="modal-header border-0 pb-0">
                <div class="w-100 text-center">
                  <div class="text-success fw-bold" style="font-size:1.25rem;">
                    ฿ {{ formatMoney(totalPr) }}
                  </div>
                  <div class="small text-muted">จำนวนที่ต้องชำระ</div>
                </div>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <!-- แท็บ (แสดงเฉยๆ ให้เหมือนภาพ; โฟกัสที่เงินสด) -->
              <div class="px-3 pt-2 pb-1 border-bottom-soft d-flex justify-content-around text-center">
                <div class="paytab active">
                  <div class="icon">💵</div>
                  <div class="small">เงินสด</div>
                </div>
                <div class="paytab">
                  <div class="icon">🏦</div>
                  <div class="small">ธนาคาร</div>
                </div>
                <div class="paytab">
                  <div class="icon">📱</div>
                  <div class="small">ออนไลน์</div>
                </div>
                <div class="paytab">
                  <div class="icon">💳</div>
                  <div class="small">บัตร</div>
                </div>
                <div class="paytab">
                  <div class="icon">🕒</div>
                  <div class="small">เชื่อ</div>
                </div>
              </div>

              <div class="modal-body pt-2">
                <!-- ช่องแสดงจำนวนที่ลูกค้าจ่าย -->
                <div class="display-amount text-end px-2"> {{ keypadDisplay }} </div>

                <!-- แป้นพิมพ์ -->
                <div class="keypad-grid mt-2">
                  <button class="key" @click="pressKey('7')">7</button>
                  <button class="key" @click="pressKey('8')">8</button>
                  <button class="key" @click="pressKey('9')">9</button>
                  <button class="key key-quick" @click="fillQuick(1000)">฿ 1000</button>

                  <button class="key" @click="pressKey('4')">4</button>
                  <button class="key" @click="pressKey('5')">5</button>
                  <button class="key" @click="pressKey('6')">6</button>
                  <button class="key key-quick" @click="fillQuick(500)">฿ 500</button>

                  <button class="key" @click="pressKey('1')">1</button>
                  <button class="key" @click="pressKey('2')">2</button>
                  <button class="key" @click="pressKey('3')">3</button>
                  <button class="key key-quick" @click="fillQuick(100)">฿ 100</button>

                  <button class="key" @click="pressKey('.')">.</button>
                  <button class="key" @click="pressKey('0')">0</button>
                  <button class="key" @click="backspace()">
                    <span class="bi bi-backspace"></span>⌫
                  </button>
                  <button class="key key-fill" @click="fillExact">เต็ม</button>
                </div>

                <!-- แถวล่าง: ล้าง / ยืนยัน -->
                <div class="d-flex justify-content-between mt-3">
                  <button class="btn btn-light" @click="clearKeypad">ล้าง</button>
                  <button class="btn btn-primary" @click="confirmCash">ตกลง</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ====== END: Checkout Modal ====== -->

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
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), {
        damping: '0.5'
      });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

  <!-- Vue 3 (ESM) -->
  <script type="module">
    import {
      createApp
    } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';

    createApp({
      data() {
        return {
          hidId: '',
          warehouseId: '',
          whName: '',
          dataWarehouses: [],
          products: [],
          searchKeyword: '',
          currentPage: 1,
          perPage: 12,

          cart: [],
          cashReceived: 0, // จำนวนเงินสดที่รับจริง (คำนวณเงินทอนจากตัวนี้)
          keypadValue: '',
          // checkout
          checkout: false,
          checkoutCf: false,
          totalPr: 0,
          paymentType: 'เงินสด',
          cashBreakdown: {
            1000: 0,
            500: 0,
            100: 0,
            50: 0,
            20: 0,
            10: 0,
            5: 0,
            1: 0
          },
          amount: '0',
          qrDataUrl: '',
          dataRoles: '',
          // qty quick edit
          editingProduct: null,
          thisItem: 0,

          // misc
          iDetail: '',
        };
      },
      computed: {
        totalPages() {
          return Math.ceil(this.products.length / this.perPage) || 1;
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.perPage;
          return this.products.slice(start, start + this.perPage);
        },
        totalQty() {
          return (this.cart || []).reduce((s, r) => s + (Number(r.qty) || 0), 0);
        },
        totalPrice() {
          return (this.cart || []).reduce((s, r) => s + (Number(r.price) * Number(r.qty)), 0);
        },
        hasRole1() {
          return this.dataRoles && this.dataRoles.some(r => r.id === 1);
        },
        totalCashFromBreakdown() {
          // ถ้าใช้แป้นพิมพ์จะมี cashReceived > 0
          if (this.cashReceived && !isNaN(this.cashReceived)) return Number(this.cashReceived);
          // ถ้ายังอยากรองรับโหมด breakdown เดิม
          if (this.cashBreakdown) {
            return Object.entries(this.cashBreakdown).reduce((sum, [denom, qty]) => {
              const d = Number(denom);
              const q = Number(qty || 0);
              return sum + (d * q);
            }, 0);
          }
          return 0;
        },
        keypadDisplay() {
          return (this.keypadValue === '' ? '0' : Number(this.keypadValue).toLocaleString(undefined, {
            maximumFractionDigits: 2
          }));
        }
      },
      watch: {
        cart: {
          deep: true,
          handler(v) {
            v.forEach(it => {
              if (it.qty < 1) it.qty = 0;
            });
          }
        }
      },
      async mounted() {
  const profile = JSON.parse(localStorage.getItem("Fin-Profile"));

  if (!profile || profile === "undefined") {
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: "warning",
      title: "session die !",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
    window.location = "../";
    return;
  }

  // 1) โหลด role (ถ้าต้องใช้สิทธิ์แสดงปุ่ม)
  await this.getEmployeeRoles(profile.data.id);

  // 2) โหลดคลังให้เสร็จก่อน
  await this.loadWarehouses();

  // 3) เลือกคลังให้ได้ "แน่นอน" (มี lastWh ก็ใช้, ไม่มีให้ fallback)
  const lastWh = localStorage.getItem("LAST_WAREHOUSE_ID");

  // แปลงเป็นตัวเลขถ้า id เป็นเลข (แนะนำ)
  const lastWhId = lastWh ? Number(lastWh) : null;

  // เช็คว่าคลังนั้นมีจริงใน list ไหม
  const exists = lastWhId && this.dataWarehouses?.some(w => Number(w.id) === lastWhId);

  if (exists) {
    this.warehouseId = lastWhId;
  } else {
    // fallback: เลือกคลังใหญ่ก่อน ถ้ามี ไม่งั้นเอาคลังแรก
    const main = this.dataWarehouses?.find(w => (w.name || "").includes("คลังใหญ่"));
    this.warehouseId = main ? Number(main.id) : (this.dataWarehouses?.[0]?.id ? Number(this.dataWarehouses[0].id) : null);
  }

  // 4) ถ้ามีคลังจริง ค่อยโหลดสินค้า + ตะกร้า
  if (this.warehouseId) {
    await this.onChangeWarehouse();
  }

  // 5) QR
  if (this.paymentType === "โอนเงิน" || this.paymentType === "พร้อมเพย์") {
    this.generateQR();
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
        openCashModal() {
          this.keypadValue = this.cashReceived ? String(this.cashReceived) : '';
          const modal = new bootstrap.Modal(document.getElementById('cashKeypadModal'));
          modal.show();
        },

        // กดปุ่มตัวเลข/จุด
        pressKey(key) {
          // กันจุดซ้ำ และทศนิยมเกิน 2 ตำแหน่ง
          if (key === '.') {
            if (this.keypadValue.includes('.')) return;
            if (this.keypadValue === '') this.keypadValue = '0';
            this.keypadValue += '.';
            return;
          }
          // จำกัดทศนิยม 2 ตำแหน่ง
          const [intPart, decPart] = this.keypadValue.split('.');
          if (decPart && decPart.length >= 2) return;

          // กัน 0 นำหน้าโดยไม่จำเป็น
          if (this.keypadValue === '0' && key !== '.') {
            this.keypadValue = key;
          } else {
            this.keypadValue += key;
          }
        },

        backspace() {
          if (!this.keypadValue) return;
          this.keypadValue = this.keypadValue.slice(0, -1);
        },

        clearKeypad() {
          this.keypadValue = '';
        },

        fillQuick(v) {
          // เติมค่าปัจจุบัน + v (เหมาะกรณีลูกค้าส่งแบงก์หลายใบ)
          const curr = Number(this.keypadValue || 0);
          const next = curr + Number(v);
          this.keypadValue = String(next.toFixed(2)).replace(/\.00$/, '');
        },

        fillExact() {
          // รับเท่ากับยอดที่ต้องชำระ (เช่น ลูกค้าบอกให้รูด/โอน “เต็ม”)
          this.keypadValue = String(Number(this.totalPr).toFixed(2)).replace(/\.00$/, '');
        },

        confirmCash() {
          const val = Number(this.keypadValue || 0);
          this.cashReceived = isNaN(val) ? 0 : val;

          // ปิดโมดัล
          const modalEl = document.getElementById('cashKeypadModal');
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal && modal.hide();
        },
        // ---------- UI helpers ----------
        formatMoney(v) {
          return Number(v || 0).toLocaleString('th-TH', {
            minimumFractionDigits: 2
          });
        },
        changePage(p) {
          if (p >= 1 && p <= this.totalPages) this.currentPage = p;
        },

        // ---------- Warehouses ----------
        async loadWarehouses() {
  try {
    const res = await axios.post('../../api/', {
      post: 'get_warehouses_fproduct'
    });

    this.dataWarehouses = res.data.data || [];

    // 🔴 ตั้งค่า warehouseId default ถ้ายังไม่มี
    if (!this.warehouseId && this.dataWarehouses.length > 0) {
      // เลือก "คลังใหญ่" ก่อน ถ้ามี
      const main = this.dataWarehouses.find(w =>
        (w.name || '').includes('คลังใหญ่')
      );

      this.warehouseId = main ? Number(main.id) : Number(this.dataWarehouses[0].id);
    }

    return true; // ✅ บอกว่าทำสำเร็จ
  } catch (e) {
    console.error(e);
    Swal.fire('โหลดคลังสินค้าล้มเหลว', '', 'error');
    return false;
  }
},

        async onChangeWarehouse() {
          localStorage.setItem('LAST_WAREHOUSE_ID', this.warehouseId || '');
          const found = this.dataWarehouses.find(w => String(w.id) === String(this.warehouseId));
          this.whName = found ? found.name : '';
          if (!this.warehouseId) {
            this.products = [];
            this.cart = [];
            return;
          }
          await this.getProducts()
          await this.getCart()
          // await Promise.all([this.getProducts(), this.getCart()]);
        },
        selectWarehouse(id) {
          this.warehouseId = id;
          this.onChangeWarehouse();
        },

        // ---------- Products ----------
        async getProducts() {
          try {
            const res = await axios.post('../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: this.warehouseId
            });
            if (res.data.status) this.products = res.data.products || [];
          } catch (e) {
            console.error(e);
          }
        },
        searchProduct() {
          if (!this.searchKeyword || this.searchKeyword.trim().length < 2) {
            this.getProducts();
            return;
          }
          axios.post('../../api/', {
              post: 'search_products',
              keyword: this.searchKeyword,
              warehouses_id: this.warehouseId
            })
            .then(res => {
              this.products = res.data.products || [];
            })
            .catch(() => {});
        },

        // ---------- Cart ----------
        async getCart() {
          try {
            const res = await axios.post('../../api/', {
              post: 'get_cart',
              warehouse_id: this.warehouseId
            });
            if (res.data.status) this.cart = res.data.cart || [];
          } catch (e) {
            console.error('โหลดตะกร้าพลาด', e);
          }
        },
        async saleProducts(item) {
          if (!this.warehouseId) {
            console.warn("ยังไม่ได้เลือกคลัง");
            return;
          }

          try {
            const res = await axios.post('../../api/', {
              post: 'add_to_cart',
              product_id: item.id,
              warehouse_id: this.warehouseId,
              qty: 1,
              price: item.price
            });

            if (res.data.status) {
              this.cart = res.data.cart;
            }
          } catch (e) {
            console.error(e);
          }
        },
        returnQty(item) {
          const f = (this.cart || []).find(c => String(c.product_id) === String(item.id));
          return f ? Number(f.qty || 0) : 0;
        },
        openQtyModal(item) {
          this.editingProduct = item;
          this.thisItem = this.returnQty(item) || 1;
          const el = document.getElementById('exampleModalQty');
          const modal = new bootstrap.Modal(el);
          modal.show();
        },
        async handleQty() {
          if (!this.editingProduct) {
            alert('ไม่พบสินค้าที่ต้องการแก้จำนวน');
            return;
          }
          const newQty = Number(this.thisItem);
          if (Number.isNaN(newQty) || newQty < 0) {
            alert('กรุณาระบุจำนวนให้ถูกต้อง');
            return;
          }

          // 0 = ลบออก
          if (newQty === 0) {
            await this.removeFromCartByProduct(this.editingProduct.id);
            const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalQty'));
            modal && modal.hide();
            this.editingProduct = null;
            this.thisItem = 0;
            return;
          }

          try {
            const res = await axios.post('../../api/', {
              post: 'update_cart',
              warehouse_id: this.warehouseId,
              product_id: this.editingProduct.id,
              qty: newQty
            });
            if (res.data?.status) {
              this.cart = res.data.cart;
              const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalQty'));
              modal && modal.hide();
              this.editingProduct = null;
              this.thisItem = 0;
            } else {
              alert(res.data?.message || 'อัปเดตจำนวนไม่สำเร็จ');
            }
          } catch (e) {
            console.error(e);
            alert('เกิดข้อผิดพลาดในการอัปเดตจำนวน');
          }
        },
        async removeFromCartByProduct(productId) {
  try {
    const res = await axios.post('../../api/', {
      post: 'remove_item',
      product_id: productId,
      warehouse_id: this.warehouseId   // ส่งไปด้วยเพื่อความชัดเจน
    });

    if (res.data?.status) {
      // ถ้าแบ็กเอนด์ส่งตะกร้ากลับมา ใช้เลย
      if (Array.isArray(res.data.cart)) {
        this.cart = res.data.cart;
      } else {
        // ไม่ส่งมา ก็ลบทิ้งฝั่งหน้า
        const idx = this.cart.findIndex(c => String(c.product_id) === String(productId));
        if (idx > -1) this.cart.splice(idx, 1);
      }
    } else {
      Swal.fire('ลบไม่สำเร็จ', res.data?.message || '', 'error');
    }
  } catch (e) {
    console.error(e);
    Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถลบสินค้าได้', 'error');
  }
},
        async removeItem(index) {
          const item = this.cart[index];
          if (!item) return;
          if (!confirm(`คุณต้องการลบสินค้า "${item.product_name}" หรือไม่?`)) return;
          await this.removeFromCartByProduct(item.product_id);
        },
        async removeItem2(item) {
          if (!item) return;
          if (!confirm(`คุณต้องการลบสินค้า "${item.product_name}" หรือไม่?`)) return;
          await this.removeFromCartByProduct(item.id);
        },
        async updateQty(item, newQty) {
          if (newQty < 1) newQty = 1;
          item.qty = newQty;
          try {
            const res = await axios.post('../../api/', {
              post: 'update_cart',
              warehouse_id: this.warehouseId,
              product_id: item.product_id,
              qty: item.qty
            });
            if (res.data?.status) this.cart = res.data.cart;
          } catch (e) {
            console.error(e);
          }
        },
        async clearCart() {
          if (!confirm("คุณต้องการล้างรายการทั้งหมดหรือไม่?")) return;
          try {
            const res = await axios.post('../../api/', {
              post: 'clear_cart',
              warehouse_id: this.warehouseId
            });
            if (res.data.status) this.cart = [];
          } catch (e) {
            console.error(e);
          }
        },

        // ---------- Checkout ----------
        checkPromotion() {
          this.checkout = true;
          this.checkoutCf = false;
          axios.post('../../api/', {
              post: 'check_promotion',
              cart: this.cart,
              warehouse_id: this.warehouseId
            })
            .then(res => {
              if (res.data.status) {
                this.cart = res.data.cart;
                this.totalPr = res.data.total;
              }
            }).catch(() => {});
        },
        handleCheckout() {
          this.checkPromotion();
          this.checkoutCf = true;
          this.checkout = false;
        },
        async confirmSale() {
          let receivedMoney = this.totalCashFromBreakdown || 0;
          const res = await axios.post('../../api/', {
            post: 'generate_receipt_html',
            cart: this.cart,
            warehouse_name: this.whName,
            cash: this.paymentType,
            warehouse_id: this.warehouseId,
            total: this.totalPr,
            received: receivedMoney
          });
          if (res.data.status) {
            await this.getCart();
            alert("ขายสินค้าเรียบร้อย");
            const modal = bootstrap.Modal.getInstance(document.getElementById('exampleModalCalculat'));
            modal && modal.hide();
          }
        },

        // ---------- PromptPay / QR ----------
        buildPromptpayPayload(mobile, amount) {
          const m = mobile.replace(/[^0-9]/g, '');
          const id = '0066' + m.slice(1);
          const amt = parseFloat(amount || 0).toFixed(2);
          const base = [
            '000201', '010212',
            '29370016A0000006770101110113' + id,
            '5802TH', '5303764',
            `54${String(amt.length).padStart(2,'0')}${amt}`,
            '6304'
          ].join('');
          return base + this.calculateCRC(base);
        },
        calculateCRC(payload) {
          let crc = 0xFFFF;
          for (let i = 0; i < payload.length; i++) {
            crc ^= payload.charCodeAt(i) << 8;
            for (let j = 0; j < 8; j++) crc = (crc & 0x8000) ? (crc << 1) ^ 0x1021 : (crc << 1);
          }
          crc &= 0xFFFF;
          return crc.toString(16).toUpperCase().padStart(4, '0');
        },
        generateQR() {
          const mobile = '0653035491';
          const payload = this.buildPromptpayPayload(mobile, parseFloat(this.totalPr || 0).toFixed(2));
          QRCode.toDataURL(payload, {
            errorCorrectionLevel: 'H'
          }, (err, url) => {
            if (!err) this.qrDataUrl = url;
          });
        },

        // ---------- Print ----------
        printReceipt() {
          const pr = document.getElementById('receipt').innerHTML;
          const w = window.open('', '', 'width=720,height=900');
          w.document.write(`<html><head><title>Receipt</title></head><body>${pr}</body></html>`);
          w.document.close();
          w.focus();
          w.print();
          w.close();
        },
      }
    }).mount('#app');
  </script>

  <script>
    // ไฮไลต์เมนู active
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidenav .nav-link').forEach(link => {
      const linkPath = new URL(link.href).pathname;
      if (currentPath === linkPath) link.classList.add('active');
      else link.classList.remove('active');
    });
  </script>
</body>

</html>