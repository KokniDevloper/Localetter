<?php
require "../db.php";
require "../auth/guard.php";
header('Content-Type: application/json');
$db_obj = new database;
$conn = $db_obj->conn;

$request = file_get_contents('php://input');
$data = json_decode($request, true);

$prep = mysqli_prepare($conn, "DELETE FROM `list` WHERE `emails` = ?");

$deleted = 0;

foreach ($data as $email) {

    mysqli_stmt_bind_param($prep, "s", $email);
    mysqli_stmt_execute($prep);

    $deleted += mysqli_stmt_affected_rows($prep);
}

http_response_code(200);
$response = [
    'success' => true,
    'deleted' => $deleted
];

$db_obj->close();
echo json_encode($response);
exit;
