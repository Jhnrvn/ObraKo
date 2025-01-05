<?php
require '../../../vendor/autoload.php';

session_start();

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$productsCollection = $database->products;
$usersCollection = $database->users;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Cart</title>
  <!-- ! Custom CSS -->
  <link rel="stylesheet" href="/client/css/userCart.css" />
  <link rel="stylesheet" href="/client/css/footer.css" />
  <link rel="stylesheet" href="/client/css/modal.css" />
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
  <link rel="shortcut icon" href="/client/assets/SVGs/Favicon.svg" type="image/x-icon" />
</head>

<body>
  <main>
    <!--! navigation bar -->
    <nav>
      <div class="logo-container">
        <img src="/client/assets/SVGs/ObraKo_Logo.svg" alt="ObraKo Logo" class="obrako-logo" id="obrako-logo"
          title="Go Back To Home Page" />
        <script>
          $("#obrako-logo").on("click", function () {
            $.ajax({
              url: "../../php/login.php",
              type: "GET",
              data: {
                action: "userCartToHomePage",
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
      <div class="nav-links">
        <ul>
          <li><a href="#" id="orders">Orders</a></li>
          <li><a href="#" id="manage-account">Manege Account</a></li>
          <li><a href="#" id="home">Home</a></li>
          <script>
            $("#orders").on("click", function () {
              window.location.href = "/client/php/orders_php/orders.php";
            });
            $("#manage-account").on("click", function () {
              window.location.href = "/client/php/manage_account_php/manageMyAccount.php";
            });
            $("#home").on("click", function () {
              window.location.href = "/client/index.php";
            });
          </script>
        </ul>
      </div>
    </nav>
    <!-- user cart section -->
    <section class="user-cart-section">
      <div class="user-cart-section-container">
        <div class="cart-total">
          <p class="cart-header"><i class="fa-solid fa-cart-arrow-down"></i> My Cart</p>

          <table id="checkoutSummary">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              <!-- Summary rows added dynamically -->
            </tbody>
          </table>
          </>
          <div class="summary">
            <!-- display the total price of the checked products -->
            <p>Total: <span class="total-product-price"></span></p>
            <button id="checkoutButton">Proceed to Payment</button>
          </div>
        </div>
        <div class="user-cart-container">
          <div class="table-container">
            <!-- user product cart table -->
            <table class="cart-table">
              <thead>
                <tr>
                  <th>Product/s</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total</th>
                  <th>Remove</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
                $products
                  = $user['cart'] ?? [];
                foreach ($products as $index => $product): ?>
                  <!-- table row -->
                  <tr data-product-id="<?= $product['product_id'] ?>"
                    data-shipping="<?= $product['product_shipping_fee'] ?>">
                    <td class="product-img-column">
                      <img src="<?= htmlspecialchars($product['product_image']) ?>" alt="" />
                      <input type="checkbox" />
                      <div class="product-name">
                        <p><?= htmlspecialchars($product['product_name']) ?></p>
                      </div>
                    </td>
                    <td>
                      <div class="cart-quantity-container">
                        <!-- subtract quantity -->
                        <button class="subtract-quantity" data-index="<?= $index ?>">
                          <i class="fa-solid fa-minus"></i>
                        </button>
                        <!-- quantity input -->
                        <input type="text" value="<?= htmlspecialchars($product['quantity']) ?>"
                          id="quantity-<?= $index ?>" />
                        <!-- add quantity -->
                        <button class="add-quantity" data-index="<?= $index ?>">
                          <i class="fa-solid fa-plus"></i>
                        </button>
                      </div>
                      <!-- product ID -->
                      <input type="hidden" value="<?= $product['product_id'] ?>" id="product-id-<?= $index ?>"
                        class="product-id-hidden" />
                    </td>
                    <td class="product-price" id="price-<?= $index ?>">
                      ₱<?= htmlspecialchars($product['product_price']) ?>.00
                    </td>
                    <td class="product-total" id="total-<?= $index ?>">
                      ₱<?= htmlspecialchars($product['product_total_price']) ?>.00
                    </td>
                    <td>
                      <button class="remove-product" data-index="<?= $index ?>">
                        <i class="fa-solid fa-trash-can"></i>
                      </button>
                      <!-- product ID -->

                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
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
  <!-- ! Custom JS -->
  <script src="/client/js/cart_scripts/userCart.js"></script>
  <script src="/client/js/cart_scripts/delete_product.js"></script>
  <script src="/client/js/cart_scripts/product_summary.js"></script>
</body>

</html>