<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem;
        }
        nav {
            background-color: #f4f4f4;
            padding: 0.5rem 0;
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
            color: #333;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        .product {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .product:hover {
            background-color: #f0f0f0;
        }
        .product-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
        }
        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 0;
            border-radius: 5px;
            width: 80%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .overlay-header {
            background-color: #333;
            color: white;
            padding: 1rem;
            position: relative;
        }
        .overlay-nav {
            background-color: #f4f4f4;
            padding: 0.5rem 0;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        #customization-area {
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Company Name</h1>
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

    <main>
        <h2>Our Products</h2>
        <div id="product-list"></div>
    </main>

    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <div class="overlay-header">
                <h2 id="product-title">Product Name</h2>
                <span class="close-btn">&times;</span>
            </div>
            <div class="overlay-nav">
                <ul>
                    <li><a href="#">Details</a></li>
                    <li><a href="#">Customize</a></li>
                    <li><a href="#">Add to Cart</a></li>
                </ul>
            </div>
            <div id="customization-area"></div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Your Company Name. All rights reserved.</p>
    </footer>

    <script src="../js/database.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productList = document.getElementById('product-list');
            const products = getAllProducts();
            const overlay = document.getElementById('overlay');
            const customizationArea = document.getElementById('customization-area');
            const closeBtn = document.querySelector('.close-btn');
            const productTitle = document.getElementById('product-title');

            let currentProduct = null;

            products.forEach(product => {
                const productElement = document.createElement('div');
                productElement.className = 'product';
                productElement.innerHTML = `
                    <img src="${product.imageUrl}" alt="${product.name}" class="product-image">
                    <h3>${product.name}</h3>
                `;
                productList.appendChild(productElement);

                productElement.addEventListener('click', function() {
                    customizationArea.innerHTML = '';
                    overlay.style.display = 'block';
                    currentProduct = product;
                    productTitle.textContent = product.name;
                    product.createCustomizer(customizationArea, embroideryCatalog);
                });
            });

            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
                if (currentProduct) {
                    currentProduct.clearAllEmbroideries();
                    currentProduct = null;
                }
                customizationArea.innerHTML = '';
            });

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    overlay.style.display = 'none';
                    if (currentProduct) {
                        currentProduct.clearAllEmbroideries();
                        currentProduct = null;
                    }
                    customizationArea.innerHTML = '';
                }
            });
        });
    </script>
</body>
</html>