$(function () {
  $("#search-bar").on("keyup", function () {
    let query = $(this).val().toLowerCase();
    console.log(query);

    if (query.length >= 1) {
      $("#home").css("display", "none");
      $("#load-more-btn ").css("display", "none");
      $(".products-container").empty(); // Clear previous results

      // Send an AJAX request to the PHP script
      $.ajax({
        url: "/client/php/login_php/search_products.php",
        method: "GET",
        data: { query: query },
        success: function (data) {
          const productContainer = $(".products-container");

          if (data.length === 0) {
            productContainer.append("<p class='no-results'>No products found</p>");
          } else {
            data.forEach((product) => {
              const productCard = `
                <div class="available-products-card" onclick="viewProductDetails('${product._id["$oid"]}')">
                  <div class="product-card-container">
                    <img src="${product.image}" alt="${product.name}" loading="lazy">
                    <h3 class="product-name">${product.name}</h3>
                    <p class="product-description">${product.description}</p>
                    <hr>
                    <p class="product-price"><span>₱</span> ${product.price}.00</p>
                  </div>
                </div>`;

              // Append the new product card to the container
              productContainer.append(productCard);
            });
          }
        },
        error: function (xhr, status, error) {
          console.log("AJAX request failed: " + error);
        },
      });
    } else {
      $("#results").empty(); // Clear results if query is empty
      $("#home").css("display", "block");
      $("#load-more-btn ").css("display", "block");
    }
  });
});
