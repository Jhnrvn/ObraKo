$(document).on("click", ".edit-order", function () {
  const order_id = $(this).data("id");
  $("#process-order-modal").css("display", "flex");

  $.ajax({
    url: "/admin/php/orders/edit_status_fetch.php",
    type: "POST",
    data: {
      order_id: order_id, // order id
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
        $(".order-id-span").text(order.order_id);
        $(".mode-of-payment-span").text(order.mode_of_payment);
        $(".transaction-id-span").text(order.transaction_id);
        $(".status-span").text(order.status);
      }
    },
  });

  // ! cancel button (close edit modal)
  $(".manage-order-close-btn").on("click", function () {
    $("#process-order-modal").css("display", "none");
  });
});

// ! update order status
$(document).on("click", ".manage-order-ok-btn", function () {
  const order_id = $(".order-id-span").text();
  console.log(order_id);

  $.ajax({
    url: "/admin/php/orders/update_order_status.php",
    type: "POST",
    data: {
      order_id: order_id,
      status: $("#new-status").val(),
    },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $(".modal-container").css("display", "none");
        swal
          .fire({
            title: "Success!",
            text: `${response.message}`,
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
            customClass: {
              title: "modal-title",
              htmlContainer: "modal-text",
              confirmButton: "confirm-button",
              popup: "popup-radius",
            },
          })
          .then(() => {
            location.reload();
          });
      }
    },
  });
});
