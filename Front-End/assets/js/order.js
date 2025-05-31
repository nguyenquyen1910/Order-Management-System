let orders = [];

$(document).ready(function () {
  const apiUrl = "http://localhost/Order Management/Back-End/api/OrderApi.php";

  // Load danh sách đơn hàng
  loadOrders();

  // Tìm kiếm đơn hàng
  $(".search-input").on("keyup", function () {
    const keyword = $(this).val().toLowerCase();
    if (keyword.length > 0) {
      searchOrders(keyword);
    } else {
      displayOrders(orders);
    }
  });

  // Lọc đơn hàng theo tình trạng
  $("#order-status").on("change", function () {
    const status = $(this).val();
    if (status === "Tất cả") {
      displayOrders(orders);
    } else {
      filterOrdersByStatus(status);
    }
  });

  $("#list-orders-table").on("click", ".btn-view", function () {
    const orderId = $(this).data("id");
    loadOrderDetail(orderId);
  });

  $("#list-orders-table").on("click", ".btn-edit-order", function () {
    const orderId = $(this).data("id");
    window.location.href = "edit-order.php?id=" + orderId;
  });

  $("#list-orders-table").on("click", ".btn-delete-order", function () {
    const orderId = $(this).data("id");
    confirmDeleteOrder(orderId);
  });

  function loadOrders() {
    showLoading();

    const savedOrders = localStorage.getItem("orders");

    $.ajax({
      url: apiUrl,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          orders = response.data;
          localStorage.setItem("orders", JSON.stringify(orders));
          displayOrders(orders);
        } else {
          displayNoOrders();
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải danh sách đơn hàng:", error);
        displayNoOrders();
        hideLoading();
      },
    });
  }

  function filterOrdersByStatus(status) {
    const orders = JSON.parse(localStorage.getItem("orders"));
    const filteredOrders = orders.filter((order) => {
      switch (status) {
        case "Chưa xử lý":
          return parseInt(order.status) === 0;
        case "Đã xử lý":
          return parseInt(order.status) === 1;
        case "Đã hủy":
          return parseInt(order.status) === -1;
        default:
          return true;
      }
    });
    displayOrders(filteredOrders);
  }

  // Hàm tìm kiếm đơn hàng
  function searchOrders(keyword) {
    const searchResults = orders.filter(
      (order) =>
        order.receiver_name.toLowerCase().includes(keyword) ||
        order.receiver_phone.toLowerCase().includes(keyword) ||
        order.id.toString().includes(keyword)
    );
    displayOrders(searchResults);
  }

  // Hàm hiển thị danh sách đơn hàng
  function displayOrders(ordersToDisplay) {
    const tableBody = $("#list-orders-table tbody");
    tableBody.empty();

    if (ordersToDisplay && ordersToDisplay.length > 0) {
      ordersToDisplay.forEach((order) => {
        let statusClass, statusText;
        switch (parseInt(order.status)) {
          case 0:
            statusClass = "status-unprocessed";
            statusText = "Chưa xử lý";
            break;
          case 1:
            statusClass = "status-active";
            statusText = "Đã xử lý";
            break;
          case -1:
            statusClass = "status-canceled";
            statusText = "Đã hủy";
            break;
          default:
            statusClass = "";
            statusText = "Không xác định";
        }

        // Định dạng ngày
        const orderDate = new Date(order.created_at);
        const formattedDate =
          orderDate.getDate() +
          "/" +
          (orderDate.getMonth() + 1) +
          "/" +
          orderDate.getFullYear();

        // Định dạng tiền tệ
        const formattedAmount =
          parseInt(order.total_amount).toLocaleString("vi-VN") + " ₫";
        const row = `
                    <tr data-id="${order.id}">
                    <td>${order.id}</td>
                    <td>${order.receiver_name}</td>
                    <td>${order.receiver_phone}</td>
                    <td>${formattedDate}</td>
                    <td>${formattedAmount}</td>
                    <td><span class="${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons d-flex justify-content-center">
                            <button class="btn-view" data-id="${order.id}" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <button class="btn-edit btn-edit-order" data-id="${order.id}">
                                <i class="fa-light fa-pen-to-square"></i>
                            </button>
                            <button class="btn-delete btn-delete-order" data-id="${order.id}">
                                <i class="fa-regular fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        tableBody.append(row);
      });

      registerButtonEvents();
    } else {
      displayNoOrders();
    }
  }

  // Hiển thị thông báo không có đơn hàng
  function displayNoOrders() {
    const tableBody = $("#list-orders-table tbody");
    tableBody.html(`
            <tr>
                <td colspan="7" class="text-center py-3">Không có đơn hàng nào</td>
            </tr>
        `);
  }

  // Đăng ký sự kiện cho các nút
  function registerButtonEvents() {
    // Xóa các sự kiện cũ để tránh trùng lặp
    $(".btn-view").off("click");
    $(".btn-edit-order").off("click");
    $(".btn-delete-order").off("click");

    // Đăng ký lại sự kiện mới
    $(".btn-view").on("click", function () {
      const orderId = $(this).data("id");
      loadOrderDetail(orderId);
    });

    $(".btn-edit-order").on("click", function () {
      const orderId = $(this).data("id");
      window.location.href = "edit-order.php?id=" + orderId;
    });
  }

  // Hiển thị chi tiết đơn hàng trong modal
  function loadOrderDetail(orderId) {
    showLoading();
    $.ajax({
      url: apiUrl + "?id=" + orderId,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          $("#orderDetailModal").data("currentOrder", response.data);
          displayOrderDetail(response.data);
        } else {
          showNotification({
            title: "Lỗi",
            message: "Không thể tải thông tin đơn hàng",
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi tải chi tiết đơn hàng:", error);
        showNotification({
          title: "Lỗi",
          message: "Đã xảy ra lỗi khi tải thông tin đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  // Hiển thị thông tin đơn hàng trong modal chi tiết
  function displayOrderDetail(order) {
    const orderDate = new Date(order.created_at);
    const formattedDate =
      orderDate.getDate() +
      "/" +
      (orderDate.getMonth() + 1) +
      "/" +
      orderDate.getFullYear();

    // Định dạng ngày giao hàng
    const deliveryDate = new Date(order.delivery_date);
    const formattedDeliveryDate =
      deliveryDate.getDate() +
      "/" +
      (deliveryDate.getMonth() + 1) +
      "/" +
      deliveryDate.getFullYear();

    $(".order-products").empty();

    // Thêm sản phẩm vào đơn hàng
    if (order.items && order.items.length > 0) {
      order.items.forEach((item) => {
        const productPrice =
          parseInt(item.product_price).toLocaleString("vi-VN") + " đ";
        const productItem = `
              <div class="order-product-item d-flex mb-3">
                  <div class="product-img">
                    <img src="${
                      item.product_image
                    }" class="rounded" width="70" height="70" alt="Rau xào ngũ sắc">
                  </div>
                  <div class="product-info ms-4 flex-grow-1">
                    <h6 class="product-name">${item.product_name}</h6>
                    <div class="product-note text-muted small">
                      <i class="fa-light fa-comment-dots me-1"></i>${
                        item.note || "Không có ghi chú"
                      }
                    </div>
                    <div class="product-quantity">SL: ${item.quantity}</div>
                </div>
                <div class="product-price text-danger fw-bold">
                  ${productPrice}
                </div>
              </div>
            `;
        $(".order-products").append(productItem);
      });
    }

    // Cập nhật thông tin khác
    $("#orderDetailModal .info-value").eq(0).text(formattedDate);
    $("#orderDetailModal .info-value").eq(1).text(order.delivery_method);
    $("#orderDetailModal .info-value").eq(2).text(order.receiver_name);
    $("#orderDetailModal .info-value").eq(3).text(order.receiver_phone);
    $("#orderDetailModal .info-value").eq(4).text(formattedDeliveryDate);
    $("#orderDetailModal .info-value").eq(5).text(order.receiver_address);
    $("#orderDetailModal .info-value")
      .eq(6)
      .text(order.note || "Không có ghi chú");

    // Cập nhật tổng tiền
    $(".total-value").text(
      parseInt(order.total_amount).toLocaleString("vi-VN") + " đ"
    );

    // Cập nhật trạng thái nút xử lý
    updateStatusButton(parseInt(order.status));
  }

  // Cập nhật trạng thái nút xử lý
  function updateStatusButton(status) {
    const modalFooter = $("#orderDetailModal .modal-footer");
    modalFooter.empty();

    switch (parseInt(status)) {
      case 0: // Chưa xử lý
        modalFooter.append(`
          <button class="btn btn-success me-2 btn-success-modal" data-status="1">
            <i class="fa-light fa-check-circle me-1"></i> Đã xử lý
          </button>
          <button class="btn btn-danger btn-success-modal" data-status="-1">
            <i class="fa-light fa-times-circle me-1"></i> Hủy đơn
          </button>
        `);
        break;
      case 1: // Đã xử lý
        modalFooter.append(`
          <button class="btn btn-success btn-success-modal" disabled>
            <i class="fa-light fa-check-circle me-1"></i> Đã xử lý
          </button>
        `);
        break;
      case -1: // Đã hủy
        modalFooter.append(`
          <button class="btn btn-danger btn-success-modal" disabled>
            <i class="fa-light fa-times-circle me-1"></i> Đã hủy
          </button>
        `);
        break;
    }
  }

  // Xử lý thay đổi trạng thái đơn hàng
  function handleOrderStatus(orderId, newStatus) {
    showLoading();

    const currentOrder = $("#orderDetailModal").data("currentOrder");

    $.ajax({
      url: apiUrl + "?id=" + orderId + "&status=" + newStatus,
      type: "PUT",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          // Cập nhật trạng thái trong modal
          const currentOrder = $("#orderDetailModal").data("currentOrder");
          if (currentOrder) {
            currentOrder.status = newStatus;
            $("#orderDetailModal").data("currentOrder", currentOrder);
            updateStatusButton(newStatus);
          }

          // Cập nhật trạng thái trong bảng
          const orderRow = $(`tr[data-id="${orderId}"]`);
          const statusCell = orderRow.find("td:eq(5)");

          switch (parseInt(newStatus)) {
            case 0:
              statusCell.html(
                '<span class="status-unprocessed">Chưa xử lý</span>'
              );
              break;
            case 1:
              statusCell.html('<span class="status-active">Đã xử lý</span>');
              break;
            case -1:
              statusCell.html('<span class="status-canceled">Đã hủy</span>');
              break;
          }

          // Cập nhật trạng thái trong mảng orders
          const index = orders.findIndex((o) => o.id == orderId);
          if (index !== -1) {
            orders[index].status = newStatus;
            // Cập nhật localStorage
            localStorage.setItem("orders", JSON.stringify(orders));
          }

          showNotification({
            title: "Thành công",
            message: "Cập nhật trạng thái đơn hàng thành công!",
            type: "success",
            duration: 3000,
          });

          // Tải lại danh sách đơn hàng
          loadOrders();
        } else {
          showNotification({
            title: "Lỗi",
            message: response.message || "Lỗi khi cập nhật trạng thái đơn hàng",
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi cập nhật trạng thái đơn hàng:", error);
        showNotification({
          title: "Lỗi",
          message: "Đã xảy ra lỗi khi cập nhật trạng thái đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  // Đăng ký sự kiện cho nút xử lý trạng thái
  $(document).on(
    "click",
    "#orderDetailModal .modal-footer button:not([disabled])",
    function () {
      const currentOrder = $("#orderDetailModal").data("currentOrder");
      if (!currentOrder) {
        showNotification({
          title: "Error",
          message: "Không tìm thấy thông tin đơn hàng",
          type: "error",
          duration: 3000,
        });
        return;
      }

      const orderId = currentOrder.id;

      const newStatus = $(this).attr("data-status");

      if (
        confirm("Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này không?")
      ) {
        handleOrderStatus(orderId, newStatus);
      }
    }
  );

  // Xác nhận xóa đơn hàng
  function confirmDeleteOrder(orderId) {
    if (confirm("Bạn có chắc chắn muốn xóa đơn hàng này không?")) {
      deleteOrder(orderId);
    }
  }

  // Xóa đơn hàng
  function deleteOrder(orderId) {
    showLoading();
    $.ajax({
      url: apiUrl + "/" + orderId,
      type: "DELETE",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          const index = orders.findIndex((o) => o.id == orderId);
          if (index !== -1) {
            orders.splice(index, 1);
            localStorage.setItem("orders", JSON.stringify(orders));
          }

          displayOrders(orders);
          showNotification({
            title: "Thành công",
            message: "Đã xóa đơn hàng thành công",
            type: "success",
            duration: 3000,
          });
        } else {
          showNotification({
            title: "Lỗi",
            message: "Không thể xóa đơn hàng: " + response.message,
            type: "error",
            duration: 3000,
          });
        }
        hideLoading();
      },
      error: function (xhr, status, error) {
        console.error("Lỗi khi xóa đơn hàng:", error);
        showNotification({
          title: "Lỗi",
          message: "Đã xảy ra lỗi khi xóa đơn hàng",
          type: "error",
          duration: 3000,
        });
        hideLoading();
      },
    });
  }

  // Hàm hiển thị hiệu ứng loading
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

  function formatDateTime(dateTime) {
    const date = new Date(dateTime);
    if (isNaN(date.getTime())) {
      return "Ngày không hợp lệ";
    }
    const hours = date.getHours().toString().padStart(2, "0");
    const minutes = date.getMinutes().toString().padStart(2, "0");
    const day = date.getDate().toString().padStart(2, "0");
    const month = (date.getMonth() + 1).toString().padStart(2, "0");
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
  }
});
