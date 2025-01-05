$("#edit-address-form-btn").on("click", function () {
  $(".edit-address-form-container").css("display", "flex");
});

$("#close-edit-address-form").on("click", function () {
  $(".edit-address-form-container").css("display", "none");
});

// show edit form of a the row that was clicked and display the data to the input field of the form
document.querySelectorAll(".editBtn").forEach((button) => {
  button.addEventListener("click", function () {
    const rowIndex = this.getAttribute("data-index");
    document.getElementById("editIndex").value = rowIndex;
    let newIndex = parseInt(rowIndex);

    $(".edit-address-form-container").css("display", "flex");

    $.ajax({
      url: "/client/php/manage_account_php/userAddress_Edit.php",
      type: "POST",
      data: {
        index: newIndex,
      },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          $("#edit-user-full-name").val(response.fullName);
          $("#edit-user-mobile-number").val(response.mobileNumber);
          $("#edit-user-unit-number").val(response.unitNumber);
          $("#province").append(`<option value="${response.province}">${response.province_name}</option>`);
          $("#city").append(`<option value="${response.city}">${response.city_name}</option>`);
          $("#barangay").append(`<option value="${response.barangay}">${response.barangay_name}</option>`);
          loadPSGC();
        }
      },
    });
  });
});

// send the new data to the server
$("#edit-address-save-btn").on("click", function (e) {
  e.preventDefault();

  const userEditedData = {
    index: $("#editIndex").val(),
    fullName: $("#edit-user-full-name").val(),
    mobileNumber: $("#edit-user-mobile-number").val(),
    unitNumber: $("#edit-user-unit-number").val(),
    province_name: $("#province option:selected").text(),
    province: $("#province").val(),
    city_name: $("#city option:selected").text(),
    city: $("#city").val(),
    barangay_name: $("#barangay option:selected").text(),
    barangay: $("#barangay").val(),
  };

  $.ajax({
    url: "/client/php/manage_account_php/userAddress_Edit_New.php",
    type: "POST",
    data: userEditedData,
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
              // reset form
              $("#edit-address-form").trigger("reset");
              // hide form
              $(".edit-address-form-container").css("display", "none");
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
});

// set address as default
$("#set-default").on("click", function (e) {
  e.preventDefault();

  const userEditedData = {
    index: $("#editIndex").val(),
    fullName: $("#edit-user-full-name").val(),
    mobileNumber: $("#edit-user-mobile-number").val(),
    unitNumber: $("#edit-user-unit-number").val(),
    province_name: $("#province option:selected").text(),
    province: $("#province").val(),
    city_name: $("#city option:selected").text(),
    city: $("#city").val(),
    barangay_name: $("#barangay option:selected").text(),
    barangay: $("#barangay").val(),
  };

  $.ajax({
    url: "/client/php/manage_account_php/set_default_address.php",
    type: "POST",
    data: userEditedData,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // reset form
        $("#edit-address-form").trigger("reset");
        // hide form
        $(".edit-address-form-container").css("display", "none");
        // reload page
        location.reload();
      }
    },
  });
});

// ! Philippine Standard Geographical Code (PSGC) API
function loadPSGC() {
  const psgcApiBase = "https://psgc.gitlab.io/api/";

  // Load provinces on page load
  $.ajax({
    url: psgcApiBase + "provinces/",
    method: "GET",
    dataType: "json",
    success: function (provinces) {
      provinces.forEach((province) => {
        $("#province").append(`<option value="${province.code}">${province.name}</option>`);
      });
    },
    error: function () {
      alert("Failed to load provinces.");
    },
  });

  // Load cities when a province is selected
  $("#province").change(function () {
    const provinceCode = $(this).val();
    $("#city").html('<option value="">Select City/Municipality</option>');
    $("#barangay").html('<option value="">Select Barangay</option>');

    if (provinceCode) {
      $.ajax({
        url: psgcApiBase + `provinces/${provinceCode}/cities-municipalities/`,
        method: "GET",
        dataType: "json",
        success: function (cities) {
          cities.forEach((city) => {
            $("#city").append(`<option value="${city.code}">${city.name}</option>`);
          });
        },
        error: function () {
          alert("Failed to load cities.");
        },
      });
    }
  });

  // Load barangays when a city is selected
  $("#city").change(function () {
    const cityCode = $(this).val();
    $("#barangay").html('<option value="">Select Barangay</option>');

    if (cityCode) {
      $.ajax({
        url: psgcApiBase + `cities-municipalities/${cityCode}/barangays/`,
        method: "GET",
        dataType: "json",
        success: function (barangays) {
          barangays.forEach((barangay) => {
            $("#barangay").append(`<option value="${barangay.code}">${barangay.name}</option>`);
          });
        },
        error: function () {
          alert("Failed to load barangays.");
        },
      });
    }
  });
}
