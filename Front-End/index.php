<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hệ thống quản lý đơn hàng</title>
  <link href="assets/images/favicon.png" rel="icon" type="image/x-icon" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="assets/fonts/font-awesome-pro-v6-6.2.0/css/all.min.css" rel="stylesheet" />
  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/dashboard.css" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Counter.js -->
  <script src="https://cdn.jsdelivr.net/npm/countup.js@2.0.8/dist/countUp.umd.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <!-- Map -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWTx7bREpM5B6JKdbzOvMW-RRlhkukmVE"></script>
  <script src="assets/js/delivery.js"></script>
</head>

<body>
  <header class="header"></header>
  <!-- Main -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar d-flex flex-column">
        <!-- Logo -->
        <div class="text-center sidebar-top">
          <img src="assets/images/favicon.png" alt="Logo" class="img-fluid" />
          <img src="assets/images/admin/vy-food-title.png" alt="Logo" class="img-fluid" />
        </div>

        <!-- Navigation -->
        <nav class="nav flex-column mb-2 sidebar-nav pt-4 flex-grow-1">
          <a class="nav-link active w-100" href="#">
            <i class="fa-light fa-house"></i>
            Trang tổng quan
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-pot-food"></i>
            Sản phẩm
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-users"></i>
            Khách hàng
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-basket-shopping"></i>
            Đơn hàng
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-person-carry-box"></i>
            Vận chuyển
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-chart-simple"></i>
            Thống kê
          </a>
        </nav>

        <!-- User Section -->
        <nav class="nav flex-column sidebar-section mb-2 pt-4 mt-auto">
          <a class="nav-link w-100" href="#">
            <i class="fa-thin fa-circle-chevron-left"></i>
            Trang chủ
          </a>
          <a class="nav-link w-100" href="#">
            <i class="fa-light fa-circle-user"></i>
            <span class="sidebar-username"></span>
          </a>
          <a class="nav-link w-100" href="#" id="logout-btn">
            <i class="fa-light fa-arrow-right-from-bracket"></i>
            Đăng xuất
          </a>
        </nav>
      </div>

      <!-- Content -->
      <div class="col-md-10 content">
        <h1 class="text-center page-title p-2">
          Hệ thống quản lý của Vy Food
        </h1>
        <!-- Overview  -->
        <div class="section overview mb-4">
          <!-- Mini cards row -->
          <div class="row g-3 mb-4">
            <!-- Tổng doanh thu -->
            <div class="col-md-3">
              <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="card-title">Tổng doanh thu</div>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tháng
                  </div>
                </div>
                <div class="card-value text-success" id="revenueValue">12.5M ₫</div>
                <div class="d-flex align-items-center">
                  <span id="revenueGrowth" class="percentage up me-2">
                    <i class="fa-light fa-arrow-up me-1"></i>0%
                  </span>
                  <span class="text-muted fs-11">so với tháng trước</span>
                </div>
                <div class="chart-container">
                  <canvas id="revenueLineChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Tổng đơn hàng -->
            <div class="col-md-3">
              <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="card-title">Tổng đơn hàng</div>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tháng
                  </div>
                </div>
                <div class="card-value text-danger" id="ordersValue">86</div>
                <div class="d-flex align-items-center">
                  <span id="ordersGrowth" class="percentage up me-2">
                    <i class="fa-light fa-arrow-up me-1"></i>0%
                  </span>
                  <span class="text-muted fs-11">so với tháng trước</span>
                </div>
                <div class="chart-container">
                  <canvas id="ordersLineChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Khách hàng -->
            <div class="col-md-3">
              <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="card-title">Khách hàng</div>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tháng
                  </div>
                </div>
                <div class="card-value text-primary" id="customersValue">42</div>
                <div class="d-flex align-items-center">
                  <span id="customersGrowth" class="percentage up me-2">
                    <i class="fa-light fa-arrow-up me-1"></i>0%
                  </span>
                  <span class="text-muted fs-11">so với tháng trước</span>
                </div>
                <div class="chart-container">
                  <canvas id="customersLineChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Đơn trung bình -->
            <div class="col-md-3">
              <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="card-title">Đơn trung bình</div>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tháng
                  </div>
                </div>
                <div class="card-value text-info" id="avgOrderValue">320K ₫</div>
                <div class="d-flex align-items-center">
                  <span id="avgOrderGrowth" class="percentage up me-2">
                    <i class="fa-light fa-arrow-up me-1"></i>0%
                  </span>
                  <span class="text-muted fs-11">so với tháng trước</span>
                </div>
                <div class="chart-container">
                  <canvas id="avgOrderLineChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Detailed analytics row -->
          <div class="row g-3">
            <!-- Doanh thu theo thời gian -->
            <div class="col-md-6">
              <div class="stats-card">
                <div class="panel-header">
                  <h6>Doanh thu</h6>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tháng
                  </div>
                </div>
                <div class="d-flex align-items-end mb-3">
                  <div>
                    <div class="text-muted fs-12 mb-1">Doanh thu</div>
                    <div class="fs-5 fw-bold" id="monthlyRevenue">3.7M ₫</div>
                  </div>
                  <div class="ms-4">
                    <div class="text-muted fs-12 mb-1">Đơn hàng</div>
                    <div class="fs-5 fw-bold" id="monthlyOrders">28</div>
                  </div>
                  <div class="ms-auto">
                    <span class="percentage up"><i class="fa-light fa-arrow-up me-1"></i>8.6%</span>
                  </div>
                </div>
                <div class="bar-chart-container">
                  <canvas id="revenueBarChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Trạng thái đơn hàng và Top sản phẩm -->
            <div class="col-md-6">
              <div class="row g-3">
                <!-- Trạng thái đơn hàng -->
                <div class="col-md-6">
                  <div class="stats-card">
                    <div class="panel-header">
                      <h6>Trạng thái đơn hàng</h6>
                      <div class="time-selector">
                        <i class="fa-light fa-calendar-days me-1"></i> Tháng
                      </div>
                    </div>
                    <div class="text-center mb-2">
                      <div class="fs-4 fw-bold mb-1" id="total-orders-count">86</div>
                      <div class="text-muted fs-12">Tổng đơn hàng</div>
                    </div>
                    <div class="pie-chart-container">
                      <canvas id="orderStatusChart"></canvas>
                    </div>
                  </div>
                </div>

                <!-- Top sản phẩm bán chạy -->
                <div class="col-md-6">
                  <div class="stats-card">
                    <div class="panel-header">
                      <h6>Top sản phẩm bán chạy</h6>
                      <div class="time-selector">
                        <i class="fa-light fa-calendar-days me-1"></i> Tháng
                      </div>
                    </div>

                    <div class="top-product-list" id="top-selling-products">
                      <div class="top-product-item d-flex align-items-center">
                        <img src="assets/images/products/khau-nhuc.jpeg" alt="Khâu nhục" class="me-2">
                        <div class="flex-grow-1">
                          <div class="product-name">Khâu nhục</div>
                          <div class="product-price">150.000 đ</div>
                        </div>
                        <div class="product-sales">48 đơn</div>
                      </div>

                      <div class="top-product-item d-flex align-items-center">
                        <img src="assets/images/products/tai-cuon-luoi.jpeg" alt="Tai cuộn lưỡi" class="me-2">
                        <div class="flex-grow-1">
                          <div class="product-name">Tai cuộn lưỡi</div>
                          <div class="product-price">200.000 đ</div>
                        </div>
                        <div class="product-sales">42 đơn</div>
                      </div>

                      <div class="top-product-item d-flex align-items-center">
                        <img src="assets/images/products/nam-dui-ga-chay-toi.jpeg" alt="Nấm đùi gà xào" class="me-2">
                        <div class="flex-grow-1">
                          <div class="product-name">Nấm đùi gà xào</div>
                          <div class="product-price">180.000 đ</div>
                        </div>
                        <div class="product-sales">35 đơn</div>
                      </div>

                      <div class="top-product-item d-flex align-items-center">
                        <img src="assets/images/products/ca-chien-gion-mam-thai.jpeg" alt="Cá chiên giòn" class="me-2">
                        <div class="flex-grow-1">
                          <div class="product-name">Cá chiên giòn</div>
                          <div class="product-price">200.000 đ</div>
                        </div>
                        <div class="product-sales">30 đơn</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Đơn hàng gần đây -->
          <div class="row mt-3">
            <div class="col-12">
              <div class="stats-card">
                <div class="panel-header">
                  <h6>Đơn hàng gần đây</h6>
                  <a href="" id="view-all-orders" class="btn btn-export-excel">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                      </tr>
                    </thead>
                    <tbody id="recent-orders-list">
                      <!-- <tr>
                        <td>DH113</td>
                        <td>Nguyễn Viết Quyền</td>
                        <td>Hôm nay, 10:25</td>
                        <td>350.000 đ</td>
                        <td><span class="badge bg-warning text-dark status-unprocessed">Chưa xử lý</span></td>
                        <td>
                          <button class="btn btn-sm btn-light rounded-circle">
                            <i class="fa-light fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>DH112</td>
                        <td>Bùi Ngọc Linh</td>
                        <td>Hôm nay, 09:18</td>
                        <td>520.000 đ</td>
                        <td><span class="badge bg-warning text-dark status-unprocessed">Chưa xử lý</span></td>
                        <td>
                          <button class="btn btn-sm btn-light rounded-circle">
                            <i class="fa-light fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>DH111</td>
                        <td>Lê Văn B</td>
                        <td>Hôm qua, 18:42</td>
                        <td>180.000 đ</td>
                        <td><span class="badge bg-success status-active">Đã xử lý</span></td>
                        <td>
                          <button class="btn btn-sm btn-light rounded-circle">
                            <i class="fa-light fa-eye"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td>DH110</td>
                        <td>Trần Thị C</td>
                        <td>Hôm qua, 15:30</td>
                        <td>430.000 đ</td>
                        <td><span class="badge bg-success status-active">Đã xử lý</span></td>
                        <td>
                          <button class="btn btn-sm btn-light rounded-circle">
                            <i class="fa-light fa-eye"></i>
                          </button>
                        </td>
                      </tr> -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Hàng tính năng mới -->
          <div class="row g-3 mt-3">
            <!-- Thông báo & Nhắc nhở -->
            <div class="col-md-4">
              <div class="stats-card">
                <div class="panel-header">
                  <h6><i class="fa-light fa-bell me-2 text-warning"></i>Thông báo & Nhắc nhở</h6>
                  <a href="#" class="btn btn-sm btn-link text-decoration-none">Xem tất cả</a>
                </div>
                <div class="notification-list" id="notification-list">
                  <div class="notification-item d-flex align-items-start p-2 border-bottom">
                    <div class="notification-icon me-3 mt-1">
                      <span class="badge rounded-circle bg-danger p-2"><i class="fa-light fa-exclamation"></i></span>
                    </div>
                    <div>
                      <h6 class="mb-0 fs-13">Đơn hàng DH109 đã quá hạn xử lý</h6>
                      <p class="text-muted mb-0 fs-11">2 giờ trước</p>
                    </div>
                  </div>
                  <div class="notification-item d-flex align-items-start p-2 border-bottom">
                    <div class="notification-icon me-3 mt-1">
                      <span class="badge rounded-circle bg-primary p-2"><i class="fa-light fa-box"></i></span>
                    </div>
                    <div>
                      <h6 class="mb-0 fs-13">Hàng mới về: Nấm đùi gà (50 phần)</h6>
                      <p class="text-muted mb-0 fs-11">Hôm nay, 08:25</p>
                    </div>
                  </div>
                  <div class="notification-item d-flex align-items-start p-2">
                    <div class="notification-icon me-3 mt-1">
                      <span class="badge rounded-circle bg-success p-2"><i class="fa-light fa-user"></i></span>
                    </div>
                    <div>
                      <h6 class="mb-0 fs-13">3 khách hàng mới đăng ký</h6>
                      <p class="text-muted mb-0 fs-11">Hôm qua</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Phân tích khách hàng - Thay thế widget "Sản phẩm sắp hết hàng" -->
            <div class="col-md-4">
              <div class="stats-card" id="customer-analysis">
                <div class="panel-header">
                  <h6><i class="fa-light fa-users me-2 text-primary"></i>Phân tích khách hàng</h6>
                  <a href="#" id="view-all-customers" class="btn btn-sm btn-link text-decoration-none">Chi tiết</a>
                </div>

                <div class="d-flex justify-content-around mb-3 text-center">
                  <div>
                    <div class="fs-4 fw-bold text-primary">42</div>
                    <div class="fs-11 text-muted">Tổng KH</div>
                  </div>
                  <div>
                    <div class="fs-4 fw-bold text-success">68%</div>
                    <div class="fs-11 text-muted">Quay lại</div>
                  </div>
                  <div>
                    <div class="fs-4 fw-bold text-warning">12</div>
                    <div class="fs-11 text-muted">KH mới/tháng</div>
                  </div>
                </div>

                <!-- Khách hàng thân thiết -->
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fs-12 fw-semibold">Khách hàng thân thiết</span>
                  </div>
                  <div class="customer-list" id="loyal-customers">
                    <!-- <div class="d-flex align-items-center p-2 border-bottom">
                      <div class="customer-avatar me-2">
                        <div
                          class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                          style="width: 35px; height: 35px;">NQ</div>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                          <div class="fs-13 fw-semibold">Nguyễn Viết Quyền</div>
                          <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">VIP</div>
                        </div>
                        <div class="d-flex justify-content-between">
                          <div class="fs-11 text-muted">12 đơn hàng</div>
                          <div class="fs-11 fw-semibold text-primary">4.5M đ</div>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center p-2 border-bottom">
                      <div class="customer-avatar me-2">
                        <div
                          class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center"
                          style="width: 35px; height: 35px;">BL</div>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                          <div class="fs-13 fw-semibold">Bùi Ngọc Linh</div>
                          <div class="badge bg-info bg-opacity-10 text-info rounded-pill px-2">Thân thiết</div>
                        </div>
                        <div class="d-flex justify-content-between">
                          <div class="fs-11 text-muted">8 đơn hàng</div>
                          <div class="fs-11 fw-semibold text-primary">3.2M đ</div>
                        </div>
                      </div>
                    </div>
                    <div class="d-flex align-items-center p-2">
                      <div class="customer-avatar me-2">
                        <div
                          class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                          style="width: 35px; height: 35px;">LV</div>
                      </div>
                      <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                          <div class="fs-13 fw-semibold">Lê Văn B</div>
                          <div class="badge bg-info bg-opacity-10 text-info rounded-pill px-2">Thân thiết</div>
                        </div>
                        <div class="d-flex justify-content-between">
                          <div class="fs-11 text-muted">7 đơn hàng</div>
                          <div class="fs-11 fw-semibold text-primary">2.8M đ</div>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>

            <!-- Thời gian đặt hàng -->
            <div class="col-md-4">
              <div class="stats-card">
                <div class="panel-header">
                  <h6><i class="fa-light fa-clock me-2 text-info"></i>Xu hướng đặt hàng</h6>
                  <div class="time-selector">
                    <i class="fa-light fa-calendar-days me-1"></i> Tuần
                  </div>
                </div>
                <div class="text-center mb-3">
                  <div class="fs-12 text-muted mb-0">Thời điểm đặt hàng nhiều nhất</div>
                  <div class="fs-4 fw-bold text-info">18:00 - 20:00</div>
                </div>
                <div style="height: 135px;">
                  <canvas id="orderTimeChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Product -->
        <div class="section product-all">
          <!-- Admin control -->
          <div class="row d-flex justify-content-between">
            <!-- Category -->
            <div class="col-md-2 admin-control-left">
              <div class="col-md-2 admin-control-left w-100">
                <select class="form-select bg-light list-categories" id="the-loai">
                  <!-- <option value="Tất cả" selected>Tất cả</option>
                    <option value="Món chay">Món chay</option>
                    <option value="Món mặn">Món mặn</option>
                    <option value="Món lẩu">Món lẩu</option>
                    <option value="Món ăn vặt">Món ăn vặt</option>
                    <option value="Món tráng miệng">Món tráng miệng</option>
                    <option value="Nước uống">Nước uống</option>
                    <option value="Đã xóa">Đã xóa</option> -->
                </select>
              </div>
            </div>

            <!-- Search -->
            <div class="col-md-6 admin-control-middle">
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="fa-light fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control bg-light border-0 search-input" placeholder="Tìm kiếm tên món..."
                  aria-label="Tìm kiếm" />
              </div>
            </div>

            <!-- Button control -->
            <div class="col-md-4 admin-control-right d-flex justify-content-end">
              <button class="btn btn-primary btn-add-product" data-bs-toggle="modal" data-bs-target="#productModal">
                <i class="fa-light fa-plus"></i>
                Thêm món
              </button>
            </div>
          </div>

          <!-- Product list -->
          <div id="product-list" class="product-list mt-4">
            <!-- <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/nam-dui-ga-chay-toi.jpeg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Nấm đùi gà xào cháy tỏi</h4>
                      <p class="product-text small text-secondary mb-2">
                        Một Món chay ngon miệng với nấm đùi gà thái chân hương,
                        xào săn với lửa và thật nhiều tỏi băm, nêm nếm với mắm
                        và nước tương chay, món ngon đưa cơm và rất dễ ăn cả cho
                        người lớn và trẻ nhỏ.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món chay</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">180.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                        data-bs-toggle="modal"
                        data-bs-target="#editProductModal"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/rau-xao-ngu-sac.png"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Rau xào ngũ sắc</h4>
                      <p class="product-text small text-secondary mb-2">
                        Rau củ quả theo mùa tươi mới xào với nước mắm chay, gia
                        vị để giữ được hương vị ngọt tươi nguyên thủy của rau
                        củ, một món nhiều vitamin và chất khoáng, rất dễ ăn.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món chay</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">180.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/tai-cuon-luoi.jpeg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Tai cuộn lưỡi</h4>
                      <p class="product-text small text-secondary mb-2">
                        Tai heo được cuộn bên trong cùng phần thịt lưỡi heo.
                        Phần tai bên ngoài giòn dai, phần thịt lưỡi bên trong
                        vẫn mềm, có độ ngọt tự nhiên của thịt. Tai cuộn lưỡi
                        được chấm với nước mắm và tiêu đen.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món mặn</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">200.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/tra-dao-chanh-sa.jpg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Trà đào chanh sả</h4>
                      <p class="product-text small text-secondary mb-2">
                        Trà đào chanh sả có vị đậm ngọt thanh của đào, vị chua
                        chua dịu nhẹ của chanh và hương thơm của sả.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Nước uống</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">30.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/ca-chien-gion-mam-thai.jpeg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Cá chiên giòn sốt mắm Thái</h4>
                      <p class="product-text small text-secondary mb-2">
                        Cá chiên giòn mắm Thái được Bếp Hoa chọn lựa từ những
                        chú cá tươi ngon, làm sạch và chế biến cẩn thận rồi đem
                        chiên vàng giòn. Cá chiên ngon là ở độ giòn rụm của cá
                        mà vẫn giữ được độ mềm ẩm của thịt bên trong.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món mặn</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">200.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/khau-nhuc.jpeg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Khâu nhục</h4>
                      <p class="product-text small text-secondary mb-2">
                        Khâu nhục - món ăn cầu kỳ mang phong vị phương Bắc. Làm
                        từ thịt lợn ta, khâu khục được hấp cách thủy trong 6
                        tiếng cùng với rất nhiều loại gia vị. Thịt mềm nhừ, ngọt
                        vị, phần bì trong và dẻo quẹo. Mỡ ngậy ngậy tan chảy
                        ngay khi vừa đưa lên miệng. Hướng dẫn bảo quản: Hâm nóng
                        lại bằng nồi hấp cách thủy hoặc lò vi sóng. Bảo quản
                        trong tủ mát từ 3-5 ngày.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món mặn</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">150.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/dau-hu-xao-nam-chay.png"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Đậu hũ xào nấm chay</h4>
                      <p class="product-text small text-secondary mb-2">
                        Món xào thanh nhẹ ngọt lịm từ rau củ và nấm tươi, thêm
                        chút đậu phụ chiên thái miếng, nêm nếm đậm đà. Ăn kèm
                        cơm trắng hay làm bún trộn rau củ cũng rất hợp.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món chay</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">100.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="list p-10 position-relative text-decoration-none d-flex justify-content-between"
              >
                <div class="row product-item">
                  <div class="col-md-2">
                    <img
                      src="assets/images/products/nam-dui-ga-chay-toi.jpeg"
                      class="product-image rounded"
                      alt=""
                    />
                  </div>
                  <div class="col-md-8">
                    <div class="product-description py-2">
                      <h4 class="mb-1">Nấm đùi gà xào cháy tỏi</h4>
                      <p class="product-text small text-secondary mb-2">
                        Một Món chay ngon miệng với nấm đùi gà thái chân hương,
                        xào săn với lửa và thật nhiều tỏi băm, nêm nếm với mắm
                        và nước tương chay, món ngon đưa cơm và rất dễ ăn cả cho
                        người lớn và trẻ nhỏ.
                      </p>
                      <span
                        class="product-category badge text-secondary rounded-pill"
                        >Món chay</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">180.000 đ</div>
                    <div class="buttons pt-4 fs-6">
                      <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                      >
                        <i class="fa-light fa-pen-to-square"></i>
                      </button>
                      <button
                        class="btn btn-sm btn-danger rounded-circle"
                        title="Xóa"
                      >
                        <i class="fa-regular fa-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div> -->
          </div>
        </div>

        <!-- Customer -->
        <div class="section customer-all">
          <!-- Admin control -->
          <div class="row d-flex justify-content-between mb-4">
            <!-- Category -->
            <div class="col-md-2 admin-control-left">
              <div class="col-md-2 admin-control-left w-100">
                <select class="form-select bg-light" id="the-loai" onchange="showCustomer()">
                  <option value="Tất cả" selected>Tất cả</option>
                  <option value="Hoạt động">Hoạt động</option>
                  <option value="Bị khóa">Bị khóa</option>
                </select>
              </div>
            </div>

            <!-- Search -->
            <div class="col-md-6 admin-control-middle">
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="fa-light fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control bg-light border-0 search-input"
                  placeholder="Tìm kiếm khách hàng..." aria-label="Tìm kiếm" />
              </div>
            </div>

            <!-- Button control -->
            <div class="col-md-4 admin-control-right d-flex justify-content-end">
              <button class="btn btn-primary btn-add-product" data-bs-toggle="modal" data-bs-target="#customerModal">
                <i class="fa-light fa-plus"></i>
                Thêm khách hàng
              </button>
            </div>
          </div>

          <!-- Customer list -->
          <div class="container-fluid p-0">
            <div class="table-responsive">
              <table id="customer-table" class="table text-center">
                <thead>
                  <tr>
                    <th>STT</th>
                    <th>HỌ VÀ TÊN</th>
                    <th>LIÊN HỆ</th>
                    <th>NGÀY THAM GIA</th>
                    <th>TÌNH TRẠNG</th>
                    <th>HÀNH ĐỘNG</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr>
                        <td>1</td>
                        <td>Nguyễn Viết Quyền</td>
                        <td>0987654312</td>
                        <td>15/10/2024</td>
                        <td><span class="status-active">Hoạt động</span></td>
                        <td>
                        <div
                          class="action-buttons d-flex justify-content-center"
                        >
                          <button class="btn-edit btn-edit-customer" data-bs-toggle="modal"
                          data-bs-target="#editCustomerModal">
                            <i
                              class="fa-light fa-pen-to-square"
                            ></i>
                          </button>
                          <button class="btn-delete">
                            <i class="fa-regular fa-trash"></i>
                          </button>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Bùi Ngọc Linh</td>
                        <td>0385438391</td>
                        <td>06/04/2025</td>
                        <td><span class="status-active">Hoạt động</span></td>
                        <td>
                        <div
                          class="action-buttons d-flex justify-content-center"
                        >
                          <button class="btn-edit btn-edit-customer" data-bs-toggle="modal"
                          data-bs-target="#editCustomerModal">
                            <i
                              class="fa-light fa-pen-to-square"
                            ></i>
                          </button>
                          <button class="btn-delete">
                            <i class="fa-regular fa-trash"></i>
                          </button>
                          </div>
                        </td>
                      </tr> -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Order -->
        <div class="section order-all" id="order-all">
          <!-- Admin control -->
          <div class="row d-flex justify-content-between mt-4 mb-4">
            <!-- Category -->
            <div class="col-md-2 admin-control-left">
              <div class="col-md-2 admin-control-left w-100">
                <select class="form-select bg-light" id="order-status">
                  <option value="Tất cả" selected>Tất cả</option>
                  <option value="Đã xử lý">Đã xử lý</option>
                  <option value="Chưa xử lý">Chưa xử lý</option>
                  <option value="Đã hủy">Đã hủy</option>
                </select>
              </div>
            </div>

            <!-- Search -->
            <div class="col-md-6 admin-control-middle">
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="fa-light fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control bg-light border-0 search-input"
                  placeholder="Tìm kiếm mã đơn hàng, khách hàng..." aria-label="Tìm kiếm" />
              </div>
            </div>

            <!-- Button control -->
            <div class="col-md-4 admin-control-right d-flex justify-content-end">
              <a href="create-order.php" class="btn btn-primary btn-add-product">
                <i class="fa-light fa-plus"></i>
                Thêm đơn hàng
              </a>
            </div>
          </div>

          <!-- Order list -->
          <div class="container-fluid p-0">
            <div class="table-responsive">
              <table id="list-orders-table" class="table text-center">
                <thead>
                  <tr>
                    <th>MÃ ĐƠN</th>
                    <th>KHÁCH HÀNG</th>
                    <th>LIÊN HỆ</th>
                    <th>NGÀY ĐẶT</th>
                    <th>TỔNG TIỀN</th>
                    <th>TÌNH TRẠNG</th>
                    <th>HÀNH ĐỘNG</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>DH01</td>
                    <td>Nguyễn Viết Quyền</td>
                    <td>0987654312</td>
                    <td>15/10/2024</td>
                    <td>150.000 ₫</td>
                    <td><span class="status-active">Đã xử lý</span></td>
                    <td>
                      <div class="action-buttons d-flex justify-content-center">
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                          <i class="fa-regular fa-eye"></i>
                        </button>
                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                          <i class="fa-light fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete">
                          <i class="fa-regular fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>DH02</td>
                    <td>Bùi Ngọc Linh</td>
                    <td>0385438391</td>
                    <td>06/04/2025</td>
                    <td>1.050.000 ₫</td>
                    <td><span class="status-unprocessed">Chưa xử lý</span></td>
                    <td>
                      <div class="action-buttons d-flex justify-content-center">
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                          <i class="fa-regular fa-eye"></i>
                        </button>
                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                          <i class="fa-light fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete">
                          <i class="fa-regular fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>

                  <tr>
                    <td>DH03</td>
                    <td>Lê Văn B</td>
                    <td>0951357852</td>
                    <td>31/12/2024</td>
                    <td>950.000 ₫</td>
                    <td><span class="status-canceled">Đã hủy</span></td>
                    <td>
                      <div class="action-buttons d-flex justify-content-center">
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                          <i class="fa-regular fa-eye"></i>
                        </button>
                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                          <i class="fa-light fa-pen-to-square"></i>
                        </button>
                        <button class="btn-delete">
                          <i class="fa-regular fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Statistic -->
        <div class="section statistic-all">
          <!-- Bộ lọc nâng cao -->
          <div class="card mb-4">
            <div class="card-body">
              <form class="row g-3 align-items-end" id="order-statistics-filter">
                <div class="col-md-3">
                  <label for="filter-date-from" class="form-label">Từ ngày</label>
                  <input type="date" class="form-control" id="filter-date-from" name="from_date">
                </div>
                <div class="col-md-3">
                  <label for="filter-date-to" class="form-label">Đến ngày</label>
                  <input type="date" class="form-control" id="filter-date-to" name="to_date">
                </div>
                <div class="col-md-2">
                  <label for="filter-status" class="form-label ms-2">Trạng thái</label>
                  <select class="form-select" id="filter-status" name="status">
                    <option value="">Tất cả</option>
                    <option value="1">Đã xử lý</option>
                    <option value="0">Chưa xử lý</option>
                    <option value="-1">Đã hủy</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="filter-channel" class="form-label ms-2">Nhân viên phụ trách</label>
                  <select class="form-select" id="filter-channel" name="channel">
                    <option value="Nguyễn Viết Quyền">Nguyễn Viết Quyền</option>
                    <option value="Bùi Ngọc Linh">Nguyễn Khắc Cường</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-filter d-flex justify-content-center align-items-center">
                    <i class="fa-light fa-filter-list me-1"></i> Lọc
                  </button>
                </div>
              </form>
            </div>
          </div>

          <div class="mb-4" id="filter-results" style="display: none;">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="card-title mb-0 fw-bold">Kết quả lọc</h6>
                  <div class="d-flex gap-2">
                    <button type="button"
                      class="btn btn-export-excel d-flex justify-content-center align-items-center">
                      <i class="fa-light fa-file-export me-2"></i> Xuất Excel
                    </button>
                    <button type="button"
                      class="btn btn-export-excel d-flex justify-content-center align-items-center">
                      <i class="fa-light fa-print me-1"></i> In
                    </button>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-hover" id="filtered-orders-table">
                    <thead class="table-light">
                      <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Nhân viên phụ trách</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Thống kê tổng quan -->
          <div class="row g-3 mb-4">
            <div class="col-md-2">
              <div class="stat-card-custom" id="total-orders">
                <div class="text-muted">Tổng số đơn</div>
                <div class="fs-4 fw-bold" id="total-orders-value">120</div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="stat-card-custom" id="total-success-orders">
                <div class="text-muted">Đơn thành công</div>
                <div class="fs-4 fw-bold text-success" id="total-success-orders-value">90</div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="stat-card-custom" id="total-unprocessed-orders">
                <div class="text-muted">Đơn chưa xử lý</div>
                <div class="fs-4 fw-bold text-warning" id="total-unprocessed-orders-value">25</div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="stat-card-custom" id="total-canceled-orders">
                <div class="text-muted">Đơn bị hủy</div>
                <div class="fs-4 fw-bold text-danger" id="total-canceled-orders-value">5</div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="stat-card-custom" id="total-revenue">
                <div class="text-muted">Doanh thu</div>
                <div class="fs-4 fw-bold" id="total-revenue-value">12.500.000</div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="stat-card-custom" id="average-revenue">
                <div class="text-muted">Giá trị TB/đơn</div>
                <div class="fs-4 fw-bold" id="total-average-revenue-value">320K đ</div>
              </div>
            </div>
          </div>

          <!-- Biểu đồ phân tích -->
          <div class="row g-4 mb-4">
            <div class="col-md-8">
              <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h6 class="mb-0">Đơn hàng & Doanh thu theo thời gian</h6>
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary active" data-period="day">Ngày</button>
                    <button class="btn btn-outline-secondary" data-period="week">Tuần</button>
                    <button class="btn btn-outline-secondary" data-period="month">Tháng</button>
                  </div>
                </div>
                <canvas id="orderStatisticRevenueChart" height="300"></canvas>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card p-3">
                <h6 class="mb-3">Phân bố trạng thái đơn hàng</h6>
                <canvas id="orderStatisticStatusPieChart" height="300"></canvas>
                <div class="chart-legend mt-3">
                  <div class="legend-item">
                    <span class="legend-color" style="background-color: #28a745;"></span>
                    <span>Hoàn thành (75%)</span>
                  </div>
                  <div class="legend-item">
                    <span class="legend-color" style="background-color: #17a2b8;"></span>
                    <span>Đang giao (12.5%)</span>
                  </div>
                  <div class="legend-item">
                    <span class="legend-color" style="background-color: #ffc107;"></span>
                    <span>Chờ xử lý (8.3%)</span>
                  </div>
                  <div class="legend-item">
                    <span class="legend-color" style="background-color: #dc3545;"></span>
                    <span>Đã hủy (4.2%)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Bảng chi tiết đơn hàng -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="card-title mb-0">Danh sách đơn hàng chi tiết</h6>
                <button class="btn btn-sm btn-export-orders" id="export-orders">
                  <i class="fa-light fa-file-export me-1"></i> Xuất danh sách
                </button>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="orders-table">
                  <thead class="table-light">
                    <tr>
                      <th>Mã đơn</th>
                      <th>Khách hàng</th>
                      <th>Ngày đặt</th>
                      <th>Giá trị</th>
                      <th>Trạng thái</th>
                      <th>Thời gian xử lý</th>
                      <th>Phương thức giao hàng</th>
                      <th>Nhân viên quản lý</th>
                      <th class="text-center">Chi tiết</th>
                    </tr>
                  </thead>
                  <tbody id="orders-detail-table-body">

                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Phân tích chuyên sâu -->
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <div class="card p-3">
                <h6 class="mb-3">Top sản phẩm bán chạy</h6>
                <div class="table-responsive">
                  <table class="table table-sm" id="top-products-table">
                    <thead>
                      <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Doanh thu</th>
                        <th class="text-center">Xu hướng</th>
                      </tr>
                    </thead>
                    <tbody id="top-selling-products-table-body">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card p-3">
                <h6 class="mb-3">Top khách hàng giá trị</h6>
                <div class="table-responsive">
                  <table class="table table-sm" id="top-customers-table">
                    <thead>
                      <tr>
                        <th>Khách hàng</th>
                        <th>Số đơn</th>
                        <th>Tổng chi</th>
                        <th>Xu hướng</th>
                      </tr>
                    </thead>
                    <tbody id="top-customers-table-body">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Cảnh báo & Đề xuất -->
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fa-light fa-triangle-exclamation me-2"></i>
                Có 3 đơn hàng bị hủy trong 24h qua, kiểm tra lý do để cải thiện quy trình!
              </div>
            </div>
            <div class="col-md-6">
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fa-light fa-lightbulb-on me-2"></i>
                Đề xuất: Tăng nhân sự xử lý vào khung giờ 18:00-20:00 do lượng đơn tăng cao.
              </div>
            </div>
          </div>
        </div>

        <!-- Delivery -->
        <div class="section delivery-all">
          <div class="container-fluid p-0">
            <!-- Tab Content -->
            <div class="tab-content mt-4">
              <!-- Tab Chờ giao hàng -->
              <div class="tab-pane fade show active" id="pending-delivery">
                <!-- Bộ lọc nâng cao -->
                <div class="card mb-4">
                  <div class="card-body">
                    <form class="row g-3">
                      <div class="col-md-3">
                        <label class="form-label">Khoảng thời gian</label>
                        <select class="form-select" id="time-range">
                          <option value="all">Tất cả</option>
                          <option value="today">Hôm nay</option>
                          <option value="last_3_days">3 ngày qua</option>
                          <option value="last_7_days">7 ngày qua</option>
                          <option value="last_15_days">15 ngày qua</option>
                          <option value="last_30_days">30 ngày qua</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Khu vực</label>
                        <select class="form-select" id="districts">
                          <option>Tất cả</option>
                          <option>Quận 1</option>
                          <option>Quận 2</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Nhân viên giao</label>
                        <select class="form-select" id="delivery-staff-name">
                          <option>Tất cả</option>
                          <option>Nguyễn Văn A</option>
                          <option>Trần Thị B</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" placeholder="Mã đơn, tên khách hàng...">
                      </div>
                      <div class="col-md-2 d-flex align-items-end mb-1">
                        <button type="submit"
                          class="btn btn-primary me-3 d-flex justify-content-center align-items-center">
                          <i class="fa-light fa-filter-list me-2"></i> Lọc
                        </button>
                        <button type="button"
                          class="btn btn-export-excel d-flex justify-content-center align-items-center">
                          <i class="fa-light fa-file-export me-2"></i> Xuất Excel
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Danh sách đơn hàng -->
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Mã đơn</th>
                          <th>Thời gian đặt</th>
                          <th>Khách hàng</th>
                          <th>Địa chỉ giao hàng</th>
                          <th>Thời gian giao dự kiến</th>
                          <th>Nhân viên giao</th>
                          <th>Trạng thái</th>
                          <th>Thao tác</th>
                        </tr>
                      </thead>
                      <tbody id="delivery-orders-table-body">
                      
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Bản đồ và thông tin -->
              <div class="row mt-4 mb-4">
                <div class="col-md-8">
                  <div class="card">
                    <div class="card-body p-0">
                      <div class="map-header d-flex justify-content-between align-items-center p-3 border-bottom">
                        <h5 class="mb-0 font-weight-bold"><i class="fa-light fa-map-location-dot"></i> Theo dõi vị trí giao hàng</h5>
                      </div>
                      <div id="deliveryMap" style="height: 500px;"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <!-- Thông tin nhân viên đang giao -->
                  <div class="card mb-3">
                    <div class="card-header">
                      <h6 class="mb-0"><i class="fa-light fa-truck"></i> Nhân viên đang giao</h6>
                    </div>
                    <div class="card-body p-0">
                      <div class="delivery-staff-list" id="delivery-staff-list">
                        <div class="staff-item p-3 border-bottom">
                          <div class="d-flex align-items-center">
                            <div class="staff-info flex-grow-1">
                              <h6 class="mb-1">Nguyễn Văn A</h6>
                              <div class="d-flex align-items-center">
                                <span class="status-unprocessed me-2">Đang giao</span>
                                <small class="text-muted">1 đơn hàng</small>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Thêm các nhân viên khác tương tự -->
                      </div>
                    </div>
                  </div>

                  <!-- Thông tin đơn hàng đang giao -->
                  <div class="card mb-3">
                    <div class="card-header">
                      <h6 class="mb-0"><i class="fa-light fa-box"></i> Đơn hàng đang giao</h6>
                    </div>
                    <div class="card-body p-0">
                      <div class="delivery-orders-list" id="delivery-orders-list">
                        <div class="order-item p-3 border-bottom">
                          <div class="d-flex justify-content-between align-items-start">
                            <div>
                              <h6 class="mb-1">#DH123</h6>
                              <p class="mb-1 small">Nguyễn Văn B</p>
                              <p class="mb-0 small text-muted">123 Đường ABC, Quận 1</p>
                            </div>
                            <div class="text-end">
                              <span class="status-unprocessed">Đang giao</span>
                              <div class="small text-muted mt-1">Còn 15 phút</div>
                            </div>
                          </div>
                        </div>
                        <!-- Thêm các đơn hàng khác tương tự -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal add product -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductLabel">
              THÊM MỚI SẢN PHẨM
            </h5>
            <h5 class="modal-title edit-product-e" id="editProductLabel" style="display: none">
              CHỈNH SỬA SẢN PHẨM
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="add-product-form">
              <div class="row d-flex justify-content-center align-items-center">
                <!-- Upload image -->
                <div class="col-md-6 text-center mb-4 mb-md-0">
                  <img src="assets/images/blank-image.png" alt=""
                    class="position-relative upload-image-preview rounded mb-3" />
                  <div class="d-flex justify-content-center align-items-center">
                    <label for="up-hinh-anh" class="btn btn-outline-secondary upload-image-button">
                      <i class="fa-regular fa-cloud-arrow-up me-2"></i>Chọn hình
                      ảnh
                    </label>
                    <input accept="image/jpeg, image/png, image/jpg" id="up-hinh-anh" name="up-hinh-anh" type="file"
                      class="form-control d-none justify-content-center" onchange="uploadImage(this)" />
                  </div>
                </div>

                <!-- Info form -->
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="ten-mon" class="form-label">Tên món</label>
                    <input id="ten-mon" name="ten-mon" type="text" placeholder="Nhập tên món" class="form-control" />
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3 d-flex justify-content-center align-items-center">
                    <label for="chon-mon" class="form-label text-center">Chọn món</label>
                    <select name="category" id="chon-mon" class="form-select">
                      <option>Món chay</option>
                      <option>Món mặn</option>
                      <option>Món lẩu</option>
                      <option>Món ăn vặt</option>
                      <option>Món tráng miệng</option>
                      <option>Nước uống</option>
                    </select>
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3">
                    <label for="gia-moi" class="form-label">Giá bán</label>
                    <input id="gia-moi" name="gia-moi" type="text" placeholder="Nhập giá bán" class="form-control" />
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3">
                    <label for="mo-ta" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="mo-ta" rows="4" placeholder="Nhập mô tả món ăn..."></textarea>
                    <div class="form-text text-danger"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">
              Hủy
            </button>
            <button type="button" class="btn btn-primary add-product-e add-product-button" id="add-product-button">
              <i class="fa-regular fa-plus me-1"></i>Thêm mới
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal edit product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProductModalLabel">
              CHỈNH SỬA SẢN PHẨM
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="edit-product-form">
              <div class="row d-flex justify-content-center align-items-center">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-md-6 text-center mb-4 mb-md-0">
                  <img src="" alt="" id="edit-image-preview"
                    class="position-relative upload-image-preview rounded mb-3" />
                  <div class="d-flex justify-content-center align-items-center">
                    <label for="edit-hinh-anh" class="btn btn-outline-secondary upload-image-button">
                      <i class="fa-regular fa-cloud-arrow-up me-2"></i>Chọn hình
                      ảnh
                    </label>
                    <input accept="image/jpeg, image/png, image/jpg" id="edit-hinh-anh" name="edit-hinh-anh" type="file"
                      class="form-control d-none justify-content-center" onchange="uploadEditImage(this)" />
                  </div>
                </div>

                <!-- Form thông tin sản phẩm -->
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-ten-mon" class="form-label">Tên món</label>
                    <input id="edit-ten-mon" name="edit-ten-mon" type="text" placeholder="Nhập tên món"
                      class="form-control" />
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3 d-flex justify-content-center align-items-center">
                    <label for="edit-chon-mon" class="form-label text-center">Chọn món</label>
                    <select name="edit-category" id="edit-chon-mon" class="form-select">
                      <option>Món chay</option>
                      <option>Món mặn</option>
                      <option>Món lẩu</option>
                      <option>Món ăn vặt</option>
                      <option>Món tráng miệng</option>
                      <option>Nước uống</option>
                    </select>
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3">
                    <label for="edit-gia-moi" class="form-label">Giá bán</label>
                    <input id="edit-gia-moi" name="edit-gia-moi" type="text" placeholder="Nhập giá bán"
                      class="form-control" />
                    <div class="form-text text-danger"></div>
                  </div>

                  <div class="mb-3">
                    <label for="edit-mo-ta" class="form-label">Mô tả</label>
                    <textarea class="form-control" id="edit-mo-ta" rows="4"
                      placeholder="Nhập mô tả món ăn..."></textarea>
                    <div class="form-text text-danger"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">
              Hủy
            </button>
            <button type="button" class="btn btn-primary add-product-button" id="save-edit-button">
              <i class="fa-light fa-pencil me-1"></i>Lưu thay đổi
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal add customer -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-customer">
          <div class="modal-header">
            <h5 class="modal-title add-product-e" id="addCustomerLabel">
              THÊM MỚI KHÁCH HÀNG
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="add-customer-form">
              <div class="row d-flex flex-column">
                <!-- Info form -->
                <div class="form-group">
                  <label for="fullName">Tên đầy đủ</label>
                  <input type="text" class="form-control" id="fullName" placeholder="VD: Viết Quyền" />
                </div>
                <div class="form-group">
                  <label for="phoneNumber">Số điện thoại</label>
                  <input type="text" class="form-control" id="phoneNumber" placeholder="Nhập số điện thoại" />
                </div>
                <div class="form-group">
                  <label for="password">Mật khẩu</label>
                  <input type="password" class="form-control" id="password" placeholder="Nhập mật khẩu" />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">
              Hủy
            </button>
            <button type="button" class="btn btn-primary add-product-e add-product-button" id="add-product-button">
              <i class="fa-regular fa-plus me-1"></i>Thêm mới
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal edit customer -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCustomerLabel">
              CHỈNH SỬA THÔNG TIN
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="edit-customer-form">
              <div class="row">
                <!-- Info form -->
                <div class="form-group mb-3">
                  <label for="fullName">Tên đầy đủ</label>
                  <input type="text" class="form-control" id="fullName" />
                </div>
                <div class="form-group mb-3">
                  <label for="phoneNumber">Số điện thoại</label>
                  <input type="text" class="form-control" id="phoneNumber" />
                </div>
                <div class="form-group mb-3">
                  <label for="password">Mật khẩu</label>
                  <input type="password" class="form-control" id="password" />
                </div>
                <div class="form-group mb-3 d-flex justify-content-start align-items-center">
                  <label class="pe-3" for="user-status">Trạng thái</label>
                  <div>
                    <input type="checkbox" id="user-status" class="switch-input" checked />
                    <label for="user-status" class="switch"></label>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger btn-save-customer" id="save-customer-button">
              <i class="fa-regular fa-floppy-disk me-1"></i>Lưu thông tin
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal order detail -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="orderDetailModalLabel">CHI TIẾT ĐƠN HÀNG</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex justify-content-center align-items-center">
              <!-- Danh sách sản phẩm -->
              <div class="col-md-6 order-products flex-column pe-4">
                <!-- Sản phẩm 1 -->
                <!-- <div class="order-product-item d-flex mb-3">
                  <div class="product-img">
                    <img src="assets/images/products/rau-xao-ngu-sac.png" class="rounded" width="70" height="70" alt="Rau xào ngũ sắc">
                  </div>
                  <div class="product-info ms-3 flex-grow-1">
                    <h6 class="product-name">Rau xào ngũ sắc</h6>
                    <div class="product-note text-muted small">
                      <i class="fa-light fa-comment-dots"></i> Không có ghi chú
                    </div>
                    <div class="product-quantity">SL: 1</div>
                  </div>
                  <div class="product-price text-danger fw-bold">
                    180.000 đ
                  </div>
                </div> -->

                <!-- Sản phẩm 2 -->
                <!-- <div class="order-product-item d-flex mb-3">
                  <div class="product-img">
                    <img src="assets/images/products/banh_lava_pho_mai_nuong.jpeg" class="rounded" width="70" height="70" alt="Bánh lava phô mai nướng">
                  </div>
                  <div class="product-info ms-3 flex-grow-1">
                    <h6 class="product-name">Bánh lava phô mai nướng</h6>
                    <div class="product-note text-muted small">
                      <i class="fa-light fa-comment-dots"></i> Không có ghi chú
                    </div>
                    <div class="product-quantity">SL: 1</div>
                  </div>
                  <div class="product-price text-danger fw-bold">
                    180.000 đ
                  </div>
                </div> -->
              </div>

              <hr>

              <!-- Thông tin đơn hàng -->
              <div class="col-md-6 order-details">
                <div class="row">
                  <!-- Thông tin trái -->
                  <div class="order-info-item d-flex align-items-center mb-3">
                    <i class="fa-regular fa-calendar me-2 text-danger"></i>
                    <div class="info-label">Ngày đặt hàng</div>
                    <div class="info-value ms-auto">05/10/2024</div>
                  </div>

                  <div class="order-info-item d-flex align-items-center mb-3">
                    <i class="fa-light fa-truck me-2 text-danger"></i>
                    <div class="info-label">Hình thức giao</div>
                    <div class="info-value ms-auto">Tự đến lấy</div>
                  </div>

                  <div class="order-info-item d-flex align-items-center mb-3">
                    <i class="fa-light fa-user me-2 text-danger"></i>
                    <div class="info-label">Người nhận</div>
                    <div class="info-value ms-auto">Nguyễn Viết Quyền</div>
                  </div>

                  <!-- Thông tin phải -->
                  <div class="order-info-item d-flex align-items-center mb-3">
                    <i class="fa-light fa-phone me-2 text-danger"></i>
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value ms-auto">0987656789</div>
                  </div>

                  <div class="order-info-item d-flex align-items-center mb-3">
                    <i class="fa-regular fa-clock me-2 text-danger"></i>
                    <div class="info-label">Thời gian giao</div>
                    <div class="info-value ms-auto">05/10/2024</div>
                  </div>

                  <!-- Địa chỉ nhận -->
                  <div class="order-info-item d-flex align-items-start mb-3">
                    <i class="fa-light fa-location-dot me-2 text-danger"></i>
                    <div class="info-label">Địa chỉ nhận</div>
                    <div class="info-value ms-auto text-end">
                      273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà Đông
                    </div>
                  </div>

                  <!-- Ghi chú -->
                  <div class="order-info-item d-flex align-items-start mb-3">
                    <i class="fa-light fa-comment me-2 text-danger"></i>
                    <div class="info-label">Ghi chú</div>
                    <div class="info-value ms-auto text-end">
                      Không có ghi chú
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>

            <!-- Tổng thanh toán -->
            <div class="order-total">
              <div class="d-flex align-items-center">
                <div class="total-label">Thành tiền</div>
                <div class="total-value ms-auto text-danger fw-bold">360.000 đ</div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-success">
              <i class="fa-light fa-check-circle me-1"></i> Đã xử lý
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal edit order -->
    <div class="modal fade" id="editOrderModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">CHỈNH SỬA ĐƠN HÀNG</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="edit-order-form">
              <!-- Thông tin khách hàng -->
              <h6 class="mb-3">Thông tin khách hàng</h6>
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label>Người nhận</label>
                  <input type="text" class="form-control" id="edit-recipient" value="Nguyễn Viết Quyền">
                </div>
                <div class="col-md-6 mb-3">
                  <label>Số điện thoại</label>
                  <input type="text" class="form-control" id="edit-phone" value="0987656789">
                </div>
                <div class="col-md-12 mb-3">
                  <label>Địa chỉ</label>
                  <input type="text" class="form-control" id="edit-address"
                    value="273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà Đông">
                </div>
                <div class="col-md-6 mb-3">
                  <label>Hình thức giao</label>
                  <select class="form-select" id="edit-delivery-method">
                    <option selected>Tự đến lấy</option>
                    <option>Giao hàng tận nơi</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Thời gian giao</label>
                  <input type="date" class="form-control" id="edit-delivery-date" value="2024-10-05">
                </div>
              </div>

              <!-- Danh sách sản phẩm -->
              <h6 class="mb-3">Danh sách sản phẩm</h6>
              <div class="edit-product-list mb-4">
                <!-- Sản phẩm 1 -->
                <div class="edit-product-row d-flex align-items-center mb-2 p-2 border rounded">
                  <div class="d-flex align-items-center flex-grow-1">
                    <img src="assets/images/products/rau-xao-ngu-sac.png" width="50" height="50" class="rounded me-2">
                    <div>Rau xào ngũ sắc</div>
                  </div>
                  <div class="d-flex align-items-center quantity-control-wrapper">
                    <div class="quantity-control d-flex align-items-center">
                      <button type="button" class="btn-quantity btn-decrease">
                        <i class="fa-solid fa-minus"></i>
                      </button>
                      <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center"
                        value="1" min="1">
                      <button type="button" class="btn-quantity btn-increase">
                        <i class="fa-solid fa-plus"></i>
                      </button>
                    </div>
                    <div class="text-danger fw-bold ms-4 me-2" data-price="180000">180.000 đ</div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                      <i class="fa-light fa-trash"></i>
                    </button>
                  </div>
                </div>

                <!-- Sản phẩm 2 -->
                <div class="edit-product-row d-flex align-items-center mb-2 p-2 border rounded">
                  <div class="d-flex align-items-center flex-grow-1">
                    <img src="assets/images/products/banh_lava_pho_mai_nuong.jpeg" width="50" height="50"
                      class="rounded me-2">
                    <div>Bánh lava phô mai nướng</div>
                  </div>
                  <div class="d-flex align-items-center quantity-control-wrapper">
                    <div class="quantity-control d-flex align-items-center">
                      <button type="button" class="btn-quantity btn-decrease">
                        <i class="fa-solid fa-minus"></i>
                      </button>
                      <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center"
                        value="1" min="1">
                      <button type="button" class="btn-quantity btn-increase">
                        <i class="fa-solid fa-plus"></i>
                      </button>
                    </div>
                    <div class="text-danger fw-bold ms-4 me-2" data-price="180000">180.000 đ</div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                      <i class="fa-light fa-trash"></i>
                    </button>
                  </div>
                </div>

                <!-- Thêm sản phẩm mới -->
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-product-btn">
                  <i class="fa-light fa-plus me-1"></i> Thêm sản phẩm
                </button>
              </div>

              <!-- Ghi chú -->
              <div class="form-group mb-3">
                <label>Ghi chú</label>
                <textarea class="form-control" id="edit-note" rows="2"></textarea>
              </div>

              <!-- Tổng tiền -->
              <div class="d-flex justify-content-end">
                <div class="fw-bold me-2">Tổng tiền:</div>
                <div class="text-danger fw-bold">360.000 đ</div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="d-flex align-items-center me-auto">
              <span class="me-2">Tổng thanh toán:</span>
              <span class="text-danger fw-bold">360.000 đ</span>
            </div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-danger" id="save-order-changes">
              <i class="fa-regular fa-floppy-disk me-1"></i> Lưu thay đổi
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab chọn khách hàng sẵn có -->
    <div class="tab-pane fade" id="existing-customer" role="tabpanel" aria-labelledby="existing-customer-tab">
      <!-- Tìm kiếm khách hàng -->
      <div class="input-group mb-3">
        <span class="input-group-text bg-light border-0">
          <i class="fa-light fa-magnifying-glass"></i>
        </span>
        <input type="text" class="form-control bg-light border-0" id="search-customer"
          placeholder="Tìm kiếm khách hàng...">
      </div>

      <!-- Danh sách khách hàng -->
      <div class="customer-list scrollable-list mb-4">
        <div class="list-group">
          <a href="#" class="list-group-item list-group-item-action customer-item" data-name="Nguyễn Viết Quyền"
            data-phone="0987654312" data-address="273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà Đông">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">Nguyễn Viết Quyền</h6>
              <small class="text-muted">15/10/2024</small>
            </div>
            <p class="mb-1 text-muted small">0987654312</p>
            <small class="text-truncate d-inline-block w-100">273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà Đông</small>
          </a>

          <a href="#" class="list-group-item list-group-item-action customer-item" data-name="Bùi Ngọc Linh"
            data-phone="0385438391" data-address="12 Trần Hưng Đạo, Phường Phạm Ngũ Lão, Quận 1, TP.HCM">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">Bùi Ngọc Linh</h6>
              <small class="text-muted">06/04/2025</small>
            </div>
            <p class="mb-1 text-muted small">0385438391</p>
            <small class="text-truncate d-inline-block w-100">12 Trần Hưng Đạo, Phường Phạm Ngũ Lão, Quận 1,
              TP.HCM</small>
          </a>

          <a href="#" class="list-group-item list-group-item-action customer-item" data-name="Lê Văn B"
            data-phone="0951357852" data-address="45 Lê Duẩn, Bến Nghé, Quận 1, TP.HCM">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">Lê Văn B</h6>
              <small class="text-muted">31/12/2024</small>
            </div>
            <p class="mb-1 text-muted small">0951357852</p>
            <small class="text-truncate d-inline-block w-100">45 Lê Duẩn, Bến Nghé, Quận 1, TP.HCM</small>
          </a>
        </div>
      </div>

      <!-- Thông tin khách hàng đã chọn -->
      <div class="selected-customer-info alert alert-info d-none">
        <h6 class="alert-heading">Khách hàng đã chọn</h6>
        <p class="mb-1"><i class="fa-light fa-user me-2"></i> <span id="selected-customer-name"></span></p>
        <p class="mb-1"><i class="fa-light fa-phone me-2"></i> <span id="selected-customer-phone"></span></p>
        <p class="mb-0"><i class="fa-light fa-location-dot me-2"></i> <span id="selected-customer-address"></span></p>
      </div>
    </div>
  </div>

  <!-- Thông tin giao hàng -->
  <h6 class="mb-3 mt-4">Thông tin giao hàng</h6>
  <div class="row mb-4">
    <div class="col-md-6 mb-3">
      <label class="required">Hình thức giao</label>
      <select class="form-select" id="add-delivery-method">
        <option value="" selected disabled>Chọn hình thức giao</option>
        <option>Tự đến lấy</option>
        <option>Giao hàng tận nơi</option>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label class="required">Thời gian giao</label>
      <input type="datetime-local" class="form-control" id="add-delivery-date">
    </div>
  </div>

  <!-- Danh sách sản phẩm -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">Danh sách sản phẩm</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" id="add-product-to-order">
      <i class="fa-light fa-plus me-1"></i> Thêm sản phẩm
    </button>
  </div>

  <div class="add-product-list mb-4">
    <!-- Sản phẩm sẽ được thêm vào đây bằng JavaScript -->
    <div class="text-center text-muted py-4" id="no-products">
      <i class="fa-light fa-cart-shopping fs-1 mb-2 d-block"></i>
      <p class="mb-0">Chưa có sản phẩm nào trong đơn hàng</p>
    </div>
  </div>

  <!-- Ghi chú -->
  <div class="form-group mb-3">
    <label>Ghi chú</label>
    <textarea class="form-control" id="add-note" rows="2" placeholder="Nhập ghi chú cho đơn hàng nếu có"></textarea>
  </div>

  <!-- Tổng tiền -->
  <div class="d-flex justify-content-end">
    <div class="fw-bold me-2">Tổng tiền:</div>
    <div class="text-danger fw-bold" id="order-total-body">0 đ</div>
  </div>
  </form>
  </div>
  <div class="modal-footer">
    <div class="d-flex align-items-center me-auto">
      <span class="me-2">Tổng thanh toán:</span>
      <span class="text-danger fw-bold" id="order-total-footer">0 đ</span>
    </div>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
    <button type="button" class="btn btn-danger" id="save-new-order">
      <i class="fa-regular fa-plus me-1"></i> Tạo đơn hàng
    </button>
  </div>
  </div>
  </div>
  </div>

  <!-- Modal select product -->
  <div class="modal fade" id="selectProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #dc3545;">
          <h5 class="modal-title">CHỌN SẢN PHẨM</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Thanh tìm kiếm và lọc -->
          <div class="row mb-4">
            <!-- Danh mục/Category -->
            <div class="col-md-3">
              <select class="form-select bg-light" id="product-category-filter">
                <option value="Tất cả" selected>Tất cả</option>
                <option value="Món chay">Món chay</option>
                <option value="Món mặn">Món mặn</option>
                <option value="Món lẩu">Món lẩu</option>
                <option value="Món ăn vặt">Món ăn vặt</option>
                <option value="Món tráng miệng">Món tráng miệng</option>
                <option value="Nước uống">Nước uống</option>
              </select>
            </div>

            <!-- Tìm kiếm -->
            <div class="col-md-9">
              <div class="input-group">
                <span class="input-group-text bg-light border-0">
                  <i class="fa-light fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control bg-light border-0" id="product-search"
                  placeholder="Tìm kiếm tên sản phẩm...">
                <button class="btn bg-light border-0" id="clear-search" type="button" style="display: none;">
                  <i class="fa-light fa-times"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Danh sách sản phẩm có thể chọn -->
          <div class="product-select-list" style="max-height: 400px; overflow-y: auto;">
            <!-- Sản phẩm 1 -->
            <div class="product-select-item d-flex align-items-center p-3 border rounded mb-2" data-category="Món chay"
              data-price="180000">
              <img src="assets/images/products/rau-xao-ngu-sac.png" width="60" height="60" class="rounded me-3"
                alt="Rau xào ngũ sắc">
              <div class="flex-grow-1">
                <h6 class="mb-1">Rau xào ngũ sắc</h6>
                <div class="d-flex align-items-center">
                  <span class="badge bg-light text-secondary me-2">Món chay</span>
                  <span class="text-danger fw-bold">180.000 đ</span>
                </div>
              </div>
              <div class="d-flex align-items-center ms-3">
                <div class="quantity-control d-flex align-items-center me-3">
                  <button type="button" class="btn-quantity btn-decrease">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" value="1"
                    min="1" style="width: 40px;">
                  <button type="button" class="btn-quantity btn-increase">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
                <button class="btn btn-sm btn-danger add-to-order">
                  <i class="fa-light fa-plus me-1"></i>Thêm
                </button>
              </div>
            </div>

            <!-- Sản phẩm 2 -->
            <div class="product-select-item d-flex align-items-center p-3 border rounded mb-2" data-category="Món chay"
              data-price="100000">
              <img src="assets/images/products/dau-hu-xao-nam-chay.png" width="60" height="60" class="rounded me-3"
                alt="Đậu hũ xào nấm chay">
              <div class="flex-grow-1">
                <h6 class="mb-1">Đậu hũ xào nấm chay</h6>
                <div class="d-flex align-items-center">
                  <span class="badge bg-light text-secondary me-2">Món chay</span>
                  <span class="text-danger fw-bold">100.000 đ</span>
                </div>
              </div>
              <div class="d-flex align-items-center ms-3">
                <div class="quantity-control d-flex align-items-center me-3">
                  <button type="button" class="btn-quantity btn-decrease">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" value="1"
                    min="1" style="width: 40px;">
                  <button type="button" class="btn-quantity btn-increase">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
                <button class="btn btn-sm btn-danger add-to-order">
                  <i class="fa-light fa-plus me-1"></i>Thêm
                </button>
              </div>
            </div>

            <!-- Sản phẩm 3 -->
            <div class="product-select-item d-flex align-items-center p-3 border rounded mb-2" data-category="Món mặn"
              data-price="200000">
              <img src="assets/images/products/tai-cuon-luoi.jpeg" width="60" height="60" class="rounded me-3"
                alt="Tai cuộn lưỡi">
              <div class="flex-grow-1">
                <h6 class="mb-1">Tai cuộn lưỡi</h6>
                <div class="d-flex align-items-center">
                  <span class="badge bg-light text-secondary me-2">Món mặn</span>
                  <span class="text-danger fw-bold">200.000 đ</span>
                </div>
              </div>
              <div class="d-flex align-items-center ms-3">
                <div class="quantity-control d-flex align-items-center me-3">
                  <button type="button" class="btn-quantity btn-decrease">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" value="1"
                    min="1" style="width: 40px;">
                  <button type="button" class="btn-quantity btn-increase">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
                <button class="btn btn-sm btn-danger add-to-order">
                  <i class="fa-light fa-plus me-1"></i>Thêm
                </button>
              </div>
            </div>

            <!-- Sản phẩm 4 -->
            <div class="product-select-item d-flex align-items-center p-3 border rounded mb-2" data-category="Nước uống"
              data-price="30000">
              <img src="assets/images/products/tra-dao-chanh-sa.jpg" width="60" height="60" class="rounded me-3"
                alt="Trà đào chanh sả">
              <div class="flex-grow-1">
                <h6 class="mb-1">Trà đào chanh sả</h6>
                <div class="d-flex align-items-center">
                  <span class="badge bg-light text-secondary me-2">Nước uống</span>
                  <span class="text-danger fw-bold">30.000 đ</span>
                </div>
              </div>
              <div class="d-flex align-items-center ms-3">
                <div class="quantity-control d-flex align-items-center me-3">
                  <button type="button" class="btn-quantity btn-decrease">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" value="1"
                    min="1" style="width: 40px;">
                  <button type="button" class="btn-quantity btn-increase">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
                <button class="btn btn-sm btn-danger add-to-order">
                  <i class="fa-light fa-plus me-1"></i>Thêm
                </button>
              </div>
            </div>
          </div>

          <!-- Không tìm thấy sản phẩm -->
          <div class="no-products-found text-center py-4 d-none">
            <i class="fa-light fa-search fs-1 text-muted mb-3"></i>
            <p class="text-muted">Không tìm thấy sản phẩm phù hợp</p>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fa-light fa-times me-1"></i> Đóng
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal select customer -->
  <div class="modal fade" id="selectCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">CHỌN KHÁCH HÀNG</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Tìm kiếm -->
          <div class="input-group mb-3">
            <span class="input-group-text bg-light border-0">
              <i class="fa-light fa-magnifying-glass"></i>
            </span>
            <input type="text" class="form-control bg-light border-0" placeholder="Tìm kiếm khách hàng...">
          </div>

          <!-- Danh sách khách hàng -->
          <div class="customer-select-list">
            <div class="customer-select-item d-flex align-items-center p-2 border rounded mb-2">
              <div class="flex-grow-1">
                <h6 class="mb-0">Nguyễn Viết Quyền</h6>
                <div class="text-muted small">0987654312</div>
              </div>
              <button class="btn btn-sm btn-outline-primary">
                Chọn
              </button>
            </div>

            <div class="customer-select-item d-flex align-items-center p-2 border rounded mb-2">
              <div class="flex-grow-1">
                <h6 class="mb-0">Bùi Ngọc Linh</h6>
                <div class="text-muted small">0385438391</div>
              </div>
              <button class="btn btn-sm btn-outline-primary">
                Chọn
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Model delivery assign -->
  <div class="modal fade" id="assignDeliveryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Phân công giao hàng cho đơn <span id="assign-order-id"></span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="assign-delivery-form">
            <div class="mb-3">
              <label class="form-label w-100">Nhân viên giao hàng</label>
              <select class="form-select" id="delivery-staff" required>
                <option value="">Chọn nhân viên giao hàng</option>
                <!-- JS sẽ render -->
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label w-100">Thời gian giao dự kiến</label>
              <input type="datetime-local" class="form-control" id="estimated-delivery-time" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" id="assign-delivery-btn">
            <i class="fa-light fa-check me-1"></i> Xác nhận phân công
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Chatbot -->
  <div class="chatbot-button" id="chatbot-btn">
    <i class="fa-light fa-comments"></i>
  </div>

  <div class="chatbot-container" id="chatbot-container">
    <div class="chatbot-header">
      <h5>Chatbot hỗ trợ trực tuyến</h5>
      <button class="close-chatbot" id="close-chatbot">
        <i class="fa-light fa-times"></i>
      </button>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
      <div class="message message-bot">
        Xin chào! Tôi là trợ lý ảo. Tôi có thể giúp gì cho bạn?
      </div>
    </div>
    <div class="chatbot-input">
      <input type="text" id="user-input" placeholder="Nhập tin nhắn của bạn..." />
      <button id="send-message">
        <i class="fa-light fa-paper-plane"></i>
      </button>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <!-- Custom JS -->
  <script src="assets/js/script.js"></script>
  <script src="assets/js/notification.js"></script>
  <script src="assets/js/toast-message.js"></script>
  <script src="assets/js/product.js"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/customer.js"></script>
  <script src="assets/js/order.js"></script>
  <script src="assets/js/chatbot.js"></script>
  <script src="assets/js/statistic.js"></script>
</body>

</html>