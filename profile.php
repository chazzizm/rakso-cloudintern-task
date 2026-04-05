<?php
session_start();
include 'config.php';

// Force the user to log in first
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// VAPT FLAW: IDOR (Insecure Direct Object Reference)
// The code looks at the URL parameter to decide whose orders to show.
// It fails to verify if the person logged in actually matches the 'user' in the URL.
// VAPT FLAW: IDOR hidden behind basic Base64 encoding
$target_user = isset($_GET['user']) ? base64_decode($_GET['user']) : $_SESSION['username'];

// Also vulnerable to SQL Injection!
$sql = "SELECT * FROM orders WHERE username = '$target_user' ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($target_user); ?>'s Profile | KeebMods</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; }
        
        /* Shared Header */
        header { background: var(--deep-blue); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; color: white; font-weight: 900; text-transform: uppercase; }
        header h1 span { color: #60a5fa; }
        header a { color: white; text-decoration: none; font-weight: bold; margin-left: 15px; transition: 0.3s; }
        header a:hover { color: #93c5fd; }
        
        .container { max-width: 800px; margin: 50px auto; padding: 0 20px; }
        
        /* Profile Header */
        .profile-header { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; }
        .profile-avatar { width: 80px; height: 80px; background: var(--primary-blue); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 2rem; font-weight: bold; }
        .profile-info h2 { margin: 0; color: var(--deep-blue); font-size: 1.8rem; }
        .profile-info p { margin: 5px 0 0 0; color: #64748b; }
        
        /* Orders List */
        .section-title { font-size: 1.4rem; border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; margin-bottom: 20px; color: var(--slate); }
        .order-card { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .order-details strong { display: block; color: var(--deep-blue); font-size: 1.1rem; margin-bottom: 5px; }
        .order-details span { color: #64748b; font-size: 0.9rem; }
        .order-price { font-size: 1.3rem; font-weight: bold; color: var(--primary-blue); }
    </style>
</head>
<body>

    <header>
        <h1><a href="index.php" style="margin:0; padding:0; color:white; text-decoration:none;">Keeb<span>Mods</span></a></h1>
        <div>
            <a href="index.php">&larr; Back to Store</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="container">
        
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($target_user); ?>'s Profile</h2>
                <p>Member Status: <strong>Active</strong></p>
                <?php if($target_user !== $_SESSION['username']): ?>
                    <p style="color: #ef4444; font-size: 0.85rem; font-weight: bold; margin-top: 10px;"><i class="fa-solid fa-triangle-exclamation"></i> VAPT Target: You are viewing someone else's data via IDOR.</p>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="section-title">Order History</h3>
        
        <div class="orders-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="order-card">';
                    echo '<div class="order-details">';
                    echo '<strong>Order #' . $row['id'] . '</strong>';
                    echo '<span>Shipped to: ' . htmlspecialchars($row['shipping_address']) . '</span><br>';
                    echo '<span>Date: ' . $row['order_date'] . '</span>';
                    echo '</div>';
                    echo '<div class="order-price">₱' . number_format($row['total_amount'], 2) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p style="color: #94a3b8;">No orders found for this user.</p>';
            }
            ?>
        </div>

    </div>

</body>
</html>