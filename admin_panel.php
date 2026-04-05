<?php
session_start();
include 'config.php';

// VAPT FLAW: Broken Access Control (BAC)
// It verifies that a user is logged in, but fails to check if they are actually an Admin!
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | KeebMods</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --danger-red: #ef4444; --dark-red: #991b1b; --pure-white: #ffffff; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: var(--light-gray); color: var(--slate); margin: 0; padding: 0; }
        
        header { background: var(--dark-red); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-bottom: 4px solid var(--danger-red); }
        header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; color: white; font-weight: 900; text-transform: uppercase; }
        header h1 span { color: #fca5a5; }
        header a { color: white; text-decoration: none; font-weight: bold; margin-left: 20px; transition: 0.3s; }
        header a:hover { color: #fecaca; }
        
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .dashboard-header h2 { color: var(--dark-red); margin: 0; font-size: 2rem; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border-left: 5px solid var(--primary-blue); }
        .stat-card.red { border-left-color: var(--danger-red); }
        .stat-card h3 { margin: 0 0 10px 0; color: #64748b; font-size: 1rem; text-transform: uppercase; }
        .stat-card .value { font-size: 2.5rem; font-weight: bold; color: var(--deep-blue); }
        
        .admin-section { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .admin-section h3 { color: var(--deep-blue); margin-top: 0; border-bottom: 2px solid var(--primary-blue); padding-bottom: 10px; display: inline-block; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px 15px; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f1f5f9; color: var(--slate); font-weight: bold; }
        tr:hover { background-color: #f8fafc; }
        
        .btn-danger { background-color: var(--danger-red); color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.85rem; }
        .btn-danger:hover { background-color: #dc2626; }
    </style>
</head>
<body>

    <header>
        <h1>Keeb<span>Mods</span> // ADMIN</h1>
        <div>
            <a href="index.php"><i class="fa-solid fa-store"></i> Back to Store</a>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="dashboard-header">
            <h2>System Overview</h2>
            <span style="background: var(--danger-red); color: white; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> VAPT Target Mode Active
            </span>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="value">
                    <?php 
                    $user_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
                    echo mysqli_fetch_assoc($user_count)['count']; 
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>Total Products</h3>
                <div class="value">
                    <?php 
                    $prod_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM products");
                    echo mysqli_fetch_assoc($prod_count)['count']; 
                    ?>
                </div>
            </div>
            <div class="stat-card red">
                <h3>System Alerts</h3>
                <div class="value" style="color: var(--danger-red);">3</div>
            </div>
        </div>

        <div class="admin-section">
            <h3>Recent Users Registered</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = mysqli_query($conn, "SELECT id, username, role FROM users ORDER BY id DESC LIMIT 5");
                    while($u = mysqli_fetch_assoc($users)) {
                        echo "<tr>";
                        echo "<td>" . $u['id'] . "</td>";
                        echo "<td><strong>" . htmlspecialchars($u['username']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars($u['role']) . "</td>";
                        echo "<td><button class='btn-danger'>Ban User</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>