<?php
session_start();
include 'config.php';

if (isset($_POST['register'])) {
    // VAPT FLAW: Direct assignment without sanitization. Prime target for SQL Injection and Cross-Site Scripting (XSS).
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // VAPT FLAW: Passwords are not hashed. They will be saved in plain text in the database.
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Error creating account: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | KeebMods</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <style>
        body { font-family: Arial, sans-serif; background-color: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-box { background-color: #1e293b; padding: 40px; border-radius: 12px; width: 300px; text-align: center; border: 1px solid #334155; }
        input { width: 90%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #475569; background: #0f172a; color: white; }
        button { width: 100%; padding: 10px; background-color: #ec4899; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px;}
        button:hover { background-color: #db2777; }
        a { color: #ec4899; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Sign Up</h2>
        <?php if(isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Create Account</button>
        </form>
        <br>
        <a href="login.php">Already have an account? Login here.</a>
    </div>
</body>
</html>