<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sky_hospital";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch inbox messages from database
$sql = "SELECT sender, message, received_at FROM inbox ORDER BY received_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inbox - Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #66cc66;
    }
    .sidebar {
      position: fixed;
      width: 200px;
      height: 100vh;
      background-color: #66cc66;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .sidebar img {
      width: 100px;
      border-radius: 50%;
      margin-bottom: 20px;
    }
    .sidebar a {
      text-decoration: none;
      width: 120px;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      text-align: center;
      display: block;
    }
    .inbox {
      background-color: #647cfb;
      pointer-events: none;
      opacity: 0.6;
      cursor: not-allowed;
    }
    .updates {background-color: #5bf576; }
    .analytic { background-color: #f8e561; color: #333; }
    .patient { background-color: #7d3ac1; }
    .doctor { background-color: #c29f58; }
    .logout {
      background-color: #e8483b;
      color: white;
      font-weight: bold;
      width: 120px;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-align: center;
      font-family: 'Segoe UI', sans-serif;
      display: block;
    }
    .logout:hover {
      background-color: #c9302c;
    }
    .main {
      margin-left: 220px;
      padding: 20px;
    }
    .header h1 {
      background-color: #7d91f1;
      padding: 10px 20px;
      border-radius: 15px;
      color: white;
    }
    .messages {
      background-color: white;
      padding: 20px;
      border-radius: 15px;
    }
    .message {
      background-color: #d3f8d3;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 15px;
      color: #333;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      color: white;
    }
  </style>
</head>
<body>
  <div class="sidebar">
   <img src="Admin-Profile.png" alt="Admin" onclick="window.location.href='DashBoard.php'" style="cursor:pointer;" />
    <a href="#" class="inbox">Inbox</a>
    <a href="add_inventory.php" class="updates">Inventory</a>
    <a href="analytics.php" class="analytic">Analysic</a>
    <a href="patient_info.php" class="patient">Patient Info</a>
    <a href="doctor-info.php" class="doctor">Doctor Info</a>
    <button class="logout" id="logoutBtn">Log Out</button>
  </div>

  <div class="main">
    <div class="header">
      <h1>Inbox</h1>
    </div>

    <div class="messages">
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="message">';
          echo '<strong>' . htmlspecialchars($row['sender']) . '</strong><br/>';
          echo 'Message: "' . htmlspecialchars($row['message']) . '"<br/>';
          echo '<small>Received: ' . date("d F Y, h:i A", strtotime($row['received_at'])) . '</small>';
          echo '</div>';
        }
      } else {
        echo '<p>No messages in the inbox.</p>';
      }
      $conn->close();
      ?>
    </div>

    <div class="footer">
      <p>&copy; 2025 Sky Hospital. All rights reserved.</p>
    </div>
  </div>

  <script>
    document.getElementById('logoutBtn').addEventListener('click', function () {
      const confirmed = confirm('Are you sure you want to log out?');
      if (confirmed) {
        window.location.href = 'index.html';
      }
    });
  </script>
</body>
</html>
