<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($id <= 0) {
            throw new Exception('Invalid ID');
        }

        // Validate required fields
        $requiredFields = ['studentId', 'studentName', 'program'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }

        // Validate numeric fields
        $numericFields = ['waiverPercentage', 'maintainedResult', 'sscWith4th', 'hscWith4th'];
        foreach ($numericFields as $field) {
            if (isset($data[$field]) && (!is_numeric($data[$field]) || $data[$field] < 0)) {
                throw new Exception("Invalid value for '$field'");
            }
        }

        // Build dynamic UPDATE query
        $updateFields = [];
        $params = [];
        
        $possibleFields = [
            'studentId', 'studentName', 'tuitionType', 'waiverCategory',
            'program', 'sscWith4th', 'sscWithout4th', 'hscWith4th',
            'hscWithout4th', 'gender', 'waiverPercentage', 'maintainedResult'
        ];

        foreach ($possibleFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        if (empty($updateFields)) {
            throw new Exception('No fields to update');
        }

        // Add the ID parameter
        $params[] = $id;

        $sql = "UPDATE students SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No changes made or student not found']);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}
