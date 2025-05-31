// Handle login
$(document).ready(function () {
  const user = JSON.parse(localStorage.getItem("currentUser"));
  if (user && user.name) {
    $(".sidebar-section .fa-circle-user")
      .parent()
      .find(".sidebar-username")
      .text(user.name);
  }
});

// Handle logout
$(document).ready(function () {
  $("#logout-btn").on("click", function (e) {
    e.preventDefault();
    localStorage.removeItem("currentUser");
    window.location.href = "/Order%20Management/Front-End/login.php";
  });
});

// Handle not access
$(document).ready(function () {
  const user = localStorage.getItem("currentUser");
  if (!user) {
    window.location.href = "not-access.php";
  }
});

// Handle active class on sidebar nav
$(document).ready(function () {
  $(".overview").addClass("active");
  $(".section.product-all").hide();
  $(".section.customer-all").hide();
  $(".section.order-all").hide();
  $(".section.statistic-all").hide();
  $(".section.delivery-all").hide();
  $(".sidebar-nav .nav-link").on("click", function () {
    $(".sidebar-nav .nav-link").removeClass("active");
    $(this).addClass("active");
    const index = $(this).index();
    $(".section").removeClass("active").hide();
    if (index === 0) {
      $(".overview").addClass("active").show();
      if (typeof loadDataDashboard === "function") {
        loadDataDashboard();
      } else {
        console.error("Hàm loadDataDashboard không tồn tại");
      }
    } else if (index === 1) {
      $(".section.product-all").addClass("active").show();
    } else if (index === 2) {
      $(".section.customer-all").addClass("active").show();
    } else if (index === 3) {
      $(".section.order-all").addClass("active").show();
      if (typeof loadOrders === "function") {
        loadOrders();
      }
    } else if (index === 4) {
      $(".section.delivery-all").addClass("active").show();
    } else if (index === 5) {
      $(".section.statistic-all").addClass("active").show();
      if (typeof initOrderCharts === "function") {
        initOrderCharts();
      } else {
        console.error("Hàm initOrderCharts không tồn tại");
      }
    }
  });
});

// Handle edit product
$(document).ready(function () {
  $(document).on("click", ".edit-product", function () {
    // Get information product from DOM
    const productItem = $(this).closest(".product-item");
    const productName = productItem.find("h4").text().trim();
    let productDesc = productItem.find(".product-text").text().trim();
    productDesc = productDesc.replace(/\s+/g, " ").trim();
    const productCategory = productItem.find(".product-category").text().trim();
    const productPrice = productItem
      .find(".price")
      .text()
      .replace(/[^\d]/g, "");
    const productImage = productItem.find("img").attr("src");

    $("#edit-ten-mon").val(productName);
    $("#edit-mo-ta").val(productDesc);
    $("#edit-chon-mon").val(productCategory);
    $("#edit-gia-moi").val(productPrice);
    $("#edit-image-preview").attr("src", productImage);

    $("#editProductModal").modal("show");
  });
});

// Handle delete product
$(document).ready(function () {
  $(".btn-delete-product").on("click", function () {
    const productItem = $(this).closest(".product-item");
    const productName = productItem.find("h4").text().trim();

    if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm ${productName} không?`)) {
      productItem.fadeOut(300, function () {
        $(this).closest(".list").remove();

        showNotification({
          title: "Success",
          message: `Đã xóa sản phẩm ${productName} thành công!`,
          type: "success",
          duration: 3000,
        });
      });
    }
  });
});

// Handle add customer
$(document).ready(function () {
  $(document).on("click", "#add-customer-button", function () {
    const customerData = {
      name: $("#fullName").val(),
      phone: $("#phoneNumber").val(),
      password: $("#password").val(),
      status: 1,
    };

    if (!customerData.name || !customerData.phone) {
      showNotification({
        title: "Error",
        message: "Vui lòng nhập đầy đủ thông tin khách hàng",
        type: "error",
        duration: 3000,
      });
      return;
    }

    $.ajax({
      url: "http://localhost/Order%20Management/Back-End/api/CustomerApi.php",
      type: "POST",
      data: JSON.stringify(customerData),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        if (response.status === 201) {
          $("#customerModal").modal("hide");
          $("#customerModal form")[0].reset();
          loadCustomers();
          showNotification({
            title: "Success",
            message: "Thêm khách hàng thành công!",
            type: "success",
            duration: 3000,
          });
        } else {
          showNotification({
            title: "Error",
            message: "Có lỗi xảy ra",
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
  });
});

// Handle edit customer
$(document).ready(function () {
  $(document).on("click", ".btn-edit-customer", function () {
    // Lấy ID của khách hàng từ hàng
    const row = $(this).closest("tr");
    const customerId = row.find("td:first").text().trim();

    // Gọi API để lấy thông tin chi tiết của khách hàng
    $.ajax({
      url:
        "http://localhost/Order%20Management/Back-End/api/CustomerApi.php/" +
        customerId,
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200) {
          const customer = response.data;

          // Điền thông tin vào modal
          $("#editCustomerModal #fullName").val(customer.name);
          $("#editCustomerModal #phoneNumber").val(customer.phone);
          $("#editCustomerModal #editCustomerId").val(customerId);

          // Mặc định trạng thái là hoạt động
          $("#editCustomerModal #user-status").prop("checked", true);

          $("#editCustomerModal").modal("show");
        } else {
          showNotification({
            title: "Error",
            message: response.message,
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
  });

  // Gắn sự kiện cho nút lưu
  $("#save-customer-button").on("click", function () {
    // Lấy thông tin từ form
    const customerId = $("#editCustomerModal #editCustomerId").val();
    const updatedName = $("#editCustomerModal #fullName").val();
    const updatedPhone = $("#editCustomerModal #phoneNumber").val();
    const updatedPassword = $("#editCustomerModal #password").val();
    const updatedStatus = $("#editCustomerModal #user-status").is(":checked")
      ? 1
      : 0;

    // Tạo đối tượng dữ liệu để gửi đi
    const customerData = {
      name: updatedName,
      phone: updatedPhone,
      status: updatedStatus,
    };

    // Thêm mật khẩu nếu được nhập
    if (updatedPassword) {
      customerData.password = updatedPassword;
    }

    // Gọi API để cập nhật thông tin khách hàng
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
          // Đóng modal
          $("#editCustomerModal").modal("hide");
          loadCustomers();
          showNotification({
            title: "Success",
            message: "Cập nhật khách hàng thành công!",
            type: "success",
            duration: 3000,
          });
        } else {
          showNotification({
            title: "Error",
            message: response.message,
            type: "error",
            duration: 3000,
          });
        }
      },
      error: function (xhr, status, error) {
        showNotification({
          title: "Error",
          message: "Lỗi khi cập nhật thông tin khách hàng",
          type: "error",
          duration: 3000,
        });
        console.error(error);
      },
    });
  });
});

// Handle delete customer
$(document).ready(function () {
  $(".btn-delete-customer").on("click", function () {
    const row = $(this).closest("tr");
    const customerName = row.find("td:eq(1)").text().trim();

    if (
      confirm(`Bạn có chắc chắn muốn xóa khách hàng ${customerName} không?`)
    ) {
      row.fadeOut(300, function () {
        $(this).remove();
        showNotification({
          title: "Success",
          message: `Đã xóa khách hàng ${customerName} thành công!`,
          type: "success",
          duration: 3000,
        });
      });
    }
  });
});

// Handle view all orders
$(document).ready(function () {
  $("#view-all-orders").on("click", function (e) {
    e.preventDefault();
    $(".section").hide();
    $("#order-all").show();

    $(".sidebar-nav .nav-link").removeClass("active");
    $(".sidebar-nav .nav-link").eq(3).addClass("active");
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      500
    );
  });
});

// Handle view all customers
$(document).ready(function () {
  $("#view-all-customers").on("click", function (e) {
    e.preventDefault();
    $(".section").hide();
    $("#customer-all").show();

    $(".sidebar-nav .nav-link").removeClass("active");
    $(".sidebar-nav .nav-link").eq(2).addClass("active");
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      500
    );
  });
});
