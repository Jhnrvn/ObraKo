<?php

use Google\Service\Batch\Script;
require "../../../vendor/autoload.php";

session_start();

// mongodb database connection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$usersCollection = $database->users;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout</title>
  <!-- ! css file -->
  <link rel="stylesheet" href="../../css/checkout.css" />
  <link rel="stylesheet" href="../../css/footer.css" />
  <link rel="stylesheet" href="../../css/modal.css" />
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
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
        <i class="fa-solid fa-cart-shopping"></i>
        <span id="cart-counter" class="cart-counter"></span>
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
              url: "/client/php/products/cart_counter.php",
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
        });
      </script>
      <div class="nav-links">
        <ul>
          <li><a href="#" id="Home">Home</a></li>
          <Script>
            $("#Home").on("click", function () {
              window.location.href = "/client/index.php";
            });
          </script>
        </ul>
      </div>
    </nav>
    <section class="checkout-section">
      <div class="products-summary-container">
        <h3>Product Summary</h3>

        <?php
        // Check if product IDs are stored in the session
        if (!isset($_SESSION['selected_product_ids'])) {

          $_SESSION['selected_product_ids'] = ['none'];
        }

        // Get the selected product IDs from the session
        $selectedProductIds = $_SESSION['selected_product_ids'];

        // Connect to MongoDB
        $MongodbClient = new MongoDB\Client("mongodb://localhost:27017");
        $databaseName = "ObraKo_E-commerce";
        $database = $MongodbClient->$databaseName;
        $userCollection = $database->users;

        // Assuming user ID is stored in session
        $userId = $_SESSION['user_id'];

        // Fetch user data from MongoDB
        $user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

        if (!$user || !isset($user['cart'])) {
          die("Cart is empty or user not found.");
        }

        // Convert BSONArray to a regular PHP array
        $cartItems = json_decode(json_encode($user['cart']), true);

        // Filter the selected products from the cart based on the selected product IDs
        $selectedProducts = array_filter($cartItems, function ($item) use ($selectedProductIds) {
          // Convert the MongoDB ObjectId to string for comparison
          $productId = (string) $item['product_id']['$oid'];  // Get the product ID as string
          return in_array($productId, $selectedProductIds);  // Check if the product ID is in selected product IDs
        });

        // If no selected products, show a message
        if (empty($selectedProducts)) {
        }
        // Display the selected products
        foreach ($selectedProducts as $index => $product): ?>
          <form form action="" class="product-info-form" data - index="<?= $index ?>">
            <div class="product-info info">
              <div class="img-container">
                <!-- display product image -->
                <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="" />
              </div>
              <div class="product-name-price-container">
                <!-- display product name -->
                <p class="name"><?= htmlspecialchars($product['product_name']) ?></p>
                <!-- display product price -->
                <p class="price">Price: ₱ <?= htmlspecialchars($product['product_price']) ?></p>
                <!-- display product quantity, shipping fee and total price -->
                <div class="quantity-total-price-container">
                  <p>Quantity: <span><?= htmlspecialchars($product['quantity']) ?></span></p>
                  <p>Shipping Fee: ₱ <span><?= htmlspecialchars($product['product_shipping_fee']) ?></span>.00</p>
                  <p>total: ₱ <span><?= htmlspecialchars($product['product_total_price']) ?>.00</span></p>

                </div>
              </div>

            </div>
          </form>
        <?php endforeach; ?>
        <Script>
          window.products = [];

          <?php foreach ($selectedProducts as $index => $product): ?>
            window.products.push({
              product_id: "<?= htmlspecialchars($product['product_id']['$oid']) ?>",
              product_image: "<?= htmlspecialchars($product['product_image']) ?>",
              product_name: "<?= htmlspecialchars($product['product_name']) ?>",
              product_price: <?= htmlspecialchars($product['product_price']) ?>,
              product_shipping_fee: <?= htmlspecialchars($product['product_shipping_fee']) ?>,
              product_total_price: <?= htmlspecialchars($product['product_total_price']) ?>,
              quantity: <?= htmlspecialchars($product['quantity']) ?>,
            });
          <?php endforeach; ?>

          console.log(window.products);

        </Script>

      </div>
      <div class="products-payment-container">
        <h3>Payment Information</h3>
        <form action="">
          <h4>Shipping address</h4>
          <div class="user-default-address">
            <i class="fa-solid fa-location-dot"></i>
            <div class="user-display-address-container">
              <!--  -->
              <?php
              $MongodbClient = new MongoDB\Client("mongodb://localhost:27017");
              $databaseName = "ObraKo_E-commerce";
              $database = $MongodbClient->$databaseName;
              $userCollection = $database->users;

              // Assuming user ID is stored in session
              $userId = $_SESSION['user_id'];

              // Fetch user data from MongoDB
              $user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

              if (!$user || !isset($user['address'])) {
                die("No user address found.");
              }

              // Find the default address
              $defaultAddress = null;
              foreach ($user['address'] as $address) {
                if (isset($address['is_default']) && $address['is_default'] === true) {
                  $defaultAddress = $address;
                  break;
                }
              }

              // If no default address is found, handle the fallback case
              if (!$defaultAddress) {
                die("No default address set.");
              }
              ?>
              <div class="address-unit-number">
                <?php echo htmlspecialchars($defaultAddress['unitNumber']); ?>
              </div>
              <div class="address-post-code">
                <!-- change with address that is set to default -->
                <?php echo htmlspecialchars($defaultAddress['province_name']); ?>
                -
                <?php echo htmlspecialchars($defaultAddress['city_name']); ?>
                -
                <?php echo htmlspecialchars($defaultAddress['barangay_name']); ?>

              </div>
            </div>
            <div class="change-address-btn-container">
              <button type="button" class="change-address" onclick="changeAddress()">Change</button>

              <script>
                // go to manage account to change default address
                function changeAddress() {
                  window.location.href = "../manageMyAccount.php"
                }

                // user address for checkout process
                window.userAddress = [];

                window.userAddress.push({
                  full_name: "<?php echo htmlspecialchars($defaultAddress['fullName']); ?>",
                  mobile_number: "<?php echo htmlspecialchars($defaultAddress['mobileNumber']); ?>",
                  unit_number: "<?php echo htmlspecialchars($defaultAddress['unitNumber']); ?>",
                  province_name: "<?php echo htmlspecialchars($defaultAddress['province_name']); ?>",
                  city_name: "<?php echo htmlspecialchars($defaultAddress['city_name']); ?>",
                  barangay_name: "<?php echo htmlspecialchars($defaultAddress['barangay_name']); ?>",

                });
                console.log(window.userAddress);
              </Script>

            </div>
          </div>
          <!-- payment method container -->
          <h4>Payment Method</h4>
          <div class="payment-method-container">
            <label>
              <div class="cash-on-delivery-icon">
                <i class="fa-solid fa-money-bill"></i>
              </div>
              Cash on Delivery
              <input type="radio" name="payment-method" value="COD" id="cash-on-delivery-option" checked>
            </label>
            <label>
              <div class="paypal-icon">
                <i class="fa-brands fa-paypal"></i>
              </div>
              PayPal
              <input type="radio" name="payment-method" value="paypal" id="paypal-option">
            </label>
          </div>
          <div class="order-summary-container">
            <h4>Order Summary</h4>
            <?php
            $price = 0;
            $shippingFee = 0;
            $totalPrice = 0;
            $itemCount = array();
            foreach ($selectedProducts as $index => $product) {
              array_push($itemCount, $index); // product count
              $price += $product['product_price']; // price
              $shippingFee += $product['product_shipping_fee']; // shipping fee
              $totalPrice += $product['product_total_price']; // total price
            }
            ?>
            <div>
              <!-- display the total price of the checked products -->
              <table class="order-summary-table">
                <tbody>
                  <!-- product subtotal price -->
                  <tr>
                    <!-- item count -->
                    <td>Subtotal (<span class="item-count"><?php echo count($itemCount) ?></span> items)</td>
                    <td> ₱ <span class="product-price-display"><?php echo $price ?? 0; ?></span>.00</td>
                  </tr>
                  <!-- product shipping fee -->
                  <tr>
                    <td>Shipping Fee</td>
                    <td>₱ <span class="product-shipping-fee-display"><?php echo $shippingFee ?? 0; ?></span>.00</td>
                  </tr>
                  <!-- product total price -->
                  <tr>
                    <td>Total:</td>
                    <td>₱ <span class="product-total-price-display"><?php echo $totalPrice ?? 0; ?></span>.00</td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
          <!-- paypal button container -->
          <div id="paypal-button-container"></div>
          <!-- place order button -->
          <div class="place-order-btn-container">
            <button type="button" id="place-order-btn">PLACE ORDER</button>
          </div>
        </form>

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
  <!-- ! V8n Form Validation -->
  <script src="https://cdn.jsdelivr.net/npm/v8n/dist/v8n.min.js"></script>
  <!-- paypal API integration -->
  <script
    src="https://www.paypal.com/sdk/js?client-id=AXWlzn_i2_TfI4UcWO-6PBN9psZI0QRXjlV1sbTn9-Nrq5ZcKW7NURZEOwm5xL2QNSHH1ZINYsHYBm_L&buyer-country=PH&currency=PHP&components=buttons&enable-funding=card&disable-funding=venmo,paylater"
    data-sdk-integration-source="developer-studio"></script>
  <script src="/client/js/checkout_scripts/paypal.js"></script>
  <!-- ! Custom JS -->
  <script src="/client/js/checkout_scripts/checkout.js"></script>
</body>

</html>