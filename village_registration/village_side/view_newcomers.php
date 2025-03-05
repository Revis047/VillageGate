<?php

session_start();

if (!isset($_SESSION['user_id'])) {
   
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
} 
// admin.php - Admin Interface for managing user registration status
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin approval or rejection logic
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $conn->query("UPDATE newcomers SET status='Approved' WHERE id=$id");
    header("Location:view_newcomers.php"); // Redirect to refresh the page
    exit();
}

if (isset($_GET['reject'])) {
    $id = $_GET['reject'];
    $conn->query("UPDATE newcomers SET status='Rejected' WHERE id=$id");
    header("Location: view_newcomers.php"); // Redirect to refresh the page
    exit();
}
$userid=$_SESSION['user_id'];
// Fetch all users from the database
$result = $conn->query("SELECT * FROM newcomers WHERE user_id ='$userid'");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Village Registration</title>
    <link rel="icon" type="image/x-icon" href="ss.jpeg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- Include jsPDF -->
    <style>
        /* Custom Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the page takes at least the full viewport height */
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

        .dropdown-menu {
            background: #007bff;
            border: none;
            border-radius: 5px;
        }

        .dropdown-item {
            color: #fff !important;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: #00a8ff;
        }

        .hero {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            color: #fff;
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
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

        .btn-success, .btn-danger, .btn-info, .btn-warning {
            margin: 2px;
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
            <h1>Admin Panel</h1>
            <p>Manage newcomer registrations and approvals.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container flex-grow-1">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row_<?php echo $row['id']; ?>">
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <!-- View Button -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#viewModal_<?php echo $row['id']; ?>">
                                <i class="fas fa-eye"></i> View
                            </button>

                            <?php if ($row['status'] == 'Pending'): ?>
                                <button class="btn btn-success btn-sm" onclick="approveUser(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="rejectUser(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            <?php else: ?>
                                <span class="badge badge-<?php echo ($row['status'] == 'Approved' ? 'success' : 'danger'); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                                <?php if ($row['status'] == 'Approved' || $row['status'] == 'Rejected'): ?>
                                    <button class="btn btn-info btn-sm" onclick="downloadCertificate('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['phone']; ?>', '<?php echo $row['address']; ?>', '<?php echo $row['status']; ?>')">
    <i class="fas fa-download"></i> Download
</button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- <a href="update_newcomer.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i> Modify
    </a> -->
    <a class="btn btn-danger btn-sm" href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                    
        <i class="fas fa-trash-alt"></i> Delete
     </a>
</td>
                        </td>
                    </tr>

                    <!-- View Modal for Each Newcomer -->
                    <div class="modal fade" id="viewModal_<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel">View Newcomer Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Name:</strong> <?php echo $row['name']; ?></p>
                                            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                                            <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
                                            <p><strong>Address:</strong> <?php echo $row['address']; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>ID Proof:</strong></p>
                                            <?php if (pathinfo($row['id_proof'], PATHINFO_EXTENSION) === 'pdf'): ?>
                                                <a href="<?php echo 'uploads/'. $row['id_proof']; ?>" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            <?php else: ?>
                                                <img src="<?php echo 'uploads/'.  $row['id_proof']; ?>" alt="ID Proof" class="img-fluid">
                                            <?php endif; ?>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->

    <?php include("includes/footer.php");  ?>


    <script>
      // Function to handle approval
      function approveUser(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `view_newcomers.php?approve=${id}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Reload the page to reflect the changes
                    window.location.reload();
                } else {
                    alert('Error updating user status.');
                }
            };
            xhr.send();
        }

        // Function to handle rejection
        function rejectUser(id) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `view_newcomers.php?reject=${id}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Reload the page to reflect the changes
                    window.location.reload();
                } else {
                    alert('Error rejecting user.');
                }
            };
            xhr.send();
        }

        // Function to handle the download of the certificate
        function downloadCertificate(id, name, email, phone, address, status) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFont('Arial', 'B', 16);

            if (status === 'Approved') {
                // Generate Approval Certificate
                doc.text('Village Newcomer Residency Certificate', 105, 10, null, null, 'center');
                doc.setFont('Arial', '', 12);
                doc.text("This is to certify that:", 105, 30, null, null, 'center');
                doc.text(`Name: ${name}`, 10, 40);
                doc.text(`Email: ${email}`, 10, 50);
                doc.text(`Phone: ${phone}`, 10, 60);
                doc.text(`Address: ${address}`, 10, 70);
                doc.text('Approved by the Village Authority', 105, 90, null, null, 'center');
                doc.setFont('Arial', 'I', 10);
                doc.text('This certificate serves as proof of your residency status.', 10, 110);
                doc.text('Thank you for joining the Village Community!', 10, 120);
                doc.line(10, 160, 100, 160);
                doc.text('Signature of Authority', 10, 165);
                doc.line(105, 160, 200, 160);
                doc.text('Signature of Resident', 105, 165);
                doc.save(`Residency_Certificate_${name}.pdf`);
            } else if (status === 'Rejected') {
                // Generate Rejection Certificate
                doc.text('Village Newcomer Application Rejection', 105, 10, null, null, 'center');
                doc.setFont('Arial', '', 12);
                doc.text("This is to inform you that your application has been rejected.", 105, 30, null, null, 'center');
                doc.text(`Name: ${name}`, 10, 40);
                doc.text(`Email: ${email}`, 10, 50);
                doc.text(`Phone: ${phone}`, 10, 60);
                doc.text(`Address: ${address}`, 10, 70);
                doc.text('Reason for Rejection:', 10, 90);
                doc.text('Your application does not meet the required criteria.', 10, 100);
                doc.text('Please contact the village office for further details.', 10, 110);
                doc.setFont('Arial', 'I', 10);
                doc.text('We regret any inconvenience caused.', 10, 130);
                doc.line(10, 160, 100, 160);
                doc.text('Signature of Authority', 10, 165);
                doc.save(`Rejection_Notice_${name}.pdf`);
            }
        }         
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>