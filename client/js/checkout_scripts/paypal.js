// PAYPAL
window.paypal
  .Buttons({
    style: {
      shape: "rect",
      layout: "vertical",
      color: "gold",
      label: "paypal",
    },
    createOrder: function (data, actions) {
      console.log(window.products);
      console.log(window.userAddress);

      // Calculate the total amount
      // Function to create purchase units for each product
      const purchaseUnits = window.products.map((item) => ({
        reference_id: item.product_id,
        amount: {
          value: (
            parseFloat(item.product_price) * parseInt(item.quantity) +
            parseFloat(item.product_shipping_fee)
          ).toFixed(2),
          currency_code: "PHP",
          breakdown: {
            item_total: {
              value: (parseFloat(item.product_price) * parseInt(item.quantity)).toFixed(2),
              currency_code: "PHP",
            },
            shipping: {
              value: parseFloat(item.product_shipping_fee).toFixed(2),
              currency_code: "PHP",
            },
          },
        },
        items: [
          {
            name: item.product_name,
            unit_amount: {
              value: parseFloat(item.product_price).toFixed(2),
              currency_code: "PHP",
            },
            quantity: item.quantity,
          },
        ],
      }));

      // Create an order for all purchase units
      return actions.order.create({
        purchase_units: purchaseUnits,
      });
    },
    onApprove: function (data, actions) {
      return actions.order.capture().then(function (details) {
        // Iterate through purchase units
        details.purchase_units.forEach((unit, index) => {
          if (unit.payments && unit.payments.captures && unit.payments.captures.length > 0) {
            const transaction = unit.payments.captures[0]; // Access the transaction

            // Ensure you're sending the right product data in the AJAX request
            const productData = window.products[index]; // Ensure this is the correct product data for each unit

            // Send transaction details to the server via AJAX
            $.ajax({
              method: "POST",
              url: "paypal_processor.php",
              data: {
                mode_of_payment: "Paypal",
                transaction_id: transaction.id,
                transaction_status: transaction.status,
                products: JSON.stringify([productData]), // Send only the relevant product data
                user_address: JSON.stringify(window.userAddress),
              },
              dataType: "json",
              success: function (response) {
                if (response.status === 1) {
                  console.log(`Payment Successful for ${productData.product_name}. Transaction ID: ${transaction.id}`);
                } else {
                  console.error(`Failed to process payment for ${productData.product_name}`);
                }
              },
            });
          } else {
            console.error(`No transaction details found for product: ${window.products[index].product_name}`);
          }
        });

        // Show a success message after processing all transactions
        swal
          .fire({
            imageUrl: "/client/assets/GIFs/shopping-cart.gif",
            text: `All transactions were processed successfully.`,
            showConfirmButton: false,
            timer: 2000,
            customClass: {
              image: "modal-img",
              htmlContainer: "modal-text",
              confirmButton: "confirm-button",
              popup: "popup-radius",
            },
          })
          .then(() => {
            window.location.href = "/client/php/orders_php/orders.php";
          });
      });
    },

    onError: function (error) {
      // Handle errors and display an error message to the user
      console.log(error + "An error occurred while processing the payment. Please try again later.");
    },
  })
  .render("#paypal-button-container");
// Render the PayPal button in the specified container
