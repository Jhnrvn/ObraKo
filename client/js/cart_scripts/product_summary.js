$(document).ready(function () {
  // Function to update the total price in the summary
  function updateTotalPrice() {
    let totalPrice = 0;

    // Loop through each row in the summary table
    $("#checkoutSummary tbody tr").each(function () {
      const row = $(this);
      const quantity = parseInt(row.find(".product-quantity").text()) || 0;
      const price = parseFloat(row.find(".product-price").text().replace("₱", "").replace(".00", "")) || 0;
      totalPrice += quantity * price;
    });

    // Display the total price
    $(".total-product-price").text(`₱${totalPrice.toFixed(2)}`);
  }

  // Function to add product to the summary
  function addToSummary(product) {
    const summaryRow = `
        <tr data-product-id="${product.product_id}">
          <td >${product.name}</td>
          <td class="product-quantity">${product.quantity}</td>
          <td class="product-price hide-column" >₱${product.price.toFixed(2)}</td>
        </tr>
      `;
    $("#checkoutSummary tbody").append(summaryRow);
    updateTotalPrice(); // Recalculate the total
  }

  // Function to remove product from the summary
  function removeFromSummary(productName) {
    $(`#checkoutSummary tbody tr:contains(${productName})`).remove();
    updateTotalPrice(); // Recalculate the total
  }

  // Example: Event listeners for checkboxes (as in a cart table)
  $('input[type="checkbox"]').on("change", function () {
    const row = $(this).closest("tr");
    const product_id = row.data("product-id");
    const productName = row.find(".product-name p").text();
    const productQuantity = parseInt(row.find('input[type="text"]').val()) || 0;
    const productPrice = parseFloat(row.find(".product-price").text().replace("₱", "").replace(".00", "")) || 0;

    if ($(this).is(":checked")) {
      // Add product to the summary
      addToSummary({ product_id: product_id, name: productName, quantity: productQuantity, price: productPrice });
    } else {
      // Remove product from the summary
      removeFromSummary(productName);
    }
  });
});

// Checkout summary click event
$("#checkoutButton").on("click", function () {
  const selected_product_ids = [];
  console.log(selected_product_ids);

  // Iterate over each row in the checkout summary table and get the product IDs
  $("#checkoutSummary tbody tr").each(function () {
    const row = $(this);
    const product_id = row.data("product-id"); // Only get the product ID, not wrapped in an object
    selected_product_ids.push(product_id); // Add the product ID to the array
  });

  // Send selected product IDs to the server using AJAX
  $.ajax({
    url: "/client/php/checkout_php/checkout_products_session.php", // PHP script to handle session storage
    method: "POST",
    data: { product_ids: selected_product_ids }, // Send product IDs
    dataType: "json",
    success: function (response) {
      if (response.success) {
        window.location.href = "/client/php/checkout_php/checkout.php"; // Redirect to checkout page
      }
    },
  });
});
