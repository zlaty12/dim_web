<?php
include 'db_connection.php';

$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'products' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>