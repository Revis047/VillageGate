<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'village_registration';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'village_admin') {
                header("Location: index.php");
            } else {
                header("Location: ./cell_side/cell_dashboard.php"); // Redirect sector admins to their dashboard
            }
            exit();
        } else {
            echo "<div class='alert alert-danger'>Invalid password!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>User not found!</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .form-container .form-label {
            font-weight: 500;
            color: #555;
        }
        .form-container .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }
        .form-container .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.2);
        }
        .form-container .btn {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        .form-container .btn:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
        }
        .form-container .input-icon {
            position: relative;
        }
        .form-container .input-icon i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #888;
        }
        .form-container .input-icon input {
            padding-left: 40px;
        }
        .form-container .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
        .form-container .signup-link a {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 500;
        }
        .form-container .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form method="POST" action="login.php">
            <div class="mb-3 input-icon">
                <label for="username" class="form-label">Username:</label>
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="mb-3 input-icon">
                <label for="password" class="form-label">Password:</label>
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>

            <div class="signup-link">
                Don't have an account? <a href="sign_up.php">Sign Up</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>