<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bank_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total transactions
function getTotalTransactions($conn) {
    $sql = "SELECT COUNT(*) as total FROM transactions";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to get total deposits
function getTotalDeposits($conn) {
    $sql = "SELECT COUNT(*) as total FROM transactions WHERE transaction_type = 'deposit'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to get total withdrawals
function getTotalWithdrawals($conn) {
    $sql = "SELECT COUNT(*) as total FROM transactions WHERE transaction_type = 'withdrawal'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to get total transfers
function getTotalTransfers($conn) {
    $sql = "SELECT COUNT(*) as total FROM transactions WHERE transaction_type = 'transfer'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to get monthly labels (e.g., January, February, etc.)
function getMonthlyLabels() {
    return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
}

// Function to get monthly deposits
function getMonthlyDeposits($conn) {
    $sql = "SELECT MONTH(created_at) as month, COUNT(*) as total FROM transactions WHERE transaction_type = 'deposit' GROUP BY MONTH(created_at)";
    $result = $conn->query($sql);
    $data = array_fill(0, 12, 0); // Initialize array with 12 months
    while ($row = $result->fetch_assoc()) {
        $data[$row['month'] - 1] = $row['total']; // Adjust for 0-based index
    }
    return $data;
}

// Function to get monthly withdrawals
function getMonthlyWithdrawals($conn) {
    $sql = "SELECT MONTH(created_at) as month, COUNT(*) as total FROM transactions WHERE transaction_type = 'withdrawal' GROUP BY MONTH(created_at)";
    $result = $conn->query($sql);
    $data = array_fill(0, 12, 0); // Initialize array with 12 months
    while ($row = $result->fetch_assoc()) {
        $data[$row['month'] - 1] = $row['total']; // Adjust for 0-based index
    }
    return $data;
}

// Function to get monthly transfers
function getMonthlyTransfers($conn) {
    $sql = "SELECT MONTH(created_at) as month, COUNT(*) as total FROM transactions WHERE transaction_type = 'transfer' GROUP BY MONTH(created_at)";
    $result = $conn->query($sql);
    $data = array_fill(0, 12, 0); // Initialize array with 12 months
    while ($row = $result->fetch_assoc()) {
        $data[$row['month'] - 1] = $row['total']; // Adjust for 0-based index
    }
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Statistics - MicroFinance Pro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
       
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .footer {
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
   

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-bar-chart"></i> Transaction Statistics</h2>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-list-task"></i> Total Transactions</h5>
                            <p class="card-text display-4"><?php echo getTotalTransactions($conn); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-cash"></i> Total Deposits</h5>
                            <p class="card-text display-4"><?php echo getTotalDeposits($conn); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-cash-stack"></i> Total Withdrawals</h5>
                            <p class="card-text display-4"><?php echo getTotalWithdrawals($conn); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Total Transfers</h5>
                            <p class="card-text display-4"><?php echo getTotalTransfers($conn); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-bar-chart"></i> Transaction Trends</h5>
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js Script -->
    <script>
        // Transaction Trends Chart
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(getMonthlyLabels()); ?>,
                datasets: [
                    {
                        label: 'Deposits',
                        data: <?php echo json_encode(getMonthlyDeposits($conn)); ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Withdrawals',
                        data: <?php echo json_encode(getMonthlyWithdrawals($conn)); ?>,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        fill: true,
                    },
                    {
                        label: 'Transfers',
                        data: <?php echo json_encode(getMonthlyTransfers($conn)); ?>,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.2)',
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Monthly Transaction Trends'
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>