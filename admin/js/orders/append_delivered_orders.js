$(function () {
  fetch("/admin/php/orders/fetch_delivered_orders.php").then((response) =>
    response.json().then((data) => {
      const ordersContainer = $("#delivered-orders");

      data.forEach((order) => {
        const orderCard = `
         <form class="order-card">
                <div class="product-basic-info">
                  <p>${order.mode_of_payment}</p>  
                  <p>${order.transaction_id}</p>
                  <p>${order.product[0].product_name}</p>   
                </div>
                <div class="quantity-total-container">
                  <p>${order.product[0].quantity}</p>
                  <p>₱ ${order.product[0].product_price}.00</p>
                  <p>₱ ${order.product[0].product_total_price}.00</p>
                </div>
                <div class="button-container">
                  <button type="button" class="view-order" data-id="${order.order_id}">View</button>
                </div>
              </form>
        `;
        ordersContainer.append(orderCard);
      });
    })
  );
});