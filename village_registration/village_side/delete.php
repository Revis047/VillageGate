<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set 
    $id =$_GET['id'];

    // Delete the record
    $sql = "DELETE FROM newcomers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Newcomer record deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record!";
    }

    $stmt->close();


// Close connection
$conn->close();

// Redirect back to the main page
header("Location: view_newcomers.php");
exit();
?>
