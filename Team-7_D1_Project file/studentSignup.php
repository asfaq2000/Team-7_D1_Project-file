<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => ''];
    
    $conn = new mysqli('localhost', 'root', '', 'daffodil');

    if ($conn->connect_error) {
        $response['message'] = 'Connection failed';
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $studentId = filter_var($_POST['newUserId'], FILTER_SANITIZE_STRING);
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Invalid email format';
            echo json_encode($response);
            exit;
        }

        // Validate password
        $password = $_POST['newPassword'];
        if (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters long';
            echo json_encode($response);
            exit;
        }
        if (!preg_match("/[A-Z]/", $password)) {
            $response['message'] = 'Password must contain at least one uppercase letter';
            echo json_encode($response);
            exit;
        }
        if (!preg_match("/[0-9]/", $password)) {
            $response['message'] = 'Password must contain at least one number';
            echo json_encode($response);
            exit;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        // Check if student ID exists in students table
        $stmt = $conn->prepare("SELECT studentId FROM students WHERE studentId = ?");
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $response['message'] = 'Invalid Student ID. Please contact administration.';
        } else {
            // Check if student already has an account
            $stmt = $conn->prepare("SELECT id FROM userstudent WHERE studentId = ? OR email = ?");
            $stmt->bind_param("ss", $studentId, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['message'] = 'An account already exists with this Student ID or Email';
            } else {
                // Create new account
                $stmt = $conn->prepare("INSERT INTO userstudent (email, studentId, password, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("sss", $email, $studentId, $password);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Account created successfully';
                    $response['redirect'] = 'login.php';
                } else {
                    $response['message'] = 'Error creating account';
                }
            }
        }
        $stmt->close();
        $conn->close();
    }
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm position-fixed back-button" style="left: 20px; top: 20px;">
    <i class="fas fa-arrow-left me-2"></i>Back
    </a>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5 shadow-sm" style="width: 100%; max-width: 440px;">
            <div class="text-center mb-5">
                <img src="logo.png" alt="Logo" class="mb-4" style="max-width: 120px;">
                <h2 class="fw-bold mb-2">Student Sign Up</h2>
                <p class="text-muted">Create your student account</p>
            </div>
            <form id="signupForm" method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="newUserId" class="form-label">Student ID</label>
                    <input type="text" class="form-control" name="newUserId" id="newUserId" placeholder="Enter your student ID" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Retype Password</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Retype password" required>
                </div>
                <div id="passwordRequirements" class="mb-3 small text-muted">
                    Password must:
                    <ul>
                        <li>Be at least 6 characters long</li>
                        <li>Contain at least one uppercase letter</li>
                        <li>Contain at least one number</li>
                    </ul>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>
            <div id="signupErrorMessage" class="mt-3 text-danger text-center" style="display: none;"></div>
            <div id="signupSuccessMessage" class="mt-3 alert alert-success text-center" style="display: none;"></div>
            <div class="mt-3 text-center">
                <p>Already have an account? <a href="studentLogin.php">Login</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (document.getElementById('newPassword').value !== document.getElementById('confirmPassword').value) {
            document.getElementById('signupErrorMessage').textContent = 'Passwords do not match. Please try again.';
            document.getElementById('signupErrorMessage').style.display = 'block';
            document.getElementById('signupSuccessMessage').style.display = 'none';
            return;
        }

        fetch('studentSignup.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('signupErrorMessage').style.display = 'none';
                document.getElementById('signupSuccessMessage').textContent = 'Account created successfully! Redirecting to login page...';
                document.getElementById('signupSuccessMessage').style.display = 'block';
                setTimeout(() => {
                    window.location.href = 'studentLogin.php';
                }, 2000);
            } else {
                document.getElementById('signupErrorMessage').textContent = data.message;
                document.getElementById('signupErrorMessage').style.display = 'block';
                document.getElementById('signupSuccessMessage').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('signupErrorMessage').textContent = 'An error occurred. Please try again.';
            document.getElementById('signupErrorMessage').style.display = 'block';
            document.getElementById('signupSuccessMessage').style.display = 'none';
        });
    });
    </script>
</body>
</html>
