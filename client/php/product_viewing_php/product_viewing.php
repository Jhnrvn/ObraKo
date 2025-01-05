<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$productsCollection = $database->products;

// Fetch the product_id from session
$productId = isset($_SESSION['product_id']) ? $_SESSION['product_id'] : null;
// Ensure $productId is valid before using it
if (!$productId || !is_string($productId)) {
  die('Invalid product ID');
}

$product = $productsCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($productId)]);
if ($product === null) {
  die('Product not found');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ObraKo - Product</title>
  <link rel="stylesheet" href="../../css/productViewing.css" />
  <link rel="stylesheet" href="../../css/footer.css" />
  <link rel="stylesheet" href="../../css/modal.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="shortcut icon" href="../../assets/SVGs/Favicon.svg" type="image/x-icon" />
</head>

<body>
  <main>
    <!--! Navigation Bar -->
    <nav>
      <div class="logo-container">
        <img src="../../assets/SVGs/ObraKo_Logo.svg" alt="ObraKo Logo" class="obrako-logo" id="obrako-logo"
          title="Go Back To Home Page" />
        <script>
          $("#obrako-logo").on("click", function () {
            $.ajax({
              url: "../../php/login.php",
              type: "GET",
              data: {
                action: "viewProductToHomePage",
              },
              dataType: "json",
              success: function (response) {
                if (response.success && response.url) {
                  window.location.href = response.url;
                }
              },
            });
          });
        </script>
      </div>
      <div class="user-control-container notification-counter">
        <?php
        if (isset($_SESSION['user_id'])) {
          echo '<i class="fa-solid fa-cart-shopping"></i>
        <span id="cart-counter" class="cart-counter"></span>';
        } else {
          echo "<i></i>";
        }
        ?>

      </div>
      <script>
        // hide the cart counter as default 
        $("#cart-counter").hide();
        $(".fa-cart-shopping").on("click", function () {
          window.location.href = "/client/php/cart_php/userCart.php";
        });

        // update cart counter
        $(document).ready(function () {
          function updateCartCounter() {
            $.ajax({
              url: "/client/php/cart_php/cart_counter.php",
              type: "GET",
              data: {
                action: "updateCartCounter",
              },
              dataType: "json",
              success: function (response) {
                if (response.success) {

                  if (response.cartCount === 0) {
                    // hide if the cart is empty
                    $("#cart-counter").hide();
                  } else {
                    // show if the cart is not empty
                    $("#cart-counter").show();
                    $("#cart-counter").text(response.cartCount);
                  }
                }
              },
            });
          }
          updateCartCounter();
        })
      </script>
      <div class="nav-links">
        <ul>
          <li><a href="#" id="Home">Home</a></li>
          <script>
            $("#Home").on("click", function () {
              window.location.href = "/client/index.php#product-categories-container";
            });
          </script>
        </ul>
      </div>
    </nav>
    <section class="product-view-section">
      <div class="product-container">
        <div class="image-container">
          <img src="<?php echo $product['additionalImages'][0]; ?>" alt="Product Image" />
          <div class="product-sub-image-container">
            <div class="product-sub-image"> <img src="<?php echo $product['additionalImages'][0]; ?>"
                alt="Product Image Sub 1" /></div>
            <div class="product-sub-image"><img src="<?php echo $product['additionalImages'][1]; ?>"
                alt="Product Image Sub 1" /></div>
            <div class="product-sub-image"> <img src="<?php echo $product['additionalImages'][2]; ?>"
                alt="Product Image Sub 1" /></div>
          </div>
        </div>

        <div class="product-basic-info">
          <h2 id="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
          <hr>
          <!-- product price -->
          <p id="product-price"><i class="fa-solid fa-tag"></i>Price: ₱ <span
              id="product-price-span"><?php echo $product['price']; ?></span>.00</p>
          <!-- product shipping fee -->
          <p id="shipping-fee">Shipping fee: ₱ <span class="shipping-fee-amount"></span>.00</p>

          <div class="button-container">
            <div class="quantity-total-container">
              <p class="quantity-label">Quantity</p>
              <div class="quantity-container">
                <div class="quantity-button-container">
                  <button type="button" class="minus-btn"><i class="fa-solid fa-minus"></i></button>
                  <input type="number" min="1" max="10" value="1" class="quantity-input" id="quantity" />
                  <button type="button" class="add-btn"><i class="fa-solid fa-plus"></i></button>
                </div>
                <div>
                  <p class="available-stocks">Available Stocks: <span
                      id="available-stocks-span"><?php echo $product['stocks']; ?></span></p>
                </div>
              </div>
              <!-- product available stock -->

            </div>
            <div class="checkout-add-to-cart-btn-container">
              <!-- checkout button -->
              <button class="checkout-btn">Checkout</button>
              <!-- add to cart button -->
              <button class="add-to-cart-btn">Add to Cart</button>
            </div>
            <input type="hidden" value="<?php echo $productId; ?>" id="product-id">
          </div>
        </div>
      </div>
    </section>

    <section class="description-section">
      <div class="description-container">
        <h3 class="description-header">Description</h3>
        <p class="product-description">
          <?php echo htmlspecialchars($product['description']); ?>
        </p>
      </div>
    </section>
  </main>
  <?php
  // ! footer
  include '../login_php/footer.php';
  ?>
  <!--! Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--! Bootstrap JS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <!--! Font Awesome -->
  <script src="https://kit.fontawesome.com/23c655eb58.js" crossorigin="anonymous"></script>
  <!-- ! Custom JS -->
  <script src="/client/js/product_viewing_scripts/productViewing.js"></script>
</body>

</html>