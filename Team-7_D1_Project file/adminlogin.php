<?php
session_start();
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db.php';
    
    if (empty($_POST['userId']) || empty($_POST['password'])) {
        $errorMessage = "Both User ID and Password are required.";
    } else {
        $userId = filter_var($_POST['userId'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        try {
            // Debug information
            error_log("Login attempt started for user: " . $userId);
            
            $stmt = $pdo->prepare("SELECT * FROM userAdmin WHERE userId = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if ($user) {
                // Support both hashed and unhashed passwords (temporary)
                $passwordValid = password_verify($password, $user['password']) || 
                               $password === $user['password'];
                
                if ($passwordValid) {
                    $_SESSION['admin_id'] = $user['userId'];
                    $_SESSION['admin_logged_in'] = true;
                    error_log("Login successful for user: " . $userId);
                    header("Location: addpage.php");
                    exit();
                } else {
                    error_log("Invalid password for user: " . $userId);
                    $errorMessage = "Invalid User ID or Password.";
                }
            } else {
                error_log("User not found: " . $userId);
                $errorMessage = "Invalid User ID or Password.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $errorMessage = "System error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Daffodil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
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
    </style>
</head>
<body>
    <div class="back-button">
        <a href="index2.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <img src="logo.png" alt="Logo" class="mb-4" style="max-width: 120px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                <h2 class="fw-bold mb-2">Admin Login</h2>
                <p class="text-muted">Welcome back! Please log in to continue</p>
            </div>
            <form id="loginForm" method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-4">
                    <label for="userId" class="form-label fw-semibold">User ID</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0" id="userId" name="userId" placeholder="Enter your ID" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
            </form>
            <?php if (!empty($errorMessage)): ?>
                <div class="mt-3 alert alert-danger text-center p-2 rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');

            loginForm.addEventListener('submit', function (event) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = 'Logging in...';
            });

            loginForm.addEventListener('submit', function(e) {
                const userId = document.getElementById('userId').value;
                const password = document.getElementById('password').value;
                
                if (!userId || !password) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
