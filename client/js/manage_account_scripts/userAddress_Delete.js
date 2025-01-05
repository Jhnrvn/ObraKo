document.querySelectorAll(".deleteBtn").forEach((button) => {
  button.addEventListener("click", function () {
    const rowIndex = this.getAttribute("data-index");
    Swal.fire({
      title: "Are you sure?",
      text: "You want to delete this address?",
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
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/client/php/manage_account_php/userAddress_Delete.php",
          type: "POST",
          data: {
            index: rowIndex,
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              Swal.fire({
                title: "Success!",
                text: `${response.message}`,
                icon: "success",
                showConfirmButton: false,
                timer: 1500,
                customClass: {
                  title: "modal-title",
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
