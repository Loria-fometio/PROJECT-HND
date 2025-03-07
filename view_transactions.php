<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - MicroFinance Pro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- DataTables Buttons CSS -->
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
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
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-deposit {
            background-color: #28a745;
        }
        .badge-withdrawal {
            background-color: #dc3545;
        }
        .badge-transfer {
            background-color: #ffc107;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            border-radius: 20px;
            padding: 10px 20px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 400px;
        }
        .search-bar input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .transaction-icon {
            margin-right: 8px;
        }
        .export-buttons {
            margin-bottom: 20px;
        }
        .export-buttons .btn {
            margin-right: 10px;
            border-radius: 20px;
        }
        .dataTables_wrapper .dataTables_filter {
            display: none; /* Hide default DataTables search */
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mb-4"><i class="bi bi-list-task"></i> Transaction History</h2>
                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Search transactions..." class="form-control">
                    </div>
                    <!-- Export Buttons -->
                    <div class="export-buttons">
                        <button class="btn btn-primary" id="copyButton"><i class="bi bi-clipboard"></i> Copy</button>
                        <button class="btn btn-success" id="csvButton"><i class="bi bi-file-earmark-spreadsheet"></i> CSV</button>
                        <button class="btn btn-warning" id="excelButton"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                        <button class="btn btn-danger" id="pdfButton"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
                        <button class="btn btn-info" id="printButton"><i class="bi bi-printer"></i> Print</button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="transactionsTable" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-hash"></i> ID</th>
                                            <th><i class="bi bi-credit-card"></i> Account Number</th>
                                            <th><i class="bi bi-credit-card"></i> Receiver Account</th>
                                            <th><i class="bi bi-cash"></i> Amount</th>
                                            <th><i class="bi bi-arrow-left-right"></i> Type</th>
                                            <th><i class="bi bi-calendar"></i> Date</th>
                                            <th><i class="bi bi-card-text"></i> Description</th>
                                            <th><i class="bi bi-clock"></i> Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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

                                        // Fetch transactions from the database
                                        $sql = "SELECT * FROM transactions ORDER BY created_at ASC";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $badgeClass = "";
                                                $icon = "";
                                                switch ($row['transaction_type']) {
                                                    case 'deposit':
                                                        $badgeClass = "badge-deposit";
                                                        $icon = "<i class='bi bi-arrow-down-circle transaction-icon'></i>";
                                                        break;
                                                    case 'withdrawal':
                                                        $badgeClass = "badge-withdrawal";
                                                        $icon = "<i class='bi bi-arrow-up-circle transaction-icon'></i>";
                                                        break;
                                                    case 'transfer':
                                                        $badgeClass = "badge-transfer";
                                                        $icon = "<i class='bi bi-arrow-left-right transaction-icon'></i>";
                                                        break;
                                                }
                                                echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['account_number']}</td>
                                                    <td>{$row['receiver_account_number']}</td>
                                                    <td>" . number_format($row['amount'], 2) . " FCFA</td>
                                                    <td><span class='badge $badgeClass'>$icon {$row['transaction_type']}</span></td>
                                                    <td>{$row['date']}</td>
                                                    <td>{$row['description']}</td>
                                                    <td>{$row['created_at']}</td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>No transactions found.</td></tr>";
                                        }

                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <!-- DataTables Buttons HTML5 Export -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <!-- DataTables Buttons Print -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <!-- JSZip (required for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <!-- PDFMake (required for PDF export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            var table = $('#transactionsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                paging: true, // Enable pagination
                pageLength: 10, // Default number of rows per page
                lengthMenu: [10, 25, 50, 100], // Dropdown for rows per page
                order: [[0, 'desc']], // Default sorting by ID (descending)
                language: {
                    search: "",
                    searchPlaceholder: "Search transactions..."
                }
            });

            // Add custom search bar functionality
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Add custom export button functionality
            $('#copyButton').on('click', function () {
                table.button('.buttons-copy').trigger();
            });
            $('#csvButton').on('click', function () {
                table.button('.buttons-csv').trigger();
            });
            $('#excelButton').on('click', function () {
                table.button('.buttons-excel').trigger();
            });
            $('#pdfButton').on('click', function () {
                table.button('.buttons-pdf').trigger();
            });
            $('#printButton').on('click', function () {
                table.button('.buttons-print').trigger();
            });
        });
    </script>
</body>
</html>