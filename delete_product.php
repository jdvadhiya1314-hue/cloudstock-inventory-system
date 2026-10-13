<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            die("Error deleting product: " . $e->getMessage());
        }
    }
}

// Redirect back to inventory
header("Location: index.php");
exit;
?>
