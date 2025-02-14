<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - BankingPro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <style>
        body {
            font-family: 'poppins';
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-control {
            border-radius: 50px;
            margin-bottom: 15px;
        }
        .input-group-text {
            background-color: #f0f0f0;
            border-radius: 50px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .back-to-dashboard {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h2 class="text-center">Your Profile</h2>
            
            <!-- Profile Picture Section -->
            <div class="text-center mb-4">
                <img id="profilePreview" src="https://via.placeholder.com/150" alt="Profile Icon" class="profile-icon mb-3">
                <div>
                    <!-- File Input for Uploading New Profile Picture -->
                    <input type="file" id="profileImage" accept="image/*" onchange="previewImage(event)" class="form-control">
                </div>
                <button class="btn btn-outline-primary mt-3">Update Profile Picture</button>
            </div>

            <!-- Profile Form -->
            <form action="/update-profile" method="POST" id="profileForm">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <button type="submit" class="btn-custom">Update Profile</button>
            </form>
            <div class="back-to-dashboard">
                <p>Back to <a href="dashboard.html">Dashboard</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to preview the selected image
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    
    </script>
</body>
</html>
