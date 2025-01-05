$(function () {
  // ! fetch all products from the server
  fetch("/admin/php/products/fetch_products.php")
    .then((response) => response.json())
    .then((data) => {
      const productContainer = $(".manage-products-container");

      data.forEach((product) => {
        const productCard = `
          <form class="manage-products">
            <div class="product-image-container">
              <img src="${product.image}" alt="" class="product-image" />
              <div>
                <p class="product-name">${product.name}</p>
                <p class="available-stocks">Stock: <span>${product.stocks}</span></p>
              </div>
              <div class="edit-btn-container">
                <button type="button" class="edit-product" data-id="${product.id}">Edit</button>
                <button type="button" class="delete-product" data-id="${product.id}">Delete</button>
              </div>
            </div>
          </form>`;
        productContainer.append(productCard);
      });
    });

  // ! edit product
  $(document).on("click", ".edit-product", function () {
    const productId = $(this).data("id");
    $(".edit-product-form-container").css("display", "flex");

    function appendCategoryOptions() {
      const categorySelect = $("#edit-product-category");
      const categories = ["accessories", "clothes", "textiles", "bags", "wooden crafts"];
      categories.forEach((category) => {
        categorySelect.append(`<option value="${category}">${category}</option>`);
      });
    }

    $.ajax({
      url: "/admin/php/products/edit_product_fetch.php",
      type: "POST",
      data: {
        product_id: productId,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          const product = response.product;
          $("#edit-product-name").val(response.product.name);
          $("#edit-product-origin").val(product.origin);
          $("#edit-product-category").append(`<option value="${product.category}">${product.category}</option>`);
          console.log(product._id);
          $("#edit-product-stocks").val(product.stocks);
          $("#edit-product-price").val(product.price);
          $("#edit-product-description").val(product.description);
          $("#edit-save-btn").attr("data-id", product._id);
          appendCategoryOptions();
        }
      },
    });
  });

  // ! cancel edit
  $(".cancel-btn").on("click", function () {
    $(".edit-product-form-container").css("display", "none"); // hide the edit product form
    $("#edit-product-category").empty();
  });

  // ! update product
  $("#edit-save-btn").on("click", function () {
    // get all the values from the form
    const productId = $(this).data("id");
    const productName = $("#edit-product-name").val();
    const productOrigin = $("#edit-product-origin").val();
    const productCategory = $("#edit-product-category").val();
    const productStocks = parseInt($("#edit-product-stocks").val());
    const productPrice = parseFloat($("#edit-product-price").val());
    const productDescription = $("#edit-product-description").val();

    const validator = v8n(); // V8n form validator library

    const productNameValid = validator.string().not.empty().test(productName);
    const productOriginValid = validator.string().not.empty().test(productOrigin);
    const productCategoryValid = validator.string().not.empty().test(productCategory);
    const productStocksValid = validator.number().not.empty().test(productStocks);
    const productPriceValid = validator.number().not.empty().test(productPrice);
    const productDescriptionValid = validator.string().not.empty().test(productDescription);

    if (
      productNameValid &&
      productOriginValid &&
      productCategoryValid &&
      productStocksValid &&
      productPriceValid &&
      productDescriptionValid
    ) {
      // send the data to the server
      $.ajax({
        url: "/admin/php/products/edit_product.php",
        type: "POST",
        data: {
          product_id: productId,
          product_name: productName,
          product_origin: productOrigin,
          product_category: productCategory,
          product_stocks: productStocks,
          product_price: productPrice,
          product_description: productDescription,
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            swal
              .fire({
                title: "Success!",
                text: `${response.message}`,
                icon: "success",
                showConfirmButton: false,
                timer: 1500,
                customClass: {
                  title: "modal-title",
                  htmlContainer: "modal-text",
                  confirmButton: "confirm-button",
                  popup: "popup-radius",
                },
              })
              .then(() => {
                window.location.reload();
              });
          }
        },
      });
    } else {
      // show error message
      swal.fire({
        title: "Error!",
        text: "Please fill in all the required fields.",
        icon: "error",
        confirmButtonText: "OK",
        customClass: {
          title: "modal-title",
          htmlContainer: "modal-text",
          confirmButton: "confirm-button",
          popup: "popup-radius",
        },
      });
    }
  });

  // ! delete product
  $(document).on("click", ".delete-product", function () {
    const productId = $(this).data("id");

    swal
      .fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        customClass: {
          title: "modal-title",
          htmlContainer: "modal-text",
          confirmButton: "confirm-button",
          cancelButton: "cancel-button",
          popup: "popup-radius",
        },
      })
      .then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "/admin/php/products/delete_product.php",
            type: "POST",
            data: {
              product_id: productId,
            },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                swal
                  .fire({
                    title: "Success!",
                    text: `${response.message}`,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: {
                      title: "modal-title",
                      htmlContainer: "modal-text",
                      confirmButton: "confirm-button",
                      popup: "popup-radius",
                    },
                  })
                  .then(() => {
                    location.reload();
                  });
              }
            },
          });
        }
      });
  });
});
