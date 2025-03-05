<?php
// Enable error reporting

session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to the database.";
// Registration Process (Only for logged-in users)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    echo "Registration form submitted.";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $id_proof = $_FILES['id_proof']['name'];
    $status = "Pending";

    // File Upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["id_proof"]["name"]);
    if (move_uploaded_file($_FILES["id_proof"]["tmp_name"], $target_file)) {
        echo "File uploaded successfully.";
    } else {
        echo "File upload failed.";
    }
    $userid=$_SESSION['user_id'];
    $sql = "INSERT INTO newcomers (name, email, phone, address, id_proof, status,user_id) 
            VALUES ('$name', '$email', '$phone', '$address', '$id_proof', '$status','$userid')";

    if ($conn->query($sql)) {
        echo "Registration successful! Await admin approval.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Admin Approval System (Only for village admins)
if ($_SESSION['role'] === 'village_admin') {
    echo "User is a village admin.";

    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $conn->query("UPDATE newcomers SET status='Approved' WHERE id=$id");

        // Fetch user details for certificate
        $result = $conn->query("SELECT * FROM newcomers WHERE id=$id");
        $row = $result->fetch_assoc();
        $certificatePath = generateCertificate($row['name'], $row['email'], $row['phone'], $row['address']);
    }

    if (isset($_GET['reject'])) {
        $id = $_GET['reject'];
        $conn->query("UPDATE newcomers SET status='Rejected' WHERE id=$id");
        $result = $conn->query("SELECT * FROM newcomers WHERE id=$id");
        $row = $result->fetch_assoc();
    }
}

// User Status Check (For all logged-in users)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_status'])) {
    echo "Status check requested.";

    $email = $_POST['email'];
    $result = $conn->query("SELECT status FROM newcomers WHERE email='$email'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Your application status: " . $row['status'];
    } else {
        echo "No application found for this email.";
    }
}

$conn->close();

// Function to generate certificate (Example)
function generateCertificate($name, $email, $phone, $address) {
    // Logic to generate a certificate (e.g., PDF or image)
    // Save the certificate to a file and return the file path
    $certificatePath = "certificates/$name-certificate.pdf";
    // Example: Use a library like FPDF or TCPDF to generate the PDF
    return $certificatePath;
}


?>