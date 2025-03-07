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

// Fetch statistics
$totalClientsQuery = "SELECT COUNT(*) AS total_clients FROM clients";
$totalClientsResult = $conn->query($totalClientsQuery);
$totalClients = $totalClientsResult->fetch_assoc()['total_clients'];

$loanRequestsQuery = "SELECT COUNT(*) AS total_loan_requests FROM loan_requests";
$loanRequestsResult = $conn->query($loanRequestsQuery);
$totalLoanRequests = $loanRequestsResult->fetch_assoc()['total_loan_requests'];

$totalLoanedQuery = "SELECT SUM(loan_amount) AS total_loaned FROM loan_requests";
$totalLoanedResult = $conn->query($totalLoanedQuery);
$totalLoaned = $totalLoanedResult->fetch_assoc()['total_loaned'];

// Define goals for progress bars
$totalClientsGoal = 100; // Example: Target 1000 clients
$loanRequestsGoal = 500; // Example: Target 500 loan requests
$totalLoanedGoal = 1000000; // Example: Target $1,000,000 loaned

// Calculate progress percentages
$clientsProgress = ($totalClients / $totalClientsGoal) * 100;
$loanRequestsProgress = ($totalLoanRequests / $loanRequestsGoal) * 100;
$totalLoanedProgress = ($totalLoaned / $totalLoanedGoal) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - BankPro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .statistics-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            font-size: 28px;
        }
        .stat-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .stat-card h3 {
            color: #007bff;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .stat-card p {
            font-size: 20px;
            color: #333;
            font-weight: 500;
        }
        .stat-card .icon {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .stat-card .value {
            font-size: 32px;
            color: #007bff;
            font-weight: 700;
        }
        .stat-card .label {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }
        .stat-card .progress {
            height: 10px;
            background-color: #e3f2fd;
            border-radius: 5px;
            margin-top: 20px;
            overflow: hidden;
        }
        .stat-card .progress-bar {
            background-color: #007bff;
            transition: width 1s ease;
        }
    </style>
</head>
<body>
    <div class="statistics-container">
        <h2><i class="bi bi-bar-chart"></i> Client Statistics</h2>
        <div class="row">
            <!-- Total Clients -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-people"></i></div>
                    <h3>Total Clients</h3>
                    <p class="value"><?= $totalClients ?></p>
                    <div class="label">Registered Clients</div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $clientsProgress ?>%;"></div>
                    </div>
                </div>
            </div>
            <!-- Loan Requests -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
                    <h3>Loan Requests</h3>
                    <p class="value"><?= $totalLoanRequests ?></p>
                    <div class="label">Pending & Approved Loans</div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $loanRequestsProgress ?>%;"></div>
                    </div>
                </div>
            </div>
            <!-- Total Loaned -->
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="icon"><i class="bi bi-cash-stack"></i></div>
                    <h3>Total Loaned</h3>
                    <p class="value"><?= number_format($totalLoaned, 1) ?>FCFA</p>
                    <div class="label">Approved Loan Amount</div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $totalLoanedProgress ?>%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for Animations -->
    <script>
        // Animate progress bars on page load
        document.addEventListener("DOMContentLoaded", function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0'; // Reset width to 0
                setTimeout(() => {
                    bar.style.width = width; // Animate to the calculated width
                }, 100);
            });
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>