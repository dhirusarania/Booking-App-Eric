<?php
$username = "root";
$password = "";
$servername = "localhost";
$database = "new3";
// error_reporting(0);
// $database = "nexttable";

// Create connection
$con = new mysqli($servername, $username, $password, $database);
// Check connection
if ($con->connect_error) {

    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    $data = array();
    $data['status'] = "error";
    $data['message'] = $con->connect_error;
    $res = json_encode($data);
    echo $res;
    // die("Connection failed: " . $con->connect_error);
    exit;
}

?>