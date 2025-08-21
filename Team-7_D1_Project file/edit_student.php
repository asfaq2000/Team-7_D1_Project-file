<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: addpage.php');
    exit;
}

$id = intval($_GET['id']);
$student = null;

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
    <title>Edit Student - Daffodil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            padding: 40px 20px;
            background-color: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }
        .container { 
            max-width: 800px;
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        h2 {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 32px;
        }
        .form-label {
            font-weight: 500;
            color: #444;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: #3b82f6;
            border: none;
        }
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: #f3f4f6;
            border: none;
            color: #4b5563;
        }
        .btn-danger {
            background: #ef4444;
            border: none;
        }
        .btn-danger:hover {
            background: #dc2626;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .mb-3 {
            margin-bottom: 24px !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Edit Student</h2>
        <form id="editForm">
            <input type="hidden" id="studentId" value="<?php echo htmlspecialchars($student['id']); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Student ID</label>
                    <input type="text" class="form-control" name="studentId" value="<?php echo htmlspecialchars($student['studentId']); ?>" required pattern="[A-Za-z0-9-]+" title="Only letters, numbers and hyphens allowed">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Student Name</label>
                    <input type="text" class="form-control" name="studentName" value="<?php echo htmlspecialchars($student['studentName']); ?>" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tuition Type</label>
                <select class="form-select" name="tuitionType" required>
                    <option value="Local Student" <?php echo $student['tuitionType'] == 'Local Student' ? 'selected' : ''; ?>>Local Student</option>
                    <option value="International Student" <?php echo $student['tuitionType'] == 'International Student' ? 'selected' : ''; ?>>International Student</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Program</label>
                <select class="form-select" name="program" required>
                    <option value="CSE" <?php echo $student['program'] == 'CSE' ? 'selected' : ''; ?>>Computer Science & Engineering</option>
                    <option value="EEE" <?php echo $student['program'] == 'EEE' ? 'selected' : ''; ?>>Electrical Engineering</option>
                    <option value="BBA" <?php echo $student['program'] == 'BBA' ? 'selected' : ''; ?>>Business Administration</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waiver Category</label>
                    <select class="form-select" name="waiverCategory" required>
                        <option value="Merit-Based" <?php echo $student['waiverCategory'] == 'Merit-Based' ? 'selected' : ''; ?>>Merit-Based</option>
                        <option value="Need-Based" <?php echo $student['waiverCategory'] == 'Need-Based' ? 'selected' : ''; ?>>Need-Based</option>
                        <option value="Sports" <?php echo $student['waiverCategory'] == 'Sports' ? 'selected' : ''; ?>>Sports</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Waiver Percentage</label>
                    <input type="number" class="form-control" name="waiverPercentage" value="<?php echo htmlspecialchars($student['waiverPercentage']); ?>" required min="0" max="100">
                </div>
            </div>

            <div class="alert alert-info mt-3" id="formStatus" style="display: none;"></div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="addpage.php" class="btn btn-secondary ms-2">Back to List</a>
                <button type="button" class="btn btn-danger float-end" onclick="confirmDelete(<?php echo $student['id']; ?>)">Delete Student</button>
            </div>
        </form>
    </div>

    <script>
        function showStatus(message, isError = false) {
            const statusDiv = document.getElementById('formStatus');
            statusDiv.className = `alert ${isError ? 'alert-danger' : 'alert-success'}`;
            statusDiv.textContent = message;
            statusDiv.style.display = 'block';
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                deleteStudent(id);
            }
        }

        async function deleteStudent(id) {
            try {
                const response = await fetch(`api/delete_student.php?id=${id}`, {
                    method: 'DELETE'
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'addpage.php';
                } else {
                    showStatus(data.error || 'Error deleting student', true);
                }
            } catch (error) {
                showStatus('Error deleting student', true);
            }
        }

        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            try {
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                const id = document.getElementById('studentId').value;

                const response = await fetch('api/update_student.php?id=' + id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    showStatus('Student updated successfully');
                    setTimeout(() => window.location.href = 'addpage.php', 1000);
                } else {
                    showStatus(result.error || 'Error updating student', true);
                }
            } catch (error) {
                showStatus('Error updating student', true);
            }
        });
    </script>
</body>
</html>
