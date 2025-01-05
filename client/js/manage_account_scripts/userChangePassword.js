$("#close-set-password-form").on("click", function () {
  $(".set-password-form-container").css("display", "none");
});

$("#close-change-password-form").on("click", function () {
  $(".change-password-form-container").css("display", "none");
});

// Change Password for standard login
$("#change-password-btn").on("click", function () {
  $.ajax({
    url: "/client/php/manage_account_php/userChangePassword.php",
    type: "POST",
    data: {
      action: "tryChangePassword",
    },
    dataType: "json",
    success: function (response) {
      if (!response.success) {
        swal
          .fire({
            title: "No Password",
            text: `${response.message} \n Do you want to set your password?`,
            icon: "info",
            confirmButtonText: "OK",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
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
              // show set password form
              $(".set-password-form-container").css("display", "flex");
            }
          });
      } else {
        // show change password form
        $(".change-password-form-container").css("display", "flex");
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error:", status, error);
      console.error("Response Text:", xhr.responseText);
    },
  });
});

$("#set-password-form").submit(function (e) {
  e.preventDefault();

  const $password = $("#set-password").val();
  const $confirmPassword = $("#set-confirm-password").val();

  const validator = v8n();

  const passwordValid = validator.string().not.empty().minLength(8).test($password);
  const confirmPasswordValid = validator.string().not.empty().minLength(8).test($confirmPassword);

  if (passwordValid && confirmPasswordValid) {
    if ($password === $confirmPassword) {
      const setPasswordForm = {
        action: "setPassword",
        password: $password,
      };

      $.ajax({
        url: "/client/php/manage_account_php/userChangePassword.php",
        type: "POST",
        data: setPasswordForm,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            swal.fire({
              title: "Success!",
              text: `${response.message}`,
              icon: "success",
              confirmButtonText: "OK",
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
    } else {
      swal.fire({
        title: "Error!",
        text: "Password does not match.",
        icon: "error",
        confirmButtonText: "OK",
        customClass: {
          title: "modal-title",
          htmlContainer: "modal-text",
          confirmButton: "confirm-button",
          popup: "popup-radius",
        },
      });
    }
  } else {
    swal.fire({
      title: "Error!",
      text: "Please fill out all the required fields.",
      icon: "error",
      confirmButtonText: "OK",
      customClass: {
        title: "modal-title",
        htmlContainer: "modal-text",
        confirmButton: "confirm-button",
        popup: "popup-radius",
      },
    });
  }
});

$("#change-password-form").submit(function (e) {
  e.preventDefault();

  const $currentPassword = $("#change-current-password").val();
  const $newPassword = $("#change-new-password").val();
  const $confirmPassword = $("#change-confirm-new-password").val();

  const validator = v8n();

  const currentPasswordValid = validator.string().not.empty().minLength(8).test($currentPassword);
  const newPasswordValid = validator.string().not.empty().minLength(8).test($newPassword);
  const confirmPasswordValid = validator.string().not.empty().minLength(8).test($confirmPassword);

  if (currentPasswordValid && newPasswordValid && confirmPasswordValid) {
    if ($newPassword === $confirmPassword) {
      const changePasswordForm = {
        action: "changePassword",
        currentPassword: $currentPassword,
        newPassword: $newPassword,
      };

      $.ajax({
        url: "/client/php/manage_account_php/userChangePassword.php",
        type: "POST",
        data: changePasswordForm,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            swal.fire({
              title: "Success!",
              text: `${response.message}`,
              icon: "success",
              confirmButtonText: "OK",
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
  }
});
