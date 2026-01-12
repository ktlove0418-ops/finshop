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
    href="./assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="../../../assets/img/favicon.png" />
  <title>finshop Dashboard</title>
  <!--     Fonts and icons     -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
    rel="stylesheet" />
  <!-- CSS Files -->
  <link
    id="pagestyle"
    href="../../../assets/css/argon-dashboard.css?v=2.1.0"
    rel="stylesheet" />
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js "></script> -->
  <script src="../../../assets/js/vue/vue3.js"></script>
  <!-- axios -->
  <script src="../../../assets/js/axios/axios.0.9.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .page-item.active a.page-link {
      color: #fff;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../../layout/sub_sitebar.html') ?>

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
            Dashboard /ภาพรวม สาขา <b id="whname"></b>
          </h6>
        </nav>
        <div
          class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4"
          id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group d-none">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input
                type="text"
                class="form-control"
                placeholder="Type here..." />
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
                          src="../../../assets/img/team-2.jpg"
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
                          src="../../../assets/img/small-logos/logo-spotify.svg"
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
                      ยอดขายวันนี้
                    </p>
                    <h5 class="font-weight-bolder">฿{{ formatPrice(summaryData.total_sale) }}</h5>
                    <p class="mb-0 text-nowrap">
                      <!-- <span class="text-success text-sm font-weight-bolder">+55%</span> -->
                      ยอดขายเฉพราะสาขานี้
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
                      ราคาสินค้าคงเหลือ
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
        <div class="card p-3">
          <div class="row mb-3">
            <div class="col-md-3">
              <select v-model="rangeType" class="form-select" @change="fetchSummary">
                <option value="today">วันนี้</option>
                <option value="week">สัปดาห์นี้</option>
                <option value="month">เดือนนี้</option>
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
              <button class="btn btn-primary" @click="fetchSummary">ดูผล</button>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12 col-lg-12 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
              <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-between">
                <div>
                  <h6 class="text-capitalize">Sales overview/ภาพรวมการขาย</h6>
                  <p class="text-sm mb-0">
                    <i class="fa fa-arrow-up text-success"></i>
                    <span class="font-weight-bold">{{persent}}% more</span> in
                    <script>
                      document.write(new Date().getFullYear())
                    </script>
                  </p>
                </div>
                <div class="mb-0">
                  <!-- <label class="form-label">ดูข้อมูลยอดขาย</label> -->
                  <select v-model="viewType" class="form-select mb-2" @change="loadChartData">
                    <option value="week">สัปดาห์นี้ (จันทร์ - อาทิตย์)</option>
                    <option value="month">รายเดือน (ม.ค. - ธ.ค.)</option>
                  </select>
                </div>
              </div>
              <div class="card-body p-3" style="overflow: scroll;">
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
          <div class="col-12 mb-4">
            <div class="card p-3">

              <div class="d-flex justify-content-between">
                <h6 class="mb-2">ประเภทสินค้าในสาขา</h6>
              </div>

              <div>
                <!-- แถบเมนูหมวดหมู่ -->
                <div class="btn-group mb-3">
                  <button class="btn btn-outline-primary"
                    :class="{ active: selectedCategory === null }"
                    @click="selectedCategory = null">
                    ทั้งหมด
                  </button>
                  <button v-for="cat in categories" :key="cat.id"
                    class="btn btn-outline-primary ms-2"
                    :class="{ active: selectedCategory === cat.id }"
                    @click="selectedCategory = cat.id">
                    {{ cat.cate_name }}
                  </button>
                </div>

              </div>
              <div class="d-flex justify-content-between">
                <h6 class="mb-2">สินค้าในสาขา <a href="../">{{whName}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14" class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                      <circle cx="12" cy="10" r="3" />
                    </svg></a></h6>
                <div class="input-group mb-3 w-20" style="min-width: 225px;">
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
              <div class="table-responsive">
                
                <!-- ===== TABLE (md ขึ้นไป) ===== -->
                <div class="table-responsive d-none d-md-block">
                  <table class="table align-items-center mb-3">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รูป/รายละเอียด</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อสินค้า</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">หมวดหมู่</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาสินค้า</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">จำนวนสินค้า</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">สูงสุด</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">จัดการ</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ผู้ดูแล</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in paginatedProducts" :key="index">
                        <td class="d-flex justify-content-start align-items-start">
                          <img :src="'../../../uploads/' + item.image" alt="image" class="mx-2" style="max-height: 60px;">
                          <div v-html="item.description"
                            class="text-xs text-secondary mb-0 d-none d-sm-block"
                            style="text-align:left;white-space:pre-wrap;word-break:break-word;min-width:140px;"></div>
                        </td>

                        <td>{{ item.product_name }}</td>
                        <td>{{ item.category_name }}</td>

                        <!-- ราคา -->
                        <td v-if="edtu && edId == item.id && dataRoles && dataRoles.some(r => r.id === 21)">
                          <input type="text" v-model="item.price" style="width:80px;" class="form-control">
                        </td>
                        <td v-else>{{ formatPrice(item.price) }} บาท</td>

                        <!-- จำนวน -->
                        <td v-if="edtu && edId == item.id && dataRoles && dataRoles.some(r => r.id === 20)">
                          <input type="text" v-model="item.unit" style="width:80px;" class="form-control">
                        </td>
                        <td v-else>{{ formatPrice(item.unit) }}</td>

                        <!-- สูงสุด -->
                        <td v-if="edtu && edId == item.id && dataRoles && dataRoles.some(r => r.id === 22)">
                          <input type="text" v-model="item.max" style="width:80px;" class="form-control">
                        </td>
                        <td v-else><span class="text-danger">{{ formatPrice(item.max) }}</span></td>

                        <!-- จัดการ -->
                        <td class="align-middle pt-3 text-center">
                        <button v-if="edtu && edId == item.id" class="btn btn-sm px-4 mx-1 btn-success" @click="saveEditUnits(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z" />
                            <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                          </svg> บันทึก
                        </button>
                        <button v-else class="btn btn-sm px-2 mx-2 btn-info" @click="editUnits(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit" v-if="dataRoles && dataRoles.some(r => r.id === 20) || dataRoles.some(r => r.id === 21) || dataRoles.some(r => r.id === 22)">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                          </svg> จัดการจำนวน
                        </button>
                        <button v-if="edtu && edId == item.id" class="btn btn-sm px-2 mx-1 btn-danger" @click="cancelUnits()" data-bs-toggle="modal" data-bs-target="#exampleModalDel">
                          เลิกทำ
                        </button>
                        <button v-else class="btn btn-sm px-2 mx-1 btn-secondary" @click="delModal(item)" data-bs-toggle="modal" data-bs-target="#exampleModalDel" v-if="dataRoles && dataRoles.some(r => r.id === 20)">
                          ตั้งสูงสุด
                        </button>

                          <button class="btn btn-sm px-2 mx-1 btn-warning" @click="openLogs(item)"
                            data-bs-toggle="modal"
                            data-bs-target="#logModal">
                            ดูประวัติ
                          </button>
                        </td>


                        <!-- ผู้ดูแล -->
                        <td class="text-xs text-center">
                          <span v-html="item.person"></span>
                        </td>
                      </tr>

                      <tr v-if="paginatedProducts.length < 1">
                        <td colspan="8" class="text-center">ไม่มีสินค้า</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <!-- ===== CARDS (เฉพาะมือถือ) ===== -->
                <div class="d-md-none">
                  <div v-for="(item, index) in paginatedProducts" :key="'m-'+index" class="card mb-3 shadow-sm">
                    <div class="card-body py-3">
                      <!-- แถวบน: รูป + ชื่อ + หมวด -->
                      <div class="d-flex gap-3">
                        <img :src="'../../../uploads/' + item.image" alt="image" style="max-height:72px;">
                        <div class="flex-grow-1">
                          <div class="fw-semibold">{{ item.product_name }}</div>
                          <div class="text-muted small">{{ item.category_name }}</div>
                          <!-- รายละเอียดย่อ (ซ่อนยาว ๆ) -->
                          <div v-html="item.description"
                            class="text-secondary small mt-1 desc-clamp"></div>
                        </div>
                      </div>

                      <!-- ข้อมูลตัวเลข -->
                      <div class="row g-2 mt-3">
                        <div class="col-4">
                          <div class="text-muted xxs">ราคาสินค้า</div>
                          <div v-if="edtu && edId == item.id">
                            <input type="text" v-model="item.price" class="form-control form-control-sm">
                          </div>
                          <div v-else>{{ formatPrice(item.price) }} บาท</div>
                        </div>
                        <div class="col-4">
                          <div class="text-muted xxs">จำนวน</div>
                          <div v-if="edtu && edId == item.id">
                            <input type="text" v-model="item.unit" class="form-control form-control-sm">
                          </div>
                          <div v-else>{{ formatPrice(item.unit) }}</div>
                        </div>
                        <div class="col-4">
                          <div class="text-muted xxs">สูงสุด</div>
                          <div v-if="edtu && edId == item.id">
                            <input type="text" v-model="item.max" class="form-control form-control-sm">
                          </div>
                          <div v-else><span class="text-danger">{{ formatPrice(item.max) }}</span></div>
                        </div>
                      </div>

                      <!-- ผู้ดูแล + ปุ่ม -->
                      <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="small"><span v-html="item.person"></span></div>
                        <div class="d-flex gap-2">
                          <button v-if="edtu && edId == item.id"
                            class="btn btn-success btn-sm"
                            @click="saveEditUnits(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                            บันทึก
                          </button>
                          <button v-else
                            class="btn btn-info btn-sm"
                            @click="editUnits(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                            จัดการจำนวน
                          </button>

                          <button v-if="edtu && edId == item.id"
                            class="btn btn-danger btn-sm"
                            @click="cancelUnits()" data-bs-toggle="modal" data-bs-target="#exampleModalDel">
                            เลิกทำ
                          </button>
                          <button v-else
                            class="btn btn-secondary btn-sm"
                            @click="delModal(item)" data-bs-toggle="modal" data-bs-target="#exampleModalDel">
                            ตั้งสูงสุด
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ไม่มีสินค้า (มือถือ) -->
                  <div v-if="paginatedProducts.length < 1" class="text-center text-muted py-4">ไม่มีสินค้า</div>
                </div>

                <nav aria-label="Page navigation">
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
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">สินค้ายอดขายสูงสุด ตามลำดับ</h6>
              </div>
              <div class="card-body p-3 " style="min-height: 300px;">
                <div class="text-center" v-if="topProducts">
                  <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>สินค้า</th>
                        <th>จำนวนขาย</th>
                        <th>ยอดขาย</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in topProducts" :key="item.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.product_name }}</td>
                        <td>{{ item.total_qty }}</td>
                        <td>{{ formatPrice(item.total_sale) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div v-else class=" d-flex justify-content-center align-items-center">ยังไม่มีรายการขาย</div>
                <ul class="list-group d-none">
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                        <i
                          class="ni ni-mobile-button text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Devices</h6>
                        <span class="text-xs">250 in stock,
                          <span class="font-weight-bold">346+ sold</span></span>
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                        <i class="ni ni-tag text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                        <span class="text-xs">123 closed,
                          <span class="font-weight-bold">15 open</span></span>
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                        <i class="ni ni-box-2 text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                        <span class="text-xs">1 is active,
                          <span class="font-weight-bold">40 closed</span></span>
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                  <li
                    class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                    <div class="d-flex align-items-center">
                      <div
                        class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                        <i class="ni ni-satisfied text-white opacity-10"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                        <span class="text-xs font-weight-bold">+ 430</span>
                      </div>
                    </div>
                    <div class="d-flex">
                      <button
                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                        <i class="ni ni-bold-right" aria-hidden="true"></i>
                      </button>
                    </div>
                  </li>
                </ul>
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
                  </script> Soft by <img src="../../../assets/img/lgg.png" width="20" alt="logo">ɢɪɢᴀᴊᴜ ꜱᴛᴜᴅɪᴏ.
                </div>
              </div>

            </div>
          </div>
        </footer>
      </div>
      <!-- LOG MODAL -->
      <div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ประวัติการแก้ไขสินค้า: {{ logTarget?.product_name || '-' }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <div v-if="logsLoading" class="text-center py-4">
                กำลังโหลด...
              </div>

              <div v-else>
                <div v-if="logs.length < 1" class="text-center text-muted py-4">
                  ยังไม่มีประวัติ
                </div>

                <table v-else class="table table-sm table-bordered align-middle">
                  <thead>
                    <tr>
                      <th style="width:160px;">เวลา</th>
                      <th style="width:140px;">ประเภท</th>
                      <th>รายละเอียด</th>
                      <th style="width:120px;">ผู้ทำ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="l in logs" :key="l.id">
                      <td>{{ l.created_at }}</td>
                      <td>
                        <span class="badge bg-primary" v-if="l.action_type === 'SET_MAX'">SET_MAX</span>
                        <span class="badge bg-success" v-else-if="l.action_type === 'INCREASE_UNIT'">+UNIT</span>
                        <span class="badge bg-danger" v-else-if="l.action_type === 'DECREASE_UNIT'">-UNIT</span>
                        <span class="badge bg-info" v-else>PRICE</span>
                      </td>
                      <td>
                        <div v-if="l.action_type === 'SET_MAX'">
                          max: <b>{{ l.old_max }}</b> → <b>{{ l.new_max }}</b>
                        </div>

                        <div v-else-if="l.action_type === 'INCREASE_UNIT' || l.action_type === 'DECREASE_UNIT'">
                          unit: <b>{{ l.old_unit }}</b> → <b>{{ l.new_unit }}</b>
                          <span class="ms-2 text-muted">(เปลี่ยน {{ l.diff_unit }} ชิ้น)</span>
                        </div>

                        <div v-else-if="l.action_type === 'UPDATE_PRICE'">
                          price: <b>{{ l.old_price }}</b> → <b>{{ l.new_price }}</b>
                        </div>

                        <div class="text-muted small" v-if="l.user_id">
                          user_id: {{ l.user_id }}
                        </div>
                      </td>
                      <td>{{ l.user_role || '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="../../../assets/js/core/popper.min.js"></script>
  <script src="../../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../../assets/js/plugins/chartjs.min.js"></script>
  <script>
    // document.addEventListener('DOMContentLoaded', function() {
    //   const ctx = document.getElementById('chart-line').getContext('2d');

    //   new Chart(ctx, {
    //     type: 'line',
    //     data: {
    //       labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.'],
    //       datasets: [{
    //         label: 'ยอดขาย (บาท)',
    //         data: [12000, 19000, 3000, 5000, 2000, 30000, 0],
    //         borderColor: 'rgb(150, 167, 248)',
    //         backgroundColor: 'rgba(77, 123, 247, 0.2)',
    //         fill: true,
    //         tension: 0.4
    //       }]
    //     },
    //     options: {
    //       responsive: true,
    //       plugins: {
    //         legend: {
    //           display: true
    //         }
    //       },
    //       scales: {
    //         y: {
    //           beginAtZero: true
    //         }
    //       }
    //     }
    //   });
    // });
  </script>

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
  <script src="../../../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
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
          whId: '',
          whName: '',
          edtu: false,
          edId: '',
          usersError: false,
          passError: false,
          textError: "",
          searchKeyword: '',
          selectedCategory: null,
          products: [],
          rangeType: 'today',
          customStart: todayStr,
          customEnd: tomorrowStr,
          persent: 0,
          summaryData: {
            total_sale: 0,
            total_cost: 0,
            profit: 0,
            stock_value: 0
          },
          categories: [],
          topProducts: [],
          chart: null,
          viewType: 'week', // เริ่มต้นดูรายสัปดาห์,
          currentPage: 1,
          itemsPerPage: 10,
          logs: [],
          logsLoading: false,
          logTarget: null,
          userProfile: null, // (ของคุณมีอยู่แล้วแต่ไม่เห็นประกาศใน data)
          dataRoles:'',
        };
      },
      computed: {
        filteredProducts() {
          if (this.selectedCategory === null) return this.products;
          return this.products.filter(p => p.category_id === this.selectedCategory);
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.itemsPerPage;
          return this.filteredProducts.slice(start, start + this.itemsPerPage);
        },
        totalPages() {
          return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
        }
      },
      mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        this.whId = urlParams.get('id'); // ค่าจากพารามิเตอร์ ?id= ใน URL
        this.whName = urlParams.get('warehousename'); // ค่าจากพารามิเตอร์ ?id= ใน URL
        document.getElementById('whname').innerHTML = this.whName;
        const profile = JSON.parse(localStorage.getItem("Fin-Profile"));

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
          this.getEmployeeRoles(profile.data.id);
          this.loadTopSellingProducts();
            this.getProducts();
            this.loadCategories();
            this.fetchSummary();
            this.loadChartData();
        }
      },
      methods: {
        async getEmployeeRoles(empId) {
          const res = await axios.post("../../../api/", {
            post: "get_employee_roles",
            employee_id: empId
          });
          if (res.data.status) {
            this.employee = res.data.employee;
            this.dataRoles = res.data.roles;
          }
        },
        getBack() {
          window.location = "../top";
        },
        editUnits(item) {
          this.edtu = true;
          this.edId = item.id;
        },
        cancelUnits() {
          this.edtu = false;
          this.edId = '';
        },
        formatThaiDate(dateStr) {
          return dayjs(dateStr).format("HH:mm");
          // return dayjs(dateStr).format("D MMMM YYYY เวลา HH:mm");
        },

        async loadTopSellingProducts() {
          try {
            const res = await axios.post('../../../api/', {
              post: 'get_top_selling_products',
              limit: 10
            })
            if (res.data.status) {
              this.topProducts = res.data.data
            } else {
              alert('โหลดข้อมูลยอดขายไม่สำเร็จ')
            }
          } catch (err) {
            console.error('เกิดข้อผิดพลาดในการโหลดข้อมูล:', err)
          }
        },
        async fetchSummary() {
          const payload = {
            post: 'get_summary_range',
            range: this.rangeType
          };

          if (this.rangeType === 'custom') {
            if (!this.customStart || !this.customEnd) return console.log("กรุณาระบุช่วงวันที่ให้ครบ");
            payload.start_date = this.customStart;
            payload.end_date = this.customEnd;
          }

          try {
            const res = await axios.post('../../../api/', payload);
            this.summaryData = res.data;
          } catch (err) {
            console.error(err);
          }
        },
        formatPrice(price) {
          return Number(price).toLocaleString(); // ใส่ comma (,)
        },
        async loadChartData() {
          let postType = '';
          let labels = [];
          let chartData = [];

          // 🔸เลือกส่ง post ไปตาม viewType
          if (this.viewType === 'week') {
            postType = 'get_sale_summary_by_week';
          } else if (this.viewType === 'month') {
            postType = 'get_sale_summary_by_month';
          }

          try {
            const res = await fetch('../../../api/', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                post: postType
              })
            });

            const result = await res.json();
            if (!result.status) throw new Error(result.message);
            if (result.data.total) {
              this.persent = result.data.total;
            } else {
              this.persent = 0;
            }

            labels = result.data.map(item => item.label); // เช่น ['จันทร์', 'อังคาร', ..., 'อาทิตย์'] หรือ ['ม.ค.', ...]
            chartData = result.data.map(item => item.total); // ยอดขายรวม

            this.renderChart(labels, chartData);

          } catch (err) {
            console.error('โหลดข้อมูลไม่สำเร็จ', err);
          }
        },

        renderChart(labels, data) {
          const ctx = document.getElementById('chart-line').getContext('2d');
          if (this.chart) this.chart.destroy(); // 🔁 ล้าง chart เก่าก่อนวาดใหม่

          this.chart = new Chart(ctx, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: 'ยอดขาย (บาท)',
                data: data,
                borderColor: 'rgb(77, 123, 247)',
                backgroundColor: 'rgba(77, 123, 247, 0.2)',
                fill: true,
                tension: 0.4
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  display: true
                }
              },
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        },
        searchProduct() {
          if (this.searchKeyword.length > 1) {
            axios.post('../../../api/', {
              post: 'search_products',
              keyword: this.searchKeyword
            }).then(res => {
              this.products = res.data.products;
            }).catch(err => {
              console.error(err);
            });
          } else {
            this.getProducts();
          }
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
        async saveEditUnits(item) {
          try {
            // ดึง user จาก localStorage (คุณใช้ Fin-Profile)
            const profileRaw = localStorage.getItem("Fin-Profile");
            let user_id = null;
            let user_role = "";

            if (profileRaw) {
              try {
                const parsed = JSON.parse(profileRaw);
                // แล้วแต่โครง profile จริงของคุณ:
                user_id = parsed?.data?.uid || parsed?.uid || null;
                user_role = parsed?.data?.name || null;
              } catch (e) {}
            }

            const payload = {
              post: "save_products_in_wh",
              warehouses_id: this.whId, // ✅ warehouse จากหน้า
              pd_in_whs_id: item.pw_id || item.id, // ✅ สำคัญ: id ของตาราง pd_in_whs
              product_id: item.product_id || item.prooduct_id || item.id, // ปรับตาม field จริง
              unit: Number(item.unit),
              price: Number(item.price),
              max: Number(item.max),
              user_id,
              user_role,
            };

            const response = await axios.post("../../../api/", payload);

            if (response.data.status) {
              this.cancelUnits();
              // refresh list
              this.getProducts();
            } else {
              alert(response.data.message || "บันทึกไม่สำเร็จ");
            }
          } catch (error) {
            console.error(error);
            alert("เกิดข้อผิดพลาดในการบันทึก");
          }
        },
        async openLogs(item) {
          this.logTarget = item;
          this.logs = [];
          this.logsLoading = true;

          const pd_in_whs_id = item.pw_id || item.id; // ต้องเป็น id ของ pd_in_whs

          try {
            const res = await axios.post("../../../api/", {
              post: "get_pd_in_wh_logs",
              pd_in_whs_id: pd_in_whs_id
            });

            if (res.data.status) {
              this.logs = res.data.data || [];
            } else {
              this.logs = [];
            }
          } catch (e) {
            console.error(e);
            this.logs = [];
          } finally {
            this.logsLoading = false;
          }
        },

        async getProducts() {
          try {
            const response = await axios.post('../../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: this.whId
            });
            if (response.data.status) {
              this.products = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        async loadCategories() {
          try {
            const response = await axios.post('../../../api/', {
              post: 'categories'
            });
            if (response.data.status) {
              this.categories = response.data.data;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }

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