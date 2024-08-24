<?php
// db_connection.php
include 'db_connection.php';

function getAllProducts() {
    global $conn;
    $sql = "SELECT id, name, description, price, image_url, quantity FROM products";
    $result = $conn->query($sql);
    
    if ($result === false) {
        return ["error" => "Query failed: " . $conn->error];
    }
    
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    return $products;
}

function addProduct($name, $description, $price, $image_url, $quantity) {
    global $conn;
    $sql = "INSERT INTO products (name, description, price, image_url, quantity) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $quantity);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function removeProduct($id) {
    global $conn;
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateProductQuantity($id, $new_quantity = null) {
    global $conn;
    if ($new_quantity === null) {
        // Decrease quantity by 1 (for purchases)
        $sql = "UPDATE products SET quantity = quantity - 1 WHERE id = ? AND quantity > 0";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    } else {
        // Set to a specific quantity (for admin updates)
        $sql = "UPDATE products SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_quantity, $id);
    }
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getProductById($id) {
    global $conn;
    $sql = "SELECT id, name, description, price, image_url, quantity FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    return $product;
}

function decreaseProductQuantity($id) {
    global $conn;
    $sql = "UPDATE products SET quantity = quantity - 1 WHERE id = ? AND quantity > 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function updateProduct($id, $name, $description, $price, $image_url, $quantity) {
    global $conn;
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsii", $name, $description, $price, $image_url, $quantity, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Function to search products
function searchProducts($keyword) {
    global $conn;
    $keyword = "%$keyword%";
    $sql = "SELECT id, name, description, price, image_url, quantity FROM products 
            WHERE name LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
    return $products;
}
?>
