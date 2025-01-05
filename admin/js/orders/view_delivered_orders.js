$(document).on("click", ".view-order", function () {
  const order_id = $(this).data("id");
  $("#delivered-modal").css("display", "flex");

  $.ajax({
    url: "/admin/php/orders/fetch_user_delivered_info.php",
    type: "POST",
    data: {
      order_id: order_id,
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        const order = response.order;
        const address = order.user_address[0];
        // ! display address
        $(".user-address-para").text(`${address.province_name} - ${address.city_name} - ${address.barangay_name}`);
        // ! display customer details
        $(".user-name-span").text(address.full_name);
        $(".user-phone-span").text(address.mobile_number);
        // ! display order details
        $(".order-id-span-delivered").text(order.order_id);
        $(".mode-of-payment-span").text(order.mode_of_payment);
        $(".transaction-id-span").text(order.transaction_id);
        $(".status-span").text(order.status);
      }
    },
  });

  $("#close-viewing-modal").on("click", function () {
    $("#delivered-modal").css("display", "none");
  });
});
