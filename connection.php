<?php


session_start();

// print_r($_SESSION);

$username = "root";
$password = "";
$servername = "localhost";
$database = "";


// print_r($_SESSION);

if(isset($_SESSION['db_name']) && $_SESSION['db_name']){
    
    $database = $_SESSION['db_name'];

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

 
// $query = mysqli_query($con, "SELECT * FROM circuits WHERE circuit_db  = '" . $_SESSION['db_name'] . "'") or die(mysqli_error($con));
    
// if (mysqli_num_rows($query) > 0) {
//     $row = mysqli_fetch_assoc($query);

//     // $data[] = $row;

//     $data['ssssss'] = "SELECT * FROM companies WHERE id_company  = " . $row['id_circuit'];

//     $query = mysqli_query($con, "SELECT * FROM companies WHERE id_company  = " . $row['id_circuit']) or die(mysqli_error($con));

//     if (mysqli_num_rows($query) > 0) {

//         $row = mysqli_fetch_assoc($query);

//         $query = mysqli_query($con, "SELECT * FROM pos WHERE id_company = " . $row['id_company']) or die(mysqli_error($con));

//         if (mysqli_num_rows($query) > 0) {
//             $row = mysqli_fetch_assoc($query);


//                 $id_booking_diary = $row['id_booking_diary'];



//                 $_SERVER['id_booking_diary'] = $id_booking_diary;

//         }
//     }
// } else {
//     $data['message'] = "Invalid ID Variable";
//     http_response_code(400);
// }


}
