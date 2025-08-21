<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: addpage.php');
    exit;
}

$id = intval($_GET['id']);
try {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        header('Location: addpage.php');
        exit;
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Daffodil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f0f2f5; 
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        }
        .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 1.5rem;
        }
        .student-info { 
            padding: 25px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            height: 100%;
            transition: transform 0.2s;
        }
        .student-info:hover {
            transform: translateY(-2px);
        }
        .info-label { 
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value { 
            font-size: 1.1rem;
            color: #212529;
            margin-left: 8px;
        }
        h4 {
            color: #0d6efd;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }
        hr {
            opacity: 0.1;
            margin: 1.25rem 0;
        }
        .btn {
            padding: 0.6rem 1.2rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .btn-light {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-light:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
        }
        .btn-danger {
            background: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background: #bb2d3b;
            transform: translateY(-1px);
        }
        .mt-4 {
            margin-top: 2rem !important;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Student Details</h3>
                <a href="addpage.php" class="btn btn-light">Back to List</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="student-info">
                            <h4>Personal Information</h4>
                            <hr>
                            <p><span class="info-label">Student ID:</span> <span class="info-value"><?php echo htmlspecialchars($student['studentId']); ?></span></p>
                            <p><span class="info-label">Name:</span> <span class="info-value"><?php echo htmlspecialchars($student['studentName']); ?></span></p>
                            <p><span class="info-label">Gender:</span> <span class="info-value"><?php echo ucfirst(htmlspecialchars($student['gender'])); ?></span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="student-info">
                            <h4>Academic Information</h4>
                            <hr>
                            <p><span class="info-label">Program:</span> <span class="info-value"><?php echo strtoupper(htmlspecialchars($student['program'])); ?></span></p>
                            <p><span class="info-label">Waiver Category:</span> <span class="info-value"><?php echo ucfirst(htmlspecialchars($student['waiverCategory'])); ?></span></p>
                            <p><span class="info-label">Waiver Percentage:</span> <span class="info-value"><?php echo htmlspecialchars($student['waiverPercentage']); ?>%</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $student['id']; ?>)">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                window.location.href = `api/delete_student.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
