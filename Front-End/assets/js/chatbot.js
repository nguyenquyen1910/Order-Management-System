$(document).ready(function () {
  // Hiển thị/ẩn chatbot
  $("#chatbot-btn").on("click", function () {
    $("#chatbot-container").toggleClass("active");
  });

  // Đóng chatbot
  $("#close-chatbot").on("click", function () {
    $("#chatbot-container").removeClass("active");
  });

  function typeWriterEffect(element, text, speed = 20) {
    let i = 0;
    function typing() {
      if (i < text.length) {
        element.append(text.charAt(i));
        i++;
        setTimeout(typing, speed);
      }
    }
    typing();
  }

  // Xử lý gửi tin nhắn
  function sendMessage() {
    const userInput = $("#user-input").val().trim();
    if (!userInput) return;

    // Thêm tin nhắn của người dùng vào khung chat
    $("#chatbot-messages").append(`
            <div class="message message-user">${userInput}</div>
        `);

    // Xóa nội dung nhập
    $("#user-input").val("");

    // Cuộn xuống tin nhắn mới nhất
    $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);

    // Thêm hiệu ứng đang suy nghĩ
    $("#chatbot-messages").append(`
      <div class="message message-bot typing">
        <div class="message-content">
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
    `);
    $("#chatbot-messages").scrollTop($("#chatbot-messages")[0].scrollHeight);

    setTimeout(function () {
      // API request
      $.ajax({
        url: "http://localhost:8000/api/chat",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({ message: userInput }),
        success: function (response) {
          // Xóa trạng thái đang nhập
          $(".typing").remove();

          if (response.status === "success") {
            // Thêm tin nhắn của bot
            const botMsg = $(`
              <div class="message message-bot">
                <div class="message-content"></div>
              </div>
            `);
            $("#chatbot-messages").append(botMsg);
            $("#chatbot-messages").scrollTop(
              $("#chatbot-messages")[0].scrollHeight
            );

            // Gõ từng chữ
            typeWriterEffect(
              botMsg.find(".message-content"),
              response.data.message,
              20
            );
          } else {
            // Hiển thị thông báo lỗi
            $("#chatbot-messages").append(`
              <div class="message message-bot error">
                <div class="message-content">${response.data.message}</div>
              </div>
            `);
          }

          // Cuộn xuống tin nhắn mới nhất
          $("#chatbot-messages").scrollTop(
            $("#chatbot-messages")[0].scrollHeight
          );
        },
        error: function (xhr, status, error) {
          // Xóa trạng thái đang nhập
          $(".typing").remove();

          // Hiển thị thông báo lỗi
          $("#chatbot-messages").append(`
            <div class="message message-bot error">
              <div class="message-content">Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.</div>
            </div>
          `);

          // Cuộn xuống tin nhắn mới nhất
          $("#chatbot-messages").scrollTop(
            $("#chatbot-messages")[0].scrollHeight
          );
        },
      });
    }, 1000);
  }

  // Xử lý sự kiện khi nhấn nút gửi
  $("#send-message").on("click", function () {
    sendMessage();
  });

  // Xử lý sự kiện khi nhấn Enter trong ô nhập
  $("#user-input").on("keypress", function (e) {
    if (e.which === 13) {
      sendMessage();
    }
  });
});
