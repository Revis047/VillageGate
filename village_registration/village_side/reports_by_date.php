<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
} // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "village_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id=$_SESSION['user_id'];
// Fetch newcomers based on date range (if provided)
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$sql = "SELECT * FROM newcomers WHERE (registration_date BETWEEN '$start_date' AND '$end_date') AND  user_id ='$user_id'";

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports by Date - Village Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- Include jsPDF -->
<?php include("includes/header.php");   ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Reports by Date</h1>
            <p>View newcomer registrations filtered by date range.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Date Range Filter -->
        <form method="GET" action="reports_by_date.php" class="mb-4">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Download Report Button -->
        <div class="text-right mb-4">
            <button class="btn btn-success" onclick="downloadReport()">
                <i class="fas fa-download"></i> Download Report
            </button>
        </div>

        <!-- Newcomers Table -->
        <div class="card">
            <div class="card-header">
                Newcomers
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
                            <th>Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['registration_date']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
            doc.text('Village Registration Report by Date', 105, 10, null, null, 'center');

            // Add date range
            doc.setFont('Arial', '', 12);
            let y = 30;
            doc.text(`Date Range: ${document.getElementById('start_date').value} to ${document.getElementById('end_date').value}`, 10, y);
            y += 10;

            // Add table headers
            doc.text('#', 10, y);
            doc.text('Name', 20, y);
            doc.text('Email', 60, y);
            doc.text('Phone', 110, y);
            doc.text('Address', 150, y);
            doc.text('Registration Date', 190, y);
            y += 10;

            // Add table rows
            <?php if ($result->num_rows > 0): ?>
                <?php $result->data_seek(0); // Reset result pointer ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    doc.text(`<?php echo $row['id']; ?>`, 10, y);
                    doc.text(`<?php echo $row['name']; ?>`, 20, y);
                    doc.text(`<?php echo $row['email']; ?>`, 60, y);
                    doc.text(`<?php echo $row['phone']; ?>`, 110, y);
                    doc.text(`<?php echo $row['address']; ?>`, 150, y);
                    doc.text(`<?php echo $row['registration_date']; ?>`, 190, y);
                    y += 10;
                <?php endwhile; ?>
            <?php endif; ?>

            // Save the PDF
            doc.save('Village_Registration_Report_By_Date.pdf');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>