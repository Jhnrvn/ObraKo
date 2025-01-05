// shipping fee (testing purposes)
$(document).ready(function () {
  const productPrice = parseFloat($("#product-price-span").text());
  const productShippingFee = $(".shipping-fee-amount");
  let shippingFee = 0;
  if (productPrice > 0 && productPrice <= 500) {
    shippingFee = 50;
    productShippingFee.text(shippingFee);
  } else if (productPrice > 500 && productPrice <= 1000) {
    shippingFee = 100;
    productShippingFee.text(shippingFee);
  } else if (productPrice > 1000 && productPrice <= 2000) {
    shippingFee = 150;
    productShippingFee.text(shippingFee);
  } else if (productPrice > 2000 && productPrice <= 5000) {
    shippingFee = 200;
    productShippingFee.text(shippingFee);
  } else if (productPrice > 5000) {
    shippingFee = 300;
    productShippingFee.text(shippingFee);
  }
});

// get the quantity and buttons for quantity adjustment
const quantity = $("#quantity");
const minusBtn = $(".minus-btn");
const addBtn = $(".add-btn");

// increase quantity of the product
addBtn.on("click", function () {
  quantity.val(parseInt(quantity.val()) + 1);
  if (quantity.val() > 10) {
    swal.fire({
      icon: "error",
      title: "Oops...",
      text: "You have reached the maximum quantity of 10!",
      showConfirmButton: false,
      timer: 1000,
      customClass: {
        title: "modal-title",
        htmlContainer: "modal-text",
        confirmButton: "confirm-button",
        popup: "popup-radius",
      },
    });
    // set the quantity to 10 if the user tries to increase the quantity beyond 10
    quantity.val(10);
  }
});

// decrease quantity of the product
minusBtn.on("click", function () {
  quantity.val(parseInt(quantity.val()) - 1);
  if (quantity.val() < 1) {
    quantity.val(1);
  }
});

// view other product image if the user clicks on the sub image
$(".product-sub-image-container img").on("click", function () {
  const imageUrl = $(this).attr("src");
  $(".image-container > img").attr("src", imageUrl);
});

// checkout button

$(".checkout-btn").on("click", function () {
  swal
    .fire({
      icon: "question",
      title: "checkout",
      text: "proceed to checkout?",
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
        // Store the product details in the array
        const product = [];
        const productPrice = parseFloat($("#product-price-span").text());
        const productShippingFee = parseFloat($(".shipping-fee-amount").text());
        const productTotalPrice = productPrice + productShippingFee;

        // Push to product array
        product.push({
          product_id: $("#product-id").val(),
          product_image: $(".image-container > img").attr("src"),
          product_name: $("#product-name").text(),
          product_price: productPrice,
          product_shipping_fee: productShippingFee,
          product_total_price: productTotalPrice,
          quantity: parseInt($("#quantity").val()),
        });

        // Send the product data to the server
        $.ajax({
          url: "/client/php/checkout_php/single_product_checkout.php",
          type: "POST",
          data: {
            product: JSON.stringify(product),
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              window.location.href = "/client/php/checkout_php/checkout.php";
            } else {
              // show modal that the user is not yet logged in then redirect to login if clicked the login button
              swal
                .fire({
                  imageUrl: "/client/assets/GIFs/login.gif",
                  text: "You need to be logged in to checkout.",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Login",
                  customClass: {
                    title: "modal-title",
                    image: "modal-img",
                    htmlContainer: "modal-text",
                    confirmButton: "confirm-button",
                    cancelButton: "cancel-button",
                    popup: "popup-radius",
                  },
                })
                .then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = "/client/php/login_php/login.php";
                  }
                });
            }
          }, // Expect JSON response
        });
      }
    });
});

// add to cart button
$(".add-to-cart-btn").on("click", function () {
  const productId = $("#product-id").val();
  const quantity = $("#quantity").val();
  const shippingFee = parseFloat($(".shipping-fee-amount").text());
  // shipping fee

  // check if the product id and quantity are valid
  if (!productId || isNaN(quantity) || quantity < 1) {
    Swal.fire({
      icon: "error",
      title: "Invalid Input",
      text: "Please make sure the product ID and quantity are valid.",
      showConfirmButton: false,
      timer: 1500,
      customClass: {
        title: "modal-title",
        htmlContainer: "modal-text",
        confirmButton: "confirm-button",
        popup: "popup-radius",
      },
    });
    return;
  }

  $.ajax({
    url: "/client/php/cart_php/product_carting.php",
    type: "POST",
    data: {
      productId: productId,
      quantity: quantity,
      shippingFee: shippingFee,
    },
    dataType: "json",
    success: function (response) {
      // Check if the backend responds with success
      if (response.success) {
        Swal.fire({
          imageUrl: "/client/assets/GIFs/online-shopping.gif",
          text: response.message,
          showConfirmButton: false,
          timer: 1500,
          customClass: {
            image: "modal-img",
            title: "modal-title",
            htmlContainer: "modal-text",
            confirmButton: "confirm-button",
            popup: "popup-radius",
          },
        }).then(() => {
          // send request to update cart counter after adding to cart
          $.ajax({
            url: "/client/php/cart_php/cart_counter.php",
            type: "GET",
            data: {
              action: "updateCartCounter",
            },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                // show if the cart is not empty
                $("#cart-counter").show();
                // update cart counter
                $("#cart-counter").text(response.cartCount);
              }
            },
          });
        });
      } else {
        Swal.fire({
          imageUrl: "/client/assets/GIFs/login.gif",
          text: "You need to be logged in to checkout.",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Login",
          customClass: {
            title: "modal-title",
            image: "modal-img",
            htmlContainer: "modal-text",
            confirmButton: "confirm-button",
            cancelButton: "cancel-button",
            popup: "popup-radius",
          },
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "/client/php/login_php/login.php";
          }
        });
      }
    },
  });
});
