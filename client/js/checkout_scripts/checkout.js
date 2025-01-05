// hide the paypal button container as default
const paypalButton = $("#paypal-button-container");
const placeOrderButton = $("#place-order-btn");
paypalButton.hide();

// show the paypal button container
$("#paypal-option").on("change", () => {
  paypalButton.show();
  placeOrderButton.hide();
});

// hide the paypal button container
$("#cash-on-delivery-option").on("change", () => {
  paypalButton.hide();
  placeOrderButton.show();
});

// fetch the single product checkout details
$("document").ready(function () {
  // Fetch the product data from the server

  fetch("/client/php/checkout_php/single_product_checkout.php", {
    method: "GET", // Use GET method to retrieve the data
    cache: "no-cache",
  })
    .then((response) => response.json()) // Parse the JSON response
    .then((data) => {
      console.log("Product Data:", data);

      // If the data is valid, you can use it here
      if (data.success) {
        const products = data.data; // The products data from the server
        const productContainer = $(".products-summary-container");
        // Do something with the data (e.g., render it on the page)
        products.forEach((product) => {
          console.log(product);
          const productCard = ` 
          <form form action="" class="product-info-form" data - index="<?= $index ?>">
            <div class="product-info info">
              <div class="img-container">
                <!-- display product image -->
                <img src="${product.product_image}" alt="" />
              </div>
              <div class="product-name-price-container">
                <!-- display product name -->
                <p class="name">${product.product_name}</p>
                <!-- display product price -->
                <p class="price">Price: ₱ <span>${product.product_price}</span>.00 </p>
                <!-- display product quantity, shipping fee and total price -->
                <div class="quantity-total-price-container">
                  <p>Quantity: <span>${product.quantity}</span></p>
                  <p>Shipping Fee: ₱ <span>${product.product_shipping_fee}</span>.00</p>
                  <p>total: ₱ <span>${product.product_total_price}</span>.00</p>
                </div>
              </div>
             </div>
           </form>`;
          // Parse the product price, shipping fee, and total price from single product checkout
          let productCount = data.data.length;
          let price = parseFloat(product.product_price);
          let shippingFee = parseFloat(product.product_shipping_fee);
          let totalPrice = parseFloat(product.product_total_price);
          // get the currently displayed price, shipping fee and total price
          let displayItemCount = parseFloat($(".item-count").text());
          let displayPrice = parseFloat($(".product-price-display").text());
          let displayShippingFee = parseFloat($(".product-shipping-fee-display").text());
          let displayTotalPrice = parseFloat($(".product-total-price-display").text());

          // update the displayed price, shipping fee and total price
          $(".item-count").text((displayItemCount += productCount));
          $(".product-price-display").text((displayPrice += price));
          $(".product-shipping-fee-display").text((displayShippingFee += shippingFee));
          $(".product-total-price-display").text((displayTotalPrice += totalPrice));

          window.products.push(product);
          productContainer.append(productCard);
        });
      } else {
        console.error("Error:", data.message); // Handle error if no data is received
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
});

// place order with Cash on Delivery payment method
placeOrderButton.on("click", () => {
  swal
    .fire({
      text: "Place the order?",
      imageUrl: "/client/assets/SVGs/ObraKo_Logo_Brown.svg",
      imageWidth: 150,
      imageHeight: 50,
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
      // place the order if the user click the confirm button
      if (result.isConfirmed) {
        window.products.forEach((product, index) => {
          const productData = window.products[index];

          $.ajax({
            url: "/client/php/checkout_php/cash_on_delivery_checkout.php",
            type: "POST",
            data: {
              mode_of_payment: "Cash on Delivery", // Cash on Delivery payment mode
              product_data: JSON.stringify([productData]), // Send product data
              user_address: JSON.stringify(window.userAddress), // Send user address (if applicable)
            },
            dataType: "json",
            success: function (response) {
              if (response.status === 1) {
                swal
                  .fire({
                    imageUrl: "/client/assets/GIFs/shopping-cart.gif",
                    showConfirmButton: false,
                    text: "Order Placed Successfully!",
                    timer: 1500,
                    customClass: {
                      image: "modal-img",
                      title: "modal-title",
                      htmlContainer: "modal-text",
                      confirmButton: "confirm-button",
                      popup: "popup-radius",
                    },
                  })
                  .then(() => {
                    window.location.href = "/client/php/orders_php/orders.php";
                  });
              } else {
                console.error("Failed to place order");
              }
            },
            error: function (xhr, status, error) {
              console.error("AJAX error:", error); // Log AJAX error if the request fails
            },
          });
        });
      }
    });
});
