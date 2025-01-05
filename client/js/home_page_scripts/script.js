// ! show or hide menu
$("#user-profile").on("click", function () {
  $(".dropdown-container").toggle();
});

// ! user dropdown menu options
$("#manage-account").click(function () {
  $.ajax({
    url: "/client/index.php",
    type: "GET",
    data: {
      action: "redirectToManageAccount",
    },
    dataType: "json",
    success: function (response) {
      if (response.success && response.url) {
        location.href = response.url;
        console.log(response.url);
      } else {
        console.log("Redirection failed: Invalid response.");
      }
    },
  });
});

// ! carousel contents
const carouselContents = [
  {
    class: "carousel-item active",
    attribute: "data-bs-interval",
    interval: "4000",
    image: "../client/assets/carousel/homepage-carousel-image-1.png",
    alt: "carousel image 1",
  },
  {
    class: "carousel-item",
    attribute: "data-bs-interval",
    interval: "4000",
    image: "../client/assets/carousel/homepage-carousel-image-2.png",
    alt: "carousel image 2",
  },
  {
    class: "carousel-item",
    attribute: "data-bs-interval",
    interval: "4000",
    image: "../client/assets/carousel/homepage-carousel-image-3.png",
    alt: "carousel image 3",
  },
  {
    class: "carousel-item",
    attribute: "data-bs-interval",
    interval: "4000",
    image: "../client/assets/carousel/homepage-carousel-image-4.png",
    alt: "carousel image 4",
  },
];

const carouselContainer = document.querySelector(".carousel-inner");

for (let carouselContent of carouselContents) {
  const carouselItem = document.createElement("div");
  carouselItem.className = carouselContent.class;
  carouselItem.setAttribute(carouselContent.attribute, carouselContent.interval);
  carouselItem.innerHTML = `
   <img src="${carouselContent.image}" class="d-block w-100" alt="${carouselContent.alt}" loading="eager">`;

  carouselContainer.appendChild(carouselItem);
}

// ! product categories
const productCategories = [
  {
    image: "./assets/product_categories/accessories.png",
    title: "accessories",
  },
  {
    image: "./assets/product_categories/clothes.png",
    title: "clothes",
  },
  {
    image: "./assets/product_categories/textiles.png",
    title: "textiles",
  },
  {
    image: "./assets/product_categories/bags.png",
    title: "bags",
  },
  {
    image: "./assets/product_categories/wooden_crafts.png",
    title: "wooden crafts",
  },
];
// product categories container

$(document).ready(function () {
  const productCategoriesContainer = $(".product-categories-container");

  // create product categories card
  for (let productCategory of productCategories) {
    const productCategoryItem = document.createElement("div");
    productCategoryItem.className = "product-card";
    productCategoryItem.innerHTML = `
  <img src="${productCategory.image}" alt="${productCategory.title}" loading="lazy" >
  <p class="category-title" data-category="${productCategory.title}">${productCategory.title}</p>
 `;
    productCategoriesContainer.append(productCategoryItem);
  }
});

$(document).on("click", ".category-title", function () {
  const category = $(this).data("category");
  console.log("Category:", category); // Log the category value

  $.ajax({
    url: "/client/php/products_php/sort_products.php",
    type: "POST",
    data: {
      category: category,
    },
    dataType: "json",
    success: function (data) {
      const productContainer = $(".products-container");

      // Clear any existing products before appending the new ones
      productContainer.empty();

      // Check if the response contains valid products
      if (Array.isArray(data) && data.length > 0) {
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
      } else {
        // Handle case where no products are found
        productContainer.append("<p>No products found for this category.</p>");
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
    },
  });
});

// ! Logout
$("#logoutBtn").click(function () {
  swal
    .fire({
      imageUrl: "/client/assets/GIFs/logout.gif",

      text: "You want to logout?",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
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
        $.ajax({
          url: "./index.php",
          type: "POST",
          data: {
            action: "reload",
          },
        });
        $.ajax({
          url: "/client/php/login_php/logout.php",
          type: "POST",
          success: function (response) {
            if (response.success) {
              swal.fire({
                title: "Success!",
                text: `${response.message}`,
                icon: "success",
                confirmButtonText: "OK",
                customClass: {
                  title: "modal-title",
                  htmlContainer: "modal-text",
                  confirmButton: "confirm-button",
                  popup: "popup-radius",
                },
              });
            }
            location.reload();
          },
        });
      }
    });
});
