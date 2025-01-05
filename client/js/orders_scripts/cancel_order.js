document.querySelectorAll(".cancel-btn").forEach((button) => {
  button.addEventListener("click", function () {
    const orderIndex = parseInt(this.getAttribute("data-index")); // get the order index from the button's data-index attribute
    // show a confirmation dialog
    swal
      .fire({
        title: "Are you sure?",
        text: "You want to cancel the order?",
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
          // if the user confirms the deletion, send a request to the server
          $.ajax({
            url: "/client/php/orders_php/cancel_order.php",
            type: "POST",
            data: {
              index: orderIndex,
            },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                Swal.fire({
                  imageUrl: "/client/assets/GIFs/delivery.gif",
                  text: response.message,
                  showConfirmButton: false,
                  timer: 2000,
                  customClass: {
                    title: "modal-title",
                    image: "modal-img",
                    htmlContainer: "modal-text",
                    confirmButton: "confirm-button",
                    popup: "popup-radius",
                  },
                }).then(() => {
                  location.reload();
                });
              }
            },
          });
        }
      });
  });
});
