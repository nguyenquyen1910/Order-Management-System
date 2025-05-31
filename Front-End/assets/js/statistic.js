let orderStatisticRevenueChart = null;
let orderStatisticStatusPieChart = null;
// Data cho biểu đồ đơn hàng & doanh thu
const orderRevenueData = {
  labels: ["1/3", "2/3", "3/3", "4/3", "5/3", "6/3", "7/3"],
  datasets: [
    {
      label: "Doanh thu",
      data: [1200000, 1900000, 1500000, 2100000, 1800000, 2500000, 2200000],
      borderColor: "#28a745",
      backgroundColor: "rgba(40, 167, 69, 0.1)",
      tension: 0.4,
      fill: true,
      yAxisID: "y",
    },
    {
      label: "Số đơn hàng",
      data: [15, 22, 18, 25, 20, 28, 24],
      borderColor: "#0d6efd",
      backgroundColor: "rgba(13, 110, 253, 0.1)",
      tension: 0.4,
      fill: true,
      yAxisID: "y1",
    },
  ],
};

// Data cho biểu đồ trạng thái đơn hàng
const orderStatusData = {
  labels: ["Hoàn thành", "Đang giao", "Chờ xử lý", "Đã hủy"],
  datasets: [
    {
      data: [75, 12.5, 8.3, 4.2],
      backgroundColor: [
        "#28a745", // Hoàn thành - Xanh lá
        "#17a2b8", // Đang giao - Xanh dương
        "#ffc107", // Chờ xử lý - Vàng
        "#dc3545", // Đã hủy - Đỏ
      ],
      borderWidth: 0,
    },
  ],
};

// Data cho biểu đồ trạng thái vận chuyển
const shippingStatusData = {
  labels: ["Đúng giờ", "Trễ", "Đang giao", "Đã hủy"],
  datasets: [
    {
      data: [120, 5, 8, 2],
      backgroundColor: ["#28a745", "#ffc107", "#0d6efd", "#dc3545"],
    },
  ],
};

// Data cho top sản phẩm bán chạy
// const topProductsData = [
//   {
//     name: "Khâu nhục",
//     img_url: "assets/images/products/khau-nhuc.jpeg",
//     sales: 48,
//     revenue: 7200000,
//     trend: "up",
//   },
//   {
//     name: "Tai cuộn lưỡi",
//     img_url: "assets/images/products/tai-cuon-luoi.jpeg",
//     sales: 42,
//     revenue: 8400000,
//     trend: "up",
//   },
//   {
//     name: "Nấm đùi gà xào cháy tỏi",
//     img_url: "assets/images/products/nam-dui-ga-chay-toi.jpeg",
//     sales: 35,
//     revenue: 6300000,
//     trend: "down",
//   },
// ];

// // Data cho top khách hàng
// const topCustomersData = [
//   {
//     name: "Nguyễn Viết Quyền",
//     orders: 12,
//     total: 4500000,
//     trend: "up",
//   },
//   {
//     name: "Bùi Ngọc Linh",
//     orders: 8,
//     total: 3200000,
//     trend: "up",
//   },
//   {
//     name: "Lê Văn B",
//     orders: 7,
//     total: 2800000,
//     trend: "down",
//   },
// ];
const statisticOrdersData = [
  {
    id: "DH113",
    customer: "Nguyễn Viết Quyền",
    date: "2024-03-15 10:25",
    amount: 350000,
    status: "pending",
    processTime: "2 ngày",
    delivery: "Tự đến lấy",
    staff: "Nguyễn Khắc Cường",
  },
  {
    id: "DH112",
    customer: "Bùi Ngọc Linh",
    date: "2024-03-15 09:18",
    amount: 520000,
    status: "pending",
    processTime: "2 ngày",
    delivery: "Giao hàng tiêu chuẩn",
    staff: "Nguyễn Viết Quyền",
  },
  {
    id: "DH111",
    customer: "Lê Văn B",
    date: "2024-03-14 18:42",
    amount: 180000,
    status: "processed",
    processTime: "15 phút",
    delivery: "Giao hàng tận nơi",
    staff: "Nguyễn Khắc Cường",
  },
];

// Data cho bảng đơn hàng giao trễ
const lateDeliveryData = [
  {
    id: "DH201",
    customer: "Nguyễn Viết Quyền",
    order_time: "2024-03-15 10:00",
    delivery_expected_time: "2024-03-15 10:30",
    delivery_actual_time: "2024-03-15 11:00",
    staff: "Nguyễn Khắc Cường",
  },
  {
    id: "DH202",
    customer: "Bùi Ngọc Linh",
    order_time: "2024-03-15 11:00",
    delivery_expected_time: "2024-03-15 11:30",
    delivery_actual_time: "2024-03-15 12:00",
    staff: "Nguyễn Viết Quyền",
  },
];

$(document).ready(function () {
  initOrderCharts();
  getAllEmployee();
  $("#order-statistics-filter").on("submit", function (e) {
    e.preventDefault();

    var filterData = {
      fromDate: $("#filter-date-from").val(),
      toDate: $("#filter-date-to").val(),
      status: $("#filter-status").val(),
      employee: $("#filter-channel").val(),
    };

    Object.keys(filterData).forEach(function (key) {
      if (!filterData[key]) delete filterData[key];
    });

    $("#filter-results").show();
    $.ajax({
      url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php",
      type: "GET",
      data: filterData,
      success: function (response) {
        if (response.status === 200 && response.data) {
          renderFilteredOrders(response.data);
        }
      },
    });
  });
  updateStatistic();
  getAllOrderDetails();
  getAllTopSellingProduct();
  getAllTopCustomers();
});

// Order Statistic Chart
function initOrderCharts() {
  destroyAllCharts();
  // Biểu đồ Line: Đơn hàng & Doanh thu
  const ctxLine = document.getElementById("orderStatisticRevenueChart");
  if (ctxLine) {
    orderStatisticRevenueChart = new Chart(ctxLine.getContext("2d"), {
      type: "line",
      data: orderRevenueData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: "index",
          intersect: false,
        },
        plugins: {
          legend: {
            position: "top",
            labels: {
              usePointStyle: true,
              padding: 20,
            },
          },
          tooltip: {
            backgroundColor: "rgba(255, 255, 255, 0.9)",
            titleColor: "#333",
            bodyColor: "#666",
            borderColor: "#ddd",
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
            callbacks: {
              label: function (context) {
                let label = context.dataset.label || "";
                if (label) {
                  label += ": ";
                }
                if (context.dataset.yAxisID === "y") {
                  label += new Intl.NumberFormat("vi-VN", {
                    style: "currency",
                    currency: "VND",
                  }).format(context.raw);
                } else {
                  label += context.raw + " đơn";
                }
                return label;
              },
            },
          },
        },
        scales: {
          y: {
            type: "linear",
            display: true,
            position: "left",
            title: {
              display: true,
              text: "Doanh thu (VNĐ)",
            },
            ticks: {
              callback: function (value) {
                return new Intl.NumberFormat("vi-VN", {
                  style: "currency",
                  currency: "VND",
                  maximumFractionDigits: 0,
                }).format(value);
              },
            },
          },
          y1: {
            type: "linear",
            display: true,
            position: "right",
            title: {
              display: true,
              text: "Số đơn hàng",
            },
            grid: {
              drawOnChartArea: false,
            },
          },
        },
      },
    });
  }

  // Biểu đồ Pie: Phân bố trạng thái đơn hàng
  const ctxPie = document.getElementById("orderStatisticStatusPieChart");
  if (ctxPie) {
    orderStatisticStatusPieChart = new Chart(ctxPie.getContext("2d"), {
      type: "doughnut",
      data: orderStatusData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%",
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              usePointStyle: true,
              padding: 20,
              font: {
                size: 12,
              },
            },
          },
          tooltip: {
            backgroundColor: "rgba(255, 255, 255, 0.9)",
            titleColor: "#333",
            bodyColor: "#666",
            borderColor: "#ddd",
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
            callbacks: {
              label: function (context) {
                const label = context.label || "";
                const value = context.raw;
                return `${label}: ${value}%`;
              },
            },
          },
        },
      },
    });
  }

  var ctxShip = document.getElementById("shippingStatusChart");
  if (ctxShip) {
    window.shippingStatusChart = new Chart(ctxShip.getContext("2d"), {
      type: "doughnut",
      data: shippingStatusData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%",
        plugins: {
          legend: { display: true, position: "bottom" },
        },
      },
    });
  }
}

// Destroy all charts
function destroyStatisticCharts() {
  if (window.orderStatisticRevenueChart) {
    window.orderStatisticRevenueChart.destroy();
    window.orderStatisticRevenueChart = null;
  }
  if (window.orderStatisticStatusPieChart) {
    window.orderStatisticStatusPieChart.destroy();
    window.orderStatisticStatusPieChart = null;
  }
  if (window.shippingStatusChart) {
    window.shippingStatusChart.destroy();
    window.shippingStatusChart = null;
  }
}

// Update order charts
function updateOrderCharts(newData) {
  if (orderStatisticRevenueChart) {
    orderStatisticRevenueChart.data = newData.revenueData;
    orderStatisticRevenueChart.update();
  }

  if (orderStatisticStatusPieChart) {
    orderStatisticStatusPieChart.data = newData.statusData;
    orderStatisticStatusPieChart.update();
  }
}

// Handle status badge
function getStatusBadge(status) {
  if (status === "processed") {
    return '<span class="status-active">Đã xử lý</span>';
  } else if (status === "pending") {
    return '<span class="status-unprocessed">Chưa xử lý</span>';
  } else if (status === "canceled") {
    return '<span class="status-canceled">Đã hủy</span>';
  }
  return "";
}

// Handle up/down
function getTrendIcon(trend) {
  if (trend === "up") {
    return '<span class="text-success"><i class="fa-solid fa-arrow-up"></i></span>';
  } else if (trend === "down") {
    return '<span class="text-danger"><i class="fa-solid fa-arrow-down"></i></span>';
  } else if (trend === "stable") {
    return '<span class="text-secondary"><i class="fa-solid fa-minus"></i></span>';
  }
  return "";
}

function getAllEmployee() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/EmployeeApi.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data) {
        renderRecentEmployee(response.data);
      }
    },
  });
}

function renderRecentEmployee(data) {
  var $list = $("#filter-channel");
  $list.empty();
  $list.append(`<option value="">Tất cả</option>`);
  data.forEach(function (item) {
    var row = `
      <option value="${item.id}">${item.name}</option>
    `;
    $list.append(row);
  });
}

function renderFilteredOrders(data) {
  var $tbody = $("#filtered-orders-table tbody");
  $tbody.empty();
  if (!data || data.legend === 0) {
    $tbody.append(
      '<tr><td colspan="7" class="text-center">Không có đơn hàng nào</td></tr>'
    );
    return;
  }
  data.forEach(function (item) {
    if (item.status == -1) {
      statusOrder = "Đã hủy";
      spanClass = "status-canceled";
    } else if (item.status == 0) {
      statusOrder = "Chưa xử lý";
      spanClass = "status-unprocessed";
    } else {
      statusOrder = "Đã xử lý";
      spanClass = "status-active";
    }
    $tbody.append(`
      <tr>
        <td>${item.id}</td>
        <td>${item.customer_name}</td>
        <td>${item.created_at}</td>
        <td>${Number(item.total_amount).toLocaleString("vi-VN")} đ</td>
        <td><span class="badge text-white ${spanClass}">${statusOrder}</span></td>
        <td>${item.employee_name}</td>
        <td>
          <button class="btn-view" data-id="${
            item.id
          }" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
              <i class="fa-regular fa-eye"></i>
          </button>
        </td>
      </tr>
    `);
  });
}

function updateStatistic() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php",
    type: "GET",
    data: { statistic: 1 },
    success: function (response) {
      if (response.status === 200 && response.data) {
        updateStatisticData(response.data);
      }
    },
  });
}

function updateStatisticData(data) {
  $("#total-orders-value").text(data.totalOrders);
  $("#total-success-orders-value").text(data.totalSuccessOrders);
  $("#total-unprocessed-orders-value").text(data.totalPendingOrders);
  $("#total-canceled-orders-value").text(data.totalCanceledOrders);
  $("#total-revenue-value").text(
    data.totalRevenue.toLocaleString("vi-VN") + " đ"
  );
  $("#total-average-revenue-value").text(
    data.totalAverageOrderValue.toLocaleString("vi-VN") + "K đ"
  );
}

function getAllOrderDetails() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?orderDetails=1",
    type: "GET",
    success: function (response) {
      renderOrderDetails(response.data);
    },
  });
}

function renderOrderDetails(data) {
  var $tbody = $("#orders-detail-table-body");
  $tbody.empty();
  if (!data || data.legend === 0) {
    $tbody.append(
      '<tr><td colspan="7" class="text-center">Không có đơn hàng nào</td></tr>'
    );
    return;
  }
  data.forEach(function (item) {
    var statusOrder = "";
    var spanClass = "";
    if (item.status == -1) {
      statusOrder = "Đã hủy";
      spanClass = "status-canceled";
    } else if (item.status == 0) {
      statusOrder = "Chưa xử lý";
      spanClass = "status-unprocessed";
    } else {
      statusOrder = "Đã xử lý";
      spanClass = "status-active";
    }
    $tbody.append(`
      <tr>
        <td>${item.id}</td>
        <td>${item.customer_name}</td>
        <td>${item.created_at}</td>
        <td>${Number(item.total_amount).toLocaleString("vi-VN")} đ</td>
        <td><span class="badge text-white ${spanClass}">${statusOrder}</span></td>
        <td>${item.updated_at}</td>
        <td>${item.delivery_method}</td>
        <td>${item.employee_name}</td>
        <td>
          <button class="btn-view" data-id="${
            item.id
          }" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
              <i class="fa-regular fa-eye"></i>
          </button>
        </td>
      </tr>
    `);
  });
}

// Top selling product
function getAllTopSellingProduct() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/ProductApi.php?topSelling=4&limit=5",
    type: "GET",
    success: function (response) {
      renderTopSellingProduct(response.data);
    },
  });
}

function renderTopSellingProduct(data) {
  var $tbody = $("#top-selling-products-table-body");
  $tbody.empty();
  if (!data || data.length === 0) {
    $tbody.append(`
      <tr>
        <td colspan="4" class="text-center">Không có sản phẩm bán chạy</td>
      </tr>
    `);
    return;
  }
  data.forEach(function (item) {
    var row = `
      <tr>
        <td class="d-flex align-items-center">
          <img src="${item.image_url}" alt="${
      item.name
    }" style="width:36px;height:36px;object-fit:cover;border-radius:6px;margin-right:10px;">
          <div>
            <div class="fw-semibold">${item.name}</div>
            <div class="text-muted" style="font-size:13px;">${Number(
              item.price
            ).toLocaleString("vi-VN")} đ</div>
          </div>
        </td>
        <td class="text-success fw-bold">${item.current_sold} đơn</td>
        <td>${Number(item.current_revenue).toLocaleString("vi-VN")} đ</td>
        <td class="text-center">${getTrendIcon(item.trend)}</td>
      </tr>
    `;
    $tbody.append(row);
  });
}

function getAllTopCustomers() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/CustomerApi.php?type=top",
    type: "GET",
    success: function (response) {
      renderTopCustomers(response.data);
    },
  });
}

function renderTopCustomers(data) {
  var $tbody = $("#top-customers-table-body");
  $tbody.empty();
  if (!data || data.length === 0) {
    $tbody.append(`
      <tr>
        <td colspan="4" class="text-center">Không có khách hàng bán chạy</td>
      </tr>
    `);
    return;
  }
  data.forEach(function (item) {
    var row = `
      <tr>
        <td class="d-flex align-items-center">
          <div>
            <div class="fw-semibold">${item.customer_name}</div>
            <div class="text-muted" style="font-size:13px;">${Number(
              item.total_spent
            ).toLocaleString("vi-VN")} đ</div>
          </div>
        </td>
        <td>${item.total_orders}</td>
        <td>${Number(item.total_spent).toLocaleString("vi-VN")} đ</td>
        <td class="text-center">${getTrendIcon(item.trend)}</td>
      </tr>
    `;
    $tbody.append(row);
  });
}

$("#orders-detail-table-body").on("click", ".btn-view", function () {
  const orderId = $(this).data("id");
  loadOrderDetail(orderId);
});

function loadOrderDetail(orderId) {
  $.ajax({
    url:
      "http://localhost/Order Management/Back-End/api/OrderApi.php?id=" +
      orderId,
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
    },
    error: function (xhr, status, error) {
      console.error("Lỗi khi tải chi tiết đơn hàng:", error);
      showNotification({
        title: "Lỗi",
        message: "Đã xảy ra lỗi khi tải thông tin đơn hàng",
        type: "error",
        duration: 3000,
      });
    },
  });
}

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
