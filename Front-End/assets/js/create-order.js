$(document).ready(function () {
  let selectedCustomer = null;
  let orderProducts = [];

  // Khai báo biến global để theo dõi thời gian request cuối cùng
  let lastRequestTime = 0;

  // CÁC HÀM TIỆN ÍCH (UTILITY FUNCTIONS)
  // Định dạng số tiền theo định dạng tiền tệ Việt Nam
  function formatCurrency(amount) {
    return new Intl.NumberFormat("vi-VN").format(amount) + " đ";
  }

  // Format thời gian theo định dạng HH:mm dd/MM/yyyy
  function formatDateTime(dateTimeStr) {
    const dt = new Date(dateTimeStr);
    const hours = dt.getHours().toString().padStart(2, "0");
    const minutes = dt.getMinutes().toString().padStart(2, "0");
    const day = dt.getDate().toString().padStart(2, "0");
    const month = (dt.getMonth() + 1).toString().padStart(2, "0");
    const year = dt.getFullYear();

    return `${hours}:${minutes} ${day}/${month}/${year}`;
  }

  // Tính tổng tiền đơn hàng từ danh sách sản phẩm
  function calculateTotalAmount() {
    return orderProducts.reduce((total, product) => {
      return total + product.price * product.quantity;
    }, 0);
  }

  // Load danh sách khách hàng khi chuyển sang tab khách hàng đã có
  $("#existing-customer-tab").on("click", function () {
    loadCustomers();
  });

  // Hàm tải danh sách khách hàng
  function loadCustomers() {
    showLoading();
    $.ajax({
      url: "http://localhost/Order Management/Back-End/api/CustomerApi.php",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          displayCustomers(response.data);
        } else {
          displayNoCustomers();
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải danh sách khách hàng:", error);
        displayNoCustomers();
        hideLoading();
      },
    });
  }

  // Hiển thị danh sách khách hàng
  function displayCustomers(customers) {
    const customerList = $(".customer-list .list-group");
    customerList.empty();

    if (customers && customers.length > 0) {
      customers.forEach((customer) => {
        const customerItem = `
          <a href="#" class="list-group-item list-group-item-action customer-item"
             role="option"
             aria-selected="false"
             data-id="${customer.id}"
             data-name="${customer.name}"
             data-phone="${customer.phone}"
             data-address="${customer.address}">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">${customer.name}</h6>
            </div>
            <div class="d-flex align-items-center mb-1">
              <i class="fa-light fa-phone icon-spacing text-muted"></i>
              <span class="text-muted small">${customer.phone}</span>
            </div>
            <div class="d-flex">
              <i class="fa-light fa-location-dot icon-spacing text-muted"></i>
              <small class="text-muted text-truncate">${customer.address}</small>
            </div>
          </a>
        `;
        customerList.append(customerItem);
      });
    } else {
      displayNoCustomers();
    }
  }

  // Hiển thị thông báo không có khách hàng
  function displayNoCustomers() {
    const customerList = $(".customer-list .list-group");
    customerList.html(`
      <div class="text-center text-muted py-4">
        <i class="fa-light fa-users fs-1 mb-2 d-block"></i>
        <p class="mb-0">Không có khách hàng nào</p>
      </div>
    `);
  }

  // Hiển thị loading
  function showLoading() {
    if ($("#loadingOverlay").length === 0) {
      $("body").append(`
        <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255,255,255,0.7); z-index: 9999; display: flex; justify-content: center; align-items: center;">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
          </div>
        </div>
      `);
    } else {
      $("#loadingOverlay").show();
    }
  }

  // Ẩn loading
  function hideLoading() {
    $("#loadingOverlay").hide();
  }

  // Xử lý khi chọn khách hàng từ danh sách
  $(document).on("click", ".customer-item", function (e) {
    e.preventDefault();

    selectedCustomer = {
      id: $(this).data("id"),
      name: $(this).data("name"),
      phone: $(this).data("phone"),
      address: $(this).data("address"),
    };

    // Hiển thị thông tin đã chọn
    $("#selected-customer-name").text(selectedCustomer.name);
    $("#selected-customer-phone").text(selectedCustomer.phone);
    $("#selected-customer-address").text(selectedCustomer.address);

    // Hiển thị thông báo đã chọn
    $(".selected-customer-info")
      .removeClass("d-none alert-danger")
      .addClass("alert-primary");
    $(".selected-customer-info .alert-heading").text("Khách hàng đã chọn");

    // Loại bỏ trạng thái active của tất cả các mục
    $(".customer-item").removeClass("active");

    // Đánh dấu mục đã chọn
    $(this).addClass("active");
  });

  // Xử lý tìm kiếm khách hàng
  $("#search-customer").on("keyup", function () {
    const searchText = $(this).val().toLowerCase();

    $(".customer-item").each(function () {
      const name = $(this).data("name").toLowerCase();
      const phone = $(this).data("phone").toLowerCase();

      if (name.indexOf(searchText) > -1 || phone.indexOf(searchText) > -1) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });

  // Xóa tìm kiếm
  $("#clear-search").on("click", function () {
    $("#search-customer").val("");
    $(".customer-item").show();
  });

  // Mở modal chọn sản phẩm
  $("#open-product-modal").on("click", function () {
    loadProducts();
    $("#selectProductModal").modal("show");
  });

  // Hàm tải danh sách sản phẩm
  function loadProducts() {
    showLoading();
    $.ajax({
      url: "http://localhost/Order Management/Back-End/api/ProductApi.php",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          displayProducts(response.data);
        } else {
          displayNoProducts();
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải danh sách sản phẩm:", error);
        displayNoProducts();
        hideLoading();
      },
    });
  }

  // Hiển thị danh sách sản phẩm trong modal
  function displayProducts(products) {
    const productList = $(".product-select-list");
    productList.empty();

    if (products && products.length > 0) {
      products.forEach((product) => {
        const formattedPrice =
          parseInt(product.price).toLocaleString("vi-VN") + " đ";
        const productItem = `
          <div class="product-select-item d-flex align-items-center p-3 border rounded mb-2"
               data-category="${product.category_name}"
               data-price="${product.price}"
               data-id="${product.id}">
            <img src="${product.image_url}"
                 width="70"
                 height="70"
                 class="rounded me-3"
                 alt="${product.name}" />
            <div class="flex-grow-1">
              <h6 class="mb-1">${product.name}</h6>
              <div class="d-flex align-items-center">
                <span class="badge bg-light text-secondary me-2">${product.category_name}</span>
                <span class="text-danger fw-bold">${formattedPrice}</span>
              </div>
            </div>
            <div class="d-flex align-items-center ms-3">
              <div class="quantity-control d-flex align-items-center me-3">
                <button type="button" class="btn-quantity btn-decrease">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number"
                       class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom"
                       value="0"
                       min="0" />
                <button type="button" class="btn-quantity btn-increase">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
              <button class="btn btn-sm btn-danger add-to-order">
                <i class="fa-light fa-plus icon-spacing"></i>Thêm
              </button>
            </div>
          </div>
        `;
        productList.append(productItem);
      });

      // Khởi tạo nút tăng/giảm số lượng
      initializeQuantityControls();
    } else {
      displayNoProducts();
    }
  }

  // Hiển thị không có sản phẩm
  function displayNoProducts() {
    const productList = $(".product-select-list");
    productList.html(`
      <div class="text-center text-muted py-4">
        <i class="fa-light fa-box-open fs-1 mb-2 d-block"></i>
        <p class="mb-0">Không có sản phẩm nào</p>
      </div>
    `);
  }

  // Khởi tạo hàm tăng/giảm số lượng
  function initializeQuantityControls() {
    // Tránh trùng lặp
    $("#selectProductModal").off("click.quantityBtn", ".btn-increase");
    $("#selectProductModal").off("click.quantityBtn", ".btn-decrease");
    $("#selectProductModal").off("change.quantityInput", ".quantity-input");

    // Đăng ký lại các event handler
    $("#selectProductModal").on(
      "click.quantityBtn",
      ".btn-increase",
      function (e) {
        e.stopPropagation();
        const input = $(this).siblings(".quantity-input");
        const currentValue = parseInt(input.val()) || 0;
        input.val(currentValue + 1);
      }
    );

    $("#selectProductModal").on(
      "click.quantityBtn",
      ".btn-decrease",
      function (e) {
        e.stopPropagation();
        const input = $(this).siblings(".quantity-input");
        const currentValue = parseInt(input.val()) || 0;
        if (currentValue > 0) {
          input.val(currentValue - 1);
        }
      }
    );

    $("#selectProductModal").on(
      "change.quantityInput",
      ".quantity-input",
      function (e) {
        e.stopPropagation();
        if (parseInt($(this).val()) < 0 || isNaN(parseInt($(this).val()))) {
          $(this).val(0);
        }
      }
    );
  }

  // Xử lý thêm sản phẩm vào đơn hàng
  $(document).on("click", ".add-to-order", function () {
    const productItem = $(this).closest(".product-select-item");
    const productId = productItem.data("id");
    const productName = productItem.find("h6").text();
    const productCategory = productItem.find(".badge").text();
    const productPrice = parseInt(productItem.data("price"));
    const quantity = parseInt(productItem.find(".quantity-input").val());
    const productImage = productItem.find("img").attr("src");
    // Kiểm tra sản phẩm đã tồn tại trong đơn hàng chưa
    const productExists = orderProducts.findIndex(
      (p) => p.name === productName
    );

    if (productExists > -1) {
      // Cập nhật số lượng nếu sản phẩm đã tồn tại
      orderProducts[productExists].quantity += quantity;
    } else {
      orderProducts.push({
        id: productId,
        name: productName,
        category: productCategory,
        price: productPrice,
        quantity: quantity,
        image: productImage,
      });
    }

    // Hiệu ứng nút
    const buttonElement = $(this);
    buttonElement
      .html('<i class="fa-light fa-check icon-spacing"></i>Đã thêm')
      .addClass("btn-success")
      .removeClass("btn-danger");

    setTimeout(function () {
      buttonElement
        .html('<i class="fa-light fa-plus icon-spacing"></i>Thêm')
        .removeClass("btn-success")
        .addClass("btn-danger");
    }, 1500);

    // Cập nhật
    updateSelectedProductCount();
    renderOrderProducts();
  });

  // Xử lý hoàn tất chọn sản phẩm
  $(".complete-selection").on("click", function () {
    $("#selectProductModal").modal("hide");
  });

  // Xử lý xóa sản phẩm khỏi đơn hàng
  $(document).on("click", ".remove-product", function () {
    const productRow = $(this).closest(".order-product-item");
    const productName = productRow.find(".product-name").text();

    // Xóa sản phẩm khỏi mảng
    orderProducts = orderProducts.filter((p) => p.name !== productName);
    productRow.fadeOut(300, function () {
      $(this).remove();
      updateOrderSummary();

      // Hiển thị thông báo nếu không còn sản phẩm
      if (orderProducts.length === 0) {
        $("#no-products").fadeIn(300);
      }
    });
  });

  // Cập nhật số lượng sản phẩm được chọn
  function updateSelectedProductCount() {
    $(".selected-product-count").text(orderProducts.length);
  }

  // Cập nhật tổng tiền đơn hàng
  function updateOrderSummary() {
    let totalQuantity = 0;
    let totalAmount = 0;
    orderProducts.forEach((product) => {
      totalQuantity += product.quantity;
      totalAmount += product.price * product.quantity;
    });

    $("#total-quantity").text(totalQuantity);
    $("#total-amount").text(formatCurrency(totalAmount));
  }

  // Hiển thị sản phẩm trong đơn hàng
  function renderOrderProducts() {
    $("#no-products").hide();
    $(".add-product-list .order-product-item").remove();

    // Thêm từng sản phẩm vào danh sách
    orderProducts.forEach((product) => {
      const formattedPrice = formatCurrency(product.price);
      const totalPrice = formatCurrency(product.price * product.quantity);

      const productHtml = `
        <div class="order-product-item d-flex align-items-center p-3 border rounded mb-2">
            <div class="product-img">
                <img src="${product.image}" class="rounded" width="60" height="60" alt="${product.name}">
            </div>
            <div class="product-info ms-3 flex-grow-1">
                <h6 class="product-name mb-1">${product.name}</h6>
                <div class="d-flex align-items-center">
                    <span class="badge bg-light text-secondary me-2">${product.category}</span>
                    <span class="text-danger">${formattedPrice}</span>
                </div>
            </div>
            <div class="quantity-wrapper d-flex align-items-center">
                <div class="quantity-label me-2">SL:</div>
                <div class="quantity-control me-3">
                    <button type="button" class="btn-quantity btn-decrease">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center quantity-input-custom" 
                        value="${product.quantity}" min="1" data-product="${product.name}">
                    <button type="button" class="btn-quantity btn-increase">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="text-danger fw-bold me-3">${totalPrice}</div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                    <i class="fa-light fa-trash"></i>
                </button>
            </div>
        </div>
      `;
      $(".add-product-list").append(productHtml);
    });

    // Cập nhật tổng tiền
    updateOrderSummary();
  }

  // Xử lý nút tăng giảm số lượng trong đơn hàng
  $(document).on("click", ".order-product-item .btn-increase", function () {
    const input = $(this).siblings(".quantity-input");
    const currentValue = parseInt(input.val()) || 0;
    input.val(currentValue + 1).trigger("change");
  });

  $(document).on("click", ".order-product-item .btn-decrease", function () {
    const input = $(this).siblings(".quantity-input");
    const currentValue = parseInt(input.val()) || 0;
    if (currentValue > 1) {
      input.val(currentValue - 1).trigger("change");
    }
  });

  $(document).on("change", ".order-product-item .quantity-input", function () {
    const productName = $(this).data("product");
    let newQuantity = parseInt($(this).val());
    if (newQuantity < 1 || isNaN(newQuantity)) {
      newQuantity = 1;
      $(this).val(1);
    }

    // Cập nhật số lượng trong mảng
    const productIndex = orderProducts.findIndex((p) => p.name === productName);
    if (productIndex > -1) {
      orderProducts[productIndex].quantity = newQuantity;
      const totalPrice = formatCurrency(
        orderProducts[productIndex].price * newQuantity
      );
      $(this)
        .closest(".quantity-wrapper")
        .find(".text-danger.fw-bold")
        .text(totalPrice);
      updateOrderSummary();
    }
  });

  // Lọc sản phẩm theo danh mục
  $("#product-category-filter").on("change", function () {
    const categoryId = $(this).val();
    if (categoryId === "0") {
      loadProducts();
    } else {
      loadProductsByCategory(categoryId);
    }
  });

  // Lọc sản phẩm theo danh mục
  function loadProductsByCategory(categoryId) {
    $.ajax({
      url:
        "http://localhost/Order Management/Back-End/api/ProductApi.php?categoryId=" +
        categoryId,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          displayProducts(response.data);
        } else {
          displayNoProducts();
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải danh sách sản phẩm:", error);
        displayNoProducts();
      },
    });
  }

  // Tìm kiếm sản phẩm
  let searchTimeout;
  $("#product-search").on("keyup", function () {
    const searchText = $(this).val().trim();

    // Xóa timeout cũ
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }

    // Nếu ô tìm kiếm trống, load lại tất cả sản phẩm
    if (!searchText) {
      loadProducts();
      $("#clear-search").hide();
      return;
    }

    // Hiển thị nút xóa tìm kiếm
    $("#clear-search").show();

    // Sau 300ms mới gọi API
    searchTimeout = setTimeout(() => {
      showLoading();
      $.ajax({
        url:
          "http://localhost/Order Management/Back-End/api/ProductApi.php?keyword=" +
          encodeURIComponent(searchText),
        type: "GET",
        dataType: "json",
        success: function (response) {
          if (response.status === 200 && response.data.length > 0) {
            displayProducts(response.data);
            $(".no-products-found").addClass("d-none");
          } else {
            $(".product-select-list").empty();
            $(".no-products-found").removeClass("d-none");
          }
          hideLoading();
        },
        error: function (xhr, status, error) {
          console.error("Lỗi khi tìm kiếm sản phẩm:", error);
          $(".product-select-list").empty();
          $(".no-products-found").removeClass("d-none");
          hideLoading();
        },
      });
    }, 300);
  });

  // Xử lý xóa tìm kiếm sản phẩm
  $("#clear-search").on("click", function () {
    $("#product-search").val("");
    loadProducts();
    $(".no-products-found").addClass("d-none");
    $(this).hide();
  });

  // Xử lý lưu đơn hàng
  $("#save-new-order").on("click", function () {
    let isValid = true;
    const isNewCustomer = $("#new-customer-tab").hasClass("active");

    if (isNewCustomer) {
      const customerName = $("#add-recipient").val();
      const customerPhone = $("#add-phone").val();
      const customerAddress = $("#add-address").val();

      if (!customerName) {
        $("#add-recipient").addClass("is-invalid");
        isValid = false;
      } else {
        $("#add-recipient").removeClass("is-invalid");
      }

      if (!customerPhone) {
        $("#add-phone").addClass("is-invalid");
        isValid = false;
      } else {
        $("#add-phone").removeClass("is-invalid");
      }

      if (!customerAddress) {
        $("#add-address").addClass("is-invalid");
        isValid = false;
      } else {
        $("#add-address").removeClass("is-invalid");
      }
    } else {
      if (!selectedCustomer) {
        $(".selected-customer-info")
          .removeClass("d-none alert-primary")
          .addClass("alert-danger");
        $(".selected-customer-info .alert-heading").text(
          "Chưa chọn khách hàng"
        );
        isValid = false;
      }
    }

    const deliveryMethod = $("#add-delivery-method").val();
    const deliveryTime = $("#add-delivery-date").val();
    const note = $("#add-note").val() || "";

    if (!deliveryMethod) {
      $("#add-delivery-method").addClass("is-invalid");
      isValid = false;
    } else {
      $("#add-delivery-method").removeClass("is-invalid");
    }

    if (!deliveryTime) {
      $("#add-delivery-date").addClass("is-invalid");
      isValid = false;
    } else {
      $("#add-delivery-date").removeClass("is-invalid");
    }

    if (orderProducts.length === 0) {
      showNotification({
        title: "Error",
        message: "Vui lòng thêm ít nhất một sản phẩm vào đơn hàng",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    if (isValid) {
      let orderData;
      const address = isNewCustomer
        ? $("#add-address").val()
        : selectedCustomer.address;

      getCoordinatesFromAddress(address)
        .then(function (coordinates) {
          if (isNewCustomer) {
            orderData = {
              isNewCustomer: true,
              receiverName: $("#add-recipient").val(),
              receiverPhone: $("#add-phone").val(),
              receiverAddress: address,
              destinationLatitude: coordinates.results[0].geometry.lat,
              destinationLongitude: coordinates.results[0].geometry.lng,
              deliveryMethod: $("#add-delivery-method option:selected").text(),
              deliveryTime: $("#add-delivery-date").val(),
              employeeId: Math.floor(Math.random() * (3 - 1 + 1) + 1),
              note: note,
              orderItems: orderProducts.map((product) => ({
                productId: product.id,
                quantity: product.quantity,
              })),
            };
          } else {
            orderData = {
              customerId: selectedCustomer.id,
              receiverAddress: address,
              destinationLatitude: coordinates.results[0].geometry.lat,
              destinationLongitude: coordinates.results[0].geometry.lng,
              deliveryMethod: $("#add-delivery-method option:selected").text(),
              deliveryTime: $("#add-delivery-date").val(),
              employeeId: Math.floor(Math.random() * (3 - 1 + 1) + 1),
              note: note,
              orderItems: orderProducts.map((product) => ({
                productId: product.id,
                quantity: product.quantity,
              })),
            };
          }

          createOrder(orderData);
        })
        .catch((error) => {
          console.error("Lỗi khi lấy tọa độ:", error);
          showNotification({
            title: "Error",
            message: "Không thể lấy được tọa độ từ địa chỉ đã nhập",
            type: "error",
            duration: 3000,
          });
          hideLoading();
        });
    } else {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });

  function createOrder(orderData) {
    showLoading();
    $.ajax({
      url: "http://localhost/Order Management/Back-End/api/OrderApi.php",
      type: "POST",
      dataType: "json",
      contentType: "application/json",
      data: JSON.stringify(orderData),
      success: function (response) {
        if (response.status === 200 || response.status === 201) {
          showNotification({
            title: "Success",
            message: "Tạo đơn hàng thành công!",
            type: "success",
            duration: 2000,
          });
          setTimeout(function () {
            window.location.href =
              "http://localhost/Order%20Management/Front-End/index.php";
          }, 2000);
        } else {
          showNotification({
            title: "Error",
            message: "Lỗi khi tạo đơn hàng",
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tạo đơn hàng:", error);
        showNotification({
          title: "Error",
          message: "Đã xảy ra lỗi khi tạo đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  const apiKey = "1a170613a7d0482b82948a8858017760";

  function getCoordinatesFromAddress(address) {
    // Chuẩn hóa địa chỉ
    let searchAddress = address.trim();

    // Thêm "Hà Nội" nếu chưa có
    if (!searchAddress.toLowerCase().includes("hà nội")) {
      searchAddress += ", Hà Nội";
    }

    // Thêm "Vietnam" nếu chưa có
    if (!searchAddress.toLowerCase().includes("vietnam")) {
      searchAddress += ", Vietnam";
    }

    return $.ajax({
      url: "https://api.opencagedata.com/geocode/v1/json",
      method: "GET",
      data: {
        q: searchAddress,
        key: apiKey,
      },
      success: function (response) {
        if (response.results && response.results.length > 0) {
          return {
            latitude: response.results[0].geometry.lat,
            longitude: response.results[0].geometry.lng,
          };
        }
        throw new Error("Không tìm thấy tọa độ cho địa chỉ này");
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi lấy tọa độ:", error);
        throw error;
      },
    });
  }
});
