$(document).ready(function () {
  getDeliveryStaffName();
  getDistricts();
  getOrderByDelivery();
  getStaffNameProcessing();
  getOrdersDelivering();
  getAllOrders();
  initGoogleMap();

  $.getJSON(
    "http://localhost/Order%20Management/Back-End/api/DeliveryApi.php?status=in_progress,completed",
    function (response) {
      if (response.status === 200 && response.data) {
        processOrders(response.data);
      }
    }
  );
});

let map;
function initGoogleMap() {
  map = new google.maps.Map(document.getElementById("deliveryMap"), {
    center: { lat: 21.028511, lng: 105.804817 },
    zoom: 12,
  });
}

function processOrders(orders) {
  orders.forEach(function (order) {
    const marker = new google.maps.Marker({
      position: {
        lat: parseFloat(order.current_latitude),
        lng: parseFloat(order.current_longitude),
      },
      map: map,
      title: `Đơn hàng #${order.order_id}`,
      icon: {
        url:
          order.status === "completed"
            ? "https://cdn-icons-png.flaticon.com/512/190/190411.png"
            : "https://cdn-icons-png.flaticon.com/512/684/684908.png",
        scaledSize: new google.maps.Size(32, 32),
      },
    });

    const infowindow = new google.maps.InfoWindow({
      content: `<b>Đơn hàng #${order.order_id}</b><br>
        Nhân viên: ${order.delivery_staff_name}<br>
        Người nhận: ${order.name}<br>
        Địa chỉ: ${order.receiver_address}<br>
        SĐT: ${order.receiver_phone}<br>
        Thời gian đặt hàng: ${new Date(
          order.order_created_at
        ).toLocaleString()}<br>
        Tổng thời gian giao hàng: ${Math.floor(
          (Date.parse(order.actual_delivery_time) -
            Date.parse(order.order_created_at)) /
            1000 /
            60
        )} phút<br>
        Trạng thái: ${order.status === "completed" ? "Đã giao" : "Đang giao"}`,
    });

    marker.addListener("click", function () {
      infowindow.open(map, marker);
    });

    if (order.status === "in_progress") {
      drawRouteAndAnimate(order);
    }
  });
}

function drawRouteAndAnimate(order) {
  const from = {
    lat: parseFloat(order.current_latitude),
    lng: parseFloat(order.current_longitude),
  };
  const to = {
    lat: parseFloat(order.destination_latitude),
    lng: parseFloat(order.destination_longitude),
  };

  if (order.status === "in_progress") {
    new google.maps.Marker({
      position: { lat: to.lat, lng: to.lng },
      map: map,
      icon: {
        url: "https://cdn-icons-png.flaticon.com/512/1483/1483336.png",
        scaledSize: new google.maps.Size(36, 36),
      },
      title: "Khách hàng",
    });
  }

  new google.maps.Marker({
    position: { lat: from.lat, lng: from.lng },
    map: map,
    icon: {
      url: "https://cdn-icons-png.flaticon.com/512/869/869432.png",
      scaledSize: new google.maps.Size(36, 36),
    },
    title: "Nhà hàng",
  });

  const url = `https://router.project-osrm.org/route/v1/driving/${from.lng},${from.lat};${to.lng},${to.lat}?overview=full&geometries=geojson`;
  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      if (data.code === "Ok") {
        const coords = data.routes[0].geometry.coordinates;
        const path = coords.map((coord) => ({ lat: coord[1], lng: coord[0] }));

        animateMarkerAlongRoute(path, order);
      } else {
        const path = [
          { lat: from.lat, lng: from.lng },
          { lat: to.lat, lng: to.lng },
        ];
        const routePath = new google.maps.Polyline({
          path: path,
          geodesic: true,
          strokeColor: "#007bff",
          strokeOpacity: 1.0,
          strokeWeight: 3,
          map: map,
        });
        animateMarkerAlongRoute(path, order);
      }
    });
}

function animateMarkerAlongRoute(router, order) {
  let marker = new google.maps.Marker({
    position: router[0],
    map: map,
    icon: {
      url: "https://cdn-icons-png.flaticon.com/512/9561/9561839.png",
      scaledSize: new google.maps.Size(40, 40),
    },
    title: "Đơn hàng đang giao",
  });

  const infowindow = new google.maps.InfoWindow({
    content: `<b>Đơn hàng #${order.order_id}</b><br>
      Nhân viên: ${order.delivery_staff_name}<br>
      Người nhận: ${order.name}<br>
      Địa chỉ: ${order.receiver_address}<br>
      SĐT: ${order.receiver_phone}<br>
      Thời gian đặt hàng: ${new Date(
        order.order_created_at
      ).toLocaleString()}<br>
      Trạng thái: ${order.status === "completed" ? "Đã giao" : "Đang giao"}`,
  });

  marker.addListener("click", function () {
    infowindow.open(map, marker);
  });

  let dynamicPath = new google.maps.Polyline({
    path: router,
    geodesic: true,
    strokeColor: "#007bff",
    strokeOpacity: 1.0,
    strokeWeight: 3,
    map: map,
  });

  let step = 0;
  function moveMarker() {
    if (step < router.length) {
      marker.setPosition(router[step]);
      dynamicPath.setPath(router.slice(step));
      step++;
      setTimeout(moveMarker, 1000);
    }
  }
  moveMarker();
}

function animateGoogleMarker(marker, from, to, duration, onComplete) {
  const start = Date.now();
  const timer = setInterval(function () {
    const now = Date.now();
    const progress = Math.min((now - start) / duration, 1);
    const lat = from.lat + (to.lat - from.lat) * progress;
    const lng = from.lng + (to.lng - from.lng) * progress;
    marker.setPosition({ lat, lng });
    if (progress === 1) {
      clearInterval(timer);
      if (typeof onComplete === "function") onComplete();
    }
  }, 1000 / 60);
}

function reloadMapOrders() {
  $.getJSON(
    "http://localhost/Order%20Management/Back-End/api/DeliveryApi.php?status=in_progress,completed",
    function (response) {
      if (response.status === 200 && response.data) {
        processOrders(response.data);
      }
    }
  );
}

// Lay danh sach nhan vien giao hang
function getDeliveryStaffName() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/DeliveryApi.php?staff_name",
    type: "GET",
    success: function (response) {
      renderDeliveryStaffName(response.data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function renderDeliveryStaffName(data) {
  var $dataStaffName = $("#delivery-staff-name");
  $dataStaffName.empty();
  $dataStaffName.append("<option value=''>Tất cả</option>");
  data.forEach(function (item) {
    $dataStaffName.append(
      '<option value="' + item.name + '">' + item.name + "</option>"
    );
  });
}

// Lay danh sach quan
function getDistricts() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?districts",
    type: "GET",
    success: function (response) {
      renderDistricts(response.data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function renderDistricts(data) {
  var $dataDistricts = $("#districts");
  $dataDistricts.empty();
  $dataDistricts.append("<option value=''>Tất cả</option>");
  data.forEach(function (item) {
    $dataDistricts.append(
      "<option value=" + item.district + ">" + item.district + "</option>"
    );
  });
}

// Lay tat ca don hang
function getAllOrders() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?delivery=1&timeRange=all",
    type: "GET",
    success: (response) => {
      renderOrderByDelivery(response.data);
    },
    error: (error) => {
      console.log(error);
    },
  });
}

// Lay danh sach don hang theo van chuyen
function getOrderByDelivery() {
  $(".delivery-all form").on("submit", function (e) {
    e.preventDefault();
    var timeRange = $("#time-range").val() || "all";
    var district = $("#districts").val();
    var staffName = $("#delivery-staff-name").val();
    var keyword = $(this).find('input[type="text"]').val();
    var data = {
      timeRange: timeRange,
      district: district,
      staffName: staffName,
      keyword: keyword,
    };
    $.ajax({
      url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?delivery=1",
      type: "GET",
      data: data,
      success: function (response) {
        renderOrderByDelivery(response.data);
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
}

function renderOrderByDelivery(data) {
  var $tbody = $("#delivery-orders-table-body");
  $tbody.empty();
  data.forEach(function (item) {
    var spanStatus = "";
    if (item.status === "completed") {
      spanStatus = `<span class="text-white status-active">Đã giao</span>`;
    } else if (item.status === "in_progress") {
      spanStatus = `<span class="text-white status-unprocessed">Đang giao</span>`;
    } else {
      spanStatus = `<span class="text-white status-locked">Chưa xử lý</span>`;
    }
    let assignBtn = `
      <button class="btn-delivery-view me-2 btn-assign-delivery"
        title="${
          item.status === "completed" ? "Xem giao hàng" : "Phân công giao hàng"
        }"
        data-order-id="${item.order_id}"
        data-staff-name="${item.staff_name || ""}"
        data-estimated-time="${item.estimated_time || ""}"
        data-address="${item.address || ""}">
        <i class="fa-light fa-truck"></i>
      </button>
    `;
    $tbody.append(`
      <tr>
        <td>${item.order_id}</td>
        <td>${item.created_at ? item.created_at : "Chưa xử lý"}</td>
        <td>
          <div>${item.customer_name ? item.customer_name : ""}</div>
          ${
            item.customer_phone
              ? `<small class="text-muted">${item.customer_phone}</small>`
              : ""
          }
        </td>
        <td>
          <div>${item.address ? item.address : ""}</div>
        </td>
        <td>${item.estimated_time ? item.estimated_time : "Chưa xử lý"}</td>
        <td>
          <div class="staff-name-delivery">
            ${item.staff_name ? item.staff_name : "Chưa xử lý"}
          </div>
        </td>
        <td>
          ${spanStatus}
        </td>
        <td>
          <div class="btn-group d-flex justify-content-center align-items-center">
            <button class="btn-view-order-delivery me-2" data-bs-toggle="modal"
              data-bs-target="#orderDetailModal"
              data-order-id="${item.order_id}">
              <i class="fa-regular fa-eye"></i>
            </button>
            ${assignBtn}
          </div>
        </td>
      </tr>
    `);
  });
}

// Lay danh sach nhan vien dang giao hang
function getStaffNameProcessing() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/DeliveryApi.php?staff_name_processing",
    type: "GET",
    success: function (response) {
      renderStaffNameProcessing(response.data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function renderStaffNameProcessing(data) {
  var $dataDelivery = $("#delivery-staff-list");
  $dataDelivery.empty();
  if (!data || data.length === 0) {
    $dataDelivery.append(`
      <div class="staff-item p-3 border-bottom">
        <div class="d-flex align-items-center">
          <div class="staff-info flex-grow-1">
            <h6 class="mb-1">Không có nhân viên đang giao hàng</h6>
          </div>
        </div>
      </div>
    `);
  } else {
    data.forEach(function (item) {
      $dataDelivery.append(`
        <div class="staff-item p-3 border-bottom">
          <div class="d-flex align-items-center">
            <div class="staff-info flex-grow-1">
              <h6 class="mb-1">${item.name}</h6>
              <div class="d-flex align-items-center">
                <span class="status-unprocessed me-2">Đang giao</span>
                <small class="text-muted">${item.total_order} đơn hàng</small>
              </div>
            </div>
          </div>
        </div>
      `);
    });
  }
}

// Lay danh sach don hang dang giao
function getOrdersDelivering() {
  $.ajax({
    url: "http://localhost/Order%20Management/Back-End/api/OrderApi.php?delivering",
    type: "GET",
    success: function (response) {
      renderOrdersDelivering(response.data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function renderOrdersDelivering(data) {
  var $dataDelivery = $("#delivery-orders-list");
  $dataDelivery.empty();
  if (!data || data.length === 0) {
    $dataDelivery.append(`
      <div class="order-item p-3 border-bottom">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="mb-1">Không có đơn hàng đang giao</h6>
          </div>
        </div>
      </div>
    `);
  } else {
    data.forEach(function (item) {
      $dataDelivery.append(`
        <div class="order-item p-3 border-bottom">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h6 class="mb-1">#${item.id}</h6>
              <p class="mb-1 small">${item.receiver_name}</p>
              <p class="mb-0 small text-muted">${item.receiver_address}</p>
            </div>
            <div class="text-end">
              <span class="status-unprocessed">Đang giao</span>
              <div class="small text-muted mt-1">
                Còn ${Math.floor(
                  (Date.parse(item.estimated_delivery_time) -
                    Date.parse(item.order_created_at)) /
                    1000 /
                    60
                )} phút
              </div>
            </div>
          </div>
        </div>
      `);
    });
  }
}

let address = "";

$(document).on("click", ".btn-assign-delivery", function () {
  const orderId = $(this).data("order-id");
  const staffName = $(this).data("staff-name");
  const estimatedDeliveryTime = $(this).data("estimated-time");
  address = $(this).data("address");
  $("#assign-order-id").text(orderId);
  $("#delivery-staff").val("").prop("disabled", false);
  $("#estimated-delivery-time").val("").prop("disabled", false);
  $("#assign-delivery-btn").show();
  if (staffName && estimatedDeliveryTime) {
    $("#delivery-staff").prop("disabled", true);
    $("#estimated-delivery-time").prop("disabled", true);
    $("#assign-delivery-btn").hide();

    $("#delivery-staff").html(
      `<option value="${staffName}">${staffName}</option>`
    );
    $("#estimated-delivery-time").val(estimatedDeliveryTime);
  } else {
    $.ajax({
      url: "http://localhost/Order%20Management/Back-End/api/EmployeeApi.php",
      type: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status === 200 && response.data) {
          const $select = $("#delivery-staff");
          $select
            .empty()
            .append('<option value="">Chọn nhân viên giao hàng</option>');

          response.data.forEach(function (staff) {
            $select.append(
              `<option value="${staff.id}">${staff.name}</option>`
            );
          });
        }
      },
    });
  }

  $("#assignDeliveryModal").modal("show");
});

// Lay toa do tu dia chi
const apiKey = "1a170613a7d0482b82948a8858017760";
function getCoordinatesFromAddress(address) {
  let searchAddress = address.trim();

  if (!searchAddress.toLowerCase().includes("hà nội")) {
    searchAddress += ", Hà Nội";
  }

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

// Su kien phan cong don hang
$(document).on("click", "#assign-delivery-btn", function () {
  const orderId = $("#assign-order-id").text();
  const data = {
    order_id: orderId,
    delivery_staff_name: $("#delivery-staff option:selected").text(),
    estimated_delivery_time: $("#estimated-delivery-time").val(),
  };

  if (!orderId || !data.delivery_staff_name || !data.estimated_delivery_time) {
    alert("Vui lòng chọn đơn hàng và nhân viên giao hàng");
    return;
  }

  getCoordinatesFromAddress(address).then(function (coordinates) {
    const latitude = coordinates.results[0].geometry.lat;
    const longitude = coordinates.results[0].geometry.lng;
    data.destination_latitude = latitude;
    data.destination_longitude = longitude;

    $.ajax({
      url: "http://localhost/Order%20Management/Back-End/api/DeliveryApi.php",
      type: "POST",
      data: JSON.stringify(data),
      contentType: "application/json",
      success: (response) => {
        if (response.status === 200) {
          showNotification({
            title: "Thành công",
            message: "Phân công giao hàng thành công",
            type: "success",
            duration: 3000,
          });
          $("#assignDeliveryModal").modal("hide");
          getAllOrders();
          getStaffNameProcessing();
          getOrdersDelivering();
          reloadMapOrders();
        } else {
          showNotification({
            title: "Lỗi",
            message: "Phân công giao hàng thất bại",
            type: "error",
            duration: 3000,
          });
        }
      },
    });
  });
});

$(document).on("click", ".btn-view-order-delivery", function () {
  const orderId = $(this).data("order-id");
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
