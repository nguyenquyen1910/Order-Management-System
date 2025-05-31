$(document).ready(function () {
  // Mở modal khi click vào đăng nhập
  $(".account-dropdown-item a:has(span:contains('Đăng nhập'))").on(
    "click",
    function (e) {
      e.preventDefault();
      $("#loginModal").addClass("show");
      $("body").css("overflow", "hidden");
    }
  );

  // Mở modal khi click vào đăng ký
  $(".account-dropdown-item a:has(span:contains('Đăng ký'))").on(
    "click",
    function (e) {
      e.preventDefault();
      $("#registerModal").addClass("show");
      $("body").css("overflow", "hidden");
    }
  );

  // Đóng modal khi click vào nút đóng
  $("#loginModal .btn-close").on("click", function () {
    $("#loginModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $("#registerModal .btn-close").on("click", function () {
    $("#registerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Đóng modal khi click bên ngoài
  $(window).on("click", function (e) {
    if ($(e.target).is("#loginModal")) {
      $("#loginModal").removeClass("show");
      $("body").css("overflow", "auto");
    }
    if ($(e.target).is("#registerModal")) {
      $("#registerModal").removeClass("show");
      $("body").css("overflow", "auto");
    }
  });

  // Chuyển từ đăng nhập sang đăng ký
  $("#registerLink").on("click", function (e) {
    e.preventDefault();
    $("#loginModal").removeClass("show");
    $("#registerModal").addClass("show");
  });

  // Chuyển từ đăng ký sang đăng nhập
  $("#loginLink").on("click", function (e) {
    e.preventDefault();
    $("#registerModal").removeClass("show");
    $("#loginModal").addClass("show");
  });

  // Xử lý form đăng nhập
  $("#loginModal .login-form").on("submit", function (e) {
    e.preventDefault();
    alert("Đăng nhập thành công!");
    $("#loginModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Xử lý form đăng ký
  $("#registerModal .login-form").on("submit", function (e) {
    e.preventDefault();

    // Kiểm tra mật khẩu trùng khớp
    if ($("#confirm-password").val() !== $("#reg-password").val()) {
      alert("Mật khẩu không khớp!");
      return;
    }

    // Kiểm tra đồng ý điều khoản
    if (!$("#agreeTerms").is(":checked")) {
      alert("Vui lòng đồng ý với điều khoản sử dụng!");
      return;
    }

    alert("Đăng ký thành công!");
    $("#registerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Thêm overlay vào body
  $("body").append('<div class="cart-overlay"></div>');

  // Mở modal giỏ hàng
  $(".account-cart-info.cart a").on("click", function (e) {
    e.preventDefault();
    $("#cartModal").addClass("show");
    $(".cart-overlay").addClass("show");
    $("body").css("overflow", "hidden");
  });

  // Đóng modal giỏ hàng khi click vào nút đóng
  $(".btn-close-cart").on("click", function () {
    $("#cartModal").removeClass("show");
    $(".cart-overlay").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Đóng modal giỏ hàng khi click vào overlay
  $(".cart-overlay").on("click", function () {
    $("#cartModal").removeClass("show");
    $(".cart-overlay").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Xử lý nút Thêm món
  $("#addMoreBtn").on("click", function () {
    $("#cartModal").removeClass("show");
    $(".cart-overlay").removeClass("show");
    $("body").css("overflow", "auto");
    // Cuộn lên phần thực đơn
    $("html, body").animate(
      {
        scrollTop: $(".menu-section").offset().top - 150,
      },
      500
    );
  });

  // Xử lý nút Thanh toán
  $("#checkoutBtn").on("click", function () {
    if (!$(this).prop("disabled")) {
      alert("Chuyển đến trang thanh toán");
      // Thêm logic chuyển đến trang thanh toán ở đây
    }
  });
});
