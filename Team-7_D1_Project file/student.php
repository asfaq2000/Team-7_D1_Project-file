<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: studentlogin.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'daffodil');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM students WHERE studentId = ?");
$stmt->bind_param("s", $_SESSION['student_id']);
$stmt->execute();
$result = $stmt->get_result();
$studentData = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2196f3;
            --secondary-color: #f8f9fa;
            --accent-color: #3f51b5;
            --success-color: #4caf50;
            --text-primary: #2c3e50;
            --text-secondary: #607d8b;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f3 100%);
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text-primary);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        header {
            background: white;
            padding: 1.5rem 2rem !important;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .welcome-message {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .welcome-message:hover {
            transform: translateY(-5px);
        }

        .welcome-message h2 {
            color: #fff7e6;  /* Changed to a warmer light shade for better contrast */
            margin-bottom: 0;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .welcome-message h2 span {
            color: white !important;  /* Override Bootstrap's text-primary */
        }

        .student-info {
            margin-bottom: 2rem;
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: var(--secondary-color);
            font-weight: 600;
            width: 30%;
            color: var(--text-secondary);
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            color: var(--text-primary);
        }

        .btn {
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #45b649 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-lg {
            font-size: 1.1rem !important;
            padding: 1rem 3rem !important;
        }

        h4 {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .logo img {
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 1rem;
            }

            .welcome-message, .student-info {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="logo.png" alt="Logo" width="120">
            </div>
            <a href="studentlogin.php">
                <button class="btn btn-danger px-4 py-2">Logout</button>
            </a>
        </header>

        <!-- Welcome Message -->
        <div class="welcome-message">
            <h2 class="mb-0">Welcome, <span id="studentName" class="text-primary"><?php echo htmlspecialchars($studentData['studentName']); ?></span>!</h2>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="student-info">
                    <h4>Student Information</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Name</th>
                                <td id="name"><?php echo htmlspecialchars($studentData['studentName']); ?></td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <td id="id"><?php echo htmlspecialchars($studentData['studentId']); ?></td>
                            </tr>
                            <tr>
                                <th>Eligible for Waiver</th>
                                <td id="waiver"><?php echo htmlspecialchars($studentData['waiverPercentage']); ?>%</td>
                            </tr>
                            <tr>
                                <th>Maintained CGPA</th>
                                <td id="cgpa"><?php echo htmlspecialchars($studentData['maintainedResult']); ?></td>
                            </tr>
                            <tr>
                                <th>Tuition Type</th>
                                <td id="tuitionType"><?php echo htmlspecialchars($studentData['tuitionType']); ?></td>
                            </tr>
                            <tr>
                                <th>Waiver Category</th>
                                <td id="waiverCategory"><?php echo htmlspecialchars($studentData['waiverCategory']); ?></td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td id="program"><?php echo htmlspecialchars($studentData['program']); ?></td>
                            </tr>
                            <tr>
                                <th>SSC Result (With 4th Subject)</th>
                                <td id="sscWith"><?php echo htmlspecialchars($studentData['sscWith4th']); ?></td>
                            </tr>
                            <tr>
                                <th>SSC Result (Without 4th Subject)</th>
                                <td id="sscWithout"><?php echo htmlspecialchars($studentData['sscWithout4th']); ?></td>
                            </tr>
                            <tr>
                                <th>HSC Result (With 4th Subject)</th>
                                <td id="hscWith"><?php echo htmlspecialchars($studentData['hscWith4th']); ?></td>
                            </tr>
                            <tr>
                                <th>HSC Result (Without 4th Subject)</th>
                                <td id="hscWithout"><?php echo htmlspecialchars($studentData['hscWithout4th']); ?></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td id="gender"><?php echo htmlspecialchars($studentData['gender']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <!-- Payment Scheme Button -->
                <div class="text-center mt-4">
                    <a href="payment.php" class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold">Payment Scheme</a>
                </div>
            </div>
        </div>

        <!-- Calculate CGPA Button -->
        <div class="text-center mt-4 mb-5">
            <a href="cgpa.php" class="btn btn-success btn-lg px-5 py-3 fs-4 fw-bold">Calculate CGPA</a>
        </div>
    </div>

    <!-- Bootstrap JS and Custom JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
