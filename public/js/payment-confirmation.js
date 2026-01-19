// Thêm nút xác nhận vào trang VNPAY
function addConfirmationButton() {
  // Kiểm tra xem đang ở trang VNPAY QR không
  if (
    window.location.href.includes("sandbox.vnpayment.vn/paymentv2/vpcpay.html")
  ) {
    // Tìm container của mã QR
    const qrContainer = document.querySelector(".qrcode-container");
    if (qrContainer) {
      // Tạo container cho nút
      const buttonContainer = document.createElement("div");
      buttonContainer.style.textAlign = "center";
      buttonContainer.style.marginTop = "20px";

      // Tạo text hướng dẫn
      const guideText = document.createElement("p");
      guideText.style.color = "#e74c3c";
      guideText.style.marginBottom = "15px";
      guideText.innerHTML =
        "<strong>Sau khi quét mã QR và thanh toán thành công, vui lòng bấm nút bên dưới để xác nhận:</strong>";
      buttonContainer.appendChild(guideText);

      // Tạo nút xác nhận
      const confirmButton = document.createElement("button");
      confirmButton.className = "btn btn-success btn-lg";
      confirmButton.textContent = "Xác nhận đã thanh toán thành công";
      confirmButton.onclick = function () {
        // Lấy order_id từ URL
        const urlParams = new URLSearchParams(window.location.search);
        const orderId = urlParams.get("vnp_TxnRef");
        if (orderId) {
          window.location.href =
            "/CartController/confirmPayment?order_id=" + orderId;
        }
      };
      buttonContainer.appendChild(confirmButton);

      // Thêm container vào sau mã QR
      qrContainer.appendChild(buttonContainer);
    }
  }
}

// Chạy function khi trang đã load xong
if (document.readyState === "complete") {
  addConfirmationButton();
} else {
  window.addEventListener("load", addConfirmationButton);
}
