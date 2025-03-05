<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}// Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id=$_SESSION['user_id'];
// Fetch counts for each status
$status_counts = [];
$statuses = ['Approved', 'Rejected', 'Pending'];

foreach ($statuses as $status) {
    $sql = "SELECT COUNT(*) as count FROM newcomers WHERE status = '$status' AND user_id='$user_id'";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $status_counts[$status] = $row['count'];
    } else {
        $status_counts[$status] = 0;
    }
}

// Fetch all newcomers grouped by status
$newcomers_by_status = [];
foreach ($statuses as $status) {
    $sql = "SELECT * FROM newcomers WHERE status = '$status'  AND user_id='$user_id'";
    $result = $conn->query($sql);
    $newcomers_by_status[$status] = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports by Status - Village Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- Include jsPDF -->
    <style>
        /* Custom Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .navbar {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff !important;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-size: 1rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }

        .hero {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            color: #fff;
            padding: 60px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #007bff;
            color: #fff;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .table {
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background: #007bff;
            color: #fff;
            border: none;
        }

        .table tbody tr:hover {
            background: #f1f1f1;
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
        <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Logo"> Village Registration
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_newcomers.php"><i class="fas fa-users"></i> View Newcomers</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="reports_by_date.php">By Date</a>
                            <a class="dropdown-item" href="reports_by_status.php">By Status</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Reports by Status</h1>
            <p>View newcomer registrations grouped by their status.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Download Report Button -->
        <div class="text-right mb-4">
            <button class="btn btn-success" onclick="downloadReport()">
                <i class="fas fa-download"></i> Download Report
            </button>
        </div>

        <!-- Status Summary Cards -->
        <div class="row">
            <?php foreach ($status_counts as $status => $count): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <?php echo $status; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Total: <?php echo $count; ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Detailed Tables for Each Status -->
        <?php foreach ($newcomers_by_status as $status => $newcomers): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <?php echo $status; ?> Newcomers
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($newcomers as $newcomer): ?>
                                <tr>
                                    <td><?php echo $newcomer['id']; ?></td>
                                    <td><?php echo $newcomer['name']; ?></td>
                                    <td><?php echo $newcomer['email']; ?></td>
                                    <td><?php echo $newcomer['phone']; ?></td>
                                    <td><?php echo $newcomer['address']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->

    <?php include("includes/footer.php");  ?>


    <script>
        // Function to download the report as a PDF
        function downloadReport() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add a title
            doc.setFont('Arial', 'B', 16);
            doc.text('Village Registration Report by Status', 105, 10, null, null, 'center');

            // Add status summary
            doc.setFont('Arial', '', 12);
            let y = 30;
            doc.text('Status Summary:', 10, y);
            y += 10;

            <?php foreach ($status_counts as $status => $count): ?>
                doc.text(`- <?php echo $status; ?>: <?php echo $count; ?>`, 10, y);
                y += 10;
            <?php endforeach; ?>

            // Add detailed tables for each status
            <?php foreach ($newcomers_by_status as $status => $newcomers): ?>
                doc.addPage();
                doc.setFont('Arial', 'B', 14);
                doc.text(`<?php echo $status; ?> Newcomers`, 105, 10, null, null, 'center');
                doc.setFont('Arial', '', 12);
                y = 20;

                // Table headers
                doc.text('#', 10, y);
                doc.text('Name', 30, y);
                doc.text('Email', 70, y);
                doc.text('Phone', 120, y);
                doc.text('Address', 160, y);
                y += 10;

                // Table rows
                <?php foreach ($newcomers as $newcomer): ?>
                    doc.text(`<?php echo $newcomer['id']; ?>`, 10, y);
                    doc.text(`<?php echo $newcomer['name']; ?>`, 30, y);
                    doc.text(`<?php echo $newcomer['email']; ?>`, 70, y);
                    doc.text(`<?php echo $newcomer['phone']; ?>`, 120, y);
                    doc.text(`<?php echo $newcomer['address']; ?>`, 160, y);
                    y += 10;
                <?php endforeach; ?>
            <?php endforeach; ?>

            // Save the PDF
            doc.save('Village_Registration_Report.pdf');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>