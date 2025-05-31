$(document).ready(function () {
  $("#signUp").on("click", function () {
    $("#container").addClass("right-panel-active");
  });
  $("#signIn").on("click", function () {
    $("#container").removeClass("right-panel-active");
  });

  // Login
  $("#loginForm").on("submit", function (e) {
    e.preventDefault();
    const usernameOrEmail = $("#usernameOrEmail").val();
    const password = $("#password").val();
    const data = {
      action: "login",
      usernameOrEmail: usernameOrEmail,
      password: password,
    };
    $.ajax({
      type: "POST",
      url: "http://localhost/Order Management/Back-End/api/AuthApi.php",
      data: JSON.stringify(data),
      contentType: "application/json",
      success: function (response) {
        if (response.success) {
          if (
            response.data.role === "admin" ||
            response.data.role === "manager"
          ) {
            localStorage.setItem("currentUser", JSON.stringify(response.data));
            showNotification({
              title: "Success",
              message: "Đăng nhập thành công!",
              type: "success",
              duration: 2000,
            });
            setTimeout(function () {
              window.location.href =
                "http://localhost/Order%20Management/Front-End/index.php";
            }, 1500);
          } else {
            showNotification({
              title: "Warning",
              message: "Tài khoản của bạn không có quyền truy cập!",
              type: "warning",
            });
          }
        } else {
          showNotification({
            title: "Error",
            message: "Sai tên tài khoản hoặc mật khẩu!",
            type: "error",
          });
        }
      },
    });
  });
});
