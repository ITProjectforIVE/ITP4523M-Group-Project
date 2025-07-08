function showCustomerForm() {
  document.getElementById('customer-section').classList.remove('hidden');
  document.getElementById('staff-section').classList.add('hidden');
}

function showStaffPanel() {
  document.getElementById('staff-section').classList.remove('hidden');
  document.getElementById('customer-section').classList.add('hidden');
}

document.getElementById('order-form').addEventListener('submit', function(event) {
  event.preventDefault();
  // Simulate order creation
  const order = {
      id: Math.floor(Math.random() * 1000), // Auto-generated ID
      customerId: Math.floor(Math.random() * 1000),
      orderDate: new Date().toLocaleDateString(),
      productId: "P001",
      orderQuantity: 1,
      orderCost: 100,
      deliveryDate: new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString(),
      orderStatus: "Pending"
  };
  alert('Order Created Successfully');
  viewOrders();
});

document.getElementById('update-profile-form').addEventListener('submit', function(event) {
  event.preventDefault();
  const newPassword = this.elements[0].value;
  const newContact = this.elements[1].value;
  const newAddress = this.elements[2].value;
  
  // Update profile logic (simulated)
  alert('Profile Updated Successfully');
});

function viewOrders() {
  const orderRecordsDiv = document.getElementById('order-records');
  orderRecordsDiv.innerHTML = `
      <div>
          <p>Order ID: ${Math.floor(Math.random() * 1000)}</p>
          <p>Order Date: ${new Date().toLocaleDateString()}</p>
          <p>Product ID: P001</p>
          <p>Order Quantity: 1</p>
          <p>Order Cost: $100</p>
          <p>Customer ID: ${Math.floor(Math.random() * 1000)}</p>
          <p>Order Delivery Date: ${new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString()}</p>
          <p>Order Status: Pending</p>
          <button onclick="deleteOrder(${Math.floor(Math.random() * 1000)}, '${new Date(new Date().setDate(new Date().getDate() + 7)).toLocaleDateString()}')">Delete Order</button>
      </div>
  `;
}

function deleteOrder(orderId, deliveryDate) {
  const currentDate = new Date();
  const deliveryDateObj = new Date(deliveryDate);
  const timeDiff = deliveryDateObj - currentDate;


  if (timeDiff < 48 * 60 * 60 * 1000) {
      alert('Order cannot be deleted less than two days before delivery date.');
      return;
  }

  }

function manageProducts() {
  alert('Manage Products Functionality');
}

function updateOrders() {
  alert('Update Orders Functionality');
}

