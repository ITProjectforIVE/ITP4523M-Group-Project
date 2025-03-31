//
//Item information && Material information
//
document.addEventListener("DOMContentLoaded", () => {
  const materialForm = document.getElementById("Required Material information");
  const itemForm = document.getElementById("Required Item information");
  const materials = JSON.parse(localStorage.getItem("materials")) || [];
  const products = JSON.parse(localStorage.getItem("products")) || [];

  // Save materials to localStorage
  function saveMaterialsToLocalStorage() {
    localStorage.setItem("materials", JSON.stringify(materials));
  }

  // Save products to localStorage
  function saveProductsToLocalStorage() {
    localStorage.setItem("products", JSON.stringify(products));
  }

  // Display materials
  function displayMaterials() {
    const orderRecords = document.getElementById("order-records");
    orderRecords.innerHTML = ""; // Clear previous records

    materials.forEach((material) => {
      const materialInfo = `
        <div class="record">
          <p><strong>Material ID:</strong> ${material.id}</p>
          <p><strong>Material Name:</strong> ${material.name}</p>
          <p><strong>Physical Quantity:</strong> ${material.physicalQty}</p>
          <p><strong>Reserved Quantity:</strong> ${material.reservedQty}</p>
          <p><strong>Unit:</strong> ${material.unit}</p>
          <p><strong>Re-order Level:</strong> ${material.reorderLevel}</p>
        </div>
      `;
      orderRecords.innerHTML += materialInfo;
    });
  }

  // Display products
  function displayProducts() {
    const orderRecords = document.getElementById("order-records");
    orderRecords.innerHTML = ""; // Clear previous records

    products.forEach((product) => {
      const productInfo = `
        <div class="record">
          <p><strong>Product ID:</strong> ${product.id}</p>
          <p><strong>Product Name:</strong> ${product.name}</p>
          <p><strong>Product Description:</strong> ${product.description}</p>
          <p><strong>Product Image:</strong> ${product.image}</p>
          <p><strong>Single Product Cost:</strong> $${product.cost}</p>
        </div>
      `;
      orderRecords.innerHTML += productInfo;
    });
  }

  // Check reorder level
  function checkReorderLevel(material) {
    const remainingStock = material.physicalQty - material.reservedQty;
    if (remainingStock <= material.reorderLevel) {
      alert(`Material ID: ${material.id} is running low on stock! Please restock immediately.`);
    }
  }

  // Clear materials only
  function clearMaterials() {
    if (confirm("Are you sure you want to clear all material records? This action cannot be undone.")) {
      localStorage.removeItem("materials");
      materials.length = 0; // Clear the materials array
      displayMaterials(); // Update the display
    }
  }

  // Clear products only
  function clearProducts() {
    if (confirm("Are you sure you want to clear all product records? This action cannot be undone.")) {
      localStorage.removeItem("products");
      products.length = 0; // Clear the products array
      displayProducts(); // Update the display
    }
  }

  // Add clear functionality to a button (if present on the page)
  const clearButton = document.getElementById("clear-button");
  if (clearButton) {
    if (materialForm) {
      clearButton.addEventListener("click", clearMaterials); // Clear materials on Material page
    } else if (itemForm) {
      clearButton.addEventListener("click", clearProducts); // Clear products on Item page
    }
  }

  // Handle material form submission
  if (materialForm) {
    materialForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const materialID = materialForm.elements[0].value.trim();
      const materialName = materialForm.elements[1].value.trim();
      const physicalQty = parseInt(materialForm.elements[2].value.trim());
      const reservedQty = parseInt(materialForm.elements[3].value.trim());
      const unit = materialForm.elements[4].value.trim();
      const reorderLevel = parseInt(materialForm.elements[5].value.trim());

      if (!materialID || !materialName || isNaN(physicalQty) || isNaN(reservedQty) || !unit || isNaN(reorderLevel)) {
        alert("Please fill in all required fields with valid data.");
        return;
      }

      if (reservedQty > physicalQty) {
        alert("Reserved quantity cannot exceed physical quantity.");
        return;
      }

      const material = { id: materialID, name: materialName, physicalQty, reservedQty, unit, reorderLevel };
      materials.push(material);

      saveMaterialsToLocalStorage();
      checkReorderLevel(material);
      displayMaterials();
      materialForm.reset();
    });

    // Display materials on page load
    displayMaterials();
  }

  // Handle item form submission
  if (itemForm) {
    itemForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const productID = itemForm.elements[0].value.trim();
      const productName = itemForm.elements[1].value.trim();
      const productDescription = itemForm.elements[2].value.trim();
      const productImage = itemForm.elements[3].value.trim();
      const productCost = parseFloat(itemForm.elements[4].value.trim());
      const materialID = itemForm.elements[5].value.trim();
      const materialQty = parseInt(itemForm.elements[6].value.trim());

      if (!productID || !productName || !productDescription || !productImage || isNaN(productCost) || !materialID || isNaN(materialQty)) {
        alert("Please fill in all required fields with valid data.");
        return;
      }

      // Check if material exists
      const material = materials.find((mat) => mat.id === materialID);
      if (!material) {
        alert(`Material ID: ${materialID} does not exist.`);
        return;
      }

      if (material.reservedQty + materialQty > material.physicalQty) {
        alert(`Insufficient stock for Material ID: ${materialID}. Cannot reserve more than available stock.`);
        return;
      }

      // Update reserved quantity
      material.reservedQty += materialQty;

      const product = { id: productID, name: productName, description: productDescription, image: productImage, cost: productCost };
      products.push(product);

      saveMaterialsToLocalStorage();
      saveProductsToLocalStorage();
      checkReorderLevel(material);
      displayProducts();
      itemForm.reset();
    });

    // Display products on page load
    displayProducts();
  }
});
//
//Item information && Material information
//
