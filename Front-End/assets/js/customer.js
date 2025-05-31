$(document).ready(function () {
  loadCustomers();

  // Xử lý tìm kiếm từ input search có sẵn
  $(".search-input").on("keyup", function (e) {
    const keyword = $(this).val();
    if (e.key === "Enter") {
      if (keyword) {
        searchCustomers(keyword);
      } else {
        loadCustomers();
      }
    }
  });
  // Form thêm khách hàng
  $(".add-customer-form").on("submit", function (e) {
    e.preventDefault();
    const customerData = {
      name: $("#fullName").val(),
      phone: $("#phoneNumber").val(),
      password: $("#password").val(),
    };
    createCustomer(customerData);
  });

  // Nút thêm mới khách hàng
  $("#add-product-button").on("click", function () {
    $(".add-customer-form").submit();
  });

  // Bắt sự kiện nút sửa
  $("#customer-table").on("click", ".btn-edit", function () {
    const customerId = $(this).closest("tr").find("td:first").text();
    getCustomerForEdit(customerId);
  });

  // Form sửa khách hàng
  $(".edit-customer-form").on("submit", function (e) {
    e.preventDefault();
    const customerId = $("#editCustomerId").val();
    const customerData = {
      name: $("#fullName").val(),
      phone: $("#phoneNumber").val(),
      password: $("#password").val(),
    };
    updateCustomer(customerId, customerData);
  });

  // Nút lưu thông tin chỉnh sửa
  $("#save-customer-button").on("click", function () {
    $(".edit-customer-form").submit();
  });

  // Bắt sự kiện nút xóa
  $("#customer-table").on("click", ".btn-delete", function () {
    if (confirm("Bạn có chắc chắn muốn xóa khách hàng này?")) {
      const customerId = $(this).closest("tr").find("td:first").text();
      deleteCustomer(customerId);
    }
  });
});

/**
 * Tải danh sách tất cả khách hàng
 */
function loadCustomers() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/CustomerApi.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        displayCustomers(response.data);
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi kết nối đến server",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}

/**
 * Tìm kiếm khách hàng
 */
function searchCustomers(keyword) {
  $.ajax({
    url:
      "http://localhost/Order%20Management/Back-End/api/CustomerApi.php?search=" +
      encodeURIComponent(keyword),
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        displayCustomers(response.data);
        if (response.data.length === 0) {
          showNotification({
            title: "Info",
            message: "Không tìm thấy khách hàng phù hợp",
            type: "info",
            duration: 3000,
          });
        }
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi khi tìm kiếm",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}

/**
 * Hiển thị danh sách khách hàng
 */
function displayCustomers(customers) {
  let html = "";
  if (customers && customers.length > 0) {
    customers.forEach((customer) => {
      html += `
        <tr>
          <td>${customer.id}</td>
          <td>${customer.name}</td>
          <td>${customer.phone}</td>
          <td>${formatDateTime(customer.created_at)}</td>
          <td><span class="status-${
            customer.status == 1 ? "active" : "locked"
          }">${customer.status == 1 ? "Hoạt động" : "Bị khóa"}</span></td>
          <td>
            <div class="action-buttons d-flex justify-content-center">
              <button class="btn-edit btn-edit-customer"  data-bs-toggle="modal" data-bs-target="#editCustomerModal">
                <i class="fa-light fa-pen-to-square"></i>
              </button>
              <button class="btn-delete">
                <i class="fa-regular fa-trash"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
    });
  } else {
    html = '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
  }
  $("#customer-table tbody").html(html);
}

/**
 * Format h:m d/m/y
 * @param {string|Date} dateTime
 * @returns {string}
 */
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
  return `${hours}:${minutes} ${day}/${month}/${year}`;
}

/**
 * Lấy thông tin khách hàng để sửa
 */
function getCustomerForEdit(customerId) {
  $.ajax({
    url:
      "http://localhost/Order%20Management/Back-End/api/CustomerApi.php/" +
      customerId,
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        const customer = response.data;
        $("#editCustomerId").val(customer.id);
        $("#fullName").val(customer.name);
        $("#phoneNumber").val(customer.phone);
        $("#editCustomerModal").modal("show");
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi khi lấy thông tin khách hàng",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}

/**
 * Tạo khách hàng mới
 */
function createCustomer(customerData) {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/CustomerApi.php",
    type: "POST",
    data: JSON.stringify(customerData),
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      if (response.status === 201) {
        showNotification({
          title: "Success",
          message: "Thêm khách hàng thành công",
          type: "success",
          duration: 3000,
        });
        $(".add-customer-form")[0].reset();
        $("#customerModal").modal("hide");
        loadCustomers();
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi khi thêm khách hàng",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}

/**
 * Cập nhật thông tin khách hàng
 */
function updateCustomer(customerId, customerData) {
  $.ajax({
    url:
      "http://localhost/Order%20Management/Back-End/api/CustomerApi.php/" +
      customerId,
    type: "PUT",
    data: JSON.stringify(customerData),
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        showNotification({
          title: "Success",
          message: "Cập nhật khách hàng thành công",
          type: "success",
          duration: 3000,
        });
        $(".edit-customer-form")[0].reset();
        $("#editCustomerModal").modal("hide");
        loadCustomers();
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi khi cập nhật khách hàng",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}

/**
 * Xóa khách hàng
 */
function deleteCustomer(customerId) {
  $.ajax({
    url:
      "http://localhost/Order%20Management/Back-End/api/CustomerApi.php/" +
      customerId,
    type: "DELETE",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        showNotification({
          title: "Success",
          message: "Xóa khách hàng thành công",
          type: "success",
          duration: 3000,
        });
        loadCustomers();
      } else {
        showNotification({
          title: "Error",
          message: "Lỗi: " + response.message,
          type: "error",
          duration: 3000,
        });
      }
    },
    error: function (xhr, status, error) {
      showNotification({
        title: "Error",
        message: "Lỗi khi xóa khách hàng",
        type: "error",
        duration: 3000,
      });
      console.error(error);
    },
  });
}
