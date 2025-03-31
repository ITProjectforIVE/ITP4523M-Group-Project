//
//Item information && Material information
//
document.addEventListener("DOMContentLoaded", () => {
    const materialForm = document.getElementById("Required Material information");
    const materials = [];
  
    //calculate when to alert
    function checkReorderLevel(material) 
    {
      const remainingStock = material.physicalQty - material.reservedQty;
      if (remainingStock <= material.reorderLevel) 
      {
        alert(`Material ID: ${material.id} is running low on stock! Please restock immediately.`);
      }
    }
  
    if (materialForm) 
      {
        materialForm.addEventListener("submit", function (e) 
        {
          e.preventDefault();
          // Collect form data
          const materialID = materialForm.elements[0].value.trim();
          const materialName = materialForm.elements[1].value.trim();
          const physicalQty = parseInt(materialForm.elements[2].value.trim());
          const reservedQty = parseInt(materialForm.elements[3].value.trim());
          const unit = materialForm.elements[4].value.trim();
          const reorderLevel = parseInt(materialForm.elements[5].value.trim());
  
          // check Valid
          if (!materialID || !materialName || isNaN(physicalQty) || isNaN(reservedQty) || !unit || isNaN(reorderLevel)) 
          {
            alert("Please fill in all required fields with valid data.");
            return;
          }
  
          if (reservedQty > physicalQty) 
          {
            alert("Reserved quantity cannot exceed physical quantity.");
            return;
          }
  
          const material = {id: materialID, name: materialName, physicalQty, reservedQty, unit, reorderLevel,};
          materials.push(material);
  
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
  
          // Clear All
          materialForm.reset();
        });
      }
  
    // Calculate when alert
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
  
          // Clear All
          itemForm.reset();
        });
      }
    }
  );
//
//Item information && Material information
//
