<?php
require '../../../vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ObraKo - Login</title>
  <!-- ! css file -->
  <link rel="stylesheet" href="/client/css/login.css" />
  <link rel="stylesheet" href="/client/css/footer.css" />
  <link rel="stylesheet" href="/client/css/modal.css">
  <!--! favicon -->
  <link rel="shortcut icon" href="/client/assets/SVGs/Favicon.svg" type="image/x-icon" />
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <main>
    <!--! navigation bar -->
    <nav>
      <div class="logo-container">
        <img src="/client/assets/SVGs/ObraKo_Logo.svg" alt="ObraKo Logo" class="obrako-logo" id="obrako-logo"
          title="Go Back To Home Page" />
        <script>
          $("#obrako-logo").on("click", function () {
            $.ajax({
              url: "../php/login.php",
              type: "GET",
              data: {
                action: "backToHomePage",
              },
              dataType: "json",
              success: function (response) {
                if (response.success && response.url) {
                  window.location.href = response.url;
                }
              },
            });
          });
        </script>
      </div>
      <div class="nav-links">
        <ul>
          <li><a href="#" id="Home">Home</a></li>
          <script>
            $("#Home").on("click", function () {
              window.location.href = "/client/index.php";
            });
          </script>
        </ul>

      </div>
    </nav>
    <!-- ! header section -->
    <section class="login-section" id="Login">
      <div class="login-image">
        <div class="lightDark-cover">
          <!-- ! Carousel contents -->
          <div id="login-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
            </div>
            <!-- ! Carousel image container -->
            <div class="carousel-inner"></div>
            <button class="carousel-control-prev" type="button" data-bs-target="#login-carousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#login-carousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
      <div class="login-form-container">
        <!-- ! login & sign up form container -->
        <div class="user-credentials-container">
          <!-- ! header text -->
          <h1 class="header-text">Welcome!</h1>
          <!-- ! sub header text -->
          <p class="sub-header-text">Sign in your account</p>
          <!-- ! google sign in button -->
          <div class="google-sign-in">
            <a class="google-btn" onclick="window.location.href='/client/php/login_php/userGoogleLogin.php'">
              <img src="/client/assets/SVGs/Google.svg" alt="" />Sign in with Google
            </a>
            <div class="separator">
              <hr />
              <p>Or sign in with</p>
              <hr />
            </div>
          </div>
          <!-- ! login form -->
          <form method="post" class="user-input-form" id="loginForm">
            <label for="loginEmail">Email</label>
            <input type="email" id="loginEmail" />
            <div class="login-password-container">
              <label for="login-password">Password</label>
              <input type="password" id="login-password" name="login-password" />
              <i class="fa-solid fa-eye" id="showLoginPassword"></i>
            </div>
            <p class="forgot-password">Forgot Password?</p>
            <button type="submit" class="login-signUp-btn" name="loginBtn">Login</button>
          </form>
          <!-- ! sign up form -->
          <form method="post" class="user-input-form" id="signUpForm">
            <div class="user-full-name">
              <div class="user-name">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="firstName" />
              </div>
              <div class="user-name">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="lastName" />
              </div>
              <div class="user-name">
                <label for="middle-name">Middle Name</label>
                <input type="text" id="middle-name" name="middleName" />
              </div>
            </div>
            <div class="birthday-gender-container">
              <div class="signUp-email">
                <label for="signUpEmail">Email</label>
                <input type="email" name="signUpEmail" id="signUpEmail">
              </div>
              <div class="signUp-phoneNumber">
                <label for="signUpPhoneNumber">Phone Number</label>
                <input type="text" id="signUpPhoneNumber" name="signUpPhoneNumber" />
              </div>
            </div>

            <div class="birthday-gender-container">
              <div class="birthday">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birthday" />
              </div>
              <div class="gender">
                <label for="gender">Gender</label>
                <select name="gender" id="gender">
                  <option value="">Select your gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>
            </div>
            <div class="birthday-gender-container">
              <div class="signUp-password">
                <label for="signUpPassword">Password</label>
                <input type="password" id="signUpPassword" name="signUpPassword" />
                <i class="fa-solid fa-eye" id="showSignUpPassword"></i>
              </div>
              <div class="signUp-password">
                <label for="confirmSignUpPassword">Confirm Password</label>
                <input type="password" id="confirmSignUpPassword" name="confirmSignUpPassword" />
                <i class="fa-solid fa-eye" id="showConfirmPassword"></i>
              </div>
            </div>
            <button type="submit" class="login-signUp-btn" name="signUp">Sign Up</button>
          </form>
          <!-- ! login & sign up redirect -->
          <div class="sign-up-redirect">
            <p class="login-signUp-text">Don't have an account?</p>
            <button type="submit" id="login-signUp-btn">Sign up</button>
          </div>
        </div>
      </div>
    </section>
  </main>
  <?php
  // ! footer
  include '../login_php/footer.php';
  ?>
  <!--! Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!--! Bootstrap JS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <!--! Font Awesome -->
  <script src="https://kit.fontawesome.com/23c655eb58.js" crossorigin="anonymous"></script>
  <!-- ! V8n Form Validation -->
  <script src="https://cdn.jsdelivr.net/npm/v8n/dist/v8n.min.js"></script>
  <!-- ! Custom JS -->
  <script src="/client/js/login_page_scripts/login.js"></script>
</body>

</html>