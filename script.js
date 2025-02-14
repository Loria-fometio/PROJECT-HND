// Toggle password visibility
const togglePassword = document.getElementById("toggle-password");
const passwordField = document.getElementById("password");

togglePassword.addEventListener("click", function () {
    const type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
});

// Handle form submission
const loginForm = document.getElementById("login-form");

loginForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent page reload on form submission
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (email && password) {
        // Simulate a successful login (you can replace this with a backend API call)
        alert(`Logged in as: ${email}`);
    } else {
        alert("Please fill out both fields.");
    }
});
