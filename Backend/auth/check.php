<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    http_response_code(200);
    $response = ['success' => true];
} else {
    http_response_code(401);
    $response = [
        'success' => false,
        'error' => 'Not Logged In'
    ];
}
echo json_encode($response);
exit;
