<?php
session_start();
header('Content-Type: application/json');

$response = ['loggedIn' => false];

if (isset($_SESSION['student_id'])) {
    $response['loggedIn'] = true;
    $response['student_id'] = $_SESSION['student_id'];
}

echo json_encode($response);
