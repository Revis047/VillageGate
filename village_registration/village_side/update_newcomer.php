<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $id_proof = $_POST['id_proof'];
    $status = $_POST['status'];
    $registration_date = $_POST['registration_date'];

    $query = "UPDATE newcomers SET name=?, email=?, phone=?, address=?, id_proof=?, status=?, registration_date=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $name, $email, $phone, $address, $id_proof, $status, $registration_date, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Record updated successfully!'); window.location.href='view_newcomers.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$id = $_GET['id'] ?? '';
$result = $conn->query("SELECT * FROM newcomers WHERE id='$id'");
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Newcomer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input {
            border-radius: 4px;
        }

        .btn-update {
            width: 100%;
        }

        .form-group i {
            color: #6c757d;
        }

        .form-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(38, 143, 255, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2><i class="fas fa-user-edit"></i> Update Newcomer</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <div class="form-group mb-3">
                    <label for="name"><i class="fas fa-user"></i> Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $row['name']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $row['email']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="phone"><i class="fas fa-phone-alt"></i> Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $row['phone']; ?>" required>
                </div>

                <div class="form-group mb-3">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo $row['address']; ?>" required>
                </div>

                <button type="submit" name="update" class="btn btn-primary btn-update">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
