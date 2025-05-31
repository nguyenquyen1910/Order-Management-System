let revenueChart,
  ordersChart,
  customersChart,
  avgOrderChart,
  revenueBarChart,
  orderStatusChart,
  orderTimeChart;
$(document).ready(function () {
  loadDataDashboard();
  const dashboardData = {
    revenue: {
      current: 2.5,
      trend: [3.2, 3.5, 2.8, 4.1, 3.9, 4.2, 4.5, 4.8, 5.2, 4.9, 5.6, 5.9],
      growth: 8.5,
    },
    orders: {
      current: 86,
      trend: [40, 45, 38, 52, 48, 55, 59, 62, 68, 65, 75, 78],
      growth: 12.3,
    },
    customers: {
      current: 42,
      trend: [12, 18, 22, 25, 30, 36, 42],
      growth: 12.8,
      dailyGrowth: [
        { date: "2024-04-01", total_customers: 38 },
        { date: "2024-04-02", total_customers: 39 },
        { date: "2024-04-03", total_customers: 40 },
        { date: "2024-04-05", total_customers: 41 },
        { date: "2024-04-14", total_customers: 42 },
      ],
    },
    avgOrder: {
      current: 320,
      trend: [180, 210, 230, 250, 275, 300, 320],
      growth: 6.7,
    },
    revenueByMonth: [
      3.2, 3.5, 2.8, 4.1, 3.9, 4.2, 4.5, 4.8, 5.2, 4.9, 5.6, 5.9,
    ],
    orderStatus: {
      processed: 65,
      pending: 25,
      cancelled: 10,
    },
    ordersByHour: [12, 19, 25, 16, 18, 30, 22],
  };
  fetchTopSellingProducts();
  getRecentOrders();
  getCustomerAnalysis();
  getRecentNotifications();
});

function loadDataDashboard() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/DashboardApi.php",
    type: "GET",
    data: {
      period: "month",
    },
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        initCharts(response.data);
        updateStats(response.data);
      }
    },
  });
}

function destroyAllCharts() {
  Chart.helpers.each(Chart.instances, function (instance) {
    instance.destroy();
  });
}

// Khởi tạo tất cả biểu đồ
function initCharts(data) {
  destroyAllCharts();

  if ($("#revenueLineChart").length) {
    revenueChart = new Chart($("#revenueLineChart")[0].getContext("2d"), {
      type: "line",
      data: {
        labels: [
          "T1",
          "T2",
          "T3",
          "T4",
          "T5",
          "T6",
          "T7",
          "T8",
          "T9",
          "T10",
          "T11",
          "T12",
        ],
        datasets: [
          {
            data: data.revenue.trend,
            borderColor: "#28a745",
            backgroundColor: "rgba(40, 167, 69, 0.1)",
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false },
        },
        scales: {
          x: { display: false },
          y: { display: false },
        },
      },
    });
  }

  // Biểu đồ mini cho đơn hàng
  if ($("#ordersLineChart").length) {
    ordersChart = new Chart($("#ordersLineChart")[0].getContext("2d"), {
      type: "line",
      data: {
        labels: [
          "T1",
          "T2",
          "T3",
          "T4",
          "T5",
          "T6",
          "T7",
          "T8",
          "T9",
          "T10",
          "T11",
          "T12",
        ],
        datasets: [
          {
            data: data.orders.trend,
            borderColor: "#dc3545",
            backgroundColor: "rgba(220, 53, 69, 0.1)",
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false },
        },
        scales: {
          x: { display: false },
          y: { display: false },
        },
      },
    });
  }

  // Biểu đồ mini cho khách hàng
  if ($("#customersLineChart").length) {
    customersChart = new Chart($("#customersLineChart")[0].getContext("2d"), {
      type: "line",
      data: {
        labels: data.customers.dailyGrowth.map((item) => {
          const date = new Date(item.date);
          return `${date.getDate()}/${date.getMonth() + 1}`;
        }),
        datasets: [
          {
            data: data.customers.dailyGrowth.map(
              (item) => item.total_customers
            ),
            borderColor: "#007bff",
            backgroundColor: "rgba(0, 123, 255, 0.1)",
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false },
        },
        scales: {
          x: { display: false },
          y: { display: false },
        },
      },
    });
  }

  // Biểu đồ mini cho đơn trung bình
  if ($("#avgOrderLineChart").length) {
    avgOrderChart = new Chart($("#avgOrderLineChart")[0].getContext("2d"), {
      type: "line",
      data: {
        labels: ["2018", "2019", "2020", "2021", "2022", "2023", "2024"],
        datasets: [
          {
            data: data.avgOrder.trend,
            borderColor: "#17a2b8",
            backgroundColor: "rgba(23, 162, 184, 0.1)",
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false },
        },
        scales: {
          x: { display: false },
          y: { display: false },
        },
      },
    });
  }

  // Biểu đồ cột doanh thu
  if ($("#revenueBarChart").length) {
    revenueBarChart = new Chart($("#revenueBarChart")[0].getContext("2d"), {
      type: "bar",
      data: {
        labels: [
          "T1",
          "T2",
          "T3",
          "T4",
          "T5",
          "T6",
          "T7",
          "T8",
          "T9",
          "T10",
          "T11",
          "T12",
        ],
        datasets: [
          {
            label: "Doanh thu (triệu)",
            data: data.revenueByMonth,
            backgroundColor: "rgba(220, 53, 69, 0.8)",
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: true,
              color: "rgba(0, 0, 0, 0.05)",
            },
            ticks: {
              callback: function (value) {
                return value + "M";
              },
            },
          },
          x: {
            grid: { display: false },
          },
        },
      },
    });
  }

  // Biểu đồ tròn trạng thái đơn hàng
  if ($("#orderStatusChart").length) {
    orderStatusChart = new Chart($("#orderStatusChart")[0].getContext("2d"), {
      type: "doughnut",
      data: {
        labels: ["Đã xử lý", "Chưa xử lý", "Đã hủy"],
        datasets: [
          {
            data: [
              data.orderStatus.processed,
              data.orderStatus.pending,
              data.orderStatus.cancelled,
            ],
            backgroundColor: [
              "rgba(40, 167, 69, 0.8)", // Xanh lá - Đã xử lý
              "rgba(255, 193, 7, 0.8)", // Vàng - Chưa xử lý
              "rgba(220, 53, 69, 0.8)", // Đỏ - Đã hủy
            ],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%",
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              boxWidth: 10,
              padding: 5,
              font: { size: 11 },
            },
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                return context.label + ": " + context.formattedValue + "%";
              },
            },
          },
        },
      },
    });
  }

  // Biểu đồ xu hướng đặt hàng theo giờ
  if ($("#orderTimeChart").length) {
    orderTimeChart = new Chart($("#orderTimeChart")[0].getContext("2d"), {
      type: "bar",
      data: {
        labels: ["8-10", "10-12", "12-14", "14-16", "16-18", "18-20", "20-22"],
        datasets: [
          {
            label: "Số đơn hàng",
            data: data.ordersByHour,
            backgroundColor: [
              "rgba(54, 162, 235, 0.5)",
              "rgba(54, 162, 235, 0.5)",
              "rgba(54, 162, 235, 0.5)",
              "rgba(54, 162, 235, 0.5)",
              "rgba(54, 162, 235, 0.5)",
              "rgba(23, 162, 184, 0.8)",
              "rgba(54, 162, 235, 0.5)",
            ],
            borderWidth: 0,
            borderRadius: 3,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { display: false },
            ticks: { display: false },
          },
          x: {
            grid: { display: false },
          },
        },
      },
    });
  }
}

// Cập nhật số liệu thống kê
function updateStats(data) {
  // Cập nhật giá trị hiển thị
  $("#revenueValue").text(data.revenue.current + "M ₫");
  $("#ordersValue").text(data.orders.current);
  $("#customersValue").text(data.customers.current);
  $("#avgOrderValue").text(data.avgOrder.current + "K ₫");

  // Cập nhật phần trăm tăng trưởng
  updateGrowthIndicator("#revenueGrowth", data.revenue.growth);
  updateGrowthIndicator("#ordersGrowth", data.orders.growth);
  updateGrowthIndicator("#customersGrowth", data.customers.growth);
  updateGrowthIndicator("#avgOrderGrowth", data.avgOrder.growth);

  // Cập nhật thống kê trạng thái đơn hàng
  $("#processedOrders").text(data.orderStatus.processed);
  $("#pendingOrders").text(data.orderStatus.pending);
  $("#cancelledOrders").text(data.orderStatus.cancelled);
  $("#total-orders-count").text(data.orderStatus.total);
}

// Cập nhật chỉ số tăng trưởng
function updateGrowthIndicator(selector, value) {
  const element = $(selector);
  if (!element.length) return;

  if (value > 0) {
    element
      .html(`<i class="fa-light fa-arrow-up me-1"></i>${value}%`)
      .removeClass("down")
      .addClass("up");
  } else if (value < 0) {
    element
      .html(`<i class="fa-light fa-arrow-down me-1"></i>${Math.abs(value)}%`)
      .removeClass("up")
      .addClass("down");
  } else {
    element.html("0%").removeClass("up down");
  }
}

function fetchTopSellingProducts() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/ProductApi.php?topSelling=1&limit=4",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data) {
        renderTopProductList(response.data);
      }
    },
  });
}

function renderTopProductList(products) {
  var $list = $("#top-selling-products");
  $list.empty();

  products.forEach(function (item) {
    $list.append(`
      <div class="top-product-item d-flex align-items-center">
        <img src="${
          item.image_url
        }" alt="${item.name}" class="me-2" style="width:40px;height:40px;object-fit:cover;">
        <div class="flex-grow-1">
          <div class="product-name">${item.name}</div>
          <div class="product-price">${Number(item.price).toLocaleString(
            "vi-VN"
          )} đ</div>
        </div>
        <div class="product-sales">${item.current_sold} đơn</div>
      </div>
    `);
  });
}

function getRecentOrders() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?recent=1&limit=5",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data) {
        renderRecentOrders(response.data);
      }
    },
  });
}

function renderRecentOrders(orders) {
  var $list = $("#recent-orders-list");
  $list.empty();
  orders.forEach(function (item) {
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
    $list.append(`
      <tr>
        <td>${item.id}</td>
        <td>${item.customer_name}</td>
        <td>${item.created_at}</td>
        <td>${Number(item.total_amount).toLocaleString("vi-VN")} đ</td>
        <td><span class="badge text-white ${spanClass}">${statusOrder}</span></td>
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

// Handle customer analysis
function getCustomerAnalysis() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/CustomerApi.php?type=analysis",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data) {
        renderCustomerAnalysis(response.data);
      }
    },
  });
}

function renderCustomerAnalysis(data) {
  const $analysis = $("#customer-analysis");
  $analysis.empty();

  const headerHtml = `
    <div class="stats-card">
      <div class="panel-header">
        <h6><i class="fa-light fa-users me-2 text-primary"></i>Phân tích khách hàng</h6>
        <a href="#" class="btn btn-sm btn-link text-decoration-none">Chi tiết</a>
      </div>

      <div class="d-flex justify-content-around mb-3 text-center">
        <div>
          <div class="fs-4 fw-bold text-primary">${
            data.total_customers || 0
          }</div>
          <div class="fs-11 text-muted">Tổng KH</div>
        </div>
        <div>
          <div class="fs-4 fw-bold text-success">${
            data.active_customers || 0
          }%</div>
          <div class="fs-11 text-muted">Hoạt động</div>
        </div>
        <div>
          <div class="fs-4 fw-bold text-warning">${
            data.new_customers || 0
          }</div>
          <div class="fs-11 text-muted">KH mới/tháng</div>
        </div>
      </div>
  `;

  const loyalCustomersHtml = `
    <div>
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fs-12 fw-semibold">Khách hàng thân thiết</span>
      </div>
      <div class="customer-list">
        ${renderLoyalCustomersList(data.loyal_customers || [])}
      </div>
    </div>
  `;

  $analysis.html(headerHtml + loyalCustomersHtml + "</div>");
}

function renderLoyalCustomersList(customers) {
  if (!customers.length) {
    return `
      <div class="text-center text-muted py-3">
        <i class="fa-light fa-users fs-4 d-block mb-2"></i>
        <span class="fs-12">Chưa có khách hàng thân thiết</span>
      </div>
    `;
  }

  return customers
    .map((customer) => {
      const initials = getCustomerInitials(customer.name);
      const levelClass = getCustomerLevelClass(customer.customer_level);
      const levelText = getCustomerLevelText(customer.customer_level);

      return `
      <div class="d-flex align-items-center p-2 border-bottom">
        <div class="customer-avatar me-2">
          <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
               style="width: 35px; height: 35px;">
            ${initials}
          </div>
        </div>
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between">
            <div class="fs-13 fw-semibold">${customer.name}</div>
            <div class="badge ${levelClass} rounded-pill px-2">${levelText}</div>
          </div>
          <div class="d-flex justify-content-between">
            <div class="fs-11 text-muted">${
              customer.total_orders
            } đơn hàng</div>
            <div class="fs-11 fw-semibold text-primary">
              ${formatCurrency(customer.total_spent)}
            </div>
          </div>
        </div>
      </div>
    `;
    })
    .join("");
}

function getCustomerInitials(name) {
  if (!name) return "";
  const parts = name.split(" ");
  if (parts.length === 1) return parts[0].charAt(0);
  return parts[0].charAt(0) + parts[parts.length - 1].charAt(0);
}

function getCustomerLevelClass(level) {
  const classes = {
    VIP: "bg-success bg-opacity-10 text-success",
    "Thân thiết": "bg-info bg-opacity-10 text-info",
    Mới: "bg-warning bg-opacity-10 text-warning",
  };
  return classes[level] || "bg-secondary bg-opacity-10 text-secondary";
}

function getCustomerLevelText(level) {
  return level || "Khách hàng";
}

function formatCurrency(amount) {
  return Number(amount).toLocaleString("vi-VN") + " đ";
}

// Lay thong bao gan day nhat
function getRecentNotifications() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/NotificationApi.php?limit=5",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200 && response.data) {
        renderNotificationList(response.data);
      }
    },
  });
}

function renderNotificationList(notifications) {
  const $list = $("#notification-list");
  $list.empty();
  let iconClass = "bg-secondary";
  let icon = "fa-thin fa-bell";
  notifications.forEach(function (item) {
    if (item.type == "success") {
      iconClass = "bg-success";
      icon = "fa-thin fa-bell";
    } else if (item.type == "warning") {
      iconClass = "bg-warning";
      icon = "fa-thin fa-bell";
    } else if (item.type == "error") {
      iconClass = "bg-danger";
      icon = "fa-thin fa-bell";
    } else if (item.type == "info") {
      iconClass = "bg-primary";
      icon = "fa-thin fa-triangle-exclamation";
    }
    const html = `
      <div class="notification-item d-flex align-items-start p-2 border-bottom">
        <div class="notification-icon me-3 mt-1">
          <span class="badge rounded-circle ${iconClass} p-2"><i class="${icon}"></i></span>
        </div>
        <div>
          <h6 class="mb-0 fs-13">${item.title}</h6>
          <p class="text-muted mb-0 fs-11">${getTimeAgo(item.created_at)}</p>
        </div>
      </div>
    `;
    $list.append(html);
  });
}

function getTimeAgo(dateString) {
  const timestamp = new Date(dateString);
  const now = new Date();
  const seconds = Math.floor((now - timestamp) / 1000);

  let interval = Math.floor(seconds / 31536000);
  if (interval > 1) {
    return interval + " năm trước";
  }

  interval = Math.floor(seconds / 2592000);
  if (interval > 1) {
    return interval + " tháng trước";
  }

  interval = Math.floor(seconds / 86400);
  if (interval > 1) {
    return interval + " ngày trước";
  }

  interval = Math.floor(seconds / 3600);
  if (interval > 1) {
    return interval + " giờ trước";
  }

  interval = Math.floor(seconds / 60);
  if (interval > 1) {
    return interval + " phút trước";
  }
  if (seconds < 60) {
    return "Vừa xong";
  }
  return Math.floor(seconds) + " giây trước";
}
