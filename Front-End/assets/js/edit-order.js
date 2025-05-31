$(document).ready(function () {
  const apiUrl = "http://localhost/Order Management/Back-End/api/OrderApi.php";
  const productApiUrl = "http://localhost/Order Management/Back-End/api/ProductApi.php";

  // Lấy ID đơn hàng từ URL
  const urlParams = new URLSearchParams(window.location.search);
  const orderId = urlParams.get("id");

  if (!orderId) {
    showNotification({
      title: "Error",
      message: "Không tìm thấy ID đơn hàng",
      type: "error",
      duration: 3000,
    });
    return;
  }

  // Lưu ID đơn hàng vào form
  $("#order-id").val(orderId);

  // Tải thông tin đơn hàng
  loadOrderDetails(orderId);

  // Đăng ký sự kiện
  $("#add-product-btn").on("click", openProductModal);
  $("#update-order-btn").on("click", saveOrderChanges);

  // Tải danh sách sản phẩm
  function loadProducts() {
    $.ajax({
      url: productApiUrl,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          displayProductsInModal(response.data);
        } else {
          $(".product-list").html("<p>Không có sản phẩm nào</p>");
        }
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải danh sách sản phẩm:", error);
        $(".product-list").html("<p>Lỗi khi tải danh sách sản phẩm</p>");
      },
    });
  }

  // Hiển thị sản phẩm trong modal
  function displayProductsInModal(products) {
    let html = "";

    products.forEach(function (product) {
      // Xử lý đường dẫn ảnh - loại bỏ dấu "/" ở đầu nếu có
      let imgSrc = product.image_url || "";
      if (imgSrc && imgSrc.startsWith("/")) {
        imgSrc = imgSrc.substring(1);
      }

      html += `
              <div class="product-item d-flex align-items-center border-bottom py-2" data-id="${
                product.id
              }" data-name="${product.name}" data-price="${
        product.price
      }" data-image="${imgSrc}">
                  <img src="${imgSrc}" class="rounded" width="60" height="60" alt="${
        product.name
      }" onerror="this.src='Front-End/assets/img/default-product.jpg'">
                  <div class="ms-3 flex-grow-1">
                      <h6 class="mb-1">${product.name}</h6>
                      <div class="text-muted small">${
                        product.category_name || ""
                      }</div>
                  </div>
                  <div class="text-danger fw-bold me-3">${formatPrice(
                    product.price
                  )} đ</div>
                  <button class="btn btn-sm btn-primary select-product">
                      <i class="fa-light fa-plus"></i>
                  </button>
              </div>
            `;
    });

    $(".product-list").html(html);

    // Đăng ký sự kiện chọn sản phẩm
    $(".select-product").on("click", function () {
      const productItem = $(this).closest(".product-item");
      addProductToOrder({
        id: productItem.data("id"),
        name: productItem.data("name"),
        price: productItem.data("price"),
        image: productItem.data("image"),
      });

      $("#productModal").modal("hide");
    });
  }

  // Mở modal chọn sản phẩm
  function openProductModal() {
    // Tải danh sách sản phẩm
    loadProducts();

    // Hiển thị modal
    $("#productModal").modal("show");

    // Xử lý tìm kiếm sản phẩm
    $("#product-search")
      .off("input")
      .on("input", function () {
        const searchValue = $(this).val().toLowerCase();

        $(".product-item").each(function () {
          const productName = $(this).data("name").toLowerCase();

          if (productName.includes(searchValue)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });
  }

  // Thêm sản phẩm vào đơn hàng
  function addProductToOrder(product) {
    // Kiểm tra xem sản phẩm đã có trong đơn hàng chưa
    const existingProduct = $(`.edit-product-row[data-id="${product.id}"]`);
    if (existingProduct.length > 0) {
      const quantityInput = existingProduct.find(".quantity-input");
      const currentQuantity = parseInt(quantityInput.val());
      quantityInput.val(currentQuantity + 1);
    } else {
      const productRow = `
              <div class="edit-product-row d-flex align-items-center mb-2 p-2 border rounded" data-id="${
                product.id
              }" data-price="${product.price}">
                  <div class="d-flex align-items-center flex-grow-1">
                      <img src="${
                        product.image
                      }" width="50" height="50" class="rounded me-2" onerror="this.src='Front-End/assets/img/default-product.jpg'">
                      <div>${product.name}</div>
                  </div>
                  <div class="d-flex align-items-center quantity-control-wrapper">
                      <div class="quantity-control d-flex align-items-center">
                          <button type="button" class="btn-quantity btn-decrease">
                              <i class="fa-solid fa-minus"></i>
                          </button>
                          <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" value="1" min="1">
                          <button type="button" class="btn-quantity btn-increase">
                              <i class="fa-solid fa-plus"></i>
                          </button>
                      </div>
                      <div class="text-danger fw-bold ms-4 me-2" data-price="${
                        product.price
                      }">${formatPrice(product.price)} đ</div>
                      <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                          <i class="fa-light fa-trash"></i>
                      </button>
                  </div>
              </div>
            `;

      $(".edit-product-list").prepend(productRow);

      registerProductEvents();
    }

    calculateTotalAmount();
  }

  // Đăng ký sự kiện cho các nút trong danh sách sản phẩm
  function registerProductEvents() {
    $(".btn-decrease")
      .off("click")
      .on("click", function () {
        const input = $(this).siblings(".quantity-input");
        let value = parseInt(input.val());
        if (value > 1) {
          input.val(value - 1);
          calculateTotalAmount();
        }
      });

    $(".btn-increase")
      .off("click")
      .on("click", function () {
        const input = $(this).siblings(".quantity-input");
        let value = parseInt(input.val());
        input.val(value + 1);
        calculateTotalAmount();
      });

    // Xử lý sự kiện thay đổi số lượng trực tiếp
    $(".quantity-input")
      .off("change")
      .on("change", function () {
        calculateTotalAmount();
      });

    // Xử lý sự kiện xóa sản phẩm
    $(".remove-product")
      .off("click")
      .on("click", function () {
        $(this).closest(".edit-product-row").remove();
        calculateTotalAmount();
      });
  }

  // Tính tổng tiền
  function calculateTotalAmount() {
    let total = 0;

    $(".edit-product-row").each(function () {
      const price = parseFloat($(this).data("price"));
      const quantity = parseInt($(this).find(".quantity-input").val());
      if (!isNaN(price) && !isNaN(quantity)) {
        total += price * quantity;
      }
    });

    $(".total-amount").text(formatPrice(total) + " đ");
    return total;
  }

  // Tải thông tin đơn hàng
  function loadOrderDetails(orderId) {
    showLoading();

    $.ajax({
      url: apiUrl + "?id=" + orderId,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          populateOrderForm(response.data);
        } else {
          showNotification({
            title: "Error",
            message: "Không thể tải thông tin đơn hàng",
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải thông tin đơn hàng:", error);
        showNotification({
          title: "Error",
          message: "Đã xảy ra lỗi khi tải thông tin đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  // Điền thông tin đơn hàng vào form
  function populateOrderForm(order) {
    $("#edit-recipient").val(order.receiver_name);
    $("#edit-phone").val(order.receiver_phone);
    $("#edit-address").val(order.receiver_address);

    $("#edit-delivery-method").val(order.delivery_method);

    const deliveryDate = new Date(order.delivery_date);
    const formattedDate = deliveryDate.toISOString().split("T")[0];
    $("#edit-delivery-date").val(formattedDate);

    $("#edit-note").val(order.note || "");

    $(".edit-product-list").empty();

    if (order.items && order.items.length > 0) {
      order.items.forEach((item) => {
        let imgSrc = item.product_image || "";
        if (imgSrc && imgSrc.startsWith("/")) {
          imgSrc = imgSrc.substring(1);
        }

        const itemPrice = parseFloat(item.product_price);
        const productPrice = formatPrice(itemPrice) + " đ";

        const productRow = `
          <div class="edit-product-row d-flex align-items-center mb-2 p-2 border rounded" 
               data-id="${item.product_id}" 
               data-price="${itemPrice}">
            <div class="d-flex align-items-center flex-grow-1">
              <img src="${imgSrc}" width="50" height="50" class="rounded me-2" 
                   onerror="this.src='Front-End/assets/img/default-product.jpg'">
              <div>${item.product_name}</div>
            </div>
            <div class="d-flex align-items-center quantity-control-wrapper">
              <div class="quantity-control d-flex align-items-center">
                <button type="button" class="btn-quantity btn-decrease">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" class="form-control form-control-sm quantity-input mx-1 text-center" 
                       value="${item.quantity}" min="1">
                <button type="button" class="btn-quantity btn-increase">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
              <div class="text-danger fw-bold ms-4 me-2" data-price="${itemPrice}">
                ${productPrice}
              </div>
              <button type="button" class="btn btn-sm btn-outline-danger remove-product">
                <i class="fa-light fa-trash"></i>
              </button>
            </div>
          </div>
        `;

        $(".edit-product-list").append(productRow);
      });

      registerProductEvents();
      calculateTotalAmount();
    }
  }

  // Lưu thay đổi đơn hàng
  function saveOrderChanges() {
    const orderId = $("#order-id").val();

    // Kiểm tra form
    if (!validateForm()) {
      return;
    }

    const orderData = {
      receiverName: $("#edit-recipient").val(),
      receiverPhone: $("#edit-phone").val(),
      receiverAddress: $("#edit-address").val(),
      deliveryMethod: $("#edit-delivery-method").val(),
      deliveryTime: $("#edit-delivery-date").val(),
      note: $("#edit-note").val(),
      items: [],
    };

    $(".edit-product-row").each(function () {
      const productId = $(this).data("id");
      const price = $(this).data("price");
      const quantity = parseInt($(this).find(".quantity-input").val());

      orderData.items.push({
        productId: productId,
        price: price,
        quantity: quantity,
      });
    });
    updateOrder(orderId, orderData);
  }

  function updateOrder(orderId, orderData) {

    $.ajax({
      url: apiUrl + "?id=" + orderId,
      type: "PUT",
      dataType: "json",
      contentType: "application/json",
      data: JSON.stringify(orderData),
      success: function (response) {
        if (response.status === 200) {
          showNotification({
            title: "Success",
            message: "Thay đổi đơn hàng thành công!",
            type: "success",
            duration: 2000,
          });

          // Chuyển về trang danh sách đơn hàng sau 2 giây
          setTimeout(() => {
            window.location.href = "http://localhost/Order%20Management/Front-End/index.php";
          }, 2000);
        } else {
          console.log(response.data);
          showNotification({
            title: "Error",
            message: "Lỗi khi cập nhật đơn hàng",
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi cập nhật đơn hàng:", error);
        showNotification({
          title: "Error",
          message: "Đã xảy ra lỗi khi cập nhật đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  function validateForm() {
    let isValid = true;

    // Kiểm tra thông tin khách hàng
    const recipient = $("#edit-recipient").val();
    const phone = $("#edit-phone").val();
    const address = $("#edit-address").val();

    if (!recipient) {
      showNotification({
        title: "Error",
        message: "Vui lòng nhập tên người nhận",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    if (!phone) {
      showNotification({
        title: "Error",
        message: "Vui lòng nhập số điện thoại",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    } else if (!phone.match(/^[0-9]{10}$/)) {
      showNotification({
        title: "Error",
        message: "Số điện thoại không hợp lệ (phải có 10 chữ số)",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    if (!address) {
      showNotification({
        title: "Error",
        message: "Vui lòng nhập địa chỉ",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    // Kiểm tra thông tin giao hàng
    const deliveryMethod = $("#edit-delivery-method").val();
    const deliveryDate = $("#edit-delivery-date").val();

    if (!deliveryMethod) {
      showNotification({
        title: "Error",
        message: "Vui lòng chọn hình thức giao hàng",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    if (!deliveryDate) {
      showNotification({
        title: "Error",
        message: "Vui lòng chọn ngày giao hàng",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    // Kiểm tra danh sách sản phẩm
    if ($(".edit-product-row").length === 0) {
      showNotification({
        title: "Error",
        message: "Vui lòng thêm ít nhất một sản phẩm",
        type: "error",
        duration: 3000,
      });
      isValid = false;
    }

    return isValid;
  }

  // Hiển thị hiệu ứng loading
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

  // Ẩn hiệu ứng loading
  function hideLoading() {
    $("#loadingOverlay").hide();
  }

  // Định dạng giá tiền
  function formatPrice(price) {
    return new Intl.NumberFormat("vi-VN").format(price);
  }
});
