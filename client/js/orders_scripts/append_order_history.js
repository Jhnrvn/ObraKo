$(function () {
  fetch("/client/php/orders_php/fetch_order_history.php")
    .then((response) => response.json())
    .then((data) => {
      const ordersContainer = $("#order-history");

      data.forEach((order) => {
      
        const orderStatus = order.status;

        function statusColor(status) {
          if (status === "delivered") {
            return "btn btn-success";
          }
        }

        const orderCard = `
        <form action="" class="product-orders-card">
              <!-- delivery address -->
              <section class="delivery-address-container">
                <div class="icon-container">
                  <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="user-address-container">
                  <div class="unit-address">
                    <p>
                     ${order.user_address[0].unit_number}
                     </p>
                  </div>
                  <div class="post-code">
                    <p>
                      ${order.user_address[0].province_name}
                      -
                      ${order.user_address[0].city_name}
                      -
                      ${order.user_address[0].barangay_name}
                    </p>
                  </div>

                  <div class="order-date">

                    <p>
                      place order date:
                      <span>
                        ${order.order_date}
                      </span>
                    </p>
                  </div>
                </div>
              </section>
              <!-- product details-->
              <section class="product-details-container">
                <img src="${order.product[0].product_image}" alt=""
                  class="product-image" />
                <!-- product details -->
                <div class="product-details">
                  <p class="product-name">${order.product[0].product_name}</p>
                  <div class="product-price-quantity">
                    <p>
                      Qty: <span>${order.product[0].quantity}</span>
                    </p>
                    <p>
                      Shipping fee: ₱
                      <span>${order.product[0].product_shipping_fee}</span>.00
                    </p>
                    <p>
                      Total: ₱ <span>${order.product[0].product_total_price}</span>.00
                    </p>
                  </div>
                </div>
              </section>
              <!-- order status and buttons -->
              <section class="status-container one">
                <div>
                  <p class="order-status-header">Order Status</p>

                  <div class="status-badge ">
                   <p class="${statusColor(order.status)}"> ${order.status}</p>
                  </div>
                  <div class="status-badge">
                    <p>${order.mode_of_payment}</p>
                  </div>
                </div>
                <div class="button-container">
                  
                </div>
              </section>
            </form>
        
        `;

        ordersContainer.append(orderCard);
      });
    });
});
