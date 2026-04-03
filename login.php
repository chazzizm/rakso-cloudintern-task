<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VAPT FLAW: Still vulnerable to SQL Injection!
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role']; 
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | KeebMods</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        :root { --primary-blue: #3b82f6; --deep-blue: #1e3a8a; --light-gray: #f8fafc; --slate: #334155; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--light-gray); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-box { background-color: white; padding: 40px; border-radius: 12px; width: 350px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .auth-box h2 { color: var(--deep-blue); margin-top: 0; font-weight: 800; font-size: 2rem; margin-bottom: 5px; }
        .auth-box h2 span { color: var(--primary-blue); }
        .subtitle { color: #64748b; margin-bottom: 25px; font-size: 0.9rem; }
        input { width: 90%; padding: 12px; margin: 10px 0; border-radius: 6px; border: 1px solid #cbd5e1; background: #f8fafc; color: var(--slate); font-size: 1rem; outline: none; transition: border 0.2s; }
        input:focus { border-color: var(--primary-blue); }
        button { width: 100%; padding: 12px; background-color: var(--primary-blue); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 1rem; margin-top: 15px; transition: 0.2s; }
        button:hover { background-color: #2563eb; }
        a { color: var(--primary-blue); text-decoration: none; font-size: 14px; font-weight: bold; }
        a:hover { text-decoration: underline; }
        .error-msg { background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 15px; border: 1px solid #f87171; }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Keeb<span>Mods</span></h2>
        <p class="subtitle">Enter your credentials to continue</p>
        
        <?php if(isset($error)) { echo "<div class='error-msg'>$error</div>"; } ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Secure Login</button>
        </form>
        
        <div style="margin-top: 25px;">
            <a href="register.php">Don't have an account? Sign up.</a>
        </div>
        <div style="margin-top: 15px;">
            <a href="index.php" style="color: #64748b;">&larr; Back to Store</a>
        </div>
    </div>
</body>
</html>