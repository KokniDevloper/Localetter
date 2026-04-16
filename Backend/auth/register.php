<?php
session_start();
header('Content-Type: application/json');
$conn = mysqli_connect('localhost', 'root', '', 'localetter');
$query = 'SELECT * FROM admin';
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) != 0) {
    http_response_code(409);
    $response = [
        'success' => false,
        'error' => "Admin Already Exists<br>Redirecting to Login"
    ];
    mysqli_close($conn);
    echo json_encode($response);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $prep =  mysqli_prepare($conn, "INSERT INTO `admin` (`email`, `password`) VALUES (?, ?)");
    mysqli_stmt_bind_param($prep, "ss", $email, $pass);
    mysqli_stmt_execute($prep);
    $_SESSION['loggedin'] = true;
    http_response_code(200);
    $response = ['success' => true];
}

mysqli_close($conn);
echo json_encode($response);
exit;
