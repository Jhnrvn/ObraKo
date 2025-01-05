document.querySelectorAll(".remove-product").forEach((button) => {
  button.addEventListener("click", function () {
    const rowIndex = this.getAttribute("data-index");

    $.ajax({
      url: "/client/php/cart_php/cart_delete_products.php",
      type: "POST",
      data: {
        index: rowIndex,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          location.reload();
        }
      },
    });
  });
});
