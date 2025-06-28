<?php
// Create connection once at the top
$conn = new mysqli("localhost", "root", "", "sky_hospital");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Inventory</title>
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
    .inbox { background-color: #647cfb; }
    .updates { background-color: #5bf576; }
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
    }
    .logout:hover {
      background-color: #c9302c;
    }
    .main {
      margin-left: 220px;
      padding: 30px;
    }
    .header h1 {
      background-color: #7d91f1;
      padding: 10px 20px;
      border-radius: 15px;
      color: white;
      display: inline-block;
    }
    .content-wrapper {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
    }
    .form-container,
    .inventory-list {
      background-color: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      margin-top: 30px;
    }
    .form-container {
      max-width: 500px;
      flex: 1;
    }
    .inventory-list {
      max-width: 450px;
      flex: 1;
      overflow-y: auto;
      max-height: 600px;
      padding-right: 10px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      box-sizing: border-box;
    }
    .form-group input[type="date"] {
      padding: 8px;
    }
    .form-submit {
      background-color: #4caf50;
      color: white;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }
    .form-submit:hover {
      background-color: #3e8e41;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      color: white;
    }

    /* New Inventory List styles */
    .inventory-list h3 {
      color: #4caf50;
      margin-bottom: 25px;
      font-size: 24px;
      border-bottom: 2px solid #4caf50;
      padding-bottom: 10px;
    }
    .inventory-item {
      background-color: #f9f9f9;
      border: 1px solid #ddd;
      border-left: 6px solid #4caf50;
      border-radius: 10px;
      padding: 15px 20px;
      margin-bottom: 15px;
      transition: box-shadow 0.3s ease;
      cursor: default;
    }
    .inventory-item:hover {
      box-shadow: 0 6px 12px rgba(76, 175, 80, 0.3);
    }
    .inventory-item strong {
      font-size: 18px;
      color: #333;
      display: block;
      margin-bottom: 5px;
    }
    .inventory-details {
      font-size: 14px;
      color: #666;
    }
  </style>
</head>
<body>
  <div class="sidebar">
   <img src="Admin-Profile.png" alt="Admin" onclick="window.location.href='DashBoard.php'" style="cursor:pointer;" />
    <a href="inbox.php" class="inbox">Inbox</a>
    <a href="#" class="updates" style="pointer-events: none; opacity: 0.6; cursor: not-allowed;">Inventory</a>
    <a href="analytics.php" class="analytic">Analysic</a>
    <a href="patient_info.php" class="patient">Patient Info</a>
    <a href="doctor-info.php" class="doctor">Doctor Info</a>
    <button class="logout" id="logoutBtn">Log Out</button>
  </div>

  <div class="main">
    <div class="header">
      <h1>Add Inventory Item</h1>
    </div>

    <div class="content-wrapper">
      <!-- Add Inventory Form -->
      <div class="form-container">
        <form id="inventoryForm" method="POST" action="inventory_handler.php">
          <div class="form-group">
            <label for="itemName">Item Name</label>
            <input type="text" id="itemName" name="itemName" required />
          </div>
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" required min="1" />
          </div>
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
              <option value="">Select Category</option>
              <option value="Masks">Masks</option>
              <option value="Gloves">Gloves</option>
              <option value="Syringes">Syringes</option>
              <option value="PPE">PPE</option>
              <option value="Bottles">Bottles</option>
            </select>
          </div>
          <div class="form-group">
            <label for="date">Date Received</label>
            <input type="date" id="date" name="date" required />
          </div>
          <button type="submit" class="form-submit">Add to Inventory</button>
        </form>
      </div>

      <!-- Available Inventory List -->
      <div class="inventory-list">
        <h3>Available Inventory</h3>
        <?php
        $sql = "SELECT itemName, quantity, category, date FROM inventory ORDER BY date DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<div class='inventory-item'>";
            echo "<strong>" . htmlspecialchars($row["itemName"]) . "</strong>";
            echo "<div class='inventory-details'>";
            echo htmlspecialchars($row["quantity"]) . " units &nbsp;|&nbsp; ";
            echo "Category: " . htmlspecialchars($row["category"]) . " &nbsp;|&nbsp; ";
            echo "Date: " . htmlspecialchars($row["date"]);
            echo "</div>";
            echo "</div>";
          }
        } else {
          echo "<p>No inventory items found.</p>";
        }
        $conn->close();
        ?>
      </div>
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
