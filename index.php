<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        // E-Update ang nasave na product
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity'], $id]);
    } else {
        // Magdagdag nang product
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['quantity']]);
    }
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$_GET['delete']]);
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">
        <label>Name:</label><input type="text" name="name" value="<?= $product['name'] ?? '' ?>" required><br>
        <label>Description:</label><textarea name="description" required><?= $product['description'] ?? '' ?></textarea><br>
        <label>Price:</label><input type="number" name="price" value="<?= $product['price'] ?? '' ?>" required><br>
        <label>Quantity:</label><input type="number" name="quantity" value="<?= $product['quantity'] ?? '' ?>" required><br>
        <button type="submit"><?= $id ? 'Update' : 'Submit' ?> Product</button>
    </form>

    <h2>Product Listing</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Actions</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= $product['name'] ?></td>
            <td><?= $product['description'] ?></td>
            <td><?= $product['price'] ?></td>
            <td><?= $product['quantity'] ?></td>
            <td>
                <a href="?delete=<?= $product['id'] ?>">Delete</a>
                <a href="?edit=<?= $product['id'] ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
