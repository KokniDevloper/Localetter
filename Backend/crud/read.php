<?php
require "../db.php";
require "../auth/guard.php";
header('Content-Type: application/json');
$db_obj = new database;
$conn = $db_obj->conn;
$query = "SELECT * FROM list";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    http_response_code(200);
    $response = [
        'success' => true,
        'message' => []
    ];
} else {
    http_response_code(200);
    $emails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($emails, $row);
    }
    $response = [
        'success' => true,
        'message' => $emails
    ];
}

$db_obj->close();
echo json_encode($response);
exit;
