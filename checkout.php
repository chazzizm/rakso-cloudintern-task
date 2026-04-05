<?php
session_start();
include 'config.php';

$message = "";

// Catch the cart total from the URL, default to 0 if missing
$cart_total = isset($_GET['total']) ? floatval($_GET['total']) : 0.00;
$shipping_fee = 150.00;
$grand_total = $cart_total > 0 ? $cart_total + $shipping_fee : 0.00;

if (isset($_POST['place_order'])) {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $address = $_POST['address'];
    
    // VAPT FLAW: Business Logic Abuse! 
    // The server blindly trusts the 'total_amount' sent from the HTML form.
    $total = $_POST['total_amount']; 

    $sql = "INSERT INTO orders (username, shipping_address, total_amount) VALUES ('$username', '$address', '$total')";
    
    if (mysqli_query($conn, $sql)) {
        $message = "<div style='background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;'>Order placed successfully! Amount charged: <b>₱" . number_format($total, 2) . "</b></div>";
    } else {
        $message = "<div style='background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;'>Error placing order.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | KeebMods</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .checkout-container { background: white; width: 100%; max-width: 500px; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .checkout-container h2 { color: var(--deep-blue); margin-top: 0; border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; color: var(--slate); font-size: 0.9rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 1rem; outline: none; transition: 0.2s; }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--primary-blue); }
        
        .order-summary { background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 25px; }
        .order-summary div { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: 500; }
        .order-summary .total { font-size: 1.2rem; font-weight: bold; color: var(--primary-blue); border-top: 1px solid #cbd5e1; padding-top: 10px; margin-top: 10px; }
        
        .btn-checkout { width: 100%; padding: 15px; background-color: var(--primary-blue); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; font-weight: bold; transition: 0.3s; }
        .btn-checkout:hover { background-color: #2563eb; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #64748b; text-decoration: none; font-weight: bold; }
        .back-link:hover { color: var(--primary-blue); }
    </style>
</head>
<body>

    <div class="checkout-container">
        <h2>Secure Checkout</h2>
        
        <?php echo $message; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Shipping Name</label>
                <input type="text" name="fullname" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Delivery Address</label>
                <textarea name="address" rows="3" required placeholder="123 Keeb Street, Metro Manila"></textarea>
            </div>
            
            <div class="form-group">
                <label>Credit Card Number (Fake for testing)</label>
                <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" required>
            </div>

            <!-- Dynamic Order Summary -->
            <div class="order-summary">
                <div><span>Cart Subtotal:</span> <span>₱<?php echo number_format($cart_total, 2); ?></span></div>
                <div><span>Shipping:</span> <span>₱<?php echo number_format($shipping_fee, 2); ?></span></div>
                <div class="total"><span>Total to Pay:</span> <span>₱<?php echo number_format($grand_total, 2); ?></span></div>
            </div>

            <!-- VAPT TARGET: The Hidden Field -->
            <!-- Now dynamically filled, but still vulnerable to tampering before submission -->
            <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>">

            <button type="submit" name="place_order" class="btn-checkout"><i class="fa-solid fa-lock"></i> Confirm & Pay</button>
        </form>
        
        <a href="index.php" class="back-link">&larr; Return to Store</a>
    </div>

</body>
</html>