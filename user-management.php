<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management System - Dashboard</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-12 px-4">
                <!-- Dashboard Overview -->
                <div class="row g-4 mt-4">
                    
                    <!-- Account Balance Card -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Account Balance</h5>
                                <p class="card-text display-6">$12,350.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions Card -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Recent Transactions</h5>
                                <ul>
                                    <li>$200 - Deposit</li>
                                    <li>$50 - Withdrawal</li>
                                    <li>$150 - Deposit</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions</h5>
                                <a href="money-transfer.html" class="btn btn-info w-100 mb-2">Transfer Money</a>
                                <a href="request-loan.html" class="btn btn-warning w-100">Request Loan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="row mt-5">
                    <h3 class="mb-4 text-center">User Registration Statistics</h3>
                    <!-- Bar Chart -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Chart.js Script -->
    <script>
        // Simulated user registration data (Replace this with actual data from your database)
        const userData = {
            months: ["January", "February", "March", "April", "May", "June"],
            users: [50, 80, 120, 150, 200, 250] // Number of people who created accounts per month
        };

        // Bar Chart (User Registrations Per Month)
        const ctxBar = document.getElementById("barChart").getContext("2d");
        new Chart(ctxBar, {
            type: "bar",
            data: {
                labels: userData.months,
                datasets: [{
                    label: "New Users",
                    data: userData.users,
                    backgroundColor: ["#007bff", "#17a2b8", "#ffc107", "#dc3545", "#28a745", "#6610f2"],
                    borderColor: "#333",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart (Proportion of Users Created)
        const ctxPie = document.getElementById("pieChart").getContext("2d");
        new Chart(ctxPie, {
            type: "pie",
            data: {
                labels: userData.months,
                datasets: [{
                    data: userData.users,
                    backgroundColor: ["#007bff", "#17a2b8", "#ffc107", "#dc3545", "#28a745", "#6610f2"]
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
