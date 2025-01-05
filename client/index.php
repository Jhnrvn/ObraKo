<?php

require '../vendor/autoload.php';

session_start();  // start the session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;
function userInfo(): void
{
  global $collection;
  // check if the user is logged in 
  if (isset($_SESSION['user_id'])) {
    $user = $collection->findOne([
      '_id' => new
        MongoDB\BSON\ObjectId($_SESSION['user_id'])
    ]);
    $_SESSION['first_name'] = $user['firstName'];
    $_SESSION['last_name'] = $user['lastName'];
    $_SESSION['middle_name'] = $user['middleName'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_profile'] = $user['user_profile'];
    $_SESSION['password'] = $user['password'];
    $_SESSION['google_full_name'] = $user['google_full_name'];
    $_SESSION['google_id'] = $user['google_id'];
    $_SESSION['profile_picture'] = $user['profile_picture'];
  }
  ;
}
; // load User info if the user is logged in 
userInfo(); // redirect to manage account 
$redirectToManageAccount = $_GET['action']
  ?? null;
if ($redirectToManageAccount === "redirectToManageAccount") {
  header('Content-Type: application/json');
  $response = ['success' => true, 'url' => '/client/php/manage_account_php/manageMyAccount.php'];
  echo json_encode($response);
  exit();
}
;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ObraKo - Home</title>
  <!--! Custom CSS -->
  <link rel="stylesheet" href="./css/styles.css" />
  <link rel="stylesheet" href="./css/footer.css" />
  <link rel="stylesheet" href="./css/modal.css" />
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
  <link rel="shortcut icon" href="./assets/SVGs/Favicon.svg" type="image/x-icon" />
</head>

<body>
  <main>
    <!--! Navigation Bar -->
    <nav>
      <div class="logo-container">
        <img src="./assets/SVGs/ObraKo_Logo.svg" alt="ObraKo Logo" class="obrako-logo" />
      </div>
      <div class="nav-links">
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#product-categories-container">Product</a></li>
          <li><a href="">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <div class="user-options">
        <div class="search-container">
          <!-- ! search bar -->
          <input type="search" id="search-bar" placeholder="Search..." />
          <!-- ! cart -->
          <?php
          function loggedInOrNot()
          {
            if (isset($_SESSION['user_id'])): ?>

              <div class="user-control-container notification-counter">
                <i class="fa-solid fa-cart-shopping"></i>
                <span id="cart-counter" class="cart-counter"></span>
              </div>
              <script>
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
                            $("#cart-counter").hide();
                          } else {
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

              <div class="user-control-container loggedInOrNot">

                <img src="<?php
                // user profile picture
                $googleProfile = $_SESSION['profile_picture'];
                if ($googleProfile) {
                  echo $googleProfile;
                } else {
                  $userProfile = $_SESSION['user_profile'];
                  echo $userProfile;
                }
                ?>" alt="user profile" class="user-profile" id="user-profile" />
                <div class="dropdown-container">
                  <div class="dropdown-caret"></div>
                  <div class="user-name">
                    <p class="logged-in-as-text">Logged In as:</p>
                    <p class="user-name-text">
                      <?php
                      // user sign up fullname
                      $firstName = $_SESSION['first_name'];
                      $lastName = $_SESSION['last_name'];
                      $middleName = $_SESSION['middle_name'];
                      $google_fullName = $_SESSION['google_full_name'];
                      if ($firstName && $lastName) {
                        echo $firstName . " " . $middleName . " " . $lastName;
                      } else {
                        echo $google_fullName;
                      }
                      ?>
                    </p>
                  </div>
                  <button type="button" id="manage-account">
                    <i class="fa-solid fa-user"></i>
                    Manage Account
                  </button>
                  <button type="button" id="orders-btn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    My Orders
                  </button>
                  <script>
                    $("#orders-btn").on("click", () => {
                      window.location.href = "/client/php/orders_php/orders.php";
                    });
                  </script>
                  <button type="button" id="logoutBtn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                  </button>
                </div>
              <?php else: ?>
                <button type="button" class="shop-now-btn" title="Login"
                  onclick="window.location.href='../client/php/login_php/login.php'">
                  Shop Now
                </button>
              <?php endif; ?>
              <?php
          }
          loggedInOrNot();
          $action = $_POST['action'] ?? null;

          if ($action === "reload") {
            loggedInOrNot();
          }
          ;
          ?>
          </div>
        </div>
      </div>
    </nav>
    <!-- ! Header Section -->
    <section class="carousel-section" id="home">
      <div id="carouselFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
          <!-- append carousel slide -->
        </div>
      </div>
      <!-- ! Product Categories -->
      <div class="product-categories-header">
        <h4>Product Categories</h4>
        <div class="product-categories-container">
          <!-- append product categories card -->
        </div>
      </div>
    </section>
    <section class="products-section">
      <div class="products-header">
        <h4>Explore Our Products</h4>
        <div class="products-container" id="product-categories-container">
          <!-- append products card -->
        </div>
      </div>
      <button type="button" id="load-more-btn">LOAD MORE</button>
    </section>
  </main>
  <!-- Footer -->
  <?php
  include './php/login_php/footer.php';
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
  <script src="/client/js/home_page_scripts/script.js"></script>
  <script src="/client/js/home_page_scripts/products.js"></script>
  <script src="/client/js/home_page_scripts/search.js"></script>
</body>

</html>