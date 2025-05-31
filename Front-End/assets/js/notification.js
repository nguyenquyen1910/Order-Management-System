/**
 * Hàm hiển thị thông báo chung cho toàn bộ trang web
 * @param {Object} options - Các tùy chọn cho thông báo
 * @param {string} options.title - Tiêu đề thông báo
 * @param {string} options.message - Nội dung thông báo
 * @param {string} options.type - Loại thông báo: 'success', 'warning', 'error', 'info'
 * @param {number} options.duration - Thời gian hiển thị (ms), mặc định 5000ms
 * @param {string} options.position - Vị trí: 'top-right', 'top-left', 'bottom-right', 'bottom-left', 'top-center', 'bottom-center'
 * @param {boolean} options.dismissible - Cho phép đóng thông báo bằng nút X
 * @param {boolean} options.progressBar - Hiển thị thanh tiến trình
 * @param {Function} options.onClose - Hàm callback khi thông báo đóng
 */
function showNotification(options) {
  // Xử lý tham số dạng cũ (title, message, type)
  if (typeof options === "string") {
    const title = arguments[0];
    const message = arguments[1];
    const type = arguments[2];
    options = {
      title: title,
      message: message,
      type: type,
    };
  }

  // Thiết lập giá trị mặc định
  const settings = {
    title: options.title || "Thông báo",
    message: options.message || "",
    type: options.type || "info",
    duration: options.duration !== undefined ? options.duration : 5000,
    position: options.position || "top-right",
    dismissible: options.dismissible !== undefined ? options.dismissible : true,
    progressBar: options.progressBar !== undefined ? options.progressBar : true,
    onClose: options.onClose || null,
  };

  // Loại bỏ thông báo cũ nếu có cùng vị trí
  const containerId = `notification-container-${settings.position}`;
  if (!$(`#${containerId}`).length) {
    // Tạo container cho vị trí này nếu chưa tồn tại
    createPositionContainer(containerId, settings.position);
  }

  // Tạo ID duy nhất cho thông báo
  const notificationId = "notification-" + Date.now();

  // Xác định màu sắc và biểu tượng dựa trên loại thông báo
  let bgColor, textColor, icon, borderColor;
  switch (settings.type) {
    case "success":
      bgColor = "#22bb33";
      textColor = "#fff";
      borderColor = "#45a049";
      icon = '<i class="fa-light fa-check-circle me-2 text-white"></i>';
      break;
    case "warning":
      bgColor = "#f0ad4e";
      textColor = "#fff";
      borderColor = "#e68a00";
      icon = '<i class="fa-light fa-exclamation-circle me-2 text-white"></i>';
      break;
    case "error":
      bgColor = "#bb2124";
      textColor = "#fff";
      borderColor = "#d32f2f";
      icon = '<i class="fa-light fa-triangle-exclamation me-2 text-white"></i>';
      break;
    case "info":
    default:
      bgColor = "#2196F3";
      textColor = "#fff";
      borderColor = "#0b7dda";
      icon = '<i class="fa-light fa-info-circle me-2 text-white"></i>';
      break;
  }

  // Tạo HTML cho thông báo
  const notificationHtml = `
    <div id="${notificationId}" class="notification" style="background-color: ${bgColor}; color: ${textColor}; border-left: 4px solid ${borderColor}; margin-bottom: 10px; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); overflow: hidden; opacity: 0; transform: translateX(50px); transition: all 0.3s ease;">
      <div style="padding: 15px; display: flex; align-items: flex-start;">
        <div style="flex-grow: 1;">
          ${icon}<span class="text-white" style="font-weight: bold;">${settings.title}</span>
          <p style="margin: 5px 0 0 0; font-size: 14px;">${settings.message}</p>
        </div>
        ${
          settings.dismissible
            ? `
        <button class="close-notification" style="background: transparent; border: none; color: ${textColor}; font-size: 16px; cursor: pointer; margin-left: 10px;">
          <i class="fa-light fa-times"></i>
        </button>`
            : ""
        }
      </div>
      ${
        settings.progressBar
          ? `
      <div class="notification-progress" style="height: 4px; background-color: rgba(255,255,255,0.5); width: 100%;"></div>`
          : ""
      }
    </div>
  `;

  // Thêm thông báo vào container
  $(`#${containerId}`).append(notificationHtml);

  // Hiển thị thông báo với hiệu ứng
  setTimeout(() => {
    $(`#${notificationId}`).css({
      opacity: "1",
      transform: "translateX(0)",
    });
  }, 10);

  // Thêm hiệu ứng progress bar nếu được bật
  if (settings.progressBar && settings.duration > 0) {
    $(`#${notificationId} .notification-progress`).animate(
      { width: "0%" },
      settings.duration
    );
  }

  // Thêm sự kiện click để đóng thông báo
  $(`#${notificationId} .close-notification`).on("click", function () {
    closeNotification(notificationId, settings.onClose);
  });

  // Tự động đóng sau thời gian duration (nếu duration > 0)
  if (settings.duration > 0) {
    setTimeout(function () {
      closeNotification(notificationId, settings.onClose);
    }, settings.duration);
  }

  // Trả về ID của thông báo để có thể đóng nó theo cách thủ công
  return notificationId;
}

/**
 * Đóng thông báo theo ID
 * @param {string} id - ID của thông báo
 * @param {Function} callback - Hàm callback khi đóng xong
 */
function closeNotification(id, callback) {
  $(`#${id}`).css({
    opacity: "0",
    transform: "translateX(50px)",
  });

  setTimeout(() => {
    $(`#${id}`).remove();
    if (typeof callback === "function") {
      callback();
    }
  }, 300);
}

/**
 * Tạo container cho vị trí thông báo
 * @param {string} containerId - ID của container
 * @param {string} position - Vị trí của container
 */
function createPositionContainer(containerId, position) {
  // Xác định style dựa trên vị trí
  let style = "position: fixed; z-index: 9999; max-width: 400px;";

  switch (position) {
    case "top-right":
      style += "top: 20px; right: 20px;";
      break;
    case "top-left":
      style += "top: 20px; left: 20px;";
      break;
    case "bottom-right":
      style += "bottom: 20px; right: 20px;";
      break;
    case "bottom-left":
      style += "bottom: 20px; left: 20px;";
      break;
    case "top-center":
      style += "top: 20px; left: 50%; transform: translateX(-50%);";
      break;
    case "bottom-center":
      style += "bottom: 20px; left: 50%; transform: translateX(-50%);";
      break;
    default:
      style += "top: 20px; right: 20px;";
  }

  // Tạo container và thêm vào body
  $("body").append(`<div id="${containerId}" style="${style}"></div>`);
}
