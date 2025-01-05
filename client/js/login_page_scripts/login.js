// ! login & signup page carousel contents
const carouselContents = [
  {
    // carousel image one : Philippine pottery
    class: "carousel-item active",
    image: "/client/assets/carousel/carousel-image-1.png",
    caption: "PHILIPPINE POTTERY",
    text: "Unveils a breathtaking tapestry of beauty and creativity.",
    alt: "Philippine pottery image",
  },
  {
    // carousel image two : Tnalak
    class: "carousel-item",
    image: "/client/assets/carousel/carousel-image-2.png",
    caption: "TNALAK",
    text: "The Tboli arts we’ve made with passion, and the work is marked with sacredness.",
    alt: "Tnalak image",
  },
  {
    // carousel image three : Kayasa
    class: "carousel-item",
    image: "/client/assets/carousel/carousel-image-3.png",
    caption: "KAYASA",
    text: "delicate version of woodcarving. Known for its soft pliable texture and can only be found in Laguna.",
    alt: "Kayasa image",
  },
];

// carousel content container
const carouselContainer = document.querySelector(".carousel-inner");

// display carousel contents
for (let carouselContent of carouselContents) {
  const carouselItem = document.createElement("div");
  carouselItem.className = carouselContent.class;

  carouselItem.innerHTML = `
  <img src="${carouselContent.image}" class="d-block w-100" alt="${carouselContent.alt}" />
  <div class="carousel-caption d-none d-md-block">
    <h5>${carouselContent.caption}</h5>
    <p>${carouselContent.text}</p>
  </div>`;

  carouselContainer.appendChild(carouselItem);
}

// ! login & signup form
const userInputFormContent = [
  {
    Header: "Welcome!", // form header text
    SubHeader: "Sign in your account", // form sub header text
    googleLogin: "block", // display google button
    formStatus: ["block", "none"], // display login form
    form: [signUpForm], // display sign up form
    userGuideText: "Don't have an account?", //change form guide text
    userGuideBtnText: "Sign Up", // change form guide button
  },
  {
    Header: "Sign up", // form header text
    SubHeader: "Create your account", // form sub header text
    googleLogin: "none", // hide google button
    formStatus: ["none", "block"], // display sign up form
    form: [loginForm], // display login form
    userGuideText: "Already have an account? ", //change form guide text
    userGuideBtnText: "Login", // change form guide button
  },
];

// ! login & signup form variables
const googleLogin = document.querySelector(".google-sign-in"); // google sign in
const formHeader = document.querySelector(".header-text"); // form header
const formSubHeader = document.querySelector(".sub-header-text"); // form sub header
const loginFormDisplay = document.querySelector("#loginForm"); // login form
const signUpFormDisplay = document.querySelector("#signUpForm"); // sign up form
const submitBtn = document.querySelector(".login-signup-btn"); // submit button
const loginSignUpText = document.querySelector(".login-signUp-text"); // login sign up text
const loginSignUpBtn = document.getElementById("login-signUp-btn"); // login sign up button

// display login & signup form
loginSignUpBtn.onclick = signUpForm;
// display login form
function loginForm() {
  update(userInputFormContent[0]);
}
// display sign up form
function signUpForm() {
  update(userInputFormContent[1]);
}

// function to update form container content
function update(form) {
  formHeader.textContent = form.Header; // change form header text
  formSubHeader.textContent = form.SubHeader; // change form sub header text
  googleLogin.style.display = form.googleLogin; // display or hide google button
  loginSignUpBtn.onclick = form.form[0]; // display login form
  loginFormDisplay.style.display = form.formStatus[0]; // display login form
  signUpFormDisplay.style.display = form.formStatus[1]; // display sign up form
  loginSignUpText.textContent = form.userGuideText; // change form guide text
  loginSignUpBtn.textContent = form.userGuideBtnText; // change form guide button
}

// ! the codes below is for toggle password of login confirm password
const showLoginPassword = document.getElementById("showLoginPassword");
const loginPassword = document.getElementById("login-password");

showLoginPassword.addEventListener("click", () => {
  if (loginPassword.type === "password") {
    loginPassword.type = "text";
    showLoginPassword.className = "fa-solid fa-eye-slash";
  } else {
    loginPassword.type = "password";
    showLoginPassword.className = "fa-solid fa-eye";
  }
});

// ! the codes below is for toggle password of sign up form
const showSignUpPassword = document.getElementById("showSignUpPassword");
const signUpPassword = document.getElementById("signUpPassword");

showSignUpPassword.addEventListener("click", () => {
  if (signUpPassword.type === "password") {
    signUpPassword.type = "text";
    showSignUpPassword.className = "fa-solid fa-eye-slash";
  } else {
    signUpPassword.type = "password";
    showSignUpPassword.className = "fa-solid fa-eye";
  }
});

// ! the codes below is for toggle password of confirm password
const showConfirmPassword = document.getElementById("showConfirmPassword");
const confirmPassword = document.getElementById("confirmSignUpPassword");

showConfirmPassword.addEventListener("click", () => {
  if (confirmPassword.type === "password") {
    confirmPassword.type = "text";
    showConfirmPassword.className = "fa-solid fa-eye-slash";
  } else {
    confirmPassword.type = "password";
    showConfirmPassword.className = "fa-solid fa-eye";
  }
});

// ! Form Validation
document.addEventListener("DOMContentLoaded", function () {
  const form1 = document.getElementById("loginForm");
  form1.addEventListener("submit", function (e) {
    e.preventDefault();

    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("login-password").value;

    const validator = v8n();

    const emailValid = validator.string().not.empty().test(email);

    const passwordValid = validator.string().not.empty().minLength(8).test(password);

    if (emailValid && passwordValid) {
      // ! AJAX
      // collect the login form data
      const loginFormData = {
        action: "login",
        email: $("#loginEmail").val(),
        password: $("#login-password").val(),
      };

      $.ajax({
        url: "/client/php/login_php/userLoginDataInsert.php",
        type: "POST",
        data: loginFormData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            swal
              .fire({
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
              })
              .then(() => {
                window.location.href = "/client/index.php";
              });

            // clear login form input field
            $("#loginForm").trigger("reset");
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
    } else {
      swal.fire({
        title: "Wrong Credentials",
        text: "Please enter valid email and password",
        icon: "warning",
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
  });

  const form2 = document.getElementById("signUpForm");
  form2.addEventListener("submit", function (e) {
    e.preventDefault();

    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;
    const middleName = document.getElementById("middle-name").value;
    const signUpEmail = document.getElementById("signUpEmail").value;
    const signUpPhoneNumber = document.getElementById("signUpPhoneNumber").value;
    const birthday = document.getElementById("birthday").value;
    const gender = document.getElementById("gender").value;
    const password = document.getElementById("signUpPassword").value;

    let userProfileImage;

    if (gender === "Male") {
      userProfileImage = "https://i.ibb.co/VBp4LP1/male.png";
    } else if (gender === "Female") {
      userProfileImage = "https://i.ibb.co/Th8Jr0v/female.png";
    }
    const validator = v8n();

    const firstNameValid = validator.string().not.empty().test(firstName);
    const lastNameValid = validator.string().not.empty().test(lastName);
    const middleNameValid = validator.string().not.empty().test(middleName);
    const emailValid = validator.string().not.empty().test(signUpEmail);
    const phoneNumberValid = validator.string().not.empty().length(11).first("0").test(signUpPhoneNumber);
    const birthdayValid = validator.string().not.empty().test(birthday);
    const genderValid = validator.string().not.empty().test(gender);
    const passwordValid = validator.string().not.empty().minLength(8).test(password);

    if (
      firstNameValid &&
      lastNameValid &&
      middleNameValid &&
      emailValid &&
      phoneNumberValid &&
      birthdayValid &&
      genderValid &&
      passwordValid
    ) {
      // ! AJAX
      // collect the sign up form data
      const signUpFormData = {
        firstName: $("#first-name").val(),
        lastName: $("#last-name").val(),
        middleName: $("#middle-name").val(),
        email: $("#signUpEmail").val(),
        phoneNumber: $("#signUpPhoneNumber").val(),
        birthday: $("#birthday").val(),
        gender: $("#gender").val(),
        userProfile: userProfileImage,
        password: $("#signUpPassword").val(),
      };

      // send the sign up form data to the server
      $.ajax({
        url: "/client/php/login_php/userRegistrationDataInsert.php",
        type: "POST",
        data: signUpFormData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            // show success registration message
            swal.fire({
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
            });

            // clear sign up form input field
            $("#signUpForm").trigger("reset");

            update(userInputFormContent[0]);
          } else {
            // show that the user already exists
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
        error: function (response) {
          // show error registration message
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
        },
      });
    } else {
      if (
        !passwordValid ||
        !firstNameValid ||
        !lastNameValid ||
        !middleNameValid ||
        !phoneNumberValid ||
        !birthdayValid ||
        !genderValid
      ) {
        // error registration message
        swal.fire({
          title: "Error!",
          text: "Please enter valid information.",
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
    }
  });
});
