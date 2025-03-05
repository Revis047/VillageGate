<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}
// Connect to your database (update with correct credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id=$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Form data processing
    include 'new_comers.php'; // This file handles the backend logic for registration
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_status'])) {
    // Check status code
    $email = $_POST['email'];
    $result = $conn->query("SELECT status FROM newcomers WHERE email='$email' AND user_id='$user_id' ");
    $status = $result->num_rows > 0 ? $result->fetch_assoc()['status'] : 'No application found ';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Registration System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            color: #333;
            padding-top: 70px; /* Offset for sticky navbar */
        }

        .navbar {
            background: linear-gradient(90deg, #007bff, #00a8ff);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
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

        .form-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: -50px auto 50px;
            max-width: 800px;
        }

        .form-container h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .status-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            text-align: center;
        }

        .status-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        footer {
            background: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }

        footer a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            color: #007bff;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 60px 0;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .form-container {
                margin: -30px auto 30px;
                padding: 20px;
            }
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
            <h1>Welcome to the Village Registration System</h1>
            <p>Register as a newcomer and join our vibrant community!</p>
        </div>
    </section>

    <!-- Registration Form -->
    <div class="container">
        <div class="form-container">
            <h2>Newcomer Registration</h2>
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="id_proof">ID Proof</label>
                    <input type="file" class="form-control" id="id_proof" name="id_proof" accept="image/*, .pdf" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
            </form>
        </div>

        <!-- Status Check Section -->
        <div class="form-container">
            <h2>Check Your Registration Status</h2>
            <form action="index.php" method="POST">
                <div class="form-group">
                    <label for="email">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="check_status">Check Status</button>
            </form>

            <?php if (isset($status)): ?>
                <div class="status-message <?php echo ($status === 'No application found') ? 'error' : 'success'; ?>">
                    <i class="fas <?php echo ($status === 'No application found') ? 'fa-times-circle' : 'fa-check-circle'; ?>"></i>
                    <?php echo $status; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->

    <?php include("includes/footer.php");  ?>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>