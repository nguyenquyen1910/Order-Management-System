function toast({
  title = "Success",
  message = "Tạo tài khoản thành công",
  type = "success",
  duration = 3000,
}) {
  return showNotification({
    title: title,
    message: message,
    type: type,
    duration: duration,
    position: "top-right"
  });
}
