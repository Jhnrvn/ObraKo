$(function () {
  fetch("/admin/php/orders/fetch_orders.php")
    .then((response) => response.json())
    .then((data) => {
      const ordersContainer = $("#user-orders");

      data.forEach((order) => {
        const status = order.status;

        // Determine the status color class
        function statusColor(status) {
          switch (status) {
            case "pending":
              return "btn bg-danger";
            case "processing":
              return "btn bg-warning";
            case "shipped":
              return "btn bg-primary";
            case "delivered":
              return "btn bg-success";
            default:
              return "btn bg-secondary"; // Default color for unknown status
          }
        }
        const orderCard = `
            <form class="order-card">
                <div class="product-basic-info">
                    <p class="order-status ${statusColor(status)}">${order.status}</p>
                    <p>${new Date(order.order_date).toLocaleDateString()}</p>
                    <p>${order.product[0].product_name}</p>
                </div>
                <div class="quantity-total-container">
                    <p>${order.product[0].quantity}</p>
                    <p>₱ ${order.product[0].product_price.toLocaleString()}.00</p>
                    <p>₱ ${order.product[0].product_total_price.toLocaleString()}.00</p>
                </div>
                <div class="button-container">
                    <button type="button" class="edit-order" data-id="${order.order_id}">Edit</button>
                </div>
            </form>`;

        // Append order card inside the orders container
        ordersContainer.append(orderCard);
      });
    });
});

$(document).on("click", ".edit-order", function () {
  const orderId = $(this).data("id");
});
