<?php
// index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'product_operations.php';

$message = ''; // Initialize $message to avoid undefined variable warning

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_product'])) {
    $product_id = $_POST['product_id'];

    // Fetch the current product quantity
    $product = getProductById($product_id);

    // Check if the product is in stock
    if ($product && $product['quantity'] > 0) {
        // Decrease quantity by 1
        $new_quantity = $product['quantity'] - 1;
        if (updateProductQuantity($product_id, $new_quantity)) {
            $message = "Product purchased successfully!";
        } else {
            $message = "Error updating product quantity.";
        }
    } else {
        $message = "Product is out of stock.";
    }

    // Redirect to avoid form resubmission on page refresh
    header("Location: index.php");
    exit();
}

$products = getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Company Name</title>
    <style>
        /* General styles */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: auto; /* Ensure scrolling is enabled */
        }
        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            box-sizing: border-box;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem;
            box-sizing: border-box;
        }
        nav {
            background-color: #f4f4f4;
            padding: 0.5rem 0;
            box-sizing: border-box;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        nav ul li {
            margin: 0 10px;
        }
        nav ul li a {
            text-decoration: none;
            color: #333;
        }
        .banner {
            width: 100%;
            height: 300px;
            background-image: url('../images/cat.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-sizing: border-box;
        }
        main {
            flex: 1 0 auto; /* Allow the main content to expand and push content */
            padding: 20px;
            box-sizing: border-box;
        }
        .product-list {
           display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            justify-content: center;
            margin: 0 auto;
            max-width: 1200px; /* Adjust based on your preference */
        }
        .product {
            width: 100%; /* Ensure it uses the full width of its grid cell */
            height: 650px; /* Fixed height, slightly larger than the width */
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            text-align: center;
            background-color: #f4f4f4;
            border-radius: 15px;
            overflow: extends;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Ensures spacing between elements */
        }
        .product:hover {
            transform: translateY(-5px);
        }
        .product img {
            width: 100%;
            height: 350px; /* Fixed height for the image */
            object-fit:cover; /* Maintain aspect ratio, cover the element */
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem;
            margin-top: 20px;
            box-sizing: border-box;
        }
        .buy-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .out-of-stock {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            border-radius: 4px;
        }
        .message {
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Dim's Embroidery Shop</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.html">About Us</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="faq.html">FAQ</a></li>
        </ul>
    </nav>
    <div>
    <div class="banner">
        </div>
    </div>
    <main>
        <h2>Welcome to Our Website</h2>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($products['error'])): ?>
            <p class="error">Error: <?php echo htmlspecialchars($products['error']); ?></p>
        <?php elseif (empty($products)): ?>
            <p>No products available at the moment.</p>
        <?php else: ?>
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <?php if ($product['quantity'] > 0): ?>
                            <p>In stock</p>
                            <form action="index.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="submit" name="buy_product" value="Buy" class="buy-button">
                            </form>
                        <?php else: ?>
                            <p class="out-of-stock">Out of Stock</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Dim's Embroidery Shop. All rights reserved.</p>
    </footer>
</body>
</html>
