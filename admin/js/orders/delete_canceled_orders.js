$(document).on("click", ".delete-order", function () {
  const order_id = $(this).data("id");
  console.log(order_id);
  swal
    .fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
      customClass: {
        title: "modal-title",
        htmlContainer: "modal-text",
        confirmButton: "confirm-button",
        cancelButton: "cancel-button",
        popup: "popup-radius",
      },
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/admin/php/orders/delete_canceled_orders.php",
          type: "POST",
          data: {
            order_id: order_id,
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
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
            } else {
              swal.fire({
                title: "Error!",
                text: `${response.message}`,
                icon: "error",
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6",
                customClass: {
                  title: "modal-title",
                  htmlContainer: "modal-text",
                  confirmButton: "confirm-button",
                  popup: "popup-radius",
                },
              });
            }
          },
        });
      }
    });
});
