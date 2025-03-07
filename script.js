// document.getElementById('loginForm').addEventListener('submit', function (e) {
//     e.preventDefault();

//     // Get form values
//     const username = document.getElementById('username').value;
//     const password = document.getElementById('password').value;
//     const userType = document.getElementById('userType').value;

//     // Perform login validation (example)
//     if (username === "gloria" && password === "Gloria&1" && userType === "admin") {
//         alert("Login successful! Redirecting to Admin Dashboard...");
//         window.location.href = "dah.html"; // Redirect to Admin Dashboard
//     } else if (username === "loan-officer" && password === "12345" && userType === "loan_officer") {
//         alert("Login successful! Redirecting to Loan Officer Dashboard...");
//         window.location.href = "loanDashbord.html"; // Redirect to Loan Officer Dashboard
//     } else if (username === username && password === password && userType === "client") {
//         alert("Login successful! Redirecting to Client Dashboard...");
//         window.location.href = "clientDashboard.html"; // Redirect to Client Dashboard
//     } else {
//         alert("Invalid username, password, or user type. Please try again.");
//     }
// });