<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sky_hospital";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get patient ID from query
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Update patient info if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $diagnosis = $conn->real_escape_string($_POST['diagnosis']);
    $contact = $conn->real_escape_string($_POST['contact']);

    $sql = "UPDATE patients SET 
                name='$name', 
                age=$age, 
                gender='$gender', 
                diagnosis='$diagnosis', 
                contact='$contact' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: patient_info.php");
        exit();
    } else {
        $error = "Error updating record: " . $conn->error;
    }
}

// Fetch existing data
$patient = null;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM patients WHERE id = $id");
    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        die("Patient not found");
    }
} else {
    die("Invalid patient ID");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8f5;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: auto;
        }
        h2 {
            color: #2a9d8f;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            background-color: #2a9d8f;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #21867a;
        }
        .error {
            color: red;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2a9d8f;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Patient</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required />

        <label>Age</label>
        <input type="number" name="age" value="<?= htmlspecialchars($patient['age']) ?>" required />

        <label>Gender</label>
        <select name="gender" required>
            <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Diagnosis</label>
        <input type="text" name="diagnosis" value="<?= htmlspecialchars($patient['diagnosis']) ?>" required />

        <label>Contact</label>
        <input type="text" name="contact" value="<?= htmlspecialchars($patient['contact']) ?>" required />

        <button type="submit">Update Patient</button>
    </form>
    <a href="patient_info.php" class="back-link">← Back to Patient List</a>
</div>
</body>
</html>
