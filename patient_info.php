<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sky_hospital";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete patient if delete request received
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM patients WHERE id = $id");
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// Handle search query
$search = '';
$sql = "SELECT * FROM patients";
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " WHERE name LIKE '%$search%' OR id LIKE '%$search%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Patient Info - Sky Hospital</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f8f5;
      color: #333;
      padding: 20px;
    }
    header {
      text-align: center;
      margin-bottom: 40px;
    }
    header h1 {
      color: #2a9d8f;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      align-items: center;
    }
    .top-bar input[type="text"] {
      padding: 10px;
      width: 250px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .top-bar button {
      padding: 10px 20px;
      background-color: #2a9d8f;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    .top-bar button:hover {
      background-color: #21867a;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #2a9d8f;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .btn {
      padding: 8px 12px;
      background-color: #2a9d8f;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9rem;
      margin-right: 5px;
    }
    .btn:hover {
      background-color: #21867a;
    }
    .btn-link {
      display: inline-block;
      padding: 10px 20px;
      background-color: #2a9d8f;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .btn-link:hover {
      background-color: #21867a;
    }
    .back-button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #e76f51;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .back-button:hover {
      background-color: #d65a3a;
    }
  </style>
</head>
<body>
  <header>
    <h1>Patient Information</h1>
    <p>Manage and review patient records efficiently</p>
  </header>

  <div class="top-bar">
    <form method="get" style="display: flex; gap: 10px;">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search patient by name or ID..." />
      <button type="submit">Search</button>
    </form>
    <a href="add_patient.php" class="btn-link">Add New Patient</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Diagnosis</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . htmlspecialchars($row["id"]) . "</td>
                      <td>" . htmlspecialchars($row["name"]) . "</td>
                      <td>" . htmlspecialchars($row["age"]) . "</td>
                      <td>" . htmlspecialchars($row["gender"]) . "</td>
                      <td>" . htmlspecialchars($row["diagnosis"]) . "</td>
                      <td>" . htmlspecialchars($row["contact"]) . "</td>
                      <td>
                        <a href='view_patient.php?id=" . $row['id'] . "' class='btn'>View</a>
                        <a href='edit_patient.php?id=" . $row['id'] . "' class='btn'>Edit</a>
                        <a href='?delete=" . $row['id'] . "' class='btn' onclick='return confirm(\"Are you sure to delete this patient?\")'>Delete</a>
                      </td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='7'>No patients found</td></tr>";
      }
      $conn->close();
      ?>
    </tbody>
  </table>

  <a href="Dashboard.php" class="back-button">← Back to Dashboard</a>
</body>
</html>
