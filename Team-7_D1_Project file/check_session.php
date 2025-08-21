<?php
session_start();

header('Content-Type: application/json');
$response = ['loggedIn' => false];

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $response['loggedIn'] = true;
    $response['studentId'] = $_SESSION['student_id'];
    $response['studentName'] = $_SESSION['student_name'];
}

echo json_encode($response);
?>
