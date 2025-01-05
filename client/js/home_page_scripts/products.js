$(document).ready(function () {
  let currentPage = 1; // Keep track of the current page

  function fetchProducts(page) {
    fetch("/client/php/products_php/fetch_products.php?page=" + page)
      .then((response) => response.json())
      .then((data) => {
        const productContainer = $(".products-container");

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

          productContainer.append(productCard);
        });

        // Enable/disable load more button based on whether more products are available
        if (data.length < 10) {
          $("#load-more-btn").hide(); // Hide the "Load More" button if there are no more products
        }
      });
  }

  // Load products when the page loads
  fetchProducts(currentPage);

  // Handle "Load More" button click
  $("#load-more-btn").click(function () {
    currentPage++; // Increment page number
    fetchProducts(currentPage);
  });
});

function viewProductDetails(productId) {
  sessionStorage.setItem("product_id", productId);

  $.ajax({
    url: "/client/php/product_viewing_php/set_product_session.php",
    type: "POST",
    data: { product_id: productId },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        window.location.href = "/client/php/product_viewing_php/product_viewing.php";
      }
    },
  });
}
