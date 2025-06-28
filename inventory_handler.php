<?php
// Start session if needed for messages (optional)
// session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "sky_hospital");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize inputs
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $itemName = sanitize_input($_POST['itemName'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $category = sanitize_input($_POST['category'] ?? '');
    $date = $_POST['date'] ?? '';

    // Basic validation
    if (empty($itemName) || $quantity <= 0 || empty($category) || empty($date)) {
        // Redirect back with error or just die with message
        die("Please fill all the fields correctly.");
    }

    // Prepare and bind to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO inventory (itemName, quantity, category, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $itemName, $quantity, $category, $date);

    if ($stmt->execute()) {
        // Redirect back to the form page after success
        header("Location: add_inventory.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
