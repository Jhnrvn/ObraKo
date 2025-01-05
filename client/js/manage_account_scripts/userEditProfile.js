// show edit profile form
$("#edit-profile-btn").on("click", function () {
  $(".edit-profile-form-container").css("display", "flex");
});

// close edit profile form
$("#close-edit-profile-form").on("click", function () {
  $(".edit-profile-form-container").css("display", "none");
});

// edit user profile information
$("#edit-profile-form").submit(function (e) {
  e.preventDefault();
  $firstName = $("#edit-first-name").val();
  $lastName = $("#edit-last-name").val();
  $middleName = $("#edit-middle-name").val();
  $birthday = $("#edit-birthday").val();
  $gender = $("#edit-gender").val();
  $mobileNumber = $("#edit-mobile-number").val();

  const validator = v8n(); // V8n form validator library

  const firstNameValid = validator.string().not.empty().test($firstName);
  const lastNameValid = validator.string().not.empty().test($lastName);
  const middleNameValid = validator.string().test($middleName);
  const birthdayValid = validator.string().not.empty().test($birthday);
  const genderValid = validator.string().not.empty().test($gender);
  const mobileNumberValid = validator.string().not.empty().length(11).first("0").test($mobileNumber);

  if (firstNameValid && lastNameValid && middleNameValid && birthdayValid && genderValid && mobileNumberValid) {
    //store all form data
    const editProfileForm = {
      action: "editProfile",
      firstName: $("#edit-first-name").val(),
      lastName: $("#edit-last-name").val(),
      middleName: $("#edit-middle-name").val(),
      birthday: $("#edit-birthday").val(),
      gender: $("#edit-gender").val(),
      mobileNumber: $("#edit-mobile-number").val(),
    };
    // send the form data to the server
    $.ajax({
      url: "/client/php/manage_account_php/userEditProfile.php",
      type: "POST",
      data: editProfileForm,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          swal
            .fire({
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
            })
            .then((result) => {
              if (result.isConfirmed) {
                // clear edit form input field\
                $("#edit-profile-form").trigger("reset");
                // hide edit profile form
                $(".edit-profile-form-container").css("display", "none");
                // reload page
                location.reload();
              }
            });
        } else {
          swal.fire({
            title: "Error!",
            text: `${response.message}`,
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
      },
    });
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
