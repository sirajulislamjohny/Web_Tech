<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Patient - Sky Hospital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f8f5;
      margin: 0;
      padding: 40px;
    }

    h2 {
      text-align: center;
      color: #2a9d8f;
    }

    form {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background-color: #2a9d8f;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: #21867a;
    }

    .message {
      text-align: center;
      font-weight: bold;
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

<h2>Add New Patient</h2>

<?php
// Server-side logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "sky_hospital");

    if ($conn->connect_error) {
        echo "<p class='error'>Connection failed: " . $conn->connect_error . "</p>";
    } else {
        $name = $conn->real_escape_string($_POST["name"]);
        $age = (int)$_POST["age"];
        $gender = $conn->real_escape_string($_POST["gender"]);
        $diagnosis = $conn->real_escape_string($_POST["diagnosis"]);
        $contact = $conn->real_escape_string($_POST["contact"]);

        $sql = "INSERT INTO patients (name, age, gender, diagnosis, contact)
                VALUES ('$name', $age, '$gender', '$diagnosis', '$contact')";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='message'>✅ Patient added successfully!</p>";
        } else {
            echo "<p class='error'>❌ Error: " . $conn->error . "</p>";
        }

        $conn->close();
    }
}
?>

<form action="" method="POST">
  <label for="name">Patient Name</label>
  <input type="text" id="name" name="name" required>

  <label for="age">Age</label>
  <input type="number" id="age" name="age" required>

  <label for="gender">Gender</label>
  <select id="gender" name="gender" required>
    <option value="">--Select--</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>

  <label for="diagnosis">Diagnosis</label>
  <input type="text" id="diagnosis" name="diagnosis" required>

  <label for="contact">Contact Number</label>
  <input type="text" id="contact" name="contact" required>

  <button type="submit">Add Patient</button>
</form>

</body>
</html>
