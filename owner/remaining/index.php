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
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <?php include('../layout/sitebar.html') ?>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">OWNER</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Quantity Products</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0" id="whName"></h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">

          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Owner</span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New message</span> from Laur
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
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">New album</span> by Travis Scott
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
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                  <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
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
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex align-items-center justify-content-between mb-3">
              <div v-if="currentWh!=''">
                <button class="btn" @click="goBack()">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                  </svg> กลับ</button>
              </div>
              <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group mb-3">
                  <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                  <input
                    type="text"
                    class="form-control"
                    placeholder="ค้นหาชื่อสินค้า..."
                    v-model="searchKeyword"
                    @input="searchProduct">
                </div>
              </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">เพิ่มสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-1">
                      <label for="exampleFormControlInput1" class="form-label mb-0">ประเภทสินค้า *</label>
                      <!-- หมวดหมู่ -->
                      <select class="form-select" v-model="formData.category_id">
                        <option value="">เลือกหมวดหมู่สินค้า</option>
                        <option v-for="(item,i) in dataCategory" :key="i" :value="item.id">
                          {{ item.cate_name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-1">
                      <label for="exampleFormControlInput1" class="form-label mb-0">ชื่อสินค้า *</label>
                      <!-- ชื่อสินค้า -->
                      <input type="text" class="form-control" v-model="formData.name" placeholder="ชื่อสินค้า หรือยี่ห้อ...">
                    </div>
                    <div class="row">
                      <div class="col-6 mb-1">
                        <label for="exampleFormControlInput1" class="form-label mb-0">ราคา *</label>
                        <!-- ราคา -->
                        <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาสินค้า..">
                      </div>
                      <div class="col-6 mb-1">
                        <label for="exampleFormControlInput1" class="form-label mb-0">จำนวน *</label>
                        <!-- จำนวน -->
                        <input type="number" class="form-control" v-model="formData.quantity" placeholder="จำนวนรับเข้า">
                      </div>
                    </div>
                    <div class="mb-1">
                      <label for="exampleFormControlTextarea1" class="form-label mb-0">รูปภาพ *</label>
                      <!-- รูปภาพ -->
                      <input type="file" class="form-control" @change="handleFileUpload" ref="imageInput">
                    </div>
                    <div class="mb-1">
                      <label for="exampleFormControlTextarea1" class="form-label mb-0">รายละเอียด</label>
                      <!-- รายละเอียด -->
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
            <!-- Edit -->
            <div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title">แก้ไขสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">

                    <!-- ประเภทสินค้า -->
                    <div class="mb-2">
                      <label class="form-label mb-0">ประเภทสินค้า *</label>
                      <select class="form-select" v-model="formData.category_id">
                        <option value="">เลือกหมวดหมู่สินค้า</option>
                        <option v-for="(item, i) in dataCategory" :key="i" :value="item.id">
                          {{ item.cate_name }}
                        </option>
                      </select>
                    </div>

                    <!-- ชื่อสินค้า -->
                    <div class="mb-2">
                      <label class="form-label mb-0">ชื่อสินค้า *</label>
                      <input type="text" class="form-control" v-model="formData.product_name" placeholder="ชื่อสินค้า หรือยี่ห้อ...">
                    </div>

                    <!-- ราคา และ จำนวน -->
                    <div class="row">
                      <div class="col-6 mb-2">
                        <label class="form-label mb-0">ราคา *</label>
                        <input type="number" class="form-control" v-model="formData.price" placeholder="ราคาสินค้า...">
                      </div>
                      <div class="col-6 mb-2">
                        <label class="form-label mb-0">จำนวน *</label>
                        <input type="number" class="form-control" v-model="formData.quantity" placeholder="จำนวนรับเข้า">
                      </div>
                    </div>

                    <!-- รูปภาพ -->
                    <div class="mb-2">
                      <label class="form-label mb-0">รูปภาพ *</label>
                      <input type="file" class="form-control" @change="handleFileUploads" ref="imageInput">
                      <div v-if="image_url" class="mt-2">
                        <img :src="image_url" alt="รูปสินค้า" style="max-height: 80px; height: auto;">
                      </div>
                      <div v-else class="mt-2">
                        <img :src="'../'+formData.image" alt="รูปสินค้า" style="max-height: 80px; height: auto;">
                      </div>
                    </div>

                    <!-- รายละเอียด -->
                    <div class="mb-2">
                      <label class="form-label mb-0">รายละเอียด</label>
                      <textarea class="form-control" v-model="formData.description" rows="3"></textarea>
                    </div>

                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary" @click="editForm">บันทึกข้อมูล</button>
                  </div>

                </div>
              </div>
            </div>
            <!--
              <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0" style="min-height: 280px;">
                  <table class="table table-responsive align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">รูป/รายละเอียด</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อสินค้า</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">หมวดหมู่</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ราคาสินค้า</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">จำนวนสินค้า</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">จัดการ</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ผู้ดูแล</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in paginatedProducts" :key="index">
                        <td class="d-flex justify-content-start">
                          <img :src="'../../uploads/' + item.image" alt="image" class="mx-2" style="max-height: 60px;">
                          <div
                            v-html="item.description"
                            class="text-xs text-secondary mb-0"
                            style="text-align: left; white-space: pre-wrap; word-break: break-word;min-width: 140px;"></div>
                        </td>
                        <td>{{ item.product_name }} </td>
                        <td>{{ item.category_name }}</td>
                        <td>{{ formatPrice(item.price) }} บาท</td>
                        <td>
                          <span class="badge badge-sm bg-gradient-success" v-if="item.quantity>=10">{{ item.quantity }}</span>
                          <span class="badge badge-sm bg-gradient-danger" v-else>{{ item.quantity }}</span>
                        </td>
                        <td class="align-middle pt-3">
                          <button class="btn btn-sm px-2 mx-1 btn-info" @click="editProducts(item)" data-bs-toggle="modal" data-bs-target="#exampleModalEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                          </button>
                          <button class="btn btn-sm px-2 mx-1 btn-danger" @click="delModal(item)" data-bs-toggle="modal" data-bs-target="#exampleModalDel">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                              <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                            </svg>
                          </button>
                        </td>
                        <td class="text-xs">
                          <span v-html="item.person"></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            -->

            <div class="card-body px-0 pt-0 pb-2" v-if="currentWh==''">
              <div class="row p-4 row align-items-center justify-content-center">
                <div class="col-12 col-md-8 text-center">
                  <h4>
                    <p>เลือกสาขา?</p>
                  </h4>

                  <div class="row">
                    <div class="col-4 col-md-3" v-for="(item, i) in dataWarehouses">
                      <button class="btn" @click="selecthWarehouse(item)">
                        <h1><svg xmlns="http://www.w3.org/2000/svg" width="68" height="68" fill="#344767" class="bi bi-shop-window" viewBox="0 0 16 16">
                            <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h12V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5m2 .5a.5.5 0 0 1 .5.5V13h8V9.5a.5.5 0 0 1 1 0V13a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9.5a.5.5 0 0 1 .5-.5"></path>
                          </svg></h1>
                        {{item.name}}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2" v-else>
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">สินค้า</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">จำนวน</th>
                      <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ยอดสูงสุด</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">อัตรา</th> -->
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in paginatedProducts" :key="index">
                      <td>
                        <div class="d-flex px-2">
                          <div>
                            <img :src="'../../uploads/' + item.image" alt="image" class="mx-2" style="max-height: 60px;">
                          </div>
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm">{{ item.product_name }}</h6>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <span class="badge badge-sm text-sm text-dark font-weight-bold mb-0" :class="{ 'bg-danger text-white px-2 py-1': item.unit < 50 }">{{ formatPrice(item.unit) }}</span>
                      </td>
                      <!-- <td class="text-center">
                        <span class="text-xs font-weight-bold">{{ item.max }}</span>
                      </td>
                      <td class="align-middle text-center">
                        <div class="d-flex align-items-center justify-content-center">
                          <span class="me-2 text-xs font-weight-bold">{{ getPercent(item.unit, item.max) }}%</span>
                          <div>
                            <div class="progress">
                              <div class="progress-bar"
                                :class="{
                                'bg-gradient-info': getPercent(item.unit, item.max) >= 100,
                                'bg-gradient-warning': getPercent(item.unit, item.max) > 50 && getPercent(item.unit, item.max) < 100,
                                'bg-gradient-danger': getPercent(item.unit, item.max) <= 50
                              }"
                                role="progressbar"
                                :aria-valuenow="getPercent(item.unit, item.max)"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                :style="{ width: getPercent(item.unit, item.max) + '%' }">
                              </div>
                            </div>
                          </div>
                        </div>
                      </td> -->
                      <td class="align-middle">
                        <!-- <button class="btn btn-link border text-secondary mb-0" v-if="item.unit < 50" @click="goToTranfer(item)">
                          นำเข้า
                        </button> -->
                      </td>
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

      <div class="modal fade" id="exampleModalDel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">ลบรายการสินค้า</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>ต้องการลบ <b>{{editData.product_name}}</b> จำนวน {{editData.quantity}}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="button" class="btn btn-primary" @click="delData()">บันทึกข้อมูล</button>
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
  <script type="module">
    const {
      createApp
    } = Vue;
    createApp({
      data() {
        return {
          auth: "",
          hidId: '',
          modalDel: false,
          usersError: false,
          passError: false,
          textError: "",
          dataCategory: '',
          editData: '',
          image_url: '',
          formData: {
            category_id: '',
            name: '',
            price: '',
            quantity: '',
            description: ''
          },
          searchKeyword: '',
          dataWarehouses: '',
          filteredProducts: [],
          image: null,
          currentWh: '',
          warehouses: '',
          products: [],
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
          return Math.ceil(this.products.length / this.perPage);
        },
        paginatedProducts() {
          const start = (this.currentPage - 1) * this.perPage;
          const end = start + this.perPage;
          return this.products.slice(start, end);
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
          window.location = "../../";
        } else {
          let porson = JSON.parse(profile)
          if (porson.data.position != 'owner') {
            window.location = "../../" + porson.redirect;
          }
          // this.getProducts();
          this.loadWarehouses();
        }
      },
      methods: {
        getPercent(unit, max) {
          if (!max || max === 0) return 0;
          return Math.round((unit / max) * 100);
        },
        goBack() {
          // console.log('currentWh',this.currentWh);
          this.currentWh = '';
        },
        goToTranfer(item) {
          window.location = '../transfer/?inpid='+item.id+'&inwhid='+this.currentWh;
        },
        handleFileUploads(e) {
          const file = e.target.files[0];
          if (file) {
            this.image = file;
            this.image_url = URL.createObjectURL(file);
          }
        },
        searchProduct() {
          if (this.searchKeyword.length > 2) {
            axios.post('../../api/', {
              post: 'search_products_in_wh',
              warehouses_id: this.currentWh,
              keyword: this.searchKeyword
            }).then(res => {
              this.products = res.data.products;
            }).catch(err => {
              console.error(err);
            });
          } else {
            this.getProducts(this.currentWh);
          }
        },
        delModal(item) {
          this.hidId = item.id;
          this.editData = item;

        },
        changePage(page) {
          if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
          }
        },
        formatThaiDate(dateStr) {
          return dayjs(dateStr).format("HH:mm");
          // return dayjs(dateStr).format("D MMMM YYYY เวลา HH:mm");
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
        editProducts(item) {
          this.formData = item;
          this.getType();
        },
        selecthWarehouse(item) {
          this.currentWh = item.id;
          this.getProducts(item.id);
          document.getElementById("whName").innerHTML = 'ติดตามคงเหลือ / ' + item.name;
        },
        loadWarehouses() {
          axios.post('../../api/', {
              post: 'get_warehouses'
            })
            .then(res => this.dataWarehouses = res.data.data)
            .catch(err => console.log('โหลดคลังสินค้าล้มเหลว'));
        },
        async getProducts(id) {
          try {
            const response = await axios.post('../../api/', {
              post: 'get_products_in_wh',
              warehouses_id: id
            });
            if (response.data.status) {
              this.products = response.data.products;
            }
          } catch (error) {
            console.error('Error fetching products:', error);
          }
        },
        formatPrice(price) {
          return Number(price).toLocaleString(); // ใส่ comma (,)
        },
        getType: async function() {
          try {
            const url = '../../api/';
            const config = {
              headers: {
                'Content-Type': 'application/json'
              }
            };

            const response = await axios.post(url, {
              post: 'get_type'
            }, config);

            // ตรวจสอบ response และอัปเดต dataVdo
            if (response.data && response.data.data) {
              this.dataCategory = response.data.data;
              this.cateName = '';
            } else {
              console.log("ไม่พบข้อมูลประเภทสินค้า");
            }
          } catch (error) {
            console.error('เกิดข้อผิดพลาด:', error);
          }
        },

        handleFileUpload(event) {
          this.image = event.target.files[0];
        },
        async submitForm() {
          // Validation
          if (
            !this.formData.category_id ||
            !this.formData.name ||
            !this.formData.price ||
            !this.formData.quantity ||
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
          payload.append("description", this.formData.description);
          payload.append("image", this.image);
          payload.append("position", profile.data.position);
          payload.append("username", profile.data.username);

          try {
            const response = await axios.post("../../api/", payload, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            });

            if (response.data.status) {
              Swal.fire("บันทึกสำเร็จ", "", "success");
              // รีเซตฟอร์มหรือโหลดใหม่
              this.getProducts();
              this.formData = {
                category_id: '',
                name: '',
                price: '',
                quantity: '',
                description: ''
              };
            } else {
              Swal.fire("เกิดข้อผิดพลาด", response.data.message, "error");
            }
          } catch (error) {
            console.error(error);
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        },
        async editForm() {
          console.log('formData', this.formData);
          // Validation
          if (
            !this.formData.category_id ||
            !this.formData.product_name ||
            !this.formData.id ||
            !this.formData.price ||
            !this.formData.quantity ||
            !this.image
          ) {
            Swal.fire("กรอกข้อมูลให้ครบถ้วน", "", "warning");
            return;
          }
          const profile = JSON.parse(localStorage.getItem("Fin-Profile"));
          const payload = new FormData();
          payload.append("post", "save_edit_product");
          payload.append("category_id", this.formData.category_id);
          payload.append("name", this.formData.product_name);
          payload.append("price", this.formData.price);
          payload.append("product_id", this.formData.id);
          payload.append("quantity", this.formData.quantity);
          payload.append("description", this.formData.description);
          payload.append("image", this.image);
          payload.append("position", profile.data.position);
          payload.append("username", profile.data.username);

          try {
            const response = await axios.post("../../api/", payload, {
              headers: {
                "Content-Type": "multipart/form-data"
              }
            });

            if (response.data.status) {
              Swal.fire("บันทึกสำเร็จ", "", "success");
              // รีเซตฟอร์มหรือโหลดใหม่
              this.getProducts();
              this.formData = {
                category_id: '',
                name: '',
                price: '',
                quantity: '',
                description: ''
              };
              this.image_url = '';
            } else {
              Swal.fire("เกิดข้อผิดพลาด", response.data.message, "error");
            }
          } catch (error) {
            console.error(error);
            Swal.fire("ระบบมีปัญหา", "", "error");
          }
        },
        delData: async function() {
          try {
            const url = '../../api/';
            const config = {
              headers: {
                'Content-Type': 'application/json'
              }
            }
            await axios.post(url, {
              post: 'del_product_id',
              id: this.hidId,
            }, config).then(function(response) {
              if (response.data.status) {
                Swal.fire("ลบสำเร็จ", "", "success");
                this.getProducts();
              }
              Swal.fire("ลบสำเร็จ", "", "success");
              this.getProducts();
            });
          } catch (error) {
            console.log('error', error);
          }
        },

      },
    }).mount("#app");
  </script>
</body>

</html>