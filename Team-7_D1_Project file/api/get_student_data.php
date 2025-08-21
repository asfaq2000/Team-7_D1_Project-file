<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$studentId = $_SESSION['student_id'];
$response = [];

$query = "SELECT s.*, u.email 
          FROM students s 
          JOIN userstudent u ON s.studentId = u.studentId 
          WHERE s.studentId = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $response = [
        'name' => $row['studentName'],
        'id' => $row['studentId'],
        'waiver' => $row['waiver'] ?? 0,
        'cgpa' => $row['cgpa'] ?? 0,
        'tuitionType' => $row['tuitionType'] ?? 'Regular',
        'waiverCategory' => $row['waiverCategory'] ?? 'None',
        'program' => $row['program'],
        'sscWith' => $row['sscWith'],
        'sscWithout' => $row['sscWithout'],
        'hscWith' => $row['hscWith'],
        'hscWithout' => $row['hscWithout'],
        'gender' => $row['gender']
    ];
}

echo json_encode($response);
$conn->close();
?>
