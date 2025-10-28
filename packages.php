<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit();
}

include 'config.php'; // Make sure this file contains your mysqli connection like: $conn = mysqli_connect(...);

$query = "SELECT * FROM packages ORDER BY event_name, package_name";
$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Packages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }

        h2 {
            color: #6c5ce7;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .table thead {
            background-color: #6c5ce7;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">All Service Packages</h2>

    <div class="table-responsive">
        <table id="packagesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Event</th>
                    <th>Package</th>
                    <th>Description</th>
                    <th>Price (₱)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$count}</td>
                            <td>" . htmlspecialchars($row['event_name']) . "</td>
                            <td>" . htmlspecialchars($row['package_name']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>" . number_format($row['price'], 2) . "</td>
                          </tr>";
                    $count++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#packagesTable').DataTable({
            pageLength: 10,
            lengthChange: false
        });
    });
</script>

</body>
</html>
