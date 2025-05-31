<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng nhập</title>
    <link
      rel="icon"
      href="assets/images/favicon-removebg-preview.png"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="assets/css/login.css" />
  </head>
  <body class="bg-light">
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form id="registerForm" action="#">
                <h3>Tạo tài khoản</h3>
                <span class="mt-3">Họ và tên</span>
                <input type="text">
                <span class="mt-3">Email</span>
                <input type="email">
                <span class="mt-3">Mật khẩu</span>
                <input type="password">
                <button class="btn-signup mt-3">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form id="loginForm" action="#">
                <h3>Đăng nhập</h3>
                <span class="mt-3">Email hoặc tên tài khoản</span>
                <input type="email" id="usernameOrEmail">
                <span class="mt-3">Mật khẩu</span>
                <input type="password" id="password">
                <a href="#" class="mt-3 d-flex justify-content-end" id="forgotPassword">Quên mật khẩu?</a>
                <button class="btn-signin" id="loginButton" type="submit">Đăng nhập</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <img src="assets/images/favicon-removebg-preview.png" alt="">
                    <h1>Chào mừng trở lại</h1>
                    <p>Đăng nhập để tiếp tục sử dụng dịch vụ của chúng tôi</p>
                    <button class="btn-signup-transfer" id="signIn">Đăng nhập</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <img src="assets/images/favicon-removebg-preview.png" alt="">
                    <h1>Chào mừng bạn đến với trang web của chúng tôi</h1>
                    <p>Đăng ký để bắt đầu</p>
                    <button class="btn-signup-transfer" id="signUp">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="assets/js/notification.js"></script>
    <script src="assets/js/toast-message.js"></script>
    <script src="assets/js/login.js"></script>
  </body>
</html>
