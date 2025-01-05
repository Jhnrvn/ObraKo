// product shipping

// add product quantity button
document.querySelectorAll(".add-quantity").forEach((button) => {
  button.addEventListener("click", function () {
    const rowIndex = this.getAttribute("data-index");
    const quantityInput = document.getElementById(`quantity-${rowIndex}`);
    const priceElement = document.getElementById(`price-${rowIndex}`);
    const totalElement = document.getElementById(`total-${rowIndex}`);

    // Parse values
    let quantity = parseInt(quantityInput.value);
    const price = parseFloat(priceElement.textContent.replace("₱", "").trim());

    // Update quantity and total
    quantity += 1;
    quantityInput.value = quantity;
    const total = quantity * price;
    totalElement.textContent = `₱${total.toFixed(2)}`;

    // Optional: Call a function to sync with backend
    updateQuantity(rowIndex, quantity, total);
  });
});

document.querySelectorAll(".subtract-quantity").forEach((button) => {
  button.addEventListener("click", function () {
    const rowIndex = this.getAttribute("data-index");
    const quantityInput = document.getElementById(`quantity-${rowIndex}`);
    const priceElement = document.getElementById(`price-${rowIndex}`);
    const totalElement = document.getElementById(`total-${rowIndex}`);

    // Parse values
    let quantity = parseInt(quantityInput.value);
    const price = parseFloat(priceElement.textContent.replace("₱", "").trim());

    // Update quantity and total if quantity > 1
    if (quantity > 1) {
      quantity -= 1;
      quantityInput.value = quantity;
      const total = quantity * price;
      totalElement.textContent = `₱${total.toFixed(2)}`;

      // Optional: Call a function to sync with backend
      updateQuantity(rowIndex, quantity, total);
    }
  });
});

// Example backend sync function
function updateQuantity(rowIndex, quantity, total) {
  console.log(`Row: ${rowIndex}, Quantity: ${quantity}, Total: ${total}`);
  // Add AJAX logic here if needed to update the backend
  const productId = document.getElementById(`product-id-${rowIndex}`).value; // Assume there's a hidden input for the product ID

  $.ajax({
    url: "/client/php/cart_php/quantity_update.php",
    type: "POST",
    data: {
      productId: productId,
      quantity: quantity,
      total: total,
    },
    dataType: "json",
  });
}
