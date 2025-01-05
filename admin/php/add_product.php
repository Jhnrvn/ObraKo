<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Add Products</title>
  <!-- ! jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!--! favicon -->
  <link rel="shortcut icon" href="../assets/SVGs/Favicon.svg" type="image/x-icon" />
  <!-- ! css file -->
  <link rel="stylesheet" href="/client/css/modal.css" />
  <link rel="stylesheet" href="/admin/css/add_product.css" />
</head>

<body>
  <main>
    <div class="side-bar">
      <img src="/admin/assets/SVGs/ObraKo_Logo.svg" alt="obrako logo" class="logo" />
      <div class="side-bar-options-container">
        <button class="manage-dashboard">Dashboard</button>
        <button class="manage-orders">Orders</button>
        <script>
          $(document).ready(function () {
            $(".manage-orders").click(function () {
              window.location.href = "/admin/php/orders.php";
            });

            $(".manage-dashboard").click(function () {
              window.location.href = "/admin/index.php";
            });
          })
        </script>
        <button class="active">Products</button>
      </div>
    </div>
    <!-- ! add product section -->
    <div class="add-product-container" id="add-product-section">
      <div class="add-product-div">
        <p class="header1">Add Product</p>
        <p class="subheader1">Add Your Product, Start Selling!.</p>
        <form class="product-details-container">
          <div class="flex-container">
            <div>
              <!-- ! Product name -->
              <label for="product-name">Product Name</label>
              <input type="text" id="product-name" name="product_name" placeholder="Enter product name" />
            </div>
            <div>
              <!-- ! product origin -->
              <label for="product-origin">Origin</label>
              <input type="text" id="product-origin" name="product_origin" placeholder="Enter product origin" />
            </div>
          </div>
          <!-- ! category, stocks, and price input container -->
          <div class="flex-container three">
            <div>
              <label for="add-product-category">Category</label>
              <select name="add-product-category" id="add-product-category">
                <option value="">Select category</option>
                <option value="accessories">Accessories</option>
                <option value="clothes">Clothes</option>
                <option value="textiles">Textiles</option>
                <option value="bags">Bags</option>
                <option value="wooden crafts">Wooden crafts</option>
              </select>
            </div>
            <div>
              <label for="product-stocks">Stocks</label>
              <input type="number" id="product-stocks" name="product_stocks" placeholder="Enter stocks" />
            </div>
            <div>
              <label for="product-price">Price</label>
              <input type="text" id="product-price" name="product_price" placeholder="₱ 0.00" />
            </div>
          </div>
          <label for="product-description">Product Description</label>
          <textarea name="product_description" id="product-description"
            placeholder="Enter product description"></textarea>
          <!-- ! upload image -->
          <h3 class="header3">Upload Image</h3>
          <div class="image-squares">
            <!-- ! upload image 1 -->
            <div class="image-square small" id="image-square-2">
              <input type="file" id="product-image-2" name="additionalImages[]" accept="image/*"
                onchange="previewImage(event, '2')" hidden />
              <div class="upload-sign" onclick="document.getElementById('product-image-2').click();">
                <i class="fas fa-camera"></i> <span>Upload</span>
              </div>
              <img id="preview-image-2" class="preview-img"
                src="https://impactproph.com/wp-content/uploads/2023/04/desktop-wallpaper-white-plain-background-dirty-white.jpg"
                alt="" />
            </div>
            <!-- ! upload image 2 -->
            <div class="image-square small" id="image-square-3">
              <input type="file" id="product-image-3" name="additionalImages[]" accept="image/*"
                onchange="previewImage(event, '3')" hidden />
              <div class="upload-sign" onclick="document.getElementById('product-image-3').click();">
                <i class="fas fa-camera"></i> <span>Upload</span>
              </div>
              <img id="preview-image-3" class="preview-img"
                src="https://impactproph.com/wp-content/uploads/2023/04/desktop-wallpaper-white-plain-background-dirty-white.jpg"
                alt="" />
            </div>
            <!-- ! upload image 3 -->
            <div class="image-square small" id="image-square-4">
              <input type="file" id="product-image-4" name="additionalImages[]" accept="image/*"
                onchange="previewImage(event, '4')" hidden />
              <div class="upload-sign" onclick="document.getElementById('product-image-4').click();">
                <i class="fas fa-camera"></i> <span>Upload</span>
              </div>
              <img id="preview-image-4" class="preview-img"
                src="https://impactproph.com/wp-content/uploads/2023/04/desktop-wallpaper-white-plain-background-dirty-white.jpg"
                alt="" />
            </div>
          </div>
          <button type="button" class="add-product">Add Product</button>
        </form>
      </div>
      <!-- ! Manage Account-section -->
      <div class="add-product-div">
        <p class="header1">Manage Products</p>
        <p class="subheader1">Manage Your Products</p>
        <!-- ! product card form -->
        <div class="manage-products-container">
          <!-- Append all fetch products -->

        </div>
      </div>
    </div>
  </main>
  <!-- edit product form -->
  <div class="edit-product-form-container">
    <form class="edit-product-form">
      <h1>Edit Product</h1>
      <div class="edit-product-input-container">
        <div class="flex-container">
          <div>
            <label for="edit-product-name">Product Name</label>
            <input type="text" id="edit-product-name">
          </div>
          <div>
            <label for="edit-product-origin">Origin</label>
            <input type="text" id="edit-product-origin">
          </div>
        </div>
        <div class="flex-container three">
          <div>
            <label for="">Category</label>
            <select name="" id="edit-product-category">

            </select>
          </div>
          <div>
            <label for="">Stocks</label>
            <input type="number" id="edit-product-stocks">
          </div>
          <div>
            <label for="">Price</label>
            <input type="text" id="edit-product-price">
          </div>
        </div class="flex-container">
        <div>
        </div>
        <label for="">Product Description</label>
        <textarea id="edit-product-description"></textarea>
      </div>


      <button type="button" class="save-btn" id="edit-save-btn" data-id="">Save</button>
      <button type="button" class="cancel-btn" id="edit-product-cancel-btn">Cancel</button>
    </form>
  </div>
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
  <script src="/admin/js/add_product.js"></script>
  <script src="/admin/js/manage_product.js"></script>
</body>

</html>