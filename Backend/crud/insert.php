<?php
require "../db.php";
require "../auth/guard.php";
header('Content-Type: application/json');
$db_obj = new database;
$conn = $db_obj->conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['insert'];
    $prep =  mysqli_prepare($conn, "INSERT INTO `list` (`emails`) VALUES (?)");
    mysqli_stmt_bind_param($prep, "s", $email);
    mysqli_stmt_execute($prep);
    http_response_code(200);
    $response = [
        'success' => true,
        'message' => 'Email Inserted'
    ];
}

$db_obj->close();
echo json_encode($response);
exit;
