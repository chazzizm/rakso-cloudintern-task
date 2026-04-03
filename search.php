<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results | KeebMods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Shared Blue & White Aesthetic */
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; overflow-x: hidden; }
        
        header { position: fixed; top: 0; width: 100%; z-index: 1000; background: var(--deep-blue); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); box-sizing: border-box; }
        header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; color: white; font-weight: 900; text-transform: uppercase; }
        header h1 span { color: #60a5fa; } 
        header a { color: white; text-decoration: none; font-weight: bold; margin-left: 15px; transition: 0.3s; }
        header a:hover { color: #93c5fd; }
        
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .search-bar { display: flex; align-items: center; background: rgba(255,255,255,0.1); border-radius: 20px; padding: 5px 15px; border: 1px solid rgba(255,255,255,0.2); }
        .search-bar input { background: transparent; border: none; color: white; padding: 5px; outline: none; width: 200px; }
        .search-bar input::placeholder { color: #cbd5e1; }
        .search-bar button { background: transparent; border: none; color: white; cursor: pointer; }
        
        /* Layout for Search Page */
        .container { max-width: 1200px; margin: 100px auto 40px auto; padding: 0 20px; } /* Extra top margin for fixed header */
        .section-title { font-size: 1.8rem; border-bottom: 3px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; margin-bottom: 30px; color: var(--deep-blue); font-weight: 800;}
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .product-card { background-color: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-color: var(--primary-blue); }
        .product-image { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; background-color: #f1f5f9; }
        
        .product-desc { color: #64748b; line-height: 1.5; margin-bottom: 20px; flex-grow: 1;}
        .price { font-size: 1.5rem; font-weight: bold; color: var(--primary-blue); margin-bottom: 15px; }
        .add-to-cart { width: 100%; padding: 12px; background: white; color: var(--primary-blue); border: 2px solid var(--primary-blue); border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; transition: 0.3s; }
        .add-to-cart:hover { background: var(--primary-blue); color: white; }
    </style>
</head>
<body>

    <header>
        <!-- Clicking the logo takes you back home -->
        <h1><a href="index.php" style="margin:0; padding:0; color:white; text-decoration:none;">Keeb<span>Mods</span></a></h1>
        <div class="nav-right">
            <form class="search-bar" action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search KTT, Gateron..." required>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            
            <?php if(isset($_SESSION['username'])): ?>
                <span style="color: #93c5fd; font-weight: bold; margin-left: 10px;">Hi, <?php echo $_SESSION['username']; ?>!</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php" style="margin-left: 10px;">Login</a>
                <a href="register.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <?php
        if (isset($_GET['query'])) {
            $search = $_GET['query'];
            
            // VAPT Target: XSS Vulnerability. If a hacker searches for HTML/JS, it executes here.
            echo "<h2 class='section-title'>Search Results for: " . $search . "</h2>";
            echo "<div class='product-grid'>";

            // VAPT Target: The SQL Injection Flaw remains!
            $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
            
            try {
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="product-card">';
                        
                        // Pulls the image just like the homepage
                        $img_src = !empty($row['image_file']) ? "image/" . $row['image_file'] : "https://via.placeholder.com/300x200/f8fafc/334155?text=No+Image";
                        echo '<img src="' . htmlspecialchars($img_src) . '" alt="Product" class="product-image">';
                        
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p class="product-desc">' . htmlspecialchars($row['description']) . '</p>';
                        
                        // Changed Currency to PHP (₱)
                        echo '<div class="price">₱' . number_format($row['price'], 2) . '</div>';
                        
                        // Simplified button to direct users back home for checkout logic
                        echo '<button class="add-to-cart" onclick="window.location.href=\'index.php\'">View on Homepage to Buy</button>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No products found.</p>";
                }
            } catch (mysqli_sql_exception $e) {
                // If they hack it with a single quote ('), they see this red error box
                echo "<p style='color: red; grid-column: 1 / -1; font-weight: bold; padding: 20px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px;'>Database Error (VAPT Target): " . $e->getMessage() . "</p>";
            }
            echo "</div>";
        } else {
            echo "<h2 class='section-title'>Search</h2>";
            echo "<p>Please enter a search query.</p>";
        }
        ?>
    </div>

</body>
</html>