<?php 
session_start();
include 'config.php'; 

$id = isset($_GET['id']) ? $_GET['id'] : 1; 

// Handle Review Submission
if (isset($_POST['submit_review'])) {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';
    // VAPT FLAW: A weak filter that only removes exact lowercase <script> tags
    $review_text = str_replace('<script>', '', $_POST['review_text']);
    $review_text = addslashes($review_text);
    
    $insert_sql = "INSERT INTO reviews (product_id, username, review_text) VALUES ($id, '$username', '$review_text')";
    mysqli_query($conn, $insert_sql);
    
    // Refresh to prevent duplicate submissions
    header("Location: product.php?id=$id");
    exit();
}

$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> | KeebMods</title>
    <link rel="icon" type="image/png" href="image/favicon.png?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; overflow-x: hidden; }
        
        header { position: fixed; top: 0; width: 100%; z-index: 1000; background: var(--deep-blue); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
        header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; color: white; font-weight: 900; text-transform: uppercase; }
        header h1 span { color: #60a5fa; }
        header a { color: white; text-decoration: none; font-weight: bold; margin-left: 15px; transition: 0.3s; }
        header a:hover { color: #93c5fd; }
        
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .cart-icon { color: white; font-size: 18px; cursor: pointer; position: relative; display: flex; align-items: center; gap: 8px; font-weight: bold; padding: 5px 10px; border-radius: 8px; transition: 0.2s; }
        .cart-icon:hover { background: rgba(255,255,255,0.1); }
        .cart-count { background: #ef4444; color: white; font-size: 12px; font-weight: bold; padding: 2px 6px; border-radius: 50%; position: absolute; top: -5px; right: -10px; }

        .container { max-width: 1000px; margin: 120px auto 50px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
        @media (max-width: 768px) { .container { grid-template-columns: 1fr; } }
        
        .product-image { 
            width: 100%; 
            height: 200px; /* Forces all images to be exactly this tall */
            object-fit: cover; /* The magic trick: crops the image to fit the box without stretching */
            object-position: center; /* Ensures the crop focuses on the middle of the image */
            border-radius: 8px; 
            margin-bottom: 15px; 
            background-color: #f1f5f9; 
        }
        .product-info h2 { font-size: 2.5rem; color: var(--deep-blue); margin-top: 0; margin-bottom: 10px;}
        .product-info .price { font-size: 2rem; color: var(--primary-blue); font-weight: bold; margin-bottom: 20px; }
        .product-info p { font-size: 1.1rem; line-height: 1.6; color: #64748b; margin-bottom: 30px; }
        
        .add-to-cart-large { width: 100%; padding: 15px; background-color: var(--primary-blue); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1.2rem; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3); }
        .add-to-cart-large:hover { background-color: #2563eb; transform: translateY(-2px); }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--primary-blue); text-decoration: none; font-weight: bold; }
        .back-link:hover { text-decoration: underline; }
        
        .reviews-section { grid-column: 1 / -1; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <header>
        <h1><a href="index.php" style="margin:0; padding:0; color:white; text-decoration:none;">Keeb<span>Mods</span></a></h1>
        <div class="nav-right">
            <div class="cart-icon" onclick="toggleCart()">
                <i class="fa-solid fa-cart-shopping"></i> Cart
                <span class="cart-count" id="cartBadge">0</span>
            </div>
            <?php if(isset($_SESSION['username'])): ?>
                <?php if($_SESSION['username'] === 'keeb_admin'): ?>
                    <a href="admin_panel.php" style="color: #ef4444; font-weight: bold; margin-left: 10px; text-decoration: none;"><i class="fa-solid fa-shield-halved"></i> Admin Panel</a>
                <?php endif; ?>
                <a href="profile.php?user=<?php echo base64_encode($_SESSION['username']); ?>" style="color: #93c5fd; font-weight: bold; margin-left: 10px; text-decoration: none;">Hi, <?php echo $_SESSION['username']; ?>!</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php" style="margin-left: 10px;">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <div>
            <a href="index.php" class="back-link">&larr; Back to Store</a>
            <?php $img_src = !empty($product['image_file']) ? "image/" . $product['image_file'] : "https://via.placeholder.com/600x400/f8fafc/334155?text=No+Image"; ?>
            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Product Image" class="product-image">
        </div>
        
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <div class="price">₱<?php echo number_format($product['price'], 2); ?></div>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <button class="add-to-cart-large" onclick="addToCart('<?php echo addslashes($product['name']); ?>', <?php echo $product['price']; ?>)">
                <i class="fa-solid fa-cart-plus"></i> Add to Cart
            </button>
            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 40px 0;">
            <p style="font-size: 0.9rem; color: #94a3b8;"><i class="fa-solid fa-truck"></i> Fast shipping from Metro Manila.</p>
        </div>

        <!-- NEW: The Reviews Section -->
        <div class="reviews-section">
            <h3 style="color: var(--deep-blue); margin-top: 0; border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; display: inline-block;">Customer Reviews</h3>
            
            <form method="POST" style="margin-bottom: 30px;">
            <?php 
             // Check if the logged-in user is the admin to show the hint
            $placeholder_text = (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'admin') 
                ? "Leave a review... (VAPT Target: Try injecting HTML/JS here!)" 
                : "Leave a review..."; 
            ?>
            <textarea name="review_text" rows="4" style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;" placeholder="<?php echo $placeholder_text; ?>" required></textarea>
            <button type="submit" name="submit_review" style="background-color: var(--primary-blue); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">Post Review</button>
            </form>

            <div>
                <?php
                $rev_sql = "SELECT * FROM reviews WHERE product_id = $id ORDER BY created_at DESC";
                $rev_result = mysqli_query($conn, $rev_sql);

                if (mysqli_num_rows($rev_result) > 0) {
                    while($rev = mysqli_fetch_assoc($rev_result)) {
                        echo '<div style="background: var(--light-gray); padding: 15px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid var(--primary-blue);">';
                        echo '<strong style="color: var(--deep-blue);">' . htmlspecialchars($rev['username']) . '</strong> <span style="color: #94a3b8; font-size: 0.8rem; margin-left: 10px;">' . $rev['created_at'] . '</span>';
                        
                        // VAPT FLAW: Deliberately echoing without htmlspecialchars to allow Stored XSS
                        echo '<p style="margin: 10px 0 0 0; color: var(--slate);">' . $rev['review_text'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="color: #94a3b8;">No reviews yet. Be the first!</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Slide-Out Cart Drawer -->
    <div id="cart-drawer" style="position: fixed; top: 0; right: -400px; width: 350px; height: 100vh; background-color: var(--pure-white, #ffffff); box-shadow: -5px 0 15px rgba(0,0,0,0.2); transition: right 0.3s ease; z-index: 2000; display: flex; flex-direction: column; font-family: 'Segoe UI', Tahoma, sans-serif;">
        <div style="background-color: var(--deep-blue, #1e3a8a); color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.2rem;"><i class="fa-solid fa-cart-shopping"></i> Your Cart</h3>
            <button onclick="toggleCart()" style="background: none; border: none; color: white; font-size: 28px; cursor: pointer; line-height: 1;">&times;</button>
        </div>
        <div id="cart-items" style="flex-grow: 1; padding: 20px; overflow-y: auto; color: var(--slate, #334155);">
            <p style="text-align: center; margin-top: 50px; color: #94a3b8;">Your cart is empty.</p>
        </div>
        <div style="padding: 20px; border-top: 1px solid #e2e8f0; background-color: var(--light-gray, #f8fafc);">
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; margin-bottom: 15px; color: var(--slate, #334155);">
                <span>Total:</span>
                <span id="cart-total">₱0.00</span>
            </div>
            <!-- Modified button to point to the upcoming Fake Checkout -->
            <button onclick="window.location.href='checkout.php'" style="width: 100%; padding: 15px; background-color: var(--primary-blue, #3b82f6); color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Proceed to Checkout</button>
        </div>
    </div>

    <script>
        let cart = [];
        function toggleCart() {
            const drawer = document.getElementById('cart-drawer');
            drawer.style.right = drawer.style.right === '0px' ? '-400px' : '0px';
        }
        function addToCart(name, price) {
            let existingItem = cart.find(item => item.name === name);
            if (existingItem) { existingItem.quantity += 1; } 
            else { cart.push({ name: name, price: parseFloat(price), quantity: 1 }); }
            updateCart();
            const drawer = document.getElementById('cart-drawer');
            if(drawer.style.right !== '0px') { toggleCart(); }
        }
        function changeQuantity(index, delta) {
            cart[index].quantity += delta;
            if (cart[index].quantity <= 0) { removeItem(index); } 
            else { updateCart(); }
        }
        function removeItem(index) {
            cart.splice(index, 1);
            updateCart();
        }
        function updateCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const cartBadge = document.getElementById('cartBadge');
            
            let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            if(cartBadge) cartBadge.innerText = totalItems;

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p style="text-align: center; margin-top: 50px; color: #94a3b8;">Your cart is empty.</p>';
                cartTotalElement.innerText = '₱0.00';
                return;
            }
            let html = '';
            let total = 0;
            cart.forEach((item, index) => {
                let itemTotal = item.price * item.quantity;
                total += itemTotal;
                html += `
                    <div style="margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <span style="font-weight: 600; font-size: 0.95rem; max-width: 80%; line-height: 1.2;">${item.name}</span>
                            <button onclick="removeItem(${index})" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1rem; padding: 0;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 4px; overflow: hidden;">
                                <button onclick="changeQuantity(${index}, -1)" style="background: #f1f5f9; border: none; padding: 4px 10px; cursor: pointer; color: var(--slate); font-weight: bold; border-right: 1px solid #cbd5e1;">-</button>
                                <span style="padding: 0 12px; font-size: 0.9rem; font-weight: bold;">${item.quantity}</span>
                                <button onclick="changeQuantity(${index}, 1)" style="background: #f1f5f9; border: none; padding: 4px 10px; cursor: pointer; color: var(--slate); font-weight: bold; border-left: 1px solid #cbd5e1;">+</button>
                            </div>
                            <span style="color: var(--primary-blue, #3b82f6); font-weight: bold;">₱${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                `;
            });
            cartItemsContainer.innerHTML = html;
            cartTotalElement.innerText = '₱' + total.toFixed(2);
        }
    </script>
</body>
</html>