<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Bank_system";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = '';
$searchParams = [];
if ($search) {
    $searchCondition = "WHERE first_name LIKE ? OR email LIKE ? OR phone LIKE ?";
    $searchParams = ["%$search%", "%$search%", "%$search%"];
}

// Fetch total number of records for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM clients $searchCondition";
$stmt = $conn->prepare($totalQuery);
if ($search) {
    $stmt->bind_param("sss", ...$searchParams);
}
$stmt->execute();
$totalResult = $stmt->get_result();
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch client data with pagination and search
$sql = "SELECT id, first_name, email, phone, account_number, pincode, account_type FROM clients $searchCondition LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

if ($search) {
    // Combine search parameters with limit and offset
    $params = array_merge($searchParams, [$limit, $offset]);
    $stmt->bind_param("sssii", ...$params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Export to CSV or Excel
if (isset($_GET['export'])) {
    $exportType = $_GET['export'];
    $filename = "clients_" . date('Y-m-d') . ($exportType === 'csv' ? '.csv' : '.xls');

    if ($exportType === 'csv') {
        header("Content-Type: text/csv");
    } else {
        header("Content-Type: application/vnd.ms-excel");
    }
    header("Content-Disposition: attachment; filename=$filename");

    $output = fopen('php://output', 'w');

    // Add headers
    fputcsv($output, ['ID', 'First Name', 'Email', 'Phone', 'Account Number', 'Pincode', 'Account Type']);

    // Fetch all data for export
    $exportQuery = "SELECT id, first_name, email, phone, account_number, pincode, account_type FROM clients $searchCondition";
    $stmt = $conn->prepare($exportQuery);
    if ($search) {
        $stmt->bind_param("sss", ...$searchParams);
    }
    $stmt->execute();
    $exportResult = $stmt->get_result();

    while ($row = $exportResult->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        h2 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        .table {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            border: 1px solid #dee2e6;
            padding: 12px;
        }
        .table thead {
            background-color: #007bff;
            color: #fff;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
        .no-clients {
            font-size: 18px;
            color: #6c757d;
        }
        .search-box {
            max-width: 400px;
            margin: 0 auto 20px auto;
        }
        .export-buttons {
            margin-bottom: 20px;
            text-align: center;
        }
        .export-buttons .btn {
            margin: 5px;
        }
        .pagination {
            margin-top: 20px;
        }
        .bi {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="bi bi-people-fill"></i> Client List</h2>

    <!-- Search Box -->
    <form method="GET" action="" class="search-box">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        </div>
    </form>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <a href="?export=csv" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet"></i> Export to CSV</a>
        <a href="?export=excel" class="btn btn-warning"><i class="bi bi-file-earmark-excel"></i> Export to Excel</a>
    </div>

    <!-- Client Table -->
    <table class="table table-hover table-striped table-primar">
        <thead class="table-primary">
            <tr>
                <th><i class="bi bi-hash"></i> ID</th>
                <th><i class="bi bi-person"></i> First Name</th>
                <th><i class="bi bi-envelope"></i> Email</th>
                <th><i class="bi bi-telephone"></i> Phone</th>
                <th><i class="bi bi-wallet"></i> Account Number</th>
                <th><i class="bi bi-pin-map"></i> Pincode</th>
                <th><i class="bi bi-wallet2"></i> Account Type</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['account_number']}</td>
                        <td>{$row['pincode']}</td>
                        <td>{$row['account_type']}</td>
                    </tr>";
                }
            } else {
                echo "<tr>
                    <td colspan='7' class='text-center no-clients'><i class='bi bi-exclamation-circle'></i> No clients found</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" aria-label="Previous">
                        <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" aria-label="Next">
                        <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>