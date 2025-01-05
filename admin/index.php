<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
  <link rel="shortcut icon" href="../assets/SVGs/Favicon.svg" type="image/x-icon" />
  <!-- ! css file -->
  <link rel="stylesheet" href="/client/css/modal.css" />
  <link rel="stylesheet" href="/admin/css/dashboard.css" />
</head>

<body>
  <main>
    <section class="side-bar">
      <img src="/admin/assets/SVGs/ObraKo_Logo.svg" alt="obrako logo" class="logo" />
      <div class="side-bar-options-container">
        <button class="active">Dashboard</button>
        <button id="orders">Orders</button>

        <button class="manage-products">Products</button>
      </div>
      <script>
        $(document).ready(function () {
          $("#orders").click(function () {
            window.location.href = "/admin/php/orders.php";
          });

          $(".manage-products").click(function () {
            window.location.href = "/admin/php/add_product.php";
          });
        })
      </script>
    </section>
    <section class="dashboard-main">
      <div class="dashboard-main-content">
        <h1 class="header1">Dashboard</h1>
        <p class="basic-info">Basic information about your store.</p>
        <div>
          <div class="dashboard-container">
            <!-- total orders -->
            <div class="content" id="total-orders">
              <i class="fa-solid fa-boxes-packing"></i>
              <h2>Total Orders</h2>
              <p class="total-order-number"></p>
            </div>
            <!-- total delivered orders -->
            <div class="content" id="delivered-orders">
              <i class="fa-solid fa-truck"></i>
              <h2>Delivered Orders</h2>
              <p class="total-delivered-number"></p>
            </div>
            <!-- total canceled orders -->
            <div class="content" id="canceled-orders">
              <i class="fa-solid fa-rectangle-xmark"></i>
              <h2>Canceled Orders</h2>
              <p class="total-canceled-number"></p>
            </div>
            <!-- total products -->
            <div class="content" id="total-products">
              <i class="fa-solid fa-boxes-stacked"></i>
              <h2>Total Products</h2>
              <p class="total-products-number"></p>
            </div>
          </div>
          <div class="dashboard-container-2">
            <!-- total users -->
            <div class="content-large" id="total-users">
              <i class="fa-solid fa-users"></i>
              <h2>Total Users</h2>
              <p class="total-user-number" id></p>
            </div>
            <!-- total revenue -->
            <div class="content-large" id="total-revenue">
              <i class="fa-solid fa-money-bill-trend-up"></i>
              <h2>Total Revenue</h2>
              <p class="total-number"> ₱ <span class="total-revenue">100</span>.00</p>
            </div>

          </div>
        </div>
      </div>
    </section>
  </main>
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
  <scrip src="/admin/js/dashboard.js">
    </script>
    <script src="/admin/js/dashboard/dashboard_data.js"></script>
</body>

</html>