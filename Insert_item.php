<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'projectDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert into products table
$stmt_product = $conn->prepare("INSERT INTO products (ProductName, ProductDescription, ProductImage, ProductCost) VALUES (?, ?, ?, ?)");
$stmt_product->bind_param("sssd", $_POST['product_name'], $_POST['product_description'], $_POST['product_image'], $_POST['product_cost']);
$stmt_product->execute();

$product_id = $conn->insert_id; // Get the auto-generated Product ID

// Retrieve the materials arrays
$mid = $_POST['mid'];
$mname = $_POST['mname'];
$mqty = $_POST['mqty'];
$mrqty = $_POST['mrqty'];
$munit = $_POST['munit'];
$mrecorderqty = $_POST['mrecorderqty'];

// Loop through materials and insert into the material table
for ($i = 0; $i < count($mid); $i++) {
    // Check if material exists, or insert new if needed
    // For simplicity, assuming materials are pre-existing and just linking
    // Otherwise, insert into materials table if new

    // Insert into product_materials linking table
    $stmt_material = $conn->prepare("INSERT INTO product_materials (ProductID, MaterialID, MaterialName, MaterialQuantity, RemainingQuantity, Unit, RecordedQuantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_material->bind_param(
        "issdsss",
        $product_id,
        $mid[$i],
        $mname[$i],
        $mqty[$i],
        $mrqty[$i],
        $munit[$i],
        $mrecorderqty[$i]
    );
    $stmt_material->execute();
}

$conn->close();

echo "Product and materials inserted successfully.";
?>