<?php
// admin_products.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'product_operations.php';

$message = '';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        
        // Handle file upload
        if (isset($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/'; // Directory where images will be stored
            $upload_file = $upload_dir . basename($_FILES['image']['name']);
            $image_url = $upload_file;

            // Check if the upload directory exists, if not create it
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the uploaded file to the server
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
                // File successfully uploaded
            } else {
                $message = "Ошибка при загрузке изображения.";
                $image_url = ''; // Reset image URL on failure
            }
        } else {
            $message = "Ошибка при загрузке изображения.";
            $image_url = ''; // Reset image URL on failure
        }

        if (addProduct($name, $description, $price, $image_url, $quantity)) {
            $message = "Продукт успешно добавлен!";
        } else {
            $message = "Ошибка при добавлении продукта.";
        }
        // Redirect to avoid resubmission on refresh
        header("Location: admin_products.php");
        exit();
    } elseif (isset($_POST['remove_product'])) {
        $product_id = $_POST['product_id'];
        if (removeProduct($product_id)) {
            $message = "Продукт успешно удален!";
        } else {
            $message = "Ошибка при удалении продукта.";
        }
        // Redirect to avoid resubmission on refresh
        header("Location: admin_products.php");
        exit();
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = $_POST['new_quantity'];

        if (updateProductQuantity($product_id, $new_quantity)) {
            $message = "Количество продукта успешно обновлено!";
        } else {
            $message = "Ошибка при обновлении количества продукта.";
        }
        // Redirect to avoid resubmission on refresh
        header("Location: admin_products.php");
        exit();
    }
}

// Fetch the list of products
$products = getAllProducts();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ - Управление продуктами</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], select, input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            background-color: #f2f2f2;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .product-list {
            list-style-type: none;
            padding: 0;
        }
        .product-list li {
            background-color: #f4f4f4;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header>
        <h1>Админ - Управление продуктами</h1>
    </header>
    <main>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Добавить новый продукт</h2>
        <form action="admin_products.php" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Название продукта" required>
            <input type="text" name="description" placeholder="Описание продукта" required>
            <input type="number" name="price" placeholder="Цена продукта" step="0.01" required>
            <input type="file" name="image" placeholder="Изображение" required>
            <input type="number" name="quantity" placeholder="Количество" required>
            <input type="submit" name="add_product" value="Добавить продукт">
        </form>

        <h2>Удалить продукт</h2>
        <form action="admin_products.php" method="post">
            <select name="product_id" required>
                <option value="">Выберите продукт</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="remove_product" value="Удалить продукт">
        </form>

        <h2>Обновить количество продукта</h2>
        <form action="admin_products.php" method="post">
            <select name="product_id" required>
                <option value="">Выберите продукт</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="new_quantity" placeholder="Новое количество" required>
            <input type="submit" name="update_quantity" value="Обновить количество">
        </form>

        <h2>Все продукты</h2>
        <ul class="product-list">
            <?php foreach ($products as $product): ?>
                <li>
                    <?php echo htmlspecialchars($product['name']); ?> - 
                    <?php echo htmlspecialchars($product['description']); ?> - 
                    $<?php echo number_format($product['price'], 2); ?> - 
                    В наличии: <?php echo htmlspecialchars($product['quantity']); ?>
                    <?php if ($product['image_url']): ?>
                        <br><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100px; height: auto;">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
