<?php
try {
    $conn = mysqli_connect('localhost', 'root', '');
    $query = 'CREATE DATABASE IF NOT EXISTS newsletterpro';
    mysqli_query($conn, $query);
    mysqli_close($conn);
    $conn = mysqli_connect('localhost', 'root', '', 'newsletterpro');
    $query = 'CREATE TABLE IF NOT EXISTS admin(id int AUTO_INCREMENT primary key, email varchar(320) NOT NULL, password varchar(320) NOT NULL)';
    mysqli_query($conn, $query);
    $query = "SELECT COUNT(*) AS total FROM admin";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    $query = 'CREATE TABLE IF NOT EXISTS list(id int AUTO_INCREMENT primary key, emails varchar(320) NOT NULL)';
    mysqli_query($conn, $query);
    mysqli_close($conn);
    //print_r($row);
    if ($row[0] == 0) {
        header("Location: register.php");
        exit;
    } else {
        header("Location: Frontend/login.html");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo "Something went wrong";
}
