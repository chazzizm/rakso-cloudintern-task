<?php
session_start();
include 'config.php';

// VAPT FLAW: Broken Access Control!
// The server checks if *anyone* is logged in, but fails to verify if they are an admin.
// A normal customer can type this URL and gain full control of the inventory.
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle Adding a Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_POST['image_file'];

    // VAPT FLAW: Still vulnerable to SQL Injection here too!
    $sql = "INSERT INTO products (name, description, price, image_file) VALUES ('$name', '$desc', '$price', '$image')";
    mysqli_query($conn, $sql);
    header("Location: admin_panel.php?success=added");
    exit();
}

// Handle Deleting a Product
if (isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id']; // VAPT Flaw: Unsanitized input
    $sql = "DELETE FROM products WHERE id = $del_id";
    mysqli_query($conn, $sql);
    header("Location: admin_panel.php?success=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Command Center | KeebMods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --slate: #1e293b; --light-gray: #f8fafc; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--light-gray); color: #334155; margin: 0; padding: 0; }
        
        /* Dark Admin Header */
        header { background: var(--slate); color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        header h1 { margin: 0; font-size: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #f8fafc; }
        header h1 span { color: #ef4444; } /* Red accent for admin */
        header a { color: white; text-decoration: none; font-weight: bold; font-size: 0.9rem; }
        
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .alert { background: #dcfce7; color: #166534; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #bbf7d0; font-weight: bold; }
        
        /* Admin Panels */
        .admin-card { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; margin-bottom: 30px; }
        .admin-card h2 { margin-top: 0; color: var(--slate); border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; }
        
        /* Form Styling */
        form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full-width { grid-column: 1 / -1; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: inherit; box-sizing: border-box; }
        button { background-color: var(--primary-blue); color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        button:hover { background-color: #2563eb; }
        
        /* Inventory Table */
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f1f5f9; color: var(--slate); }
        .btn-delete { background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: bold; }
        .btn-delete:hover { background-color: #dc2626; }
    </style>
</head>
<body>

    <header>
        <h1>KeebMods <span>Admin Center</span></h1>
        <div>
            Logged in as: <strong><?php echo $_SESSION['username']; ?></strong>
            <a href="index.php" style="margin-left: 20px; color: #94a3b8;">&larr; Back to Store</a>
        </div>
    </header>

    <div class="container">
        
        <?php 
        if(isset($_GET['success'])) {
            $msg = $_GET['success'] == 'added' ? 'Product successfully added to inventory.' : 'Product deleted permanently.';
            echo "<div class='alert'><i class='fa-solid fa-check'></i> $msg</div>";
        }
        ?>

        <!-- Add Product Form -->
        <div class="admin-card">
            <h2><i class="fa-solid fa-plus"></i> Add New Product</h2>
            <form method="POST" action="">
                <div>
                    <label style="font-weight:bold; font-size:0.9rem; color:#64748b;">Product Name</label>
                    <input type="text" name="name" required placeholder="e.g., Gateron Milky Yellow">
                </div>
                <div>
                    <label style="font-weight:bold; font-size:0.9rem; color:#64748b;">Price (₱)</label>
                    <input type="number" step="0.01" name="price" required placeholder="15.00">
                </div>
                <div class="full-width">
                    <label style="font-weight:bold; font-size:0.9rem; color:#64748b;">Description</label>
                    <textarea name="description" rows="2" required placeholder="Enter product specs..."></textarea>
                </div>
                <div class="full-width">
                    <label style="font-weight:bold; font-size:0.9rem; color:#64748b;">Image Filename (must exist in 'image' folder)</label>
                    <input type="text" name="image_file" required placeholder="e.g., gateron-yellow.jpg">
                </div>
                <div class="full-width">
                    <button type="submit" name="add_product">Deploy to Storefront</button>
                </div>
            </form>
        </div>

        <!-- Inventory Management Table -->
        <div class="admin-card">
            <h2><i class="fa-solid fa-box"></i> Current Inventory</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM products ORDER BY id ASC";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                        echo "<td>₱" . number_format($row['price'], 2) . "</td>";
                        echo "<td><a href='admin_panel.php?delete_id=" . $row['id'] . "' class='btn-delete'><i class='fa-solid fa-trash'></i> Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>