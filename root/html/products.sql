CREATE TABLE products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 0
);

INSERT INTO products (name, description, price, image_url, quantity) VALUES
('Classic T-Shirt', 'Comfortable cotton t-shirt', 19.99, '/images/d.jpg', 100),
('Polo Shirt', 'Elegant polo shirt for casual wear', 29.99, '/images/OIP.jpg', 75);
