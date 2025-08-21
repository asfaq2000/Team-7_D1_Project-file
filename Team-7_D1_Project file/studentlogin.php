<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli('localhost', 'root', '', 'daffodil');
    
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }

    $studentId = $_POST['studentId'];
    $password = $_POST['password'];

    // Updated query to match new table structure
    $stmt = $conn->prepare("SELECT s.*, u.password 
                           FROM students s 
                           JOIN userstudent u ON s.studentId = u.studentId 
                           WHERE s.studentId = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['studentId'];
            $_SESSION['student_name'] = $user['studentName'];
            echo json_encode(['success' => true, 'redirect' => 'student.php']);
            exit;
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Invalid ID or password']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .login-container {
            min-height: 100vh;
            padding: 2rem;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .logo-section img {
            width: 130px;
            margin-bottom: 1.5rem;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.95);
            color: #4a5568;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        #formTitle {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        #formSubtitle {
            color: #718096;
            font-size: 0.95rem;
        }

        #errorMessage {
            background: rgba(254, 215, 215, 0.9);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        #toggleFormText {
            color: #718096;
        }

        #toggleFormLink {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        #toggleFormLink:hover {
            text-decoration: underline;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="back-button">
        <a href="index2.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
    <div class="login-container d-flex align-items-center justify-content-center">
        <div class="card p-4 shadow login-card" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4 logo-section">
                <img src="logo.png" alt="Logo" class="mb-3">
                <h2 id="formTitle">Student Login</h2>
                <p class="text-muted" id="formSubtitle">Please enter your credentials</p>
            </div>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="studentId" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="studentId" name="studentId" placeholder="Enter your Student ID" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <form id="signupForm" style="display: none;">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="newUserId" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="newUserId" placeholder="Enter your ID" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>
            <div id="errorMessage" class="mt-3 text-danger text-center" style="display: none;">
                Invalid ID or Password. Please try again.
            </div>
            <div class="mt-3 text-center">
                <p id="toggleFormText">Don't have an account? <a href="studentSignup.php" id="toggleFormLink">Sign up</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');

        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const studentId = document.getElementById('studentId').value.trim();
            const password = document.getElementById('password').value.trim();

            const formData = new FormData();
            formData.append('studentId', studentId);
            formData.append('password', password);

            fetch('studentlogin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = data.message;
                }
            })
            .catch(error => {
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'An error occurred. Please try again.';
            });
        });
    });
    </script>
</body>
</html>
