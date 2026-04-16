<?php
session_start();
header('Content-Type: application/json');
$error = NULL;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect('localhost', 'root', '', 'localetter');
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $prep =  mysqli_prepare($conn, "SELECT * from admin WHERE email = ?");
    mysqli_stmt_bind_param($prep, "s", $email);
    mysqli_stmt_execute($prep);
    $result = mysqli_stmt_get_result($prep);
    $row = mysqli_fetch_row($result);
    if ($row) {
        if ($pass == $row[2]) {
            $_SESSION['loggedin'] = true;
            http_response_code(200);
            $response = ['success'=>true];
        } else {
            http_response_code(401);
            $response = [
                'success' => false,
                'error' => "Wrong Password"
            ];
        }
    } else {
        http_response_code(401);
        $response = [
                'success' => false,
                'error' => "Wrong Email"
            ];
    }
    mysqli_close($conn);
    echo json_encode($response);
    exit;
}
?>