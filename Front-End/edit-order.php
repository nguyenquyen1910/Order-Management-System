<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chỉnh sửa đơn hàng - Vy Food</title>
    <link
      href="assets/images/favicon.png"
      rel="icon"
      type="image/x-icon"
    />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link
      href="assets/fonts/font-awesome-pro-v6-6.2.0/css/all.min.css"
      rel="stylesheet"
    />
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/create-order.css" />
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
            <img
              src="assets/images/favicon.png"
              alt="Logo"
              class="img-fluid"
            />
            <img
              src="assets/images/admin/vy-food-title.png"
              alt="Logo"
              class="img-fluid"
            />
          </div>

          <!-- Navigation -->
          <nav class="nav flex-column mb-2 sidebar-nav pt-4 flex-grow-1">
            <a class="nav-link w-100" href="index.php">
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
            <a class="nav-link active w-100" href="#">
              <i class="fa-light fa-basket-shopping"></i>
              Đơn hàng
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
              Nguyễn Viết Quyền
            </a>
            <a class="nav-link w-100" href="#">
              <i class="fa-light fa-arrow-right-from-bracket"></i>
              Đăng xuất
            </a>
          </nav>
        </div>

        <!-- Content -->
        <div class="col-md-10 content pt-4">
          <!-- Header and breadcrumbs -->
          <div class="order-header">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h2 class="mb-2 fw-bold text-danger">
                  <i class="fa-light fa-edit icon-spacing"></i>
                  Chỉnh sửa đơn hàng
                </h2>
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                      <a
                        href="index.php"
                        class="text-decoration-none"
                      >
                        <i class="fa-light fa-house-chimney icon-spacing"></i
                        >Trang chủ
                      </a>
                    </li>
                    <li class="breadcrumb-item">
                      <a
                        href="index.php"
                        class="text-decoration-none"
                      >
                        <i class="fa-light fa-basket-shopping icon-spacing"></i
                        >Đơn hàng
                      </a>
                    </li>
                    <li
                      class="breadcrumb-item active fw-medium"
                      aria-current="page"
                    >
                      <i class="fa-light fa-edit icon-spacing"></i>Chỉnh sửa
                    </li>
                  </ol>
                </nav>
              </div>
              <div>
                <a
                  href="index.php"
                  class="btn btn-outline-secondary btn-rounded"
                >
                  <i class="fa-light fa-arrow-left icon-spacing"></i> Quay lại
                </a>
              </div>
            </div>
          </div>

          <!-- Order Form -->
          <form id="edit-order-form" aria-labelledby="order-form-title">
            <input type="hidden" id="order-id" value="" />

            <!-- Thông tin khách hàng -->
            <div class="card card-custom">
              <div class="card-header card-header-custom">
                <i class="fa-light fa-user icon-spacing"></i>
                <h6 class="mb-0 fw-bold">THÔNG TIN KHÁCH HÀNG</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="edit-recipient" class="form-label required"
                      >Người nhận</label
                    >
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-user"></i
                      ></span>
                      <input
                        type="text"
                        class="form-control"
                        id="edit-recipient"
                        name="recipient"
                        placeholder="Nhập tên người nhận"
                        aria-required="true"
                      />
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="edit-phone" class="form-label required"
                      >Số điện thoại</label
                    >
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-phone"></i
                      ></span>
                      <input
                        type="tel"
                        class="form-control"
                        id="edit-phone"
                        name="phone"
                        placeholder="Nhập số điện thoại"
                        pattern="[0-9]{10}"
                        aria-required="true"
                      />
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="edit-address" class="form-label required"
                      >Địa chỉ</label
                    >
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-location-dot"></i
                      ></span>
                      <input
                        type="text"
                        class="form-control"
                        id="edit-address"
                        name="address"
                        placeholder="Nhập địa chỉ giao hàng"
                        aria-required="true"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="card card-custom mt-4">
              <div class="card-header card-header-custom">
                <i class="fa-light fa-truck icon-spacing"></i>
                <h6 class="mb-0 fw-bold">THÔNG TIN GIAO HÀNG</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label
                      for="edit-delivery-method"
                      class="form-label required"
                      >Hình thức giao hàng</label
                    >
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-truck-ramp-box"></i
                      ></span>
                      <select
                        class="form-select"
                        id="edit-delivery-method"
                        name="deliveryMethod"
                        aria-required="true"
                      >
                        <option value="" selected disabled>
                          Chọn hình thức giao hàng
                        </option>
                        <option value="Giao hàng tiêu chuẩn">
                          Giao hàng tiêu chuẩn
                        </option>
                        <option value="Giao hàng nhanh">Giao hàng nhanh</option>
                        <option value="Tự đến lấy">Tự đến lấy</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="edit-delivery-date" class="form-label required"
                      >Thời gian giao hàng</label
                    >
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-calendar"></i
                      ></span>
                      <input
                        type="date"
                        class="form-control"
                        id="edit-delivery-date"
                        name="deliveryDate"
                        aria-required="true"
                      />
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="edit-note" class="form-label">Ghi chú</label>
                    <div class="input-group">
                      <span class="input-group-text"
                        ><i class="fa-light fa-comment"></i
                      ></span>
                      <textarea
                        class="form-control"
                        id="edit-note"
                        name="note"
                        placeholder="Nhập ghi chú cho đơn hàng (nếu có)"
                        rows="3"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card card-custom mt-4">
              <div
                class="card-header card-header-custom d-flex justify-content-between align-items-center"
              >
                <div>
                  <i class="fa-light fa-cart-shopping icon-spacing"></i>
                  <h6 class="mb-0 fw-bold d-inline">DANH SÁCH SẢN PHẨM</h6>
                </div>
                <button
                  type="button"
                  class="btn btn-sm btn-primary d-flex"
                  id="add-product-btn"
                >
                  <i class="fa-light fa-plus icon-spacing text-white"></i> Thêm
                  sản phẩm
                </button>
              </div>
              <div class="card-body">
                <div class="edit-product-list">
                  <!-- Danh sách sản phẩm sẽ được thêm vào đây -->
                </div>

                <!-- Tổng tiền -->
                <div class="d-flex justify-content-end mt-3">
                  <div class="total-amount-display">
                    <span class="total-label">Tổng tiền:</span>
                    <span class="total-amount text-danger fw-bold">0 đ</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nút lưu -->
            <div class="d-flex justify-content-center mt-4 mb-5">
              <button
                type="button"
                id="update-order-btn"
                class="btn btn-primary btn-lg d-flex align-items-center justify-content-center"
              >
                <i class="fa-light fa-save icon-spacing"></i> Lưu thay đổi
              </button>
              <a
                href="index.php"
                class="btn btn-outline-secondary btn-lg ms-3 d-flex align-items-center justify-content-center"
              >
                <i class="fa-light fa-times icon-spacing"></i> Hủy
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal chọn sản phẩm -->
    <div
      class="modal fade"
      id="productModal"
      tabindex="-1"
      aria-labelledby="productModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Chọn sản phẩm</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <span class="input-group-text"
                ><i class="fa-light fa-magnifying-glass"></i
              ></span>
              <input
                type="text"
                class="form-control"
                id="product-search"
                placeholder="Tìm kiếm sản phẩm..."
              />
            </div>
            <div class="product-list">
              <!-- Danh sách sản phẩm sẽ được thêm vào đây -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="assets/js/edit-order.js"></script>
    <script src="assets/js/toast-message.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/script.js"></script>
  </body>
</html>
