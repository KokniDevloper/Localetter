<?php
require "../db.php";
require "../auth/guard.php";
header('Content-Type: application/json');
$db_obj = new database;
$conn = $db_obj->conn;

$request = file_get_contents('php://input');
$data = json_decode($request, true);

$prep = mysqli_prepare($conn, "UPDATE `list` SET `emails` = ? WHERE `emails` = ?");

$updated = 0;

foreach ($data as $i => $item) {
    $oldEmail = $item['oldEmail'] ?? null;
    $newEmail = $item['replacementEmail'] ?? null;

    mysqli_stmt_bind_param($prep, "ss", $newEmail, $oldEmail);
    mysqli_stmt_execute($prep);

    $updated += mysqli_stmt_affected_rows($prep);
}

http_response_code(200);
$response = [
    'success' => true,
    'updated' => $updated
];

$db_obj->close();
echo json_encode($response);
exit;
