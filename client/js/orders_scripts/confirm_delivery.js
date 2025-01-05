document.querySelectorAll(".confirm-btn").forEach((button) => {
  button.addEventListener("click", function () {
    const orderIndex = parseInt(this.getAttribute("data-index"));

    swal
      .fire({
        title: "Are you sure?",
        text: "You want to confirm the delivery?",
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
            url: "/client/php/orders_php/confirm_delivery.php",
            type: "POST",
            data: {
              index: orderIndex,
            },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                // show success message
                swal
                  .fire({
                    imageUrl: "/client/assets/GIFs/delivery-service.gif",
                    text: response.message,
                    confirmButtonText: "OK",
                    customClass: {
                      image: "modal-img",
                      title: "modal-title",
                      htmlContainer: "modal-text",
                      confirmButton: "confirm-button",
                      popup: "popup-radius",
                    },
                  })
                  .then(() => {
                    // reload the page
                    location.reload();
                  });
              }
            },
          });
        }
      });
  });
});
