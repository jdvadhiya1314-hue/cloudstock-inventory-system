<?php
// index.php
require_once 'db.php';

// Handle Search
$search = $_GET['search'] ?? '';
$products = [];

try {
    if ($search !== '') {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search ORDER BY id DESC");
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    }
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudStock - Inventory Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>CloudStock</h1>
        <p>Cloud-Based Inventory Management System</p>
    </header>

    <main class="container">
        <!-- Add Product Section -->
        <section class="card add-product-section">
            <h2>Add New Product</h2>
            <form id="addProductForm" action="add_product.php" method="POST">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="0" required>
                </div>
                <button type="submit" class="btn-primary">Add Product</button>
            </form>
        </section>

        <!-- Inventory Section -->
        <section class="card inventory-section">
            <div class="inventory-header">
                <h2>Current Inventory</h2>
                <div class="search-box">
                    <form method="GET" action="index.php" style="display: flex; gap: 0.5rem;">
                        <input type="text" id="searchInput" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn-secondary" id="searchBtn">Search</button>
                    </form>
                </div>
            </div>

            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventoryBody">
                    <?php if (count($products) === 0): ?>
                    <tr>
                        <td colspan="6" class="empty-message">No products found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td class="actions">
                                <form action="update_stock.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="btn-sm btn-success" title="Increase Stock">+</button>
                                </form>
                                <form action="update_stock.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="btn-sm btn-warning" title="Decrease Stock">-</button>
                                </form>
                                <form action="delete_product.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn-sm btn-danger" title="Delete Product">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="js/script.js"></script>
</body>
</html>
