<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'daffodil';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO students (
            studentId, studentName, tuitionType, waiverCategory, 
            program, sscWith4th, sscWithout4th, hscWith4th, 
            hscWithout4th, gender, waiverPercentage, maintainedResult
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_POST['studentId'],
            $_POST['studentName'],
            $_POST['tuitionType'],
            $_POST['waiverCategory'],
            $_POST['program'],
            $_POST['sscWith4th'],
            $_POST['sscWithout4th'],
            $_POST['hscWith4th'],
            $_POST['hscWithout4th'],
            $_POST['gender'],
            $_POST['waiverPercentage'],
            $_POST['maintainedResult']
        ]);
        
        $success_message = "Student added successfully!";
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch existing students
$students = $pdo->query("SELECT * FROM students ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Daffodil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #059669;
            --danger-color: #dc2626;
            --background-color: #f1f5f9;
            --card-background: #ffffff;
        }

        body { 
            padding: 20px; 
            background-color: var(--background-color); 
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .container { 
            width: 95%;
            max-width: 1400px;
            background: var(--card-background);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }

        .table-responsive {
            overflow-x: auto;
            margin: 1.5rem 0;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
        }

        .table {
            white-space: nowrap;
            font-size: 0.95rem;
        }

        .table thead th {
            background-color: #1e293b;
            color: white;
            font-weight: 500;
        }

        .form-section { 
            max-width: 800px; 
            margin: 2rem auto;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #cbd5e1;
            padding: 0.625rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .btn {
            border-radius: 0.5rem;
            padding: 0.625rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .alert {
            border-radius: 0.5rem;
            margin: 1rem 0;
            padding: 1rem;
            border: none;
        }

        .form-label {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        #searchInput {
            max-width: 300px;
            margin-bottom: 1rem;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        tr:hover {
            background-color: #f1f5f9 !important;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 1rem;
            }

            .form-section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="d-flex justify-content-between align-items-center my-4">
            <img src="logo.png" alt="Logo" class="img-fluid" style="max-height: 100px;" onerror="this.src='placeholder-logo.png';">
            <button class="btn btn-danger" id="logoutButton" onclick="window.location.href='adminlogin.php';">Log Out</button>
        </header>

        <h2 class="text-center mb-4">Student Management System</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-section">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="studentId" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="studentId" name="studentId" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="studentName" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="studentName" name="studentName" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="tuitionType" class="form-label">Select Tuition Type</label>
                <select class="form-select" id="tuitionType" name="tuitionType" required>
                    <option value="Local Student">Local Student</option>
                    <option value="International Student">International Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="waiverCategory" class="form-label">Select Waiver Category</label>
                <select class="form-select" id="waiverCategory" name="waiverCategory" required>
                    <option value="Merit-Based">Merit-Based</option>
                    <option value="Need-Based">Need-Based</option>
                    <option value="Sports">Sports</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="program" class="form-label">Select Program</label>
                <select class="form-select" id="program" name="program" required>
                    <option value="CSE">Computer Science & Engineering</option>
                    <option value="EEE">Electrical Engineering</option>
                    <option value="BBA">Business Administration</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sscWith4th" class="form-label">SSC Result (With 4th subject)</label>
                    <input type="number" step="0.01" min="0" max="5" class="form-control" id="sscWith4th" name="sscWith4th" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sscWithout4th" class="form-label">SSC Result (Without 4th subject)</label>
                    <input type="number" step="0.01" min="0" max="5" class="form-control" id="sscWithout4th" name="sscWithout4th" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="hscWith4th" class="form-label">HSC Result (With 4th subject)</label>
                    <input type="number" step="0.01" min="0" max="5" class="form-control" id="hscWith4th" name="hscWith4th" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="hscWithout4th" class="form-label">HSC Result (Without 4th subject)</label>
                    <input type="number" step="0.01" min="0" max="5" class="form-control" id="hscWithout4th" name="hscWithout4th" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Select Gender</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="waiverPercentage" class="form-label">Waiver Percentage</label>
                    <input type="number" step="0.01" min="0" max="100" class="form-control" id="waiverPercentage" name="waiverPercentage" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="maintainedResult" class="form-label">Maintained Result</label>
                    <input type="number" step="0.01" min="0" max="4" class="form-control" id="maintainedResult" name="maintainedResult" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Add Student</button>
        </form>

        <div class="mt-5">
            <h3>Student Records</h3>
            <div class="mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Search students...">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="studentsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Waiver %</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['id']); ?></td>
                            <td><?php echo htmlspecialchars($student['studentId']); ?></td>
                            <td><?php echo htmlspecialchars($student['studentName']); ?></td>
                            <td><?php echo strtoupper(htmlspecialchars($student['program'])); ?></td>
                            <td><?php echo htmlspecialchars($student['waiverPercentage']); ?>%</td>
                            <td><?php echo htmlspecialchars($student['maintainedResult']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-primary" onclick="editStudent(<?php echo $student['id']; ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="viewDetails(<?php echo $student['id']; ?>)">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteStudent(<?php echo $student['id']; ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Bootstrap Icons CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

        <script>
            // Add search functionality
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const table = document.getElementById('studentsTable');
                const rows = table.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent.toLowerCase();
                        if (cellText.includes(searchText)) {
                            found = true;
                            break;
                        }
                    }

                    row.style.display = found ? '' : 'none';
                }
            });

            function viewDetails(id) {
                window.location.href = `view_student.php?id=${id}`;
            }
        </script>

    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toast = new bootstrap.Toast(document.getElementById('toast'));

        function showToast(message, isSuccess = true) {
            const toastElement = document.getElementById('toast');
            toastElement.classList.remove('bg-success', 'bg-danger');
            toastElement.classList.add(isSuccess ? 'bg-success' : 'bg-danger');
            document.getElementById('toastMessage').textContent = message;
            toast.show();
        }

        async function deleteStudent(id) {
            if (!confirm('Are you sure you want to delete this student?')) {
                return;
            }

            try {
                const response = await fetch(`api/delete_student.php?id=${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast('Student deleted successfully');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.error || 'Error deleting student', false);
                }
            } catch (error) {
                showToast('Error deleting student', false);
            }
        }

        function editStudent(id) {
            window.location.href = `edit_student.php?id=${id}`;
        }
    </script>
</body>
</html>