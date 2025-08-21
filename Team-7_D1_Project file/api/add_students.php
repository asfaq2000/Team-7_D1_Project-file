<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '../db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT * FROM students ORDER BY studentId DESC");
        echo json_encode($stmt->fetchAll());
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $pdo->prepare("INSERT INTO students (
            studentId, studentName, tuitionType, waiverCategory, 
            program, sscWith4th, sscWithout4th, hscWith4th, 
            hscWithout4th, gender, waiverPercentage, maintainedResult
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['studentId'],
            $data['studentName'],
            $data['tuitionType'],
            $data['waiverCategory'],
            $data['program'],
            $data['sscWith4th'],
            $data['sscWithout4th'],
            $data['hscWith4th'],
            $data['hscWithout4th'],
            $data['gender'],
            $data['waiverPercentage'],
            $data['maintainedResult']
        ]);
        
        echo json_encode(['message' => 'Student added successfully']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
