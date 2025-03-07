<?php
// Enable error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "bank_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize and validate input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $account_number = sanitizeInput($_POST['accountNumber']);
    $loan_amount = sanitizeInput($_POST['loanAmount']);
    $loan_type = sanitizeInput($_POST['loanType']);
    $tenure = sanitizeInput($_POST['tenure']);
    $purpose = sanitizeInput($_POST['purpose']);

    // Validate inputs
    if (empty($account_number) || empty($loan_amount) || empty($loan_type) || empty($tenure) || empty($purpose)) {
        $message = "All fields are required!";
    } elseif (!is_numeric($loan_amount) || $loan_amount <= 0) {
        $message = "Loan amount must be a positive number!";
    } elseif (!is_numeric($tenure) || $tenure <= 0) {
        $message = "Tenure must be a positive number!";
    } else {
        // Check if account exists
        $sql_check = "SELECT id FROM clients WHERE account_number = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("s", $account_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $client_id = $row['id'];
            $status = 'pending'; // Default

if ($loan_amount > 1000000) {
    $status = 'rejected';
} elseif ($loan_amount <= 5000) {
    $status = 'approved';
    
}

// Insert loan request with status
$stmt = $conn->prepare("INSERT INTO loan_requests (client_id, loan_amount, loan_type, tenure, purpose, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("idssis", $client_id, $loan_amount, $loan_type, $tenure, $purpose, $status);
$stmt->execute();


            // Insert loan request only if the account exists
            $sql_insert = "INSERT INTO loan_requests (client_id, loan_amount, loan_type, tenure, purpose, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("dssiss", $client_id, $loan_amount, $loan_type, $tenure, $purpose , "pending");

            if ($stmt->execute()) {
                $message = "Loan request submitted successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error: Account not found. Loan request not submitted.";
        }
    }
}

// Fetch loan requests
$sql = "SELECT lr.loan_id, c.account_number, lr.loan_amount, 
               lr.loan_type, lr.tenure, lr.purpose, 
               lr.request_date, lr.status 
        FROM loan_requests lr 
        JOIN clients c ON lr.client_id = c.id";

// $sql = "SELECT lr.loan_id, c.account_number, lr.loan_amount, 
//                lr.loan_type, lr.tenure, lr.purpose, 
//                lr.request_date 
//         FROM loan_requests lr 
//         JOIN clients c ON lr.client_id = c.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Requests</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom search bar styling */
        .dataTables_filter {
            float: right;
            margin-bottom: 20px;
        }
        .dataTables_filter input {
            border: 2px solid #007bff;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }
        .dataTables_filter input:focus {
            border-color: #0056b3;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .dataTables_filter label {
            position: relative;
        }
        .dataTables_filter label::after {
            content: "\f002"; /* Font Awesome search icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
            pointer-events: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Loan Requests</h2>

    <!-- Loan Request Form -->
    <form method="POST" class="mb-4" onsubmit="return validateForm()">
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="accountNumber" placeholder="Account Number" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="loanAmount" placeholder="Amount (FCFA)" required>
            </div>
            <div class="col-md-2">
                <select name="loanType" class="form-control" required>
                    <option value="">Loan Type</option>
                    <option value="personal">Personal</option>
                    <option value="home">Home</option>
                    <option value="car">Car</option>
                    <option value="education">Education</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="tenure" placeholder="Tenure (months)" required>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="purpose" placeholder="Purpose" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Apply for Loan</button>
    </form>

    <!-- Loan Requests Table -->
    <table id="loanTable" class="table table-striped table-bordered">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Account Number</th>
                <th>Loan Amount</th>
                <th>Loan Type</th>
                <th>Tenure</th>
                <th>Purpose</th>
                <th>Request Date</th>
                <th>status</th>

            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['loan_id']); ?></td>
                    <td><?= htmlspecialchars($row['account_number']); ?></td>
                    <td><?= number_format($row['loan_amount'], 2); ?>FCFA</td>
                    <td><?= htmlspecialchars($row['loan_type']); ?></td>
                    <td><?= htmlspecialchars($row['tenure']); ?> months</td>
                    <td><?= htmlspecialchars($row['purpose']); ?></td>
                    <td><?= htmlspecialchars($row['request_date']); ?></td>
                    <td ><?=htmlspecialchars($row['status']); ?></td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#loanTable').DataTable({
            "paging": true,
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50, 100],
            "ordering": true,
            "searching": true,
            "language": {
                "search": "", // Remove default "Search:" label
                "searchPlaceholder": "Search...", // Add placeholder text
            }
        });
    });

    // Client-side validation
    function validateForm() {
        const accountNumber = document.querySelector('input[name="accountNumber"]').value;
        const loanAmount = document.querySelector('input[name="loanAmount"]').value;
        const loanType = document.querySelector('select[name="loanType"]').value;
        const tenure = document.querySelector('input[name="tenure"]').value;
        const purpose = document.querySelector('input[name="purpose"]').value;

        if (!accountNumber || !loanAmount || !loanType || !tenure || !purpose) {
            alert("All fields are required!");
            return false;
        }

        if (isNaN(loanAmount) || loanAmount <= 0) {
            alert("Loan amount must be a positive number!");
            return false;
        }

        if (isNaN(tenure) || tenure <= 0) {
            alert("Tenure must be a positive number!");
            return false;
        }

        return true;
    }
</script>
</body>
</html>

<?php
$conn->close();
?>

