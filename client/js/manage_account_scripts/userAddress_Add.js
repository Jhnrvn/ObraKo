$("#add-address-btn").on("click", function () {
  $(".add-address-form-container").css("display", "flex");
});

$("#close-add-address-form").on("click", function () {
  $(".add-address-form-container").css("display", "none");
});

// ! Philippine Standard Geographical Code (PSGC) API
$(document).ready(function () {
  const psgcApiBase = "https://psgc.gitlab.io/api/";

  // Load provinces on page load
  $.ajax({
    url: psgcApiBase + "provinces/",
    method: "GET",
    dataType: "json",
    success: function (provinces) {
      provinces.forEach((province) => {
        $("#add-province").append(`<option value="${province.code}">${province.name}</option>`);
      });
    },
    error: function () {
      alert("Failed to load provinces.");
    },
  });

  // Load cities when a province is selected
  $("#add-province").change(function () {
    const provinceCode = $(this).val();
    $("#add-city").html('<option value="">Select City/Municipality</option>');
    $("#add-barangay").html('<option value="">Select Barangay</option>');

    if (provinceCode) {
      $.ajax({
        url: psgcApiBase + `provinces/${provinceCode}/cities-municipalities/`,
        method: "GET",
        dataType: "json",
        success: function (cities) {
          cities.forEach((city) => {
            $("#add-city").append(`<option value="${city.code}">${city.name}</option>`);
          });
        },
        error: function () {
          alert("Failed to load cities.");
        },
      });
    }
  });

  // Load barangays when a city is selected
  $("#add-city").change(function () {
    const cityCode = $(this).val();
    $("#add-barangay").html('<option value="">Select Barangay</option>');

    if (cityCode) {
      $.ajax({
        url: psgcApiBase + `cities-municipalities/${cityCode}/barangays/`,
        method: "GET",
        dataType: "json",
        success: function (barangays) {
          barangays.forEach((barangay) => {
            $("#add-barangay").append(`<option value="${barangay.code}">${barangay.name}</option>`);
          });
        },
        error: function () {
          alert("Failed to load barangays.");
        },
      });
    }
  });
});

$provinceName = $("#add-province").text();

$("#add-address-form").submit(function (e) {
  e.preventDefault();
  $fullName = $("#add-address-full-name").val();
  $contactNumber = $("#add-address-contact-number").val();
  $unitNumber = $("#add-address-unit-number").val();
  $provinceName = $("#add-province option:selected").text();
  $province = $("#add-province").val(); // province code
  $cityName = $("#add-city option:selected").text();
  $city = $("#add-city").val(); // city code
  $barangayName = $("#add-barangay option:selected").text();
  $barangay = $("#add-barangay").val(); // barangay code

  // v8n form validation library
  const validator = v8n();

  const fullNameValid = validator.string().not.empty().test($fullName);
  const mobileNumberValid = validator.string().not.empty().length(11).first("0").test($contactNumber);
  const unitNumberValid = validator.string().not.empty().test($unitNumber);
  const provinceNameValid = validator.string().not.empty().test($provinceName);
  const provinceValid = validator.string().not.empty().test($province); // province code
  const cityNameValid = validator.string().not.empty().test($cityName);
  const cityValid = validator.string().not.empty().test($city); // city code
  const barangayNameValid = validator.string().not.empty().test($barangayName);
  const barangayValid = validator.string().not.empty().test($barangay); // barangay code

  // check if all fields are valid
  if (
    fullNameValid &&
    mobileNumberValid &&
    unitNumberValid &&
    provinceNameValid &&
    provinceValid &&
    cityNameValid &&
    cityValid &&
    barangayNameValid &&
    barangayValid
  ) {
    const addAddressForm = {
      action: "addAddress",
      fullName: $fullName,
      mobileNumber: $contactNumber,
      unitNumber: $unitNumber,
      provinceName: $provinceName,
      province: $province, // province code
      cityName: $cityName,
      city: $city, // city code
      barangayName: $barangayName,
      barangay: $barangay, // barangay code
    };

    $.ajax({
      url: "/client/php/manage_account_php/userAddress_Add.php",
      type: "POST",
      data: addAddressForm,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          Swal.fire({
            imageUrl: "/client/assets/GIFs/Home.gif",
            text: `${response.message}`,
            confirmButtonText: "OK",
            customClass: {
              title: "modal-title",
              image: "modal-img",
              htmlContainer: "modal-text",
              confirmButton: "confirm-button",
              popup: "popup-radius",
            },
          }).then((result) => {
            if (result.isConfirmed) {
              // clear add address form
              $("#add-address-form").trigger("reset");
              // hide add address form
              $(".add-address-form-container").css("display", "none");
              // reload page
              location.reload();
            }
          });
        } else {
          Swal.fire({
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
    Swal.fire({
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
