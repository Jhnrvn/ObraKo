$(function () {
  // redirects
  $("#total-products").on("click", function () {
    window.location.href = "/admin/php/add_product.php#add-product-section";
  });

  $("#total-orders").on("click", function () {
    window.location.href = "/admin/php/orders.php#pending-orders";
  });

  $("#delivered-orders").on("click", function () {
    window.location.href = "/admin/php/orders.php#approved-orders";
  });

  $("#canceled-orders").on("click", function () {
    window.location.href = "/admin/php/orders.php#canceled-order";
  });

  // fetch total users
  fetch("/admin/php/dashboard/total_users.php")
    .then((response) => response.json())
    .then((data) => {
      const totalUsers = $(".total-user-number");
      totalUsers.text(data);
    });

  // fetch total orders, delivered orders, and canceled orders
  $.ajax({
    url: "/admin/php/dashboard/total_orders.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      const totalOrders = $(".total-order-number");
      const deliveredOrders = $(".total-delivered-number");
      const canceledOrders = $(".total-canceled-number");
      totalOrders.text(data.orderCount);
      deliveredOrders.text(data.approvedCount);
      canceledOrders.text(data.canceledCount);
    },
  });

  // fetch total count of products
  $.ajax({
    url: "/admin/php/dashboard/total_products.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      const totalProducts = $(".total-products-number");
      totalProducts.text(data.productsCount);
    },
  });

  // fetch total revenue
  $.ajax({
    url: "/admin/php/dashboard/total_revenue.php",
    type: "GET",
    dataType: "json",
    success: function (data) {
      const totalRevenue = $(".total-revenue");
      totalRevenue.text(data.totalRevenue);
    },
  });
});
