<?php
// --------- DATABASE CONNECTION & LOGIN LOGIC ----------
session_start();

// Connect to database (adjust credentials if needed)
$host = "localhost";
$user = "root";
$pass = "";
$db = "sky_hospital";

// Create connection and DB if not exists
$conn = new mysqli($host, $user, $pass);
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->select_db($db);

// Create admins table if not exists
$conn->query("
  CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
  )
");

// Insert two default admins (only if table is empty)
$result = $conn->query("SELECT COUNT(*) AS total FROM admins");
$row = $result->fetch_assoc();
if ($row['total'] == 0) {
    $admin1_pass = password_hash("adminpass1", PASSWORD_DEFAULT);
    $admin2_pass = password_hash("secureadmin", PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admins (username, password) VALUES 
      ('admin1', '$admin1_pass'), 
      ('admin2', '$admin2_pass')");
}

// Handle login form submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (strlen($password) < 7) {
        $error = "Password must be more than 6 characters.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['username'] = $username;
                header("Location: DashBoard.php"); // redirect to same file
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: LogIn.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Sky Hospital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #66cc66, #2a9d8f);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      text-align: center;
      color: #2a9d8f;
      margin-bottom: 30px;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #2a9d8f;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #21867a;
    }
    .footer {
      margin-top: 20px;
      text-align: center;
      font-size: 0.9rem;
      color: #777;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 15px;
    }
    .welcome {
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <?php if (isset($_SESSION['username'])): ?>
      <div class="welcome">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>You have successfully logged in to Sky Hospital Admin Panel.</p>
        <a href="?logout=true"><button>Logout</button></a>
      </div>
    <?php else: ?>
      <h2>Sky Hospital Admin Login</h2>
      <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
      <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
    <?php endif; ?>
    <div class="footer">
      &copy; 2025 Sky Hospital. All rights reserved.
    </div>
  </div>
</body>
</html>
