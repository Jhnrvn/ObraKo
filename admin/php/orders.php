<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orders</title>
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
  <link rel="shortcut icon" href="../assets/SVGs/Favicon.svg" type="image/x-icon" />
  <!-- ! css file -->
  <link rel="stylesheet" href="/client/css/modal.css" />
  <link rel="stylesheet" href="/admin/css/orders.css" />
</head>

<body>
  <main>
    <div class="side-bar">
      <img src="/admin/assets/SVGs/ObraKo_Logo.svg" alt="obrako logo" class="logo" />
      <div class="side-bar-options-container">
        <button class="manage-dashboard">Dashboard</button>
        <button class="active">Orders</button>
        <button class="manage-products">Products</button>
        <script>
          $(document).ready(function () {
            $(".manage-products").click(function () {
              window.location.href = "/admin/php/add_product.php";
            });

            $(".manage-dashboard").click(function () {
              window.location.href = "/admin/index.php";
            });
          })
        </script>
      </div>
    </div>
    <section class="orders-section">
      <!-- ! orders section -->
      <div class="orders-container" id="order-status">
        <p class="header1">Orders</p>
        <div class="user-order-container" id="user-orders">
          <div class="product-header">
            <div class="product-basic-info">
              <p>Status</p>
              <p>Order Date</p>
              <p>product name</p>
            </div>
            <div class="quantity-total-container">
              <p>Qty</p>
              <p>price</p>
              <p>total price</p>
            </div>
            <div class="button-container">
              <p>Control</p>
            </div>
          </div>
        </div>
      </div>
      <!-- ! approved orders -->
      <div class="orders-container" id="approved-orders">
        <p class="header1">Delivered Orders</p>
        <div class="user-order-container" id="delivered-orders">
          <div class="product-header">
            <div class="product-basic-info">
              <p>Status</p>
              <p>Order Date</p>
              <p>product name</p>
            </div>
            <div class="quantity-total-container">
              <p>Qty</p>
              <p>price</p>
              <p>total price</p>
            </div>
            <div class="button-container">
              <p>Control</p>
            </div>
          </div>
        </div>
      </div>
      <!-- ! canceled orders -->
      <div class="canceled-orders-container" id="canceled-order">
        <p class="header1">Canceled Orders</p>
        <div class="user-order-container" id="canceled-orders">
          <div class="product-header">
            <div class="product-basic-info">
              <p>Mode of Payment</p>
              <p>Transaction ID</p>
              <p>product name</p>
            </div>
            <div class="quantity-total-container">
              <p>Qty</p>
              <p>price</p>
              <p>total price</p>
            </div>
            <div class="button-container">
              <p>Control</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- process order modal -->
  <div class="modal-container" id="process-order-modal">
    <form class="process-order-form">
      <p class="form-header">Process order</p>
      <div class="info-fields">
        <div class="user-address">
          <i class="fa-solid fa-location-dot"></i>
          <p class="user-address-para"></p>
        </div>
        <div class="user-info">
          <p class="user-name">Name: <span class="user-name-span"></span></p>
          <p class="user-phone">Phone: <span class="user-phone-span"></span></p>
        </div>
        <div class="user-info">
          <p class="user-name">Order ID: <span class="order-id-span"></span></p>
          <p class="user-phone">Payment Method: <span class="mode-of-payment-span"></span></p>
          <p class="user-phone">Transaction ID: <span class="transaction-id-span"></span></p>
        </div>
        <div class="user-info">
          <p class="user-name">Status: <span class="status-span"></span></p>
        </div>
        <div class="user-info">
          <label for=""></label>
          <select name="" id="new-status">
            <option value=""></option>
            <option value="pending">pending</option>
            <option value="processing">processing</option>
            <option value="shipped">shipped</option>
            <option value="delivered">delivered</option>
          </select>
        </div>
      </div>
      <div class="button-container-modal">
        <button type="button" class="manage-order-close-btn">Close</button>
        <button type="button" class="manage-order-ok-btn">Ok</button>
      </div>
    </form>
  </div>
  <!-- view delivered products -->
  <div class="modal-container-delivered" id="delivered-modal">
    <form class="process-order-form">
      <p class="form-header">Process order</p>
      <div class="info-fields">
        <div class="user-address">
          <i class="fa-solid fa-location-dot"></i>
          <p class="user-address-para"></p>
        </div>
        <div class="user-info">
          <p class="user-name">Name: <span class="user-name-span"></span></p>
          <p class="user-phone">Phone: <span class="user-phone-span"></span></p>
        </div>
        <div class="user-info">
          <p class="user-name">Order ID: <span class="order-id-span-delivered"></span></p>
          <p class="user-phone">Payment Method: <span class="mode-of-payment-span"></span></p>
          <p class="user-phone">Transaction ID: <span class="transaction-id-span"></span></p>
        </div>
        <div class="user-info">
          <p class="user-name">Status: <span class="status-span"></span></p>
        </div>
      </div>
      <div class="button-container-modal">
        <button type="button" class="view-order-ok-btn" id="close-viewing-modal">Ok</button>
      </div>
    </form>
  </div>
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
  <script src="/admin/js/orders/append_orders.js"></script>
  <script src="/admin/js/orders/append_canceled_orders.js"></script>
  <script src="/admin/js/orders/process_orders.js"></script>
  <script src="/admin/js/orders/delete_canceled_orders.js"></script>
  <script src="/admin/js/orders/append_delivered_orders.js"></script>
  <script src="/admin/js/orders/view_delivered_orders.js"></script>
</body>

</html>