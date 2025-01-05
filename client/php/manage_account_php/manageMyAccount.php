<?php

require '../../../vendor/autoload.php';

session_start(); // start session

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$databaseName = "ObraKo_E-commerce";
$database = $mongoClient->$databaseName;
$collection = $database->users;

// check if the session is set
if (isset($_SESSION['user_id'])) {
  $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);
  $_SESSION['first_name'] = $user['firstName'];
  $_SESSION['last_name'] = $user['lastName'];
  $_SESSION['middle_name'] = $user['middleName'];
  $_SESSION['birthday'] = $user['birthday'];
  $_SESSION['gender'] = $user['gender'];
  $_SESSION['contact_number'] = $user['contact_number'];
  $_SESSION['email'] = $user['email'];
  $_SESSION['google_full_name'] = $user['google_full_name'];
}

// Back to Home Page
$action = $_GET['action'] ?? null;
if ($action === "backToHomePage") {
  header('Content-Type: application/json');
  $response = ['success' => true, 'url' => '../index.php'];
  echo json_encode($response);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Account</title>
  <!-- ! css file -->
  <link rel="stylesheet" href="/client/css/manageAccount.css" />
  <link rel="stylesheet" href="/client/css/footer.css" />
  <link rel="stylesheet" href="/client/css/modal.css">
  <!--! favicon -->
  <link rel="shortcut icon" href="../assets/SVGs/Favicon.svg" type="image/x-icon" />
  <!-- ! sweet alert css -->
  <link rel="stylesheet" href="https://cdn.jsdelivhr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
  <!-- ! jquery -->
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
          // Back to Home Page when the logo is clicked
          $("#obrako-logo").on("click", function () {
            $.ajax({
              url: "../php/manageMyAccount.php",
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
          <li><a href="#" id="cart">Cart</a></li>
          <li><a href="#" id="orders">Orders</a></li>
          <li><a href="#" id="home">Home</a></li>
          <script>
            $("#cart").on("click", function () {
              window.location.href = "/client/php/cart_php/userCart.php";
            });
            $("#orders").on("click", function () {
              window.location.href = "/client/php/orders_php/orders.php";
            });
            $("#home").on("click", function () {
              window.location.href = "/client/index.php";
            });
          </script>
        </ul>
      </div>
    </nav>
    <section class="manage-account-section">
      <fieldset class="manage-account-user-profile">
        <legend><i class="fa-regular fa-circle-user"></i>My Profile</legend>
        <h5 class="profile-label">Full Name</h5>
        <p class="user-info">
          <?php
          $firstName = $_SESSION['first_name'];
          $lastName = $_SESSION['last_name'];
          $middleName = $_SESSION['middle_name'];
          $googleFullName = $_SESSION['google_full_name'];
          // display the user full name if the user sign up or already setup the profile in  Manage Account page
          if ($firstName == null && $lastName == null) {
            echo $googleFullName;
          } else {
            echo $firstName . " " . $lastName . " " . $middleName;
          }
          ?>
        </p>
        <h5 class="profile-label">Birthday</h5>
        <p class="user-info">
          <?php
          $birthday = $_SESSION['birthday'] ?? "N/A";
          if ($birthday == null) {
            echo "N/A";
          } else {
            echo $birthday;
          }
          ?>
        </p>
        <h5 class="profile-label">Gender</h5>
        <p class="user-info">
          <?php
          $gender = $_SESSION['gender'] ?? "N/A";
          echo $gender
            ?>
        </p>
        <h5 class="profile-label">Mobile Number</h5>
        <p class="user-info">
          <?php
          $contactNumber = $_SESSION['contact_number'] ?? "N/A";
          echo $contactNumber
            ?>
        </p>
        <h5 class="profile-label">Email</h5>
        <p class="user-info">
          <?php
          $email = $_SESSION['email'] ?? "N/A";
          echo $email
            ?>
        </p>
        <div class="edit-profile-container">
          <button type="button" id="change-password-btn">Change Password</button>
          <button type="button" id="edit-profile-btn">Edit Profile</button>
        </div>

      </fieldset>
      <fieldset class="manage-account-user-address">
        <legend><i class="fa-regular fa-address-book"></i> Address Book</legend>
        <table class="table">
          <thead class="manage-account-table-header">
            <tr class="manage-account-table-header-tr">
              <th>Full Name</th>
              <th>Address</th>
              <th>Post Code</th>
              <th>Mobile Number</th>
              <th colspan="1"></th>
              <th colspan="1"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

            $addresses = $user['address'] ?? [];
            foreach ($addresses as $index => $address):
              // Function to retrieve data from PSGC API
              ?>
              <tr>
                <td><?= htmlspecialchars($address['fullName']) ?></td>
                <td><?= htmlspecialchars($address['unitNumber']) ?></td>
                <td>
                  <?= htmlspecialchars($address['province_name']) ?>
                  <?= htmlspecialchars(" - ") ?>
                  <?= htmlspecialchars($address['city_name']) ?>
                  <?= htmlspecialchars(" - ") ?>
                  <?= htmlspecialchars($address['barangay_name']) ?>
                </td>
                <td><?= htmlspecialchars($address['mobileNumber']) ?></td>
                <td class="default-badge">
                  <?php
                  $default = $address['is_default'] ? true : false;
                  if ($default) {
                    echo '<span class="badge bg-primary fs-5">Default</span>';
                  }
                  ?>
                </td>
                <td class="table-btn-container">
                  <button class="editBtn" data-index="<?= $index ?>" data-toggle="modal">Edit</button>
                  <button class="deleteBtn" data-index="<?= $index ?>" data-bs-toggle="modal"
                    data-bs-target="#editModal">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <button type="button" id="add-address-btn"><i class="fa-solid fa-plus"></i>Add New Address</button>
      </fieldset>
    </section>
  </main>
  <?php
  // ! footer
  include '../login_php/footer.php';
  ?>
  <!-- ! Edit Profile Form -->
  <section class="edit-profile-form-container">
    <div class="edit-profile-form">
      <i class="fa-solid fa-xmark" title="close" id="close-edit-profile-form"></i>
      <p class="edit-instruction">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident itaque tempore ipsa earum architecto
        similique tenetur quisquam et accusamus, vero nobis facilis cum maxime debitis sit magni nam quos amet.
      </p>
      <form action="" id="edit-profile-form">
        <div class="edit-form-container">
          <div>
            <label for="edit-first-name">First Name</label>
            <input type="text" id="edit-first-name" />
          </div>
          <div>
            <label for="edit-last-name">Last Name</label>
            <input type="text" id="edit-last-name" />
          </div>
          <div>
            <label for="edit-middle-name">Middle Name</label>
            <input type="text" id="edit-middle-name" />
          </div>
        </div>
        <di class="edit-form-container edit-bday-gender">
          <div>
            <label for="edit-birthday">Birthday</label>
            <input type="date" id="edit-birthday" />
          </div>
          <div class="gender">
            <label for="edit-gender">Gender</label>
            <select name="gender" id="edit-gender">
              <option value="">Select your gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
        </di>
        <div class="edit-form-container mobile">
          <div class="mobile-number">
            <label for="edit-mobile-number">Mobile Number</label>
            <input type="text" id="edit-mobile-number" />
          </div>
        </div>
        <button type="submit" class="save-changes">Save Changes</button>
      </form>
    </div>
  </section>
  <!-- ! Setup Password Form -->
  <section class="set-password-form-container">
    <div class="set-password-form">
      <i class="fa-solid fa-xmark" title="close" id="close-set-password-form"></i>
      <p class="edit-instruction">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident itaque tempore ipsa earum architecto
        similique tenetur quisquam et accusamus, vero nobis facilis cum maxime debitis sit magni nam quos amet.
      </p>
      <form action="" id="set-password-form">
        <label for="set-password">Password</label>
        <input type="password" id="set-password">
        <label for="set-confirm-password">Confirm Password</label>
        <input type="password" id="set-confirm-password">
        <button type="submit" id="set-password-btn">Confirm</button>
      </form>
    </div>
  </section>
  <!-- Change Password Form -->
  <section class="change-password-form-container">
    <div class="change-password-form">
      <i class="fa-solid fa-xmark" title="close" id="close-change-password-form"></i>
      <p class="edit-instruction">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident itaque tempore ipsa earum architecto
        similique tenetur quisquam et accusamus, vero nobis facilis cum maxime debitis sit magni nam quos amet.
      </p>
      <form action="" id="change-password-form">
        <label for="change-current-password">Enter Current Password</label>
        <input type="password" id="change-current-password">
        <label for="change-new-password">New Password</label>
        <input type="password" id="change-new-password">
        <label for="change-confirm-new-password">Confirm New Password</label>
        <input type="password" id="change-confirm-new-password">
        <button type="submit" id="new-password-btn">Confirm</button>
      </form>
    </div>
  </section>
  <!-- ! Add Address Form -->
  <section class="add-address-form-container">
    <div class="add-address-form">
      <i class="fa-solid fa-xmark" title="close" id="close-add-address-form"></i>
      <p class="edit-instruction">
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum aliquid expedita ex maxime nam id fugiat
        perferendis, voluptas, perspiciatis eos non praesentium numquam distinctio deserunt a delectus nesciunt saepe?
        Culpa!
      </p>
      <form action="" id="add-address-form">
        <div class="add-address-container">
          <div>
            <label for="add-address-full-name">Full Name</label>
            <input type="text" id="add-address-full-name" />
            <label for="add-address-contact-number">Mobile Number</label>
            <input type="text" id="add-address-contact-number" />
          </div>
          <div>
            <label for="add-address-unit-number">Floor/Unit Number</label>
            <input type="text" id="add-address-unit-number" />
            <label for="add-province">Province</label>
            <select id="add-province" name="province" required>
              <option value="">Select Province</option>
            </select>
            <label for="add-city">City / Municipality</label>
            <select id="add-city" name="city" required>
              <option value="">Select City/Municipality</option>
            </select>
            <label for="add-barangay">Barangay</label>
            <select id="add-barangay" name="barangay" required>
              <option value="">Select Barangay</option>
            </select>
          </div>
        </div>
        <button type="submit" class="address-save-btn" id="add-address-save-btn">SAVE</button>
        <button type="button" class="address-cancel-btn" id="add-address-cancel-btn">Cancel</button>
      </form>
    </div>
  </section>
  <!-- ! Edit Address Form -->
  <section class="edit-address-form-container">
    <div class="edit-address-form">
      <i class="fa-solid fa-xmark" title="close" id="close-edit-address-form"></i>
      <p class="edit-instruction">
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum aliquid expedita ex maxime nam id fugiat
        perferendis, voluptas, perspiciatis eos non praesentium numquam distinctio deserunt a delectus nesciunt saepe?
        Culpa!
      </p>
      <form action="" id="edit-address-form">
        <input type="hidden" id="editIndex">
        <div class="edit-address-container">
          <div class="div-1">
            <label for="edit-user-full-name">Full Name</label>
            <input type="text" id="edit-user-full-name" />
            <label for="">Mobile Number</label>
            <input type="text" id="edit-user-mobile-number" />
          </div>
          <div>
            <label for="">Floor/Unit Number</label>
            <input type="text" id="edit-user-unit-number" />
            <label for=""></label>
            <label for="province">Province</label>
            <select id="province" name="province" required>
              <!-- append province option element -->
            </select>
            <label for="city">City / Municipality</label>
            <select id="city" name="city" required>
              <!-- append city option element -->
            </select>
            <label for="barangay">Barangay</label>
            <select id="barangay" name="barangay" required>
              <!-- append barangay option element -->
            </select>
          </div>
        </div>
        <button type="button" class="address-save-btn" id="edit-address-save-btn">SAVE</button>
        <button type="button" class="address-cancel-btn" id="set-default">Set as Default</button>
      </form>
    </div>
  </section>
  <!-- Sweet Alert 2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bootstrap JS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <!-- V8n Form Validation -->
  <script src="https://cdn.jsdelivr.net/npm/v8n/dist/v8n.min.js"></script>
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/23c655eb58.js" crossorigin="anonymous"></script>
  <!-- Custom JS -->
  <script src="/client/js/manage_account_scripts/userEditProfile.js"></script>
  <script src="/client/js/manage_account_scripts/userChangePassword.js"></script>
  <script src="/client/js/manage_account_scripts/userAddress_Add.js"></script>
  <script src="/client/js/manage_account_scripts/userAddress_Edit.js"></script>
  <script src="/client/js/manage_account_scripts/userAddress_Delete.js"></script>
</body>

</html>