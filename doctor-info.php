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

// Handle new doctor form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $specialization = $conn->real_escape_string($_POST['specialization']);
    $availability = $conn->real_escape_string($_POST['availability']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $tmpName = $_FILES['image']['tmp_name'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('doc_', true) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($tmpName, $destination)) {
            // Save record in DB
            $sqlInsert = "INSERT INTO doctors (name, specialization, availability, image_url) VALUES ('$name', '$specialization', '$availability', '$destination')";
            if ($conn->query($sqlInsert) === TRUE) {
                $message = "Doctor added successfully!";
            } else {
                $message = "Error saving doctor: " . $conn->error;
            }
        } else {
            $message = "Failed to upload image.";
        }
    } else {
        $message = "Please upload a valid image.";
    }
}

// Handle search query
$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Fetch doctor information from database (with search filter if any)
$sql = "SELECT name, specialization, availability, image_url FROM doctors";
if ($search !== '') {
    $sql .= " WHERE name LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Doctor Info</title>
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
    .sidebar a, .logout {
      text-decoration: none;
      width: 120px;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      font-weight: bold;
      text-align: center;
      display: block;
    }
    .doctor { background-color: #c29f58; color: white; }
    .logout {
      background-color: #e8483b;
      color: white;
      border: none;
      cursor: pointer;
    }
    .logout:hover {
      background-color: #c9302c;
    }

    .main {
      margin-left: 220px;
      padding: 20px;
      max-width: 1200px;
    }

    .header h1 {
      background-color: #c29f58;
      padding: 10px 20px;
      border-radius: 15px;
      color: white;
      display: inline-block;
      margin-bottom: 20px;
    }

    /* Search Bar */
    .search-bar {
      margin-bottom: 20px;
      width: 100%;
      display: flex;
      justify-content: center;
    }
    .search-bar form {
      width: 100%;
      max-width: 600px;
      display: flex;
      gap: 8px;
    }
    .search-bar input[type="text"] {
      flex-grow: 1;
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .search-bar button {
      padding: 8px 20px;
      border-radius: 8px;
      border: none;
      background-color: #c29f58;
      color: white;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
    }
    .search-bar button:hover {
      background-color: #a17e3e;
    }

    /* Layout for Doctor List and Add Doctor side by side */
    .content-container {
      display: flex;
      gap: 30px;
    }

    /* Doctor list styles */
    .doctor-list {
      flex: 2;
      max-height: 75vh;
      overflow-y: auto;
      padding-right: 10px;
    }

    .doctor-card {
      background-color: white;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .doctor-card img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #c29f58;
    }
    .doctor-details {
      flex-grow: 1;
    }
    .doctor-name {
      font-size: 18px;
      font-weight: bold;
    }
    .specialization {
      color: #555;
    }
    .availability {
      margin-top: 5px;
      color: green;
      font-weight: 500;
    }

    /* Add Doctor form styles */
    .add-doctor-form {
      flex: 1;
      background-color: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      max-height: 75vh;
      overflow-y: auto;
    }
    .add-doctor-form h2 {
      margin-top: 0;
      color: #333;
      margin-bottom: 20px;
      text-align: center;
    }
    .add-doctor-form label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 600;
    }
    .add-doctor-form input[type="text"],
    .add-doctor-form input[type="file"] {
      width: 100%;
      padding: 8px;
      border-radius: 8px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    .add-doctor-form button {
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #c29f58;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      width: 100%;
      font-size: 16px;
    }
    .add-doctor-form button:hover {
      background-color: #a17e3e;
    }
    .message {
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      color: white;
      background-color: #28a745;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="sidebar">
     <img src="Admin-Profile.png" alt="Admin" onclick="window.location.href='DashBoard.php'" style="cursor:pointer;" />
    <a href="inbox.php" class="inbox">Inbox</a>
    <a href="add_inventory.php" class="updates">Inventory</a>
    <a href="analytics.php" class="analytic">Analysic</a>
    <a href="patient_info.php" class="patient">Patient Info</a>
    <a href="doctor-info.php" class="doctor">Doctor Info</a>
    <button class="logout" onclick="logout()">Log Out</button>
  </div>

  <div class="main">
    <div class="header">
      <h1>Doctor Information</h1>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
      <form action="doctor-info.php" method="GET">
        <input
          type="text"
          name="search"
          placeholder="Search doctor by name"
          value="<?php echo htmlspecialchars($search); ?>"
        />
        <button type="submit">Search</button>
      </form>
    </div>

    <div class="content-container">
      <!-- Doctor List -->
      <div class="doctor-list">
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<div class="doctor-card">';
            $img = htmlspecialchars($row['image_url']);
            // Fallback image if missing
            if (!file_exists($img) || empty($img)) {
              $img = 'default-doctor.png';
            }
            echo '<img src="' . $img . '" alt="Doctor Image">';
            echo '<div class="doctor-details">';
            echo '<div class="doctor-name">' . htmlspecialchars($row['name']) . '</div>';
            echo '<div class="specialization">' . htmlspecialchars($row['specialization']) . '</div>';
            echo '<div class="availability">Available: ' . htmlspecialchars($row['availability']) . '</div>';
            echo '</div></div>';
          }
        } else {
          echo '<p>No doctor information available.</p>';
        }
        ?>
      </div>

      <!-- Add Doctor Form -->
      <div class="add-doctor-form">
        <h2>Add New Doctor</h2>
        <?php if ($message): ?>
          <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="doctor-info.php" method="POST" enctype="multipart/form-data">
          <label for="name">Doctor Name</label>
          <input type="text" id="name" name="name" required />

          <label for="specialization">Specialization</label>
          <input type="text" id="specialization" name="specialization" required />

          <label for="availability">Availability (e.g. Mon-Fri 9am-5pm)</label>
          <input type="text" id="availability" name="availability" required />

          <label for="image">Upload Image</label>
          <input type="file" id="image" name="image" accept="image/*" required />

          <button type="submit" name="add_doctor">Add Doctor</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    function logout() {
      const confirmLogout = confirm("Are you sure you want to log out?");
      if (confirmLogout) {
        window.location.href = "index.html";
      }
    }
  </script>
</body>
</html>
