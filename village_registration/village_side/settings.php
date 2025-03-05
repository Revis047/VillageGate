<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
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

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT user_id, username, password_hash, full_name, role, created_at FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Handle form submissions
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        // Update profile information
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];

        $sql = "UPDATE users SET full_name = '$full_name', username = '$username' WHERE user_id = $user_id";
        if ($conn->query($sql)) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $result = $conn->query("SELECT user_id, username, password_hash, full_name, role, created_at FROM users WHERE user_id = $user_id");
            $user = $result->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    } elseif (isset($_POST['change_password'])) {
        // Change password
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password
        if (password_verify($current_password, $user['password_hash'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password_hash = '$hashed_password' WHERE user_id = $user_id";
                if ($conn->query($sql)) {
                    $success_message = "Password changed successfully!";
                } else {
                    $error_message = "Error changing password: " . $conn->error;
                }
            } else {
                $error_message = "New passwords do not match!";
            }
        } else {
            $error_message = "Current password is incorrect!";
        }
    }
}

$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Village Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Village Registration</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
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

    <!-- Main Content -->
    <div class="container mt-5">
        <!-- Display Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Update Profile Section -->
        <div class="card mb-4">
            <div class="card-header">
                Update Profile
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo $user['full_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="card">
            <div class="card-header">
                Change Password
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-success">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
  
    <?php include("includes/footer.php");  ?>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>