// ADD IMAGE OF PRODUCT
function previewImage(event, squareId) {
  const file = event.target.files[0]; // Get the file
  const square = document.getElementById(squareId); // Get the square element

  // Check if a file was selected
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      // Create an image element to display the uploaded image
      const img = document.createElement("img");
      img.src = e.target.result; // Set the image source to the uploaded file
      square.innerHTML = ""; // Clear any previous content
      square.appendChild(img); // Append the new image to the square
    };
    reader.readAsDataURL(file); // Read the file as a data URL
  }
}

function previewImage(event, id) {
  const input = event.target;
  const preview = document.getElementById(`preview-image-${id}`);

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = "block";
    };

    reader.readAsDataURL(input.files[0]);
  }
}

$(".add-product").on("click", function () {
  // get the value of the user input
  const productName = $("#product-name").val();
  const productOrigin = $("#product-origin").val();
  const productCategory = $("#add-product-category").val();
  const productStocks = parseInt($("#product-stocks").val());
  const productPrice = parseFloat($("#product-price").val());
  const productDescription = $("#product-description").val();

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
    //store all form data in an object
    const formData = new FormData();

    formData.append("product-name", productName);
    formData.append("product-origin", productOrigin);
    formData.append("product-category", productCategory);
    formData.append("product-stocks", productStocks);
    formData.append("product-price", productPrice);
    formData.append("product-description", productDescription);

    // Collect file inputs
    $('input[name="additionalImages[]"]').each(function () {
      if (this.files[0]) {
        formData.append("additionalImages[]", this.files[0]);
      }
    });

    // send request
    $.ajax({
      url: "/admin/php/products/upload.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      beforeSend: function () {
        // Show loading animation
        Swal.fire({
          text: "Please wait while your request is being processed.",
          imageUrl: "/admin/assets/GIFs/upload.gif",
          allowOutsideClick: false,
          showConfirmButton: false,
          customClass: {
            title: "modal-title",
            image: "modal-img",
            htmlContainer: "modal-text",
            confirmButton: "confirm-button",
            popup: "popup-radius",
          },
          didOpen: () => {
            Swal.showLoading();
          },
        });
      },
      success: function (response) {
        if (response.success) {
          Swal.close();
          // show modal with success message
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
  } else {
    swal.fire({
      title: "Error!",
      text: "Please fill out all the required fields.",
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
