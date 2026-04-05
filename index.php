<?php 
session_start();
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KeebMods | Premium Mechanical Keyboard Parts</title>
    <link rel="icon" type="image/png" href="image/favicon.png?v=2">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Your Custom CSS file -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Fallback CSS to guarantee the Blue & White aesthetic works immediately */
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; overflow-x: hidden; }
        
        /* Navy Blue Header */
        header { position: fixed; top: 0; width: 100%; z-index: 1000; background: var(--deep-blue); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
        header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; color: white; font-weight: 900; text-transform: uppercase; }
        header h1 span { color: #60a5fa; } /* Lighter blue accent */
        header a { color: white; text-decoration: none; font-weight: bold; margin-left: 15px; transition: 0.3s; }
        header a:hover { color: #93c5fd; }
        
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .search-bar { display: flex; align-items: center; background: rgba(255,255,255,0.1); border-radius: 20px; padding: 5px 15px; border: 1px solid rgba(255,255,255,0.2); }
        .search-bar input { background: transparent; border: none; color: white; padding: 5px; outline: none; width: 200px; }
        .search-bar input::placeholder { color: #cbd5e1; }
        .search-bar button { background: transparent; border: none; color: white; cursor: pointer; }
        
        /* The Clickable Cart Icon */
        .cart-icon { color: white; font-size: 18px; cursor: pointer; position: relative; display: flex; align-items: center; gap: 8px; font-weight: bold; padding: 5px 10px; border-radius: 8px; transition: 0.2s; }
        .cart-icon:hover { background: rgba(255,255,255,0.1); }
        .cart-count { background: #ef4444; color: white; font-size: 12px; font-weight: bold; padding: 2px 6px; border-radius: 50%; position: absolute; top: -5px; right: -10px; }

        /* Blue Gradient Hero Section */
        .hero { height: 50vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; background: linear-gradient(135deg, var(--deep-blue), var(--primary-blue)); color: white; padding-top: 60px; }
        .hero h2 { font-size: 3rem; margin: 0; text-shadow: 0 4px 6px rgba(0,0,0,0.2); }
        .hero p { font-size: 1.2rem; color: #e0f2fe; max-width: 600px; margin: 20px auto; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .section-title { font-size: 1.8rem; border-bottom: 3px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; margin-bottom: 30px; color: var(--deep-blue); font-weight: 800;}
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .product-card { background-color: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: var(--primary-blue); }
        .product-image { 
            width: 100%; 
            height: 200px; /* Forces all images to be exactly this tall */
            object-fit: cover; /* The magic trick: crops the image to fit the box without stretching */
            object-position: center; /* Ensures the crop focuses on the middle of the image */
            border-radius: 8px; 
            margin-bottom: 15px; 
            background-color: #f1f5f9; 
        }
        
        .product-desc { color: #64748b; line-height: 1.5; margin-bottom: 20px; flex-grow: 1;}
        .price { font-size: 1.5rem; font-weight: bold; color: var(--primary-blue); margin-bottom: 15px; }
        .add-to-cart { width: 100%; padding: 12px; background: white; color: var(--primary-blue); border: 2px solid var(--primary-blue); border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; transition: 0.3s; }
        .add-to-cart:hover { background: var(--primary-blue); color: white; }
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

    <div class="hero">
        <h2>Build Your Endgame.</h2>
        <p>Premium switches, custom keycaps, and enthusiast-grade modding supplies shipped straight to your door.</p>
    </div>

    <div class="container">
        <h2 class="section-title">Fresh Inventory</h2>
        <div class="product-grid">
            
            <?php
            $sql = "SELECT * FROM products ORDER BY id ASC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card">';
                    
                    // Image Loader
                    $img_src = !empty($row['image_file']) ? "image/" . $row['image_file'] : "https://via.placeholder.com/300x200/f8fafc/334155?text=No+Image";
                    
                    // NEW: The anchor tag linking to product.php using the product's ID
                    echo '<a href="product.php?id=' . $row['id'] . '" style="text-decoration: none; color: inherit; display: block;">';
                    echo '<img src="' . htmlspecialchars($img_src) . '" alt="Product" class="product-image">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '</a>'; // Close the anchor tag here
                    
                    echo '<p class="product-desc">' . htmlspecialchars($row['description']) . '</p>';
                    echo '<div class="price">₱' . number_format($row['price'], 2) . '</div>';
                    
                    // The Add to Cart Button properly triggering the JS function
                    echo '<button class="add-to-cart" onclick="addToCart(\'' . addslashes($row['name']) . '\', ' . $row['price'] . ')"><i class="fa-solid fa-plus"></i> Add to Cart</button>';
                    
                    echo '</div>';
                }
            } else {
                echo "<p>No products in stock right now.</p>";
            }
            ?>
            
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SLIDE-OUT CART DRAWER & JAVASCRIPT LOGIC   -->
    <!-- ========================================== -->
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
            <!-- ONLY ONE checkout button here now -->
            <button onclick="processCheckout()" style="width: 100%; padding: 15px; background-color: var(--primary-blue, #3b82f6); color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">Proceed to Checkout</button>
        </div>
    </div> <!-- This closing div is critical! -->

    <script>
        // The robust array that holds your objects
        let cart = [];

        function processCheckout() {
            if (cart.length === 0) {
                alert("Your cart is empty! Add some KeebMods first.");
                return;
            }
            // Calculate the total of everything in the cart
            let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            // Send the user to the checkout page and attach the total to the URL
            window.location.href = 'checkout.php?total=' + total;
        }

        function toggleCart() {
            const drawer = document.getElementById('cart-drawer');
            drawer.style.right = drawer.style.right === '0px' ? '-400px' : '0px';
        }

        function addToCart(name, price) {
            // Check if the item already exists in the cart array
            let existingItem = cart.find(item => item.name === name);
            
            if (existingItem) {
                // If it exists, just bump the quantity
                existingItem.quantity += 1;
            } else {
                // If it's new, push it to the array with a quantity of 1
                cart.push({ name: name, price: parseFloat(price), quantity: 1 });
            }
            
            updateCart();
            
            const drawer = document.getElementById('cart-drawer');
            if(drawer.style.right !== '0px') {
                toggleCart(); 
            }
        }

        // Logic to increase or decrease the quantity
        function changeQuantity(index, delta) {
            cart[index].quantity += delta;
            
            // If quantity drops to 0, completely remove the item
            if (cart[index].quantity <= 0) {
                removeItem(index);
            } else {
                updateCart();
            }
        }

        // Logic to delete the item using its index position in the array
        function removeItem(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function updateCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const cartBadge = document.getElementById('cartBadge');
            
            // Update the red badge to show total items (not just unique products)
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
                
                // Injecting the HTML for the quantity adjusters and trash can
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