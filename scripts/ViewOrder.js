let orders = JSON.parse(localStorage.getItem("orders")) || [];

function saveOrdersToLocalStorage() {
  localStorage.setItem("orders", JSON.stringify(orders));
}


document.addEventListener("DOMContentLoaded", () => {
  const orderForm = document.getElementById("order-form");
  const orderStatusDiv = document.getElementById("order-status");
  const clearOrdersButton = document.getElementById("clear-orders-button");

  if (orderForm) {
    orderForm.addEventListener("submit", function (event) {
      event.preventDefault();


      const customerName = this.elements[0].value.trim();
      const password = this.elements[1].value.trim();
      const telephone = this.elements[2].value.trim();
      const address = this.elements[3].value.trim();
      const companyName = this.elements[4].value.trim();
      const productId = this.elements[5].value.trim();
      const orderQuantity = parseInt(this.elements[6].value.trim(), 10);


      if (!customerName || !password || !telephone || !address || !companyName || !productId || isNaN(orderQuantity) || orderQuantity <= 0) {
        alert("Please fill in all required fields with valid data.");
        return;
      }

 
      const newOrder = {
        id: Math.floor(Math.random() * 10000),
        customerName,
        password, 
        telephone,
        address,
        companyName,
        productId,
        orderQuantity,
        orderCost: orderQuantity * 100, 
        orderDate: new Date().toLocaleDateString(),
        deliveryDate: new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString(),
        orderStatus: "Pending",
      };


      orders.push(newOrder);

      saveOrdersToLocalStorage();

      alert("Order created successfully!");


      this.reset();
    });
  }


  if (orderStatusDiv) {
    displayOrders(orderStatusDiv);

 
    if (clearOrdersButton) {
      clearOrdersButton.addEventListener("click", () => {
        clearOrders(orderStatusDiv);
      });
    }
  }
});


function displayOrders(container) {
  container.innerHTML = ""; 


  const savedOrders = JSON.parse(localStorage.getItem("orders")) || [];

  if (savedOrders.length === 0) {
    container.innerHTML = "<p>No orders found.</p>";
    return;
  }


  savedOrders.forEach((order) => {
    const orderRecord = `
      <div class="record" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
        <p><strong>Order ID:</strong> ${order.id}</p>
        <p><strong>Customer Name:</strong> ${order.customerName}</p>
        <p><strong>Telephone:</strong> ${order.telephone}</p>
        <p><strong>Address:</strong> ${order.address}</p>
        <p><strong>Company Name:</strong> ${order.companyName}</p>
        <p><strong>Product ID:</strong> ${order.productId}</p>
        <p><strong>Order Quantity:</strong> ${order.orderQuantity}</p>
        <p><strong>Order Cost:</strong> $${order.orderCost}</p>
        <p><strong>Order Date:</strong> ${order.orderDate}</p>
        <p><strong>Delivery Date:</strong> ${order.deliveryDate}</p>
        <p><strong>Order Status:</strong> ${order.orderStatus}</p>
      </div>
    `;
    container.innerHTML += orderRecord;
  });
}


function clearOrders(container) {
  if (confirm("Are you sure you want to delete all orders? This action cannot be undone.")) {

    localStorage.removeItem("orders");


    container.innerHTML = "<p>No orders found.</p>";

    alert("All orders have been successfully deleted.");
  }
}