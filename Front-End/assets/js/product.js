$(document).ready(function () {
  loadCategories();
  loadProducts();

  $("#product-category-filter").change(function () {
    var categoryId = $(this).val();
    if (categoryId) {
      loadProductByCategory(categoryId);
    } else {
      loadProducts();
    }
  });
});

function loadCategories() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/CategoryApi.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        var html = '<option value="">Tất cả</option>';
        var htmlOption = '<option value="Tất cả" selected>Tất cả</option>';
        response.data.forEach((ele) => {
          html += '<option value="' + ele.name + "</option>";
          htmlOption += `<option value="${ele.id}">${ele.name}</option>`;
        });
        $("#product-category-filter").html(html);
        $(".list-categories").html(htmlOption);
      } else {
        console.log("Error: " + response.message);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error: " + error);
    },
  });
}

function loadProducts() {
  $.ajax({
    url: "http://localhost/Order Management/Back-End/api/ProductApi.php",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        displayProducts(response.data);
      } else {
        $("#product-list").html("<p>Không có sản phẩm nào</p>");
      }
    },
    error: function (xhr, status, error) {
      console.log("Error: " + error);
    },
  });
}

function displayProducts(products) {
  if (products.length === 0) {
    $("#product-list").html("<p>Không có sản phẩm nào</p>");
    return;
  }

  var html = "";
  products.forEach(function (product) {
    html += `
            <div class="list p-10 position-relative text-decoration-none d-flex justify-content-between">
                <div class="row product-item">
                    <div class="col-md-2">
                        <img          
                            class="product-image rounded"
                            src="${product.image_url}"
                            alt=""
                        />
                    </div>
                    <div class="col-md-8">
                        <div class="product-description py-2">
                            <h4 class="mb-1">${product.name}</h4>
                            <p class="product-text small text-secondary mb-2">${
                              product.description
                            }</p>
                            <span
                            class="product-category badge text-secondary rounded-pill"
                            >${product.category_name}</span
                            >
                        </div>
                    </div>
                    <div class="col-md-2 text-end d-flex flex-column py-2 pe-3">
                    <div class="price text-danger fw-bold">${formatPrice(
                      product.price
                    )} đ</div>
                    <div class="buttons pt-4 fs-6">
                        <button
                        class="btn btn-sm btn-light rounded-circle me-1 edit-product"
                        title="Chỉnh sửa"
                        data-bs-toggle="modal"
                        data-bs-target="#editProductModal"
                        >
                        <i class="fa-light fa-pen-to-square"></i>
                        </button>
                        <button
                        class="btn btn-sm btn-danger rounded-circle btn-delete-product"
                        title="Xóa"
                        >
                        <i class="fa-regular fa-trash"></i>
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        `;
  });
  $("#product-list").html(html);
}

// Hàm format giá tiền
function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(price);
}
