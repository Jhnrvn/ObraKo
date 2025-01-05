<?php
require '../../../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$userCollection = $database->users;
$productCollection = $database->products;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Obrako - Orders</title>
  <link rel="stylesheet" href="/client/css/orders.css" />
  <link rel="stylesheet" href="/client/css/modal.css" />
  <link rel="stylesheet" href="/client/css/footer.css" />
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
        <img src="../../assets/SVGs/ObraKo_Logo.svg" alt="ObraKo Logo" class="obrako-logo" id="obrako-logo"
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
          <li><a href="#" id="cart">Cart</a></li>
          <li><a href="#" id="manage-account">Manage Account</a></li>
          <li><a href="#" id="home">Home</a></li>
          <script>
            $("#cart").on("click", function () {
              window.location.href = "/client/php/cart_php/userCart.php";
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
    <!-- user orders section -->
    <section class="orders-section">
      <div action="" class="user-order-container">
        <h2 class="container-header">Orders</h2>
        <div class="product-orders-container">
          <?php
          $user = $userCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
          $orderedProducts
            = $user['orders'] ?? [];
          foreach ($orderedProducts as $index => $product): ?>
            <form action="" class="product-orders-card">
              <!-- delivery address -->
              <section class="delivery-address-container">
                <div class="icon-container">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="user-address-container">
                  <div class="unit-address">
                    <p></p>
                    <?= htmlspecialchars($product['user_address'][0]['unit_number']) ?>
                  </div>
                  <div class="post-code">
                    <p>
                      <?= htmlspecialchars($product['user_address'][0]['province_name']) ?>
                      -
                      <?= htmlspecialchars($product['user_address'][0]['city_name']) ?>
                      -
                      <?= htmlspecialchars($product['user_address'][0]['barangay_name']) ?>
                    </p>
                  </div>

                  <div class="order-date">

                    <p>
                      place order date:
                      <span>
                        <?php
                        $orderDate = $product['order_date']->toDateTime();
                        echo $orderDate->format('j-m-y');
                        ?>
                      </span>
                    </p>
                  </div>
                </div>
              </section>
              <!-- product details-->
              <section class="product-details-container">
                <img src="<?= htmlspecialchars($product['product'][0]['product_image']) ?>" alt=""
                  class="product-image" />
                <!-- product details -->
                <div class="product-details">
                  <p class="product-name"><?= htmlspecialchars($product['product'][0]['product_name']) ?></p>
                  <div class="product-price-quantity">
                    <p>
                      Qty: <span><?= htmlspecialchars($product['product'][0]['quantity']) ?></span>
                    </p>
                    <p>
                      Shipping fee: ₱
                      <span><?= htmlspecialchars($product['product'][0]['product_shipping_fee']) ?></span>.00
                    </p>
                    <p>
                      Total: ₱ <span><?= htmlspecialchars($product['product'][0]['product_total_price']) ?></span>.00
                    </p>
                  </div>
                </div>
              </section>
              <!-- order status and buttons -->
              <section class="status-container">
                <div>
                  <p class="order-status-header">Order Status</p>

                  <div class="status-badge">
                    <?php
                    if (htmlspecialchars($product['status']) == "pending") {
                      echo '<p class="badge bg-danger">pending</p>';
                    } elseif (htmlspecialchars($product['status']) == "processing") {
                      echo '<p class="badge bg-warning">processing</p>';
                    } elseif (htmlspecialchars($product['status']) == "shipped") {
                      echo '<p class="badge bg-primary">shipped</p>';
                    } elseif (htmlspecialchars($product['status']) == "delivered") {
                      echo '<p class="badge bg-success">delivered</p>';
                    }
                    ?>
                  </div>
                  <div class="status-badge">
                    <p><?= htmlspecialchars($product['mode_of_payment']) ?></p>
                  </div>
                </div>
                <div class="button-container">
                  <?php
                  if (htmlspecialchars($product['status']) == "pending") {
                    echo '<button type="button" class="cancel-btn" data-index="' . $index . '">Cancel</button>';
                  } elseif (htmlspecialchars($product['status']) == "delivered") {
                    echo '<button type="button" class="confirm-btn" data-index="' . $index . '" >Confirm</button>';
                  } else {
                    echo '<button type="button" class="cancel-btn" data-index="' . $index . '" title="disabled" disabled >Cancel</button>';
                  }
                  ?>
                </div>
              </section>
            </form>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <section class="orders-section">
      <div action="" class="user-order-container">
        <h2 class="container-header">Order History</h2>
        <div class="product-orders-container" id="order-history">

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
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/23c655eb58.js" crossorigin="anonymous"></script>
  <!-- Custom JS -->
  <script src="/client/js/orders_scripts/cancel_order.js"></script>
  <script src="/client/js/orders_scripts/confirm_delivery.js"></script>
  <script src="/client/js/orders_scripts/append_order_history.js"></script>

</body>

</html>