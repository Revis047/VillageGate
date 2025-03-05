<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database Connection
 

// Registration Process (Only for logged-in users)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $id_proof = $_FILES['id_proof']['name'];
    $status = "Pending";

    // File Upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["id_proof"]["name"]);
    move_uploaded_file($_FILES["id_proof"]["tmp_name"], $target_file);

    $sql = "INSERT INTO newcomers (name, email, phone, address, id_proof, status) 
            VALUES ('$name', '$email', '$phone', '$address', '$id_proof', '$status')";

    if ($conn->query($sql)) {
        echo "Registration successful! Await admin approval.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Admin Approval System (Only for village admins)
if ($_SESSION['role'] === 'village_admin') {
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