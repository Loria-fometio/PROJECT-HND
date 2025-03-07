<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - BankPro</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
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
        .login-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-button:hover {
            background-color: #0056b3;
        }
        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .spinner-border {
            display: none;
            margin-left: 10px;
            width: 18px;
            height: 18px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1><i class="bi bi-bank"></i> BankPro</h1>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="bi bi-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary login-button" id="loginButton">
                <span>Login as Client</span>
                <div class="spinner-border text-light" id="loadingSpinner"></div>
            </button>
            <div class="forgot-password">
                <a href="forgotpassword.html">Forgot password?</a>
            </div>
        </form>
        <div id="responseMessage" class="mt-3"></div> <!-- Response message will be displayed here -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function (e) {
            e.preventDefault(); 

            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const loginButton = document.getElementById("loginButton");
            const loadingSpinner = document.getElementById("loadingSpinner");
            const responseMessage = document.getElementById("responseMessage");

            // Disable button & show loading
            loginButton.disabled = true;
            loadingSpinner.style.display = "inline-block";

            fetch("insert.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                // Enable button & hide loading
                loginButton.disabled = false;
                loadingSpinner.style.display = "none";

                if (data.status === "success") {
                    alert("Login successful! Redirecting...");
                    sessionStorage.setItem("isLoggedIn", "true"); // Store login state
                    window.location.href = "clientDashboard.html"; // Redirect to dashboard
                } else {
                    responseMessage.innerHTML = `
                        <div class="alert alert-danger">${data.message}</div>
                    `;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                responseMessage.innerHTML = `<div class="alert alert-danger">Something went wrong. Try again later.</div>`;

                // Enable button & hide loading
                loginButton.disabled = false;
                loadingSpinner.style.display = "none";
            });
        });
    </script>
</body>
</html>