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

  // Check if the order can be deleted
  if (timeDiff < 48 * 60 * 60 * 1000) {
      alert('Order cannot be deleted less than two days before delivery date.');
      return;
  }

  const confirmation = confirm('Are you sure you want to delete this order?');
  if (confirmation) {
      // Simulate order deletion logic
      alert('Order deleted successfully');
      // Update material quantity logic here
  }
}

function manageProducts() {
  alert('Manage Products Functionality');
}

function updateOrders() {
  alert('Update Orders Functionality');
}

//
//Item information && Material information
//

document.addEventListener("DOMContentLoaded", () => {
  const materialForm = document.getElementById("Required Material information");

  const materials = []; // Array to store material data

  // Function to calculate stock and check re-order level
  function checkReorderLevel(material) 
  {
    const remainingStock = material.physicalQty - material.reservedQty;
    if (remainingStock <= material.reorderLevel) 
    {
      alert(`Material ID: ${material.id} is running low on stock! Please restock immediately.`);
    }
  }

  // Function to handle material form submission
  if (materialForm) 
    {
      materialForm.addEventListener("submit", function (e) 
      {
        e.preventDefault(); // Prevent form submission

        // Collect form data
        const materialID = materialForm.elements[0].value.trim();
        const materialName = materialForm.elements[1].value.trim();
        const physicalQty = parseInt(materialForm.elements[2].value.trim());
        const reservedQty = parseInt(materialForm.elements[3].value.trim());
        const unit = materialForm.elements[4].value.trim();
        const reorderLevel = parseInt(materialForm.elements[5].value.trim());

        // Validate form fields
        if (!materialID || !materialName || isNaN(physicalQty) || isNaN(reservedQty) || !unit || isNaN(reorderLevel)) 
        {
          alert("Please fill in all required fields with valid data.");
          return;
      
        }

        // Reserved quantity must not exceed physical quantity
        if (reservedQty > physicalQty) 
        {
          alert("Reserved quantity cannot exceed physical quantity.");
          return;
        }

        // Add material data to the materials array
        const material = {id: materialID, name: materialName, physicalQty, reservedQty, unit, reorderLevel,};
        materials.push(material);

        // Check if material needs to be restocked
        checkReorderLevel(material);

        // Display submitted material data
        const orderRecords = document.getElementById("order-records");
        const materialInfo = `
          <div class="record">
            <p><strong>Material ID:</strong> ${materialID}</p>
            <p><strong>Material Name:</strong> ${materialName}</p>
            <p><strong>Physical Quantity:</strong> ${physicalQty}</p>
            <p><strong>Reserved Quantity:</strong> ${reservedQty}</p>
            <p><strong>Unit:</strong> ${unit}</p>
            <p><strong>Re-order Level:</strong> ${reorderLevel}</p>
          </div>
        `;
        orderRecords.innerHTML += materialInfo;

        // Clear form fields
        materialForm.reset();
      });
    }

  // Function to calculate stock and check re-order level
  function checkReorderLevel(material) 
  {
    const remainingStock = material.physicalQty - material.reservedQty;
    if (remainingStock <= material.reorderLevel) 
      {
        alert
        (
          `Material ID: ${material.id} is running low on stock! Please restock immediately.`
        );
      }
  }

  // Function to handle orders and update reserved quantity
  const itemForm = document.getElementById("Required Item information");
  if (itemForm) 
    {
      itemForm.addEventListener("submit", function (e) 
      {
        e.preventDefault(); // Prevent form submission

        // Collect form data
        const productID = itemForm.elements[0].value.trim();
        const productName = itemForm.elements[1].value.trim();
        const materialID = itemForm.elements[5].value.trim();
        const materialQty = parseInt(itemForm.elements[6].value.trim());

        // Validate form fields
        if (!productID || !productName || !materialID || isNaN(materialQty)) 
        {
          alert("Please fill in all required fields with valid data.");
          return;
        }

        // Check if material exists
        const material = materials.find((mat) => mat.id === materialID);
        if (!material) 
        {
          alert(`Material ID: ${materialID} does not exist.`);
          return;
        }

        // Update reserved quantity
        if (material.reservedQty + materialQty > material.physicalQty) 
        {
          alert(
            `Insufficient stock for Material ID: ${materialID}. Cannot reserve more than available stock.`
          );
         return;
        }
        material.reservedQty += materialQty;

        // Check if material needs to be restocked
        checkReorderLevel(material);

        // Display order confirmation
        const orderRecords = document.getElementById("order-records");
        const orderInfo = `
          <div class="record">
            <p><strong>Product ID:</strong> ${productID}</p>
            <p><strong>Product Name:</strong> ${productName}</p>
            <p><strong>Material ID:</strong> ${materialID}</p>
            <p><strong>Reserved Quantity for Order:</strong> ${materialQty}</p>
          </div>
        `;
        orderRecords.innerHTML += orderInfo;

        // Clear form fields
        itemForm.reset();
      });
    }
  }
);
