<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accountNumber = $_POST['accountNumber'];
    $loanAmount = $_POST['loanAmount'];
    $loanType = $_POST['loanType'];
    $tenure = $_POST['tenure'];
    $purpose = $_POST['purpose'];

    // Get the client ID using the account number
    $clientQuery = $conn->prepare("SELECT id, first_name FROM clients WHERE account_number = ?");
    $clientQuery->bind_param("s", $accountNumber);
    $clientQuery->execute();
    $clientResult = $clientQuery->get_result();

    if ($clientResult->num_rows > 0) {
        $client = $clientResult->fetch_assoc();
        $clientId = $client['id'];
        $clientName = $client['first_name'];

        // Insert loan request into database
        $insertLoan = $conn->prepare("INSERT INTO loan_requests (client_id, loan_amount, loan_type, tenure, purpose, request_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $insertLoan->bind_param("idssi", $clientId, $loanAmount, $loanType, $tenure, $purpose);

        if ($insertLoan->execute()) {
            // Insert notification for the loan officer
            $notificationMessage = "You received a loan request from $clientName (Account No: $accountNumber).";
            $insertNotification = $conn->prepare("INSERT INTO notifications (client_id, message, sent_at) VALUES (?, ?, NOW())");
            $insertNotification->bind_param("is", $clientId, $notificationMessage);
            $insertNotification->execute();

            echo "Loan request submitted successfully!";
        } else {
            echo "Error submitting loan request.";
        }
    } else {
        echo "Invalid account number!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Request - BankPro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .loan-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .submit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: 500;
        }
        .submit-button:hover {
            background-color: #0056b3;
        }
        .confirmation-message {
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            display: none;
        }
        .confirmation-message p {
            margin: 0;
            color: #555;
            font-size: 16px;
        }
        .confirmation-message i {
            margin-right: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="bi bi-cash-stack"></i> Loan Request</h1>
        <div class="loan-container">
            <form id="loanForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="accountNumber" class="form-label"><i class="bi bi-wallet"></i> Account Number</label>
                        <input type="text" class="form-control" name="accountNumber" placeholder="Enter account number" required>
                    </div>
                    <div class="col-md-6">
                        <label for="loanAmount" class="form-label"><i class="bi bi-cash"></i> Loan Amount</label>
                        <input type="number" class="form-control" name="loanAmount" placeholder="Enter loan amount" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">

                        <label for="loanType" class="form-label" name="loanType"><i class="bi bi-bank"></i> Loan Type</label>
                    <select class="form-control" id="loanType" name="loanType" required>
                           
                        <option value="">Select loan type</option>
                            <option value="personal">Personal Loan</option>
                            <option value="home">Home Loan</option>
                            <option value="car">Car Loan</option>
                            <option value="education">Education Loan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tenure" class="form-label"><i class="bi bi-calendar"></i> Tenure (Months)</label>
                        <input type="number" class="form-control" name="tenure" id="tenure" placeholder="Enter tenure in months" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="purpose" class="form-label"><i class="bi bi-card-text"></i> Purpose of Loan</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="3" placeholder="Enter purpose of loan"></textarea>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary submit-button">
                        <i class="bi bi-send"></i> Submit Request
                    </button>
                </div>
            </form>

            <!-- Confirmation Message -->
            <div id="confirmationMessage" class="confirmation-message mt-4">
                <h5><i class="bi bi-check-circle"></i> Loan Request Submitted</h5>
                <p><i class="bi bi-wallet"></i> Account Number: <span id="displayAccountNumber"></span></p>
                <p><i class="bi bi-cash"></i> Loan Amount: <span id="displayLoanAmount"></span></p>
                <p><i class="bi bi-bank"></i> Loan Type: <span id="displayLoanType"></span></p>
                <p><i class="bi bi-calendar"></i> Tenure: <span id="displayTenure"></span> months</p>
                <p><i class="bi bi-card-text"></i> Purpose: <span id="displayPurpose"></span></p>
            </div>

            <!-- Response Message -->
            <div id="responseMessage"></div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.getElementById("loanForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    document.getElementById("responseMessage").innerHTML = `<div class="alert alert-info">Processing request...</div>`;

    fetch("view-loan-request.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("responseMessage").innerHTML = `<div class="alert alert-success">${data}</div>`;
        document.getElementById("confirmationMessage").style.display = "block";
        document.getElementById("loanForm").reset();
    })
    .catch(error => {
        document.getElementById("responseMessage").innerHTML = `<div class="alert alert-danger">Error: ${error}</div>`;
    });
});

    </script>
</body>
</html>
