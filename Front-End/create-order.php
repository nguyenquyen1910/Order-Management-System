<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tạo đơn hàng mới - Vy Food</title>
    <link
      href="assets/images/favicon.png"
      rel="icon"
      type="image/x-icon"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link
      href="assets/fonts/font-awesome-pro-v6-6.2.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="assets/fonts/font-awesome-pro-v6-6.2.0/css/all.min.css"
      rel="stylesheet"
    />
    <!-- Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWTx7bREpM5B6JKdbzOvMW-RRlhkukmVE"></script>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/create-order.css"/>
  </head>
  <body>
    <div id="toast"></div>
    <header class="header"></header>
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
                  <i class="fa-light fa-shopping-cart icon-spacing"></i>
                  Tạo đơn hàng mới
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
                      <i class="fa-light fa-plus icon-spacing"></i>Tạo mới
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
          <form id="add-order-form" aria-labelledby="order-form-title">
            <!-- Tabs cho việc chọn khách hàng -->
            <div class="card card-custom">
              <div class="card-header card-header-custom">
                <i class="fa-light fa-user icon-spacing"></i>
                <h6 class="mb-0 fw-bold">THÔNG TIN KHÁCH HÀNG</h6>
              </div>
              <div class="card-body">
                <ul
                  class="nav nav-pills nav-justified mb-3"
                  id="customerTabs"
                  role="tablist"
                >
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link active"
                      id="new-customer-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#new-customer"
                      type="button"
                      role="tab"
                      aria-controls="new-customer"
                      aria-selected="true"
                    >
                      <i class="fa-light fa-user-plus icon-spacing"></i> Khách
                      hàng mới
                    </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button
                      class="nav-link"
                      id="existing-customer-tab"
                      data-bs-toggle="tab"
                      data-bs-target="#existing-customer"
                      type="button"
                      role="tab"
                      aria-controls="existing-customer"
                      aria-selected="false"
                    >
                      <i class="fa-light fa-address-book icon-spacing"></i>
                      Khách hàng đã có
                    </button>
                  </li>
                </ul>

                <div class="tab-content" id="customerTabsContent">
                  <!-- Tab nhập thông tin khách hàng mới -->
                  <div
                    class="tab-pane fade show active"
                    id="new-customer"
                    role="tabpanel"
                    aria-labelledby="new-customer-tab"
                  >
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="add-recipient" class="form-label required"
                          >Người nhận</label
                        >
                        <div class="input-group">
                          <span class="input-group-text"
                            ><i class="fa-light fa-user"></i
                          ></span>
                          <input
                            type="text"
                            class="form-control"
                            id="add-recipient"
                            name="recipient"
                            placeholder="Nhập tên người nhận"
                            aria-required="true"
                          />
                        </div>
                        <div class="invalid-feedback">
                          Vui lòng nhập tên người nhận
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="add-phone" class="form-label required"
                          >Số điện thoại</label
                        >
                        <div class="input-group">
                          <span class="input-group-text"
                            ><i class="fa-light fa-phone"></i
                          ></span>
                          <input
                            type="tel"
                            class="form-control"
                            id="add-phone"
                            name="phone"
                            placeholder="Nhập số điện thoại"
                            pattern="[0-9]{10}"
                            aria-required="true"
                          />
                        </div>
                        <div class="invalid-feedback">
                          Vui lòng nhập số điện thoại hợp lệ (10 số)
                        </div>
                      </div>
                      <div class="col-md-12 mb-3">
                        <label for="add-address" class="form-label required"
                          >Địa chỉ</label
                        >
                        <div class="input-group">
                          <span class="input-group-text"
                            ><i class="fa-light fa-location-dot"></i
                          ></span>
                          <input
                            type="text"
                            class="form-control"
                            id="add-address"
                            name="address"
                            placeholder="Nhập địa chỉ giao hàng"
                            aria-required="true"
                          />
                        </div>
                        <div class="invalid-feedback">
                          Vui lòng nhập địa chỉ giao hàng
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Tab chọn khách hàng sẵn có -->
                  <div
                    class="tab-pane fade"
                    id="existing-customer"
                    role="tabpanel"
                    aria-labelledby="existing-customer-tab"
                  >
                    <!-- Tìm kiếm khách hàng -->
                    <div class="input-group mb-3">
                      <span class="input-group-text">
                        <i class="fa-light fa-magnifying-glass"></i>
                      </span>
                      <input
                        type="text"
                        class="form-control"
                        id="search-customer"
                        placeholder="Tìm kiếm theo tên hoặc số điện thoại..."
                        aria-label="Tìm kiếm khách hàng"
                      />
                      <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="clear-search"
                      >
                        <i class="fa-light fa-times"></i>
                      </button>
                    </div>

                    <!-- Danh sách khách hàng -->
                    <div class="customer-list scrollable-list mb-3">
                      <div
                        class="list-group"
                        role="listbox"
                        aria-label="Danh sách khách hàng"
                      >
                        <a
                          href="#"
                          class="list-group-item list-group-item-action customer-item"
                          role="option"
                          aria-selected="false"
                          data-name="Nguyễn Viết Quyền"
                          data-phone="0987654312"
                          data-address="273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà Đông"
                        >
                          <span class="mb-1">Nguyễn Viết Quyền</span>
                          <div class="d-flex align-items-center mb-1">
                            <i
                              class="fa-light fa-phone icon-spacing text-muted"
                            ></i>
                            <span class="text-muted small">0987654312</span>
                          </div>
                          <div class="d-flex">
                            <i
                              class="fa-light fa-location-dot icon-spacing text-muted"
                            ></i>
                            <small class="text-muted text-truncate"
                              >273 Nguyễn Văn Trỗi, phường Mộ Lao, quận Hà
                              Đông</small
                            >
                          </div>
                        </a>

                        <a
                          href="#"
                          class="list-group-item list-group-item-action customer-item"
                          role="option"
                          aria-selected="false"
                          data-name="Bùi Ngọc Linh"
                          data-phone="0385438391"
                          data-address="12 Trần Hưng Đạo, Phường Phạm Ngũ Lão, Quận 1, TP.HCM"
                        >
                          <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Bùi Ngọc Linh</h6>
                          </div>
                          <div class="d-flex align-items-center mb-1">
                            <i
                              class="fa-light fa-phone icon-spacing text-muted"
                            ></i>
                            <span class="text-muted small">0385438391</span>
                          </div>
                          <div class="d-flex">
                            <i
                              class="fa-light fa-location-dot icon-spacing text-muted"
                            ></i>
                            <small class="text-muted text-truncate"
                              >12 Trần Hưng Đạo, Phường Phạm Ngũ Lão, Quận 1,
                              TP.HCM</small
                            >
                          </div>
                        </a>

                        <a
                          href="#"
                          class="list-group-item list-group-item-action customer-item"
                          role="option"
                          aria-selected="false"
                          data-name="Lê Văn B"
                          data-phone="0951357852"
                          data-address="45 Lê Duẩn, Bến Nghé, Quận 1, TP.HCM"
                        >
                          <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Lê Văn B</h6>
                          </div>
                          <div class="d-flex align-items-center mb-1">
                            <i
                              class="fa-light fa-phone icon-spacing text-muted"
                            ></i>
                            <span class="text-muted small">0951357852</span>
                          </div>
                          <div class="d-flex">
                            <i
                              class="fa-light fa-location-dot icon-spacing text-muted"
                            ></i>
                            <small class="text-muted text-truncate"
                              >45 Lê Duẩn, Bến Nghé, Quận 1, TP.HCM</small
                            >
                          </div>
                        </a>
                      </div>
                    </div>

                    <!-- Thông tin khách hàng đã chọn -->
                    <div
                      class="selected-customer-info alert alert-primary d-none"
                      role="alert"
                    >
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                          <i
                            class="fa-light fa-circle-check fs-4 icon-spacing"
                          ></i>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="alert-heading mb-1">Khách hàng đã chọn</h6>
                          <p class="mb-1">
                            <i class="fa-light fa-user icon-spacing"></i>
                            <span id="selected-customer-name"></span>
                          </p>
                          <p class="mb-1">
                            <i class="fa-light fa-phone icon-spacing"></i>
                            <span id="selected-customer-phone"></span>
                          </p>
                          <p class="mb-0">
                            <i
                              class="fa-light fa-location-dot icon-spacing"
                            ></i>
                            <span id="selected-customer-address"></span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="card card-custom">
              <div class="card-header card-header-custom">
                <i class="fa-light fa-truck icon-spacing"></i>
                <h6 class="mb-0 fw-bold">THÔNG TIN GIAO HÀNG</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="add-delivery-method" class="form-label required"
                      >Hình thức giao</label
                    >
                    <select
                      class="form-select"
                      id="add-delivery-method"
                      name="delivery_method"
                      aria-required="true"
                    >
                      <option value="" selected disabled>
                        Chọn hình thức giao
                      </option>
                      <option value="pickup">Tự đến lấy</option>
                      <option value="delivery">Giao hàng tận nơi</option>
                      <option value="standard">Giao hàng tiêu chuẩn</option>
                    </select>
                    <div class="invalid-feedback">
                      Vui lòng chọn hình thức giao hàng
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="add-delivery-date" class="form-label required"
                      >Thời gian giao</label
                    >
                    <input
                      type="datetime-local"
                      class="form-control"
                      id="add-delivery-date"
                      name="delivery_date"
                      aria-required="true"
                    />
                    <div class="invalid-feedback">
                      Vui lòng chọn thời gian giao hàng
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="add-note" class="form-label">Ghi chú</label>
                    <textarea
                      class="form-control"
                      id="add-note"
                      name="note"
                      rows="2"
                      placeholder="Nhập ghi chú cho đơn hàng nếu có"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card card-custom">
              <div
                class="card-header card-header-custom justify-content-between"
              >
                <div class="d-flex align-items-center">
                  <i class="fa-light fa-cart-shopping icon-spacing"></i>
                  <h6 class="mb-0 fw-bold">DANH SÁCH SẢN PHẨM</h6>
                </div>
                <button
                  type="button"
                  class="btn btn-add-product-order"
                  id="open-product-modal"
                >
                  <i class="fa-light fa-plus icon-spacing text-white"></i> Thêm
                  sản phẩm
                </button>
              </div>
              <div class="card-body">
                <div class="product-added-notification"></div>
                <div class="add-product-list">
                  <!-- Sản phẩm sẽ được thêm vào đây bằng JavaScript -->
                  <div class="text-center text-muted py-4" id="no-products">
                    <i class="fa-light fa-cart-shopping fs-1 mb-2 d-block"></i>
                    <p class="mb-0">Chưa có sản phẩm nào trong đơn hàng</p>
                    <p class="small text-muted mt-2">
                      Nhấn "Thêm sản phẩm" để bắt đầu tạo đơn
                    </p>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="text-muted">
                    Tổng số lượng: <span id="total-quantity">0</span> sản phẩm
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="fw-bold icon-spacing">Tổng tiền:</div>
                    <div class="text-danger fw-bold fs-5" id="total-amount">
                      0 đ
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nút tạo đơn hàng -->
            <div class="d-flex justify-content-end mb-5 mt-4">
              <a
                href="index.php"
                class="btn btn-outline-secondary btn-rounded"
              >
                <i class="fa-light fa-times icon-spacing"></i> Hủy
              </a>
              <button
                type="button"
                class="btn btn-danger btn-rounded shadow-sm btn-action"
                id="save-new-order"
              >
                <i class="fa-light fa-check icon-spacing"></i> Tạo đơn hàng
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal select product -->
    <div class="modal fade" id="selectProductModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header text-white modal-header-danger">
            <h5 class="modal-title">CHỌN SẢN PHẨM</h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <!-- Thanh tìm kiếm và lọc -->
            <div class="row mb-4">
              <!-- Danh mục/Category -->
              <div class="col-md-3">
                <select
                  class="form-select bg-light"
                  id="product-category-filter"
                >
                  <option value="0" selected>Tất cả</option>
                  <option value="1">Món chay</option>
                  <option value="2">Món mặn</option>
                  <option value="3">Món lẩu</option>
                  <option value="4">Món ăn vặt</option>
                  <option value="5">Món tráng miệng</option>
                  <option value="6">Nước uống</option>
                </select>
              </div>

              <!-- Tìm kiếm -->
              <div class="col-md-9">
                <div class="input-group">
                  <span class="input-group-text bg-light border-0">
                    <i class="fa-light fa-magnifying-glass"></i>
                  </span>
                  <input
                    type="text"
                    class="form-control bg-light border-0"
                    id="product-search"
                    placeholder="Tìm kiếm tên sản phẩm..."
                  />
                  <button
                    class="btn bg-light border-0"
                    id="clear-search"
                    type="button"
                    style="display: none"
                  >
                    <i class="fa-light fa-times"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Danh sách sản phẩm có thể chọn -->
            <div class="product-select-list product-list-container">
              <!-- Sản phẩm 1 -->
              <div
                class="product-select-item d-flex align-items-center p-3 border rounded mb-2"
                data-category="Món chay"
                data-price="180000"
              >
                <img
                  src="assets/images/products/rau-xao-ngu-sac.png"
                  width="60"
                  height="60"
                  class="rounded me-3"
                  alt="Rau xào ngũ sắc"
                />
                <div class="flex-grow-1">
                  <h6 class="mb-1">Rau xào ngũ sắc</h6>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light text-secondary me-2"
                      >Món chay</span
                    >
                    <span class="text-danger fw-bold">180.000 đ</span>
                  </div>
                </div>
                <div class="d-flex align-items-center ms-3">
                  <div class="quantity-control d-flex align-items-center me-3">
                    <button type="button" class="btn-quantity btn-decrease">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <input
                      type="number"
                      class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom"
                      value="0"
                      min="0"
                    />
                    <button type="button" class="btn-quantity btn-increase">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                  </div>
                  <button class="btn btn-sm btn-danger add-to-order">
                    <i class="fa-light fa-plus icon-spacing"></i>Thêm
                  </button>
                </div>
              </div>

              <!-- Sản phẩm 2 -->
              <div
                class="product-select-item d-flex align-items-center p-3 border rounded mb-2"
                data-category="Món chay"
                data-price="100000"
              >
                <img
                  src="assets/images/products/dau-hu-xao-nam-chay.png"
                  width="60"
                  height="60"
                  class="rounded me-3"
                  alt="Đậu hũ xào nấm chay"
                />
                <div class="flex-grow-1">
                  <h6 class="mb-1">Đậu hũ xào nấm chay</h6>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light text-secondary me-2"
                      >Món chay</span
                    >
                    <span class="text-danger fw-bold">100.000 đ</span>
                  </div>
                </div>
                <div class="d-flex align-items-center ms-3">
                  <div class="quantity-control d-flex align-items-center me-3">
                    <button type="button" class="btn-quantity btn-decrease">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <input
                      type="number"
                      class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom"
                      value="0"
                      min="0"
                    />
                    <button type="button" class="btn-quantity btn-increase">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                  </div>
                  <button class="btn btn-sm btn-danger add-to-order">
                    <i class="fa-light fa-plus icon-spacing"></i>Thêm
                  </button>
                </div>
              </div>

              <!-- Sản phẩm 3 -->
              <div
                class="product-select-item d-flex align-items-center p-3 border rounded mb-2"
                data-category="Món mặn"
                data-price="200000"
              >
                <img
                  src="assets/images/products/tai-cuon-luoi.jpeg"
                  width="60"
                  height="60"
                  class="rounded me-3"
                  alt="Tai cuộn lưỡi"
                />
                <div class="flex-grow-1">
                  <h6 class="mb-1">Tai cuộn lưỡi</h6>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light text-secondary me-2"
                      >Món mặn</span
                    >
                    <span class="text-danger fw-bold">200.000 đ</span>
                  </div>
                </div>
                <div class="d-flex align-items-center ms-3">
                  <div class="quantity-control d-flex align-items-center me-3">
                    <button type="button" class="btn-quantity btn-decrease">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <input
                      type="number"
                      class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom"
                      value="0"
                      min="0"
                    />
                    <button type="button" class="btn-quantity btn-increase">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                  </div>
                  <button class="btn btn-sm btn-danger add-to-order">
                    <i class="fa-light fa-plus icon-spacing"></i>Thêm
                  </button>
                </div>
              </div>

              <!-- Sản phẩm 4 -->
              <div
                class="product-select-item d-flex align-items-center p-3 border rounded mb-2"
                data-category="Nước uống"
                data-price="30000"
              >
                <img
                  src="assets/images/products/tra-dao-chanh-sa.jpg"
                  width="60"
                  height="60"
                  class="rounded me-3"
                  alt="Trà đào chanh sả"
                />
                <div class="flex-grow-1">
                  <h6 class="mb-1">Trà đào chanh sả</h6>
                  <div class="d-flex align-items-center">
                    <span class="badge bg-light text-secondary me-2"
                      >Nước uống</span
                    >
                    <span class="text-danger fw-bold">30.000 đ</span>
                  </div>
                </div>
                <div class="d-flex align-items-center ms-3">
                  <div class="quantity-control d-flex align-items-center me-3">
                    <button type="button" class="btn-quantity btn-decrease">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <input
                      type="number"
                      class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom"
                      value="0"
                      min="0"
                    />
                    <button type="button" class="btn-quantity btn-increase">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                  </div>
                  <button class="btn btn-sm btn-danger add-to-order">
                    <i class="fa-light fa-plus icon-spacing"></i>Thêm
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
            <div
              class="d-flex w-100 justify-content-between align-items-center"
            >
              <span class="text-muted small"
                >Đã chọn: <span class="selected-product-count">0</span> sản
                phẩm</span
              >
              <div>
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
                  <i class="fa-light fa-times icon-spacing"></i> Đóng
                </button>
                <button type="button" class="btn btn-danger complete-selection">
                  <i class="fa-light fa-check icon-spacing"></i> Hoàn tất chọn
                  sản phẩm
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/toast-message.js"></script>
    <script src="assets/js/create-order.js"></script>
    <script src="assets/js/notification.js"></script>
  </body>
</html>
