<?php
include("connection.php");
// include("moment.php");

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    // header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// error_reporting(0);

// session_start();



$USERNAME = "root";
$PASSWORD = "";
$SERVERNAME = "localhost";
$ADMIN_DATABASE = "new3";






// /*Variables */


// $id_booking_diary = 1;



// /*Variables */


// $_SESSION['id_booking_diary'] = $id_booking_diary;




if(isset($_SESSION['db_name']) && $_SESSION['db_name']){
    
    $username = $USERNAME;
    $password = $PASSWORD;
    $servername = $SERVERNAME;
    $database = $ADMIN_DATABASE;
    // error_reporting(0);
    // $database = "nexttable";

    // Create connection
    $con_admin = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($con_admin->connect_error) {

        header($_SESSION['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

        $data['status'] = "error";
        $data['message'] = $con->connect_error;
        $res = json_encode($data);
        echo $res;
        // die("Connection failed: " . $con->connect_error);
        exit;
    }


 
$query = mysqli_query($con_admin, "SELECT * FROM circuits WHERE circuit_db  = '" . $_SESSION['db_name'] . "'") or die(mysqli_error($con_admin));
    
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);

    // $data[] = $row;

    $data['ssssss'] = "SELECT * FROM companies WHERE id_company  = " . $row['id_circuit'];

    $query = mysqli_query($con_admin, "SELECT * FROM companies WHERE id_company  = " . $row['id_circuit']) or die(mysqli_error($con_admin));

    if (mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_assoc($query);

        $query = mysqli_query($con_admin, "SELECT * FROM pos WHERE id_company = " . $row['id_company']) or die(mysqli_error($con_admin));

        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);


                $id_booking_diary = $row['id_booking_diary'];



                $_SESSION['id_booking_diary'] = $id_booking_diary;

        }
    }
} else {
    $data['message'] = "Invalid ID Variable";
    http_response_code(400);
}


}





function slugify($string)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHJKMNPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijkmnpqrstuvwxyz";
    $codeAlphabet .= "123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[rand(0, $max - 1)];
    }

    return $token;
}

function timeDiff($firstTime, $lastTime)
{
    $firstTime = strtotime($firstTime);
    $lastTime = strtotime($lastTime);
    $timeDiff = $lastTime - $firstTime;
    return $timeDiff;
}


function getRandomFreeUser($con, $service_id, $exclude, $date)
{

    $data = array();



    $query = mysqli_query($con, "SELECT * FROM employee_services JOIN booking_employees ON employee_services.id_employee = booking_employees.id_employee WHERE visible_on_line = 1 and id_booking_diary = " . $_SESSION['id_booking_diary'] . " and id_service = " . $service_id . " and booking_employees.id_employee not IN (" . $exclude . ") ORDER BY RAND()") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // $row['sour'] = "jahah";
            // $data['random_employee'] = $row;

            $query2 = mysqli_query($con, "SELECT * FROM booking_personal_appointments where id_employee in ("  . $row['id_employee'] . ") and appointment_date BETWEEN CONCAT( '" . $date  . "',' 00:00:00') AND CONCAT( '" . $date  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

            if (mysqli_num_rows($query2) == 0) {

                $query3 = mysqli_query($con, "SELECT * FROM booking_employee_special_shift where id_employee in ("  . $row['id_employee'] . ") and open_date BETWEEN CONCAT( '" . $date  . "',' 00:00:00') AND CONCAT( '" . $date  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

                if (mysqli_num_rows($query3) == 0) {
                    $data['random_employee'] = $row;
                    return $data['random_employee'];
                }
            }
        }
    } else {
        // $data['random_employee'] = "no employee available";


        $query = mysqli_query($con, "SELECT * FROM booking_employees WHERE visible_on_line = 1 and id_booking_diary = " . $_SESSION['id_booking_diary'] . " ORDER BY RAND()") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                // $row['sour'] = "jahah";
                // $data['random_employee'] = $row;

                $query2 = mysqli_query($con, "SELECT * FROM booking_personal_appointments where id_employee in ("  . $row['id_employee'] . ") and appointment_date BETWEEN CONCAT( '" . $date  . "',' 00:00:00') AND CONCAT( '" . $date  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

                if (mysqli_num_rows($query2) == 0) {

                    $query3 = mysqli_query($con, "SELECT * FROM booking_employee_special_shift where id_employee in ("  . $row['id_employee'] . ") and open_date BETWEEN CONCAT( '" . $date  . "',' 00:00:00') AND CONCAT( '" . $date  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

                    if (mysqli_num_rows($query3) == 0) {
                        $data['random_employee'] = $row;
                        return $data['random_employee'];
                    }
                }
            }
        }
    }


    return $data['random_employee'];
}

function exclude_pause_time($t, $array, $start_pause, $end_pause, $durr)
{


    // print_r($array);
    // print_r($durr);

    $i = 0;

    $new_arr = [];


    // echo strtotime($start_pause);
    $start_pause = date('H:i', strtotime($start_pause) - ($durr * 60));
    $end_pause = date('H:i', strtotime($end_pause));
    // echo $start_pause;
    // echo $end_pause;

    while ($i < count($array) - 1) {

        // echo $array[$i];
        // echo $array[$i] > $end_pause;

        $i++;

        if ($array[$i] > $start_pause && $array[$i] < $end_pause) {
            // echo "True";
        } else {

            $new_arr[] = $array[$i];
        }
    }

    return $new_arr;
}

function getNewTime($diff, $start_time, $end_time, $start_pause, $end_pause, $user, $durr, $single)
{



    $t = 15;

    $diff = timeDiff($start_time, $end_time) / 60 / 60;

    $diff = $diff * 60 / $t;


    $i = 0;

    $time_all = array();

    while ($i < $diff) {

        $v = 0;

        $timestamp = strtotime($start_time) + $t * 60 * $i;

        $time = date('H:i', $timestamp);


        $time_all[] = $time;



        $i++;
    }

    // var_dump($user);

    // if ($single == 1 && count($user) == 0) {


    //     // echo $diff;

    //     // var_dump($time_all);

    //     return $time_all;
    // }

    $i = 0;

    // var_dump($user);

    // if (count($user) != 0) {



    while ($i < $diff) {

        $v = 0;

        $timestamp = strtotime($start_time) + $t * 60 * $i;

        $time = date('H:i', $timestamp);

        // var_dump($user);

        for ($j = 0; $j < count($user); $j++) {

            if (date('H:i', strtotime($user[$j]['appointment_date'])) == $time) {

                $v = 1;

                $dur = ceil($user[$j]['duration'] / $t) - 1;

                $i = $i  + $dur;

                continue;
            }
        }

        if ($v == 0) {

            $timeslot[] = $time;
        }


        $i++;
    }



    $new_time = array();
    $test = array();

    $i = 0;

    while ($i < count($time_all)) {

        $v = 0;

        if (in_array($time_all[$i],  $timeslot)) {

            // $test[] =  ceil($_REQUEST['total'] / 15);

            for ($j = 0; $j <= ceil($durr / $t); $j++) {


                for ($k = $i; $k < $i + ceil($durr / $t); $k++) {


                    if ($k < count($time_all)) {
                        // $test[] = $time_all[$k];
                        if (!in_array($time_all[$k],  $timeslot)) {

                            $v = 1;
                        }
                    }
                }
            }

            $test[] = ($i);
            if ($v == 0 && (count($time_all) - $i) >= ceil($durr / $t)) {
                $new_time[] = $time_all[$i];
            }
        }


        $i++;
    }
    // } else {


    //     return $time_all;
    // }

    $new_time = exclude_pause_time($t, $new_time, $start_pause, $end_pause, $durr);

    return $new_time;
}

function getNewTimeMul($diff, $start_time, $end_time, $start_pause, $end_pause, $user, $durr, $single)

{
    // print_r("$diff, $start_time, $end_time, $start_pause, $end_pause, $user, $durr, $single");

    $t = 15;

    $diff = timeDiff($start_time, $end_time) / 60 / 60;

    $diff = $diff * 60 / $t;


    $i = 0;

    $time_all = array();

    while ($i < $diff) {

        $v = 0;

        $timestamp = strtotime($start_time) + $t * 60 * $i;

        $time = date('H:i', $timestamp);


        $time_all[] = $time;

        $i++;
    }


    $i = 0;

    // var_dump($user);

    // if (count($user) != 0) {

    while ($i < $diff) {

        $v = 0;

        $timestamp = strtotime($start_time) + $t * 60 * $i;

        $time = date('H:i', $timestamp);

        // var_dump($user);

        for ($j = 0; $j < count($user); $j++) {

            if (date('H:i', strtotime($user[$j]['appointment_date'])) == $time) {

                $v = 1;

                $dur = ceil($user[$j]['duration'] / $t) - 1;

                $i = $i  + $dur;

                continue;
            }
        }

        if ($v == 0) {

            $timeslot[] = $time;
        }


        $i++;
    }



    $new_time = array();
    $test = array();

    $i = 0;

    while ($i < count($time_all)) {

        $v = 0;

        if (in_array($time_all[$i],  $timeslot)) {

            // $test[] =  ceil($_REQUEST['total'] / 15);

            for ($j = 0; $j <= ceil($durr / $t); $j++) {


                for ($k = $i; $k < $i + ceil($durr / $t); $k++) {


                    if ($k < count($time_all)) {
                        // $test[] = $time_all[$k];
                        if (!in_array($time_all[$k],  $timeslot)) {

                            $v = 1;
                        }
                    }
                }
            }

            $test[] = ($i);
            if ($v == 0 && (count($time_all) - $i) >= ceil($durr / $t)) {
                $new_time[] = $time_all[$i];
            }
        }


        $i++;
    }
    // } else {


    //     return $time_all;
    // }

    $new_time = exclude_pause_time($t, $new_time, $start_pause, $end_pause, $durr);

    return $new_time;
}


function in_array_field($needle, $needle_field, $haystack, $strict = false)
{
    if ($strict) {
        foreach ($haystack as $item)
            if (isset($item->$needle_field) && $item->$needle_field === $needle)
                return true;
    } else {
        foreach ($haystack as $item)
            if (isset($item->$needle_field) && $item->$needle_field == $needle)
                return true;
    }
    return false;
}


if (isset($_REQUEST['init_db']) && $_REQUEST['init_db'] == "init_db") {


    $data = array();

    unset($_SESSION["db_name"]);



    if ($_REQUEST['booking_url'] != "" && $_REQUEST['booking_url'] != 0) {

        $username = $USERNAME;
        $password = $PASSWORD;
        $servername = $SERVERNAME;
        $database = $ADMIN_DATABASE;
        // error_reporting(0);
        // $database = "nexttable";

        // Create connection
        $con_admin = new mysqli($servername, $username, $password, $database);
        // Check connection
        if ($con_admin->connect_error) {

            header($_SESSION['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

            $data['status'] = "error";
            $data['message'] = $con->connect_error;
            $res = json_encode($data);
            echo $res;
            // die("Connection failed: " . $con->connect_error);
            exit;
        }



        $query = mysqli_query($con_admin, "SELECT * FROM pos WHERE booking_url = " . $_REQUEST['booking_url']) or die(mysqli_error($con_admin));

        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);

            $data[] = $row;

            $query = mysqli_query($con_admin, "SELECT * FROM companies WHERE id_company  = " . $row['id_company']) or die(mysqli_error($con_admin));

            if (mysqli_num_rows($query) > 0) {

                $row = mysqli_fetch_assoc($query);

                $data[] = $row;

                $query = mysqli_query($con_admin, "SELECT * FROM circuits WHERE id_circuit  = " . $row['id_circuit']) or die(mysqli_error($con_admin));

                if (mysqli_num_rows($query) > 0) {
                    $data = mysqli_fetch_assoc($query);

                    $data[] = $row;
                    $_SESSION['db_name'] = $data['circuit_db'];
                }
            }
        } else {
            $data['message'] = "Invalid ID Variable";
            http_response_code(400);
        }
    } else {

        $data['message'] = "Missing ID Variable";
        http_response_code(404);
    }

    $data['booking_url'] =  $_REQUEST['booking_url'];

    $data['ses'] = $_SESSION;

    $absolpath = getcwd();
    $path  = $absolpath . '/img/landing/' . $_REQUEST['booking_url']  . '/slider/';
    $data['files'] =  array_values(array_diff(scandir($path), array('.', '..')));

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['login']) && $_REQUEST['login'] == "login") {


    // if ($_POST['booking_url'] != "" && $_POST['booking_url'] != 0) {

        unset($_SESSION["db_name"]);

        $username = $USERNAME;
        $password = $PASSWORD;
        $servername = $SERVERNAME;
        $database = $ADMIN_DATABASE;
        // error_reporting(0);
        // $database = "nexttable";

        // Create connection
        $con_admin = new mysqli($servername, $username, $password, $database);
        // Check connection
        if ($con_admin->connect_error) {

            header($_SESSION['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            $data = array();
            $data['status'] = "error";
            $data['message'] = $con->connect_error;
            $res = json_encode($data);
            echo $res;
            // die("Connection failed: " . $con->connect_error);
            exit;
        }


        $data = array();

        // $query = mysqli_query($con_admin, "SELECT * FROM pos WHERE booking_url = " . $_REQUEST['booking_url']) or die(mysqli_error($con_admin));

        // if (mysqli_num_rows($query) > 0) {
        //     $row = mysqli_fetch_assoc($query);

        //     $data[] = $row;

        //     $query = mysqli_query($con_admin, "SELECT * FROM companies WHERE id_company  = " . $row['id_company']) or die(mysqli_error($con_admin));

        //     if (mysqli_num_rows($query) > 0) {

        //         $row = mysqli_fetch_assoc($query);

        //         $query = mysqli_query($con_admin, "SELECT * FROM circuits WHERE id_circuit  = " . $row['id_circuit']) or die(mysqli_error($con_admin));

        //         if (mysqli_num_rows($query) > 0) {
        //             $data = mysqli_fetch_assoc($query);

        //             $_SESSION['db_name'] = $data['circuit_db'];
        //         }
        //     }
        // } else {
        //     $data['message'] = "Invalid ID Variable";
        //     http_response_code(400);

        //     $res = json_encode($data);
        //     echo $res;
        //     exit;
        // }
    // } else {

    //     $data['message'] = "Missing ID Variable";
    //     http_response_code(404);

    //     $res = json_encode($data);
    //     echo $res;
    //     exit;
    // }







    $data = array();

    // $data['xdsds'] = "SELECT id, municipality, iata_code, iso_country, type FROM data WHERE municipality IS NOT NULL AND type = 'large_airport' AND municipality LIKE '". $_REQUEST['query'] ."%' AND iata_code LIKE '". $_REQUEST['query'] ."%' ORDER BY FIELD(iso_country, 'US', 'IN') DESC";

    $query = mysqli_query($con_admin, "SELECT * FROM system_admin WHERE username = '" . $_POST['username'] . "'") or die(mysqli_error($con_admin));
    // $query = mysqli_query($con, "SELECT id,  'municipality',  'iata_code', 'iso_country', 'type' FROM data WHERE 'municipality' IS NOT NULL AND 'type' = large_airport AND 'municipality LIKE 'B%' AND 'iata_code' LIKE 'B%' ORDER BY FIELD('iso_country', 'US', 'IN') DESC ");

    // $data['hash'] = password_hash("admin1234", PASSWORD_DEFAULT);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {

            // $data['going'] = $_POST['password'];
            // $data['stored'] = $row['password'];

            // $data['hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if (strcmp($_POST['password'], $row['password']) == 0) {

                $data['message'] = 'Password is valid!';
                $data['code'] = 200;

                $_SESSION['loggedin'] = true;
                $data['token'] = $row['token'];




                $query = mysqli_query($con_admin, "SELECT * FROM pos WHERE booking_url = " . $row['booking_url']) or die(mysqli_error($con_admin));

                if (mysqli_num_rows($query) > 0) {
                    $row = mysqli_fetch_assoc($query);
        
                    $data[] = $row;
        
                    $query = mysqli_query($con_admin, "SELECT * FROM companies WHERE id_company  = " . $row['id_company']) or die(mysqli_error($con_admin));
        
                    if (mysqli_num_rows($query) > 0) {
        
                        $row = mysqli_fetch_assoc($query);
        
                        $data[] = $row;
        
                        $query = mysqli_query($con_admin, "SELECT * FROM circuits WHERE id_circuit  = " . $row['id_circuit']) or die(mysqli_error($con_admin));
        
                        if (mysqli_num_rows($query) > 0) {
                            $row = mysqli_fetch_assoc($query);
        
                            $data[] = $row;
                            $_SESSION['db_name'] = $row['circuit_db'];
                        }
                    }
                } else {
                    $data['message'] = "Invalid ID Variable";
                    http_response_code(400);
                }



            } else {

                $data['message'] = 'Invalid password.';

                $_SESSION['loggedin'] = false;
                $data['code'] = 401;
            }
        }
    } else {

        $data['message'] = 'Invalid Email or Password.';

        $_SESSION['loggedin'] = false;

        $data['code'] = 401;
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getCustomers']) && $_REQUEST['getCustomers'] == "getCustomers") {

    $data = array();

    $query = mysqli_query($con, "SELECT * FROM customers WHERE CONCAT(first_name, ' ', last_name) LIKE '%" . $_REQUEST['query'] . "%' OR last_name LIKE '%" . $_REQUEST['query'] . "%' order by first_name desc limit 10") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {

            $row['first_name'] = preg_replace('/[^\00-\255]+/u', '',   $row['first_name']);
            $row['last_name'] = preg_replace('/[^\00-\255]+/u', '', $row['last_name']);

            $data[] = $row;
        }
    }

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['id_booking']) && $_REQUEST['id_booking'] == "id_booking") {

    $data = array();

    $query = mysqli_query($con, "SELECT * FROM pos WHERE booking_url = " . $_REQUEST['booking_url']) or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $query = mysqli_query($con, "SELECT * FROM companies WHERE id_company  = " . $row['id_company']) or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {

            $row = mysqli_fetch_assoc($query);

            $query = mysqli_query($con, "SELECT * FROM circuits WHERE id_circuit  = " . $row['id_circuit']) or die(mysqli_error($con));

            if (mysqli_num_rows($query) > 0) {
                $data[] = mysqli_fetch_assoc($query);
            }
        }
    }

    $absolpath = getcwd();
    $path  = $absolpath . '/img/landing/' . $_REQUEST['booking_url'];
    $data['files'] =  array_values(array_diff(scandir($path), array('.', '..')));

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getAllAppointmentState']) && $_REQUEST['getAllAppointmentState'] == "getAllAppointmentState") {


    $data = array();

    $query = mysqli_query($con, "SELECT `appointment_state` FROM `booking_appointments` WHERE 1 GROUP by appointment_state") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {

            $data['appointment_state'][] = $row['appointment_state'];
        }
    }



    // 0 , 1, 2
    //state != deleted, state != deleted and source == internet, state = deleted 
    // $data['appointment_state'] = array('Attivi ', 'Da Internet', 'Eliminati',
    //   );

    $data['appointment_state'] = array(
        '0' => array(
            'name' => 'Attivi',
            'state' => 'active,checked_out,checked_in',
        ),
        '1' => array(
            'name' => 'Da Internet',
            'state' => 'internet',
        ),
        '2' => array(
            'name' => 'Eliminati',
            'state' => 'deleted',
        )
    );

    $data['personal'] = array(
        '0' => array(
            'name' => 'Appuntamenti',
            'color' => '04e9d2',
        ),
        '1' => array(
            'name' => 'Formazione',
            'color' => '008576',
        ),
        '2' => array(
            'name' => 'Pausa',
            'color' => 'dcdcdc',
        ),
        '3' => array(
            'name' => 'Riunione',
            'color' => 'f2e900',
        ),
        '4' => array(
            'name' => 'Permesso',
            'color' => 'db0044',
        ),
        '5' => array(
            'name' => 'Recupero',
            'color' => '5e8eff',
        ),
        '6' => array(
            'name' => 'Altro',
            'color' => 'c6ff73',
        ),
    );


    // $data[] = "Appuntamenti";
    // $data[] = "Formazione";
    // $data[] = "Pausa";
    // $data[] = "Riunione";
    // $data[] = "Permesso";
    // $data[] = "Recupero";
    // $data[] = "Altro";



    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getAllServicesAdmin']) && $_REQUEST['getAllServicesAdmin'] == "getAllServicesAdmin") {



    $data = array();

    $query = mysqli_query($con, "SELECT * FROM services WHERE 1") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {

            $data[] = $row;
        }
    }


    // $data['se'] = $_SESSION;



    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getAllServices']) && $_REQUEST['getAllServices'] == "getAllServices") {

    // url : http://localhost/booking/api.php?getAllServices=getAllServices

    $data = array();

    $query = mysqli_query($con, "SELECT * FROM services WHERE 1 Limit 10") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $row['toggle'] = false;
            if ($row['id_commodity_sector'] == 1) {
                $data['parrucchiere'][] = $row;
            } else
            if ($row['id_commodity_sector'] == 2) {
                $data['estetica'][] = $row;
            }
        }
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getAllEmployees']) && $_REQUEST['getAllEmployees'] == "getAllEmployees") {

    // url : http://localhost/booking/api.php?getAllEmployees=getAllEmployees

    $data = array();

    $query = mysqli_query($con, "SELECT * FROM employees WHERE 1") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getServiceIDBasedEmployees']) && $_REQUEST['getServiceIDBasedEmployees'] == "getServiceIDBasedEmployees") {

    // url : http://localhost/booking/api.php?getServiceIDBasedEmployees=getServiceIDBasedEmployees&service_id=136
    // count_employees : SELECT count(id_employee), id_employee FROM `employee_services` GROUP BY id_employee

    $data = array();


    // $query = mysqli_query($con, "SELECT * FROM employee_services LEFT JOIN employees ON employee_services.id_employee = employees.id_employee WHERE id_service = " . $_REQUEST['service_id']) or die(mysqli_error($con));

    $query = mysqli_query($con, "SELECT * FROM booking_employees LEFT join employees on booking_employees.id_employee = employees.id_employee WHERE visible_on_line = 1  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $query1 = mysqli_query($con, "SELECT * FROM employee_services WHERE id_employee = '" . $row['id_employee'] . "'") or die(mysqli_error($con));
            if (mysqli_num_rows($query1) > 0) {
                $query2 = mysqli_query($con, "SELECT * FROM employee_services WHERE id_service = '" . $_REQUEST['service_id'] . "' and id_employee = '" . $row['id_employee'] . "'") or die(mysqli_error($con));
                if (mysqli_num_rows($query2) > 0) {
                    $data[] = $row;
                }
            } else {
                $data[] = $row;
            }
        }
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getAppointmentList']) && $_REQUEST['getAppointmentList'] == "getAppointmentList") {


    $data = array();
    $temp = array();

    // $data = date('Y-m-d', strtotime($_REQUEST['date']));

    $newDate = $_REQUEST['date'];


    $data['sdsdsd'] =  "SELECT " . date("l", strtotime($_REQUEST['date'])) . "_start_time booking_diary_open_times where id_booking_diary = " . $_SESSION['id_booking_diary'];

    $query = mysqli_query($con, "SELECT " . date("l", strtotime($_REQUEST['date'])) . "_start_time as start_time, " . date("l", strtotime($_REQUEST['date'])) . "_end_time as end_time from booking_diary_open_times where id_booking_diary = 1 ") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data['store_open_time'] = $row;
        }
    }



    $newDate = explode("-", $newDate);

    $newDate = $newDate[0] . "-" . $newDate[2] . "-" . $newDate[1];

    // $date = str_replace('/', '-', $origDate );
    // $newDate = date("Y-m-d", strtotime($date));

    $filter_needed = in_array("Appuntamenti", explode(",", $_REQUEST['filters']));

    $not_deleted = in_array("1", explode(",", $_REQUEST['filters_state']));

    $data['getAppointmentList'] = "'" . implode("','", explode(",", $_REQUEST['filters'])) . "'";

    $data['appointments'] = [];

    $data['session'] = $_SESSION;

    if ($filter_needed) {

        // if()

        $data['ss'] = "SELECT * , DATE_ADD(appointment_date, INTERVAL duration MINUTE) as end FROM booking_appointments LEFT join employees on booking_appointments.id_employee = employees.id_employee where appointment_date BETWEEN CONCAT( '" . $newDate . "',' 00:00:00') AND CONCAT( '" . $newDate  . "',' 23:59:59')  and booking_appointments.state <> 'deleted' and appointment_state in ("  . "'" . implode("','", explode(",", $_REQUEST['filters_state'])) . "'" . ") and appointment_source in ("  . "'" . implode("','", explode(",", $_REQUEST['filters_state'])) . "'" . ")  ";
        $query = mysqli_query($con, "SELECT * , DATE_ADD(appointment_date, INTERVAL duration MINUTE) as end FROM booking_appointments LEFT join employees on booking_appointments.id_employee = employees.id_employee where appointment_date BETWEEN CONCAT( '" . $newDate . "',' 00:00:00') AND CONCAT( '" . $newDate  . "',' 23:59:59')  and booking_appointments.state <> 'deleted' and appointment_state in ("  . "'" . implode("','", explode(",", $_REQUEST['filters_state'])) . "'" . ") and appointment_source in ("  . "'" . implode("','", explode(",", $_REQUEST['filters_state'])) . "'" . ") ") or die(mysqli_error($con));



        $i = 0;

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $row['location'] = $row['id_employee'];
                $row['name'] = $row['service_name'];

                $cust_name = slugify($row['customer_name']);

                // if($row['customer_name'] == 'undefined'){
                //     $cust_name = "";
                // }

                $row['userData'] = $row;
                $row['className'] = 'sked-color-04e9d2 appointment';
                $row['admin_appointment_type'] = 'booking_appointments';
                $row['start'] = date('Y,m,d,H,i', strtotime($row['appointment_date']));
                $row['en11'] = $row['end'];
                $row['end'] = date('Y,m,d,H,i', strtotime($row['end']));
                $data['appointments'][] = $row;
                $tmp = array();

                if (array_search($row['id_employee'], array_column($temp, 'id')) === false) {

                    $tmp['id'] = $row['id_employee'];
                    $tmp['name'] = $row['employee_first_name'];
                    array_push($temp, $tmp);
                }
            }
        }
    }

    $data['ssaaaa'] = "SELECT *, booking_personal_appointments.color , DATE_ADD(appointment_date, INTERVAL duration MINUTE) as end FROM booking_personal_appointments LEFT join employees on booking_personal_appointments.id_employee = employees.id_employee where appointment_date BETWEEN CONCAT( '" . $newDate . "',' 00:00:00') AND CONCAT( '" . $newDate  . "',' 23:59:59')   and booking_personal_appointments.state <> 'deleted'  and appointment_type in ("  . "'" . implode("','", explode(",", $_REQUEST['filters'])) . "'" . ")";
    $query = mysqli_query($con, "SELECT *, booking_personal_appointments.color , DATE_ADD(appointment_date, INTERVAL duration MINUTE) as end FROM booking_personal_appointments LEFT join employees on booking_personal_appointments.id_employee = employees.id_employee where appointment_date BETWEEN CONCAT( '" . $newDate . "',' 00:00:00') AND CONCAT( '" . $newDate  . "',' 23:59:59')   and booking_personal_appointments.state <> 'deleted'  and appointment_type in ("  . "'" . implode("','", explode(",", $_REQUEST['filters'])) . "'" . ")") or die(mysqli_error($con));


    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $row['location'] = $row['id_employee'];
            $row['name'] = 'personal';
            $row['userData'] = $row;
            $row['admin_appointment_type'] = 'booking_personal_appointments';
            $row['color_mod'] = str_replace("#", "", $row['color']);
            $row['className'] = 'sked-color-' . str_replace("#", "", $row['color']);
            $row['start'] = date('Y,m,d,H,i', strtotime($row['appointment_date']));
            $row['en11'] = $row['end'];
            $row['end'] = date('Y,m,d,H,i', strtotime($row['end']));
            $data['appointments'][] = $row;
            $tmp = array();

            if (array_search($row['id_employee'], array_column($temp, 'id')) === false) {

                $tmp['id'] = $row['id_employee'];
                $tmp['name'] = $row['employee_first_name'];
                array_push($temp, $tmp);
            }
        }
    }


    $query = mysqli_query($con, "SELECT * from employees") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $row['id'] = $row['id_employee'];
            $row['name'] = $row['employee_first_name'];
            $data['users'][] = $row;
        }
    }

    // $data['users'] = $temp;
    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getHomepageInfo']) && $_REQUEST['getHomepageInfo'] == "getHomepageInfo") {


    $data = array();

    $data['sss'] = $_SESSION['id_booking_diary'];

    $data['ssssss'] = "SELECT * FROM `booking_online_info` WHERE id_booking_diary = " . $_SESSION['id_booking_diary'];

    $query = mysqli_query($con, "SELECT `id_booking_diary`, `enabled`, `path`, `title`, `address1`, `address2`, `address3`, `address4`, `address5`, `salon_tag`, `salon_lang`, `salon_pay`, `facebook_address`, `istagram_address`, `telegram_address`, `telegram_address_callback`, `mostra_prezzo_finale`, `mostra_prezzi_listino`, `mostra_seleziona_lavorante`, `mostra_dove_siamo`, `mostra_recensioni`, `mostra_altre_recensioni`, `map_link`  FROM booking_online_info WHERE id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data = $row;
        }
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['deleteAppointment']) && $_REQUEST['deleteAppointment'] == "deleteAppointment") {

    $data = array();

    $data['code']  = "401";

    $data['sqss'] = "UPDATE booking_appointments set state = 'deleted' id_appointment = '" . $_POST['id_appointment'] . "'";

    $query = mysqli_query($con, "UPDATE booking_appointments set state = 'deleted' where id_appointment = '" . $_POST['id_appointment'] . "'") or die(mysqli_error($con));

    $data['sssq'] = "UPDATE booking_personal_appointments set state = 'deleted' id_appointment = '" . $_POST['id_appointment'] . "'";

    $query = mysqli_query($con, "UPDATE booking_personal_appointments set state = 'deleted' where id_appointment = '" . $_POST['id_appointment'] . "'") or die(mysqli_error($con));

    if ($query === TRUE) {
        $data['code']  = "200";
    }

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['updateAppointment']) && $_REQUEST['updateAppointment'] == "updateAppointment") {

    $data = array();

    $data['code']  = "401";

    $data['sss'] = "UPDATE booking_appointments set customer_name = '" . $_POST['customer_name'] . "', service_name = '" . $_POST['service_name'] . "', appointment_date = '" . $_POST['appointment_date'] . "', id_employee = '" . $_POST['id_employee'] . "', id_service = '" . $_POST['id_service'] . "' where id_appointment = '" . $_POST['id_appointment'] . "'";

    $query = mysqli_query($con, "UPDATE booking_appointments set customer_name = '" . $_POST['customer_name'] . "', service_name = '" . $_POST['service_name'] . "', appointment_date = '" . $_POST['appointment_date'] . "', id_employee = '" . $_POST['id_employee'] . "', id_service = '" . $_POST['id_service'] . "' where id_appointment = '" . $_POST['id_appointment'] . "'") or die(mysqli_error($con));

    if ($query === TRUE) {
        $data['code']  = "200";
    }

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getopentime']) && $_REQUEST['getopentime'] == "getopentime") {

    // url : http://localhost/booking/api.php?getServiceIDBasedEmployees=getServiceIDBasedEmployees&service_id=136

    $data = array();

    $query = mysqli_query($con, "SELECT * FROM `booking_appointments` WHERE `id_employee` = '" . $_REQUEST['emp_id'] . "' and `state` = 'Active' and appointment_date BETWEEN CONCAT(CURDATE(),' 00:00:00') AND CONCAT(CURDATE(),' 23:59:59')") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    }


    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getStoreopentime']) && $_REQUEST['getStoreopentime'] == "getStoreopentime") {

    // url : http://localhost/booking/api.php?getServiceIDBasedEmployees=getServiceIDBasedEmployees&service_id=136


    $data = array();
    $data1 = array();
    $timeslot = array();
    $data['sssss'] = date('Y-m-d', strtotime($_REQUEST['selected_date']));
    $data['day'] = date("l", strtotime($_REQUEST['selected_date']));
    // $data['date_check'] = date('Y-m-d 0:m:s', strtotime($_REQUEST['selected_date']));



    $query = mysqli_query($con, "SELECT * FROM booking_pos_special_open_days where open_date = '" . $data['sssss'] . " 00:00:00'") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data['open_date'] = $row;
            $data['start_time'] = $data['open_date']['start_time'];
            $data['end_time'] = $data['open_date']['end_time'];
        }
    } else {


        $query = mysqli_query($con, "SELECT * FROM booking_pos_special_close_days where close_date = '" . $data['sssss'] . " 00:00:00'") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data['closed_date'] = $row;
            }
        } else {

            $query = mysqli_query($con, "SELECT * FROM booking_diary_open_times") or die(mysqli_error($con));

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['timeslot'] = $row;
                    $data['start_time'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                    $data['end_time'] = $data['timeslot'][strtolower($data['day']) . '_end_time'];
                }
            }
        }
    }

    $data['all_emp'] = $_POST['emp_a'];

    // $query = mysqli_query($con, "SELECT * FROM booking_employee_shift where id_employee in ("  . $data['all_emp'] . ")") or die(mysqli_error($con));

    // if (mysqli_num_rows($query) > 0) {
    //     while ($row = mysqli_fetch_assoc($query)) {
    //         $data['emp_shift'][$row['id_employee']] = $row;
    //     }
    // }


    $data['diff'] = timeDiff($data['start_time'], $data['end_time']) / 60 / 60;

    if ($data['diff'] == 0) {

        $data['final_date'] = [];
        $data['msg'] = "Store Closed";
        $res = json_encode($data);
        echo $res;
        exit;
    }

    $t = 10;

    $diff = timeDiff($data['start_time'], $data['end_time']) / 60 / 60;

    $diff = $diff * 60 / 10;

    $data['diff'] = $diff;


    $i = 0;

    $time_all = array();

    while ($i < $diff) {

        $v = 0;

        $timestamp = strtotime($data['start_time']) + $t * 60 * $i;

        $time = date('H:i', $timestamp);


        $time_all[] = $time;



        $i++;
    }


    $data['final_slot'] = $time_all;

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['getTimeSlot']) && $_REQUEST['getTimeSlot'] == "getTimeSlot") {

    // url : http://localhost/booking/api.php?getServiceIDBasedEmployees=getServiceIDBasedEmployees&service_id=136

    $data = array();
    $data1 = array();
    $timeslot = array();
    $data['sssss'] = date('Y-m-d', strtotime($_REQUEST['selected_date']));
    $data['day'] = date("l", strtotime($_REQUEST['selected_date']));
    // $data['date_check'] = date('Y-m-d 0:m:s', strtotime($_REQUEST['selected_date']));


    $data['final_slot'] = [];

    $query = mysqli_query($con, "SELECT * FROM booking_pos_special_open_days where open_date = '" . $data['sssss'] . " 00:00:00'") or die(mysqli_error($con));

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data['open_date'] = $row;
            $data['start_time'] = $data['open_date']['start_time'];
            $data['end_time'] = $data['open_date']['end_time'];
        }
    } else {


        $query = mysqli_query($con, "SELECT * FROM booking_pos_special_close_days where close_date = '" . $data['sssss'] . " 00:00:00'") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data['closed_date'] = $row;
            }
        } else {

            $query = mysqli_query($con, "SELECT * FROM booking_diary_open_times") or die(mysqli_error($con));

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['timeslot'] = $row;
                    $data['start_time'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                    $data['end_time'] = $data['timeslot'][strtolower($data['day']) . '_end_time'];
                }
            }
        }
    }

    $data['all_emp'] = $_POST['emp_a'];





    $data['diff'] = timeDiff($data['start_time'], $data['end_time']) / 60 / 60;

    if ($data['diff'] == 0) {

        $data['final_date'] = [];
        $data['msg'] = "Store Closed";
        $res = json_encode($data);
        echo $res;
        exit;
    }



    $emp = array();

    $app = array();

    $period = array_map('intval',  explode(",", $_POST['durr']));

    $data['eriod'] = $period;

    $data['ppp'] = explode(",", $_POST['emp_a']);
    $data['pssspp'] = count($data['ppp']);


    if (count($data['ppp']) == 1) {

        $data['ppp'] = explode(",", $_POST['emp_a'])[0];

        $data['yeee'] = array_map('intval',  explode(",", $_POST['id_service']))[0];
        if ($data['ppp'] == 0) {

            $data['random'] = getRandomFreeUser($con, $data['yeee'], $_POST['emp_a'], $data['sssss']);
            $emp_a[0] = (int) $data['random']['id_employee'];
        } else {
            $emp_a[0] =  $data['ppp'];
        }

        $data['userlist_after_random'] = $emp_a[0];

        // $res = json_encode($data);
        // echo $res;
        // exit;

        $data['sss'] = "SELECT id_appointment, appointment_date, id_employee, duration FROM booking_appointments where id_employee IN (" . $emp_a[0] . ") and  appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59') union select id_appointment, appointment_date, id_employee, duration from booking_personal_appointments where  id_employee IN (" . $data['ppp'] . ")  and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')";

        $query = mysqli_query($con, "SELECT id_appointment, appointment_date, id_employee, duration FROM booking_appointments where id_employee IN (" . $emp_a[0] . ") and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59') union select id_appointment, appointment_date, id_employee, duration from booking_personal_appointments where  id_employee IN (" . $data['ppp'] . ") and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data1[] = $row;
            }
        }



        $query3 = mysqli_query($con, "SELECT * FROM booking_employee_special_shift where id_employee = "  . $emp_a[0] . " and open_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

        $data['zzzz'] = "SELECT * FROM booking_employee_special_shift where id_employee = "  . $emp_a[0] . " and start_time BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')   ";

        if (mysqli_num_rows($query3) > 0) {
            while ($row = mysqli_fetch_assoc($query3)) {
                $data['source'] = "booking_employee_special_shift";
                $data['emp_shift'][$row['id_employee']]['start_date'] = $row['start_time'];
                $data['emp_shift'][$row['id_employee']]['end_date'] =  $row['end_time'];
                $data['emp_shift'][$row['id_employee']]['start_pause'] = $row['start_pause'];
                $data['emp_shift'][$row['id_employee']]['end_pause'] = $row['end_pause'];
            }
        } else {
            $query = mysqli_query($con, "SELECT u.*, s.*,t.* FROM employee_general_shifts u inner join employee_general_shift_details s on u.id_shift = s.id_shift inner join booking_employees t on t.shift_id = s.id_shift where t.id_employee = ("  . $emp_a[0] . ") and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

            if (mysqli_num_rows($query) > 0) {
                while ($row1 = mysqli_fetch_assoc($query)) {
                    $data['source'] = "employee_general_shifts";
                    $data['timeslot'] = $row1;
                    $data['emp_shift'][$row['id_employee']]['start_date'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                    $data['emp_shift'][$row['id_employee']]['end_date'] =  $data['timeslot'][strtolower($data['day']) . '_end_time'];

                    $data['emp_shift'][$row['id_employee']]['start_pause'] =  $data['timeslot'][strtolower($data['day']) . '_start_pause'];
                    $data['emp_shift'][$row['id_employee']]['end_pause'] =   $data['timeslot'][strtolower($data['day']) . '_end_pause'];
                }
            } else {
                $query = mysqli_query($con, "SELECT * FROM booking_diary_open_times where id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

                if (mysqli_num_rows($query) > 0) {
                    while ($row2 = mysqli_fetch_assoc($query)) {
                        $data['source'][] = "booking_diary_open_times1";
                        $data['timeslot'] = $row2;
                        $data['emp_shift'][$emp_a[0]]['start_date'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                        $data['emp_shift'][$emp_a[0]]['end_date'] = $data['timeslot'][strtolower($data['day']) . '_end_time'];

                        $data['emp_shift'][$emp_a[0]]['start_pause'] = $data['timeslot'][strtolower($data['day']) . '_start_pause'];
                        $data['emp_shift'][$emp_a[0]]['end_pause'] = $data['timeslot'][strtolower($data['day']) . '_end_pause'];
                    }
                }
            }
        }



        $data['data'] = $data1;
        $data['debu'] = $emp_a;
        // $data['debu'] = $emp_a;

        for ($i = 0; $i < count($emp_a); $i++) {

            $temp = array();

            for ($j = 0; $j < count($data1); $j++) {

                if ($data1[$j]['id_employee'] ==  $emp_a[$i]) {

                    $temp[] = $data1[$j];
                }
            }

            $app[] = $temp;
        }



        $data['alasl_emp'] = $app;
        $data['eriod1111'] = $period[0];

        // $data['ppp'] = $emp_a; 

        // for ($i = 0; $i < count($app); $i++) {

        // if ($app[$emp_a[$i]] == 0) {
        // $data['szzzzzzzzz'][] = $app[$emp_a[0]];
        $data['szzzzzzzzz'][] = explode(",", $_POST['emp_a'])[0];

        $user_start_time = $data['emp_shift'][$emp_a[0]]['start_date'];
        $user_end_time = $data['emp_shift'][$emp_a[0]]['end_date'];

        $user_start_pause = $data['emp_shift'][$emp_a[0]]['start_pause'];
        $user_end_pause = $data['emp_shift'][$emp_a[0]]['end_pause'];

        $data['zzzsadsdsds'] = $user_start_time;
        $data['dxddf'] = $data['ppp'];
        $new_time = getNewTime($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[0], $period[0], 1);
        $data['final_slot'] = getNewTime($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[0], $period[0], 1);
        // }
        // }

        $query = mysqli_query($con, "SELECT * FROM employees WHERE `id_employee` = " . $emp_a[0] . " ") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data['selected_info'][] = $row;
            }
        }
    } else {





        foreach (explode(",", $_POST['emp_a']) as $key => $value) {

            // checkUserhasTime(-);

        }


        foreach (explode(",", $_POST['emp_a']) as $key => $value) {
            $data['yeee'][] = array_map('intval',  explode(",", $_POST['id_service']))[$key];
            if ($value == 0) {
                $data['random'] = getRandomFreeUser($con, array_map('intval',  explode(",", $_POST['id_service']))[$key], $_POST['emp_a'], $data['sssss']);
                $emp_a[$key] = (int) $data['random']['id_employee'];
            } else {
                $emp_a[$key] = $value;
            }
        }


        // $res = json_encode($data);
        // echo $res;
        // exit;


        $data['ppp'] = join(', ', $emp_a);
        $data['users'] = $emp_a;
        $emp_a =  array_map('intval',  explode(",", $data['ppp']));

        $data['durr'] = $period;



        foreach ($data['users'] as $key => $value) {

            $query3 = mysqli_query($con, "SELECT * FROM booking_employee_special_shift where id_employee in ("  . $value . ") and open_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));
            $data['xxx'] = "SELECT * FROM booking_employee_special_shift where id_employee in ("  . $value . ") and open_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')  and id_booking_diary = " . $_SESSION['id_booking_diary'];

            if (mysqli_num_rows($query3) > 0) {
                while ($row = mysqli_fetch_assoc($query3)) {
                    $data['source'][] = "booking_employee_special_shift";
                    $data['emp_shift'][$row['id_employee']]['start_date'] = $row['start_time'];
                    $data['emp_shift'][$row['id_employee']]['end_date'] =  $row['end_time'];


                    $data['emp_shift'][$row['id_employee']]['start_pause'] = $row['start_pause'];
                    $data['emp_shift'][$row['id_employee']]['end_pause'] = $row['end_pause'];
                }
            } else {
                $query = mysqli_query($con, "SELECT u.*, s.*, t.* FROM employee_general_shifts u inner join employee_general_shift_details s on u.id_shift = s.id_shift inner join booking_employees t on t.shift_id = s.id_shift where id_employee in ("  . $value . ")") or die(mysqli_error($con));
                $data['zzzz'] = "SELECT u.*, s.*, t.* FROM employee_general_shifts u inner join employee_general_shift_details s on u.id_shift = s.id_shift inner join booking_employees t on t.shift_id = s.id_shift where id_employee in ("  . $value . ")";

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $data['source'][] = "booking_employee_shift";
                        $data['timeslot'] = $row;
                        $data['emp_shift'][$row['id_employee']]['start_date'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                        $data['emp_shift'][$row['id_employee']]['end_date'] =  $data['timeslot'][strtolower($data['day']) . '_end_time'];

                        $data['emp_shift'][$row['id_employee']]['start_pause'] =  $data['timeslot'][strtolower($data['day']) . '_start_pause'];
                        $data['emp_shift'][$row['id_employee']]['end_pause'] =   $data['timeslot'][strtolower($data['day']) . '_end_pause'];
                    }
                } else {
                    $query = mysqli_query($con, "SELECT * FROM booking_diary_open_times where id_booking_diary = " . $_SESSION['id_booking_diary']) or die(mysqli_error($con));

                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            $data['source'][] = "booking_diary_open_times";
                            $data['timeslot'] = $row;
                            $data['emp_shift'][$value]['start_date'] = $data['timeslot'][strtolower($data['day']) . '_start_time'];
                            $data['emp_shift'][$value]['end_date'] = $data['timeslot'][strtolower($data['day']) . '_end_time'];

                            $data['emp_shift'][$value]['start_pause'] = $data['timeslot'][strtolower($data['day']) . '_start_pause'];
                            $data['emp_shift'][$value]['end_pause'] = $data['timeslot'][strtolower($data['day']) . '_end_pause'];
                        }
                    }
                }
            }
        }






        $data['sss'] = "SELECT id_appointment, appointment_date, id_employee, duration FROM booking_appointments where id_employee IN (" . $data['ppp'] . ") and  appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59') union select id_appointment, appointment_date, id_employee, duration from booking_personal_appointments where  id_employee IN (" . $data['ppp'] . ")  and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')";

        $query = mysqli_query($con, "SELECT id_appointment, appointment_date, id_employee, duration FROM booking_appointments where id_employee IN (" . $data['ppp'] . ") and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59') union select id_appointment, appointment_date, id_employee, duration from booking_personal_appointments where  id_employee IN (" . $data['ppp'] . ") and appointment_date BETWEEN CONCAT( '" . $data['sssss']  . "',' 00:00:00') AND CONCAT( '" . $data['sssss']  . "',' 23:59:59')") or die(mysqli_error($con));

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data1[] = $row;
            }
        }


        $data['data'] = $data1;
        $data['debus'] = $emp_a;
        // $data['debu'] = $emp_a;

        for ($i = 0; $i < count($emp_a); $i++) {

            $app[$i] = [];

            for ($j = 0; $j < count($data1); $j++) {

                if ($data1[$j]['id_employee'] ==  $emp_a[$i]) {

                    $app[$i][] = $data1[$j];
                }
            }
        }


        $data['alasl_emp'] = $app;



        $data['ssszzz'] = $app;
        $data['$emp_a'] = $emp_a;

        $data['customer_duration'] = [];

        $getServiceCustomer_duration = mysqli_query($con, "SELECT customer_duration FROM services WHERE id_service IN (" .   $_POST['id_service'] . ") ORDER BY field(id_service, " .  $_POST['id_service'] . " )") or die(mysqli_error($con));

        if (mysqli_num_rows($getServiceCustomer_duration) > 0) {
            while ($row = mysqli_fetch_assoc($getServiceCustomer_duration)) {
                $data['customer_duration'][] = (int) $row['customer_duration'];
            }
        }



        $cus_dur = $data['customer_duration'];

        if (count($data1) > 0) {

            $data['hahah'] = date('H:i', strtotime($data1[0]['appointment_date']));
        }

        $new_time = array();

        $combined = 0;

        for ($i = 0; $i < count($app); $i++) {


            // if ($app[$emp_a[$i]] == 0) {
            $data['szzzzzzzzz'][] = ($emp_a[$i + 1]);

            // if ($emp_a[$i + 1] == null) {
            //     break;
            // }

            $data['loop'][] = $i;


            if ($emp_a[$i] == $emp_a[$i + 1]) {
                if ($combined == 0) {
                    $combined =  $period[$i] + $period[$i + 1] + $cus_dur[$i] + $cus_dur[$i + 1];
                } else {
                    $combined = $combined + $period[$i + 1] + $cus_dur[$i + 1];
                }
                $data['looper__'][] = $i;
                $data['looper_1_'][] = $i + 1;
                continue;
            }

            $data['looper'][] = $combined;

            if ($combined == 0) {


                $data['combined'][] = $period[$i] + $cus_dur[$i];
                $user_start_time = $data['emp_shift'][$emp_a[$i]]['start_date'];
                $user_end_time = $data['emp_shift'][$emp_a[$i]]['end_date'];

                $user_start_pause = $data['emp_shift'][$emp_a[0]]['start_pause'];
                $user_end_pause = $data['emp_shift'][$emp_a[0]]['end_pause'];


                $new_time[] = getNewTimeMul($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[$i], $period[$i] + $cus_dur[$i], 0);
                $data['newtime'][] = getNewTimeMul($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[$i], $period[$i] + $cus_dur[$i], 0);
                $combined = 0;
            } else {

                $data['combinedz'][] = $combined;
                $user_start_time = $data['emp_shift'][$emp_a[$i]]['start_date'];
                $user_end_time = $data['emp_shift'][$emp_a[$i]]['end_date'];

                $user_start_pause = $data['emp_shift'][$emp_a[0]]['start_pause'];
                $user_end_pause = $data['emp_shift'][$emp_a[0]]['end_pause'];
                $data['33'] = $app[$i];
                $data['3w3'][] = $i;
                $data['zzzsadsdsds'][] = $emp_a[$i];
                $new_time[] = getNewTimeMul($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[$i], $combined, 0);
                $data['newtime'][] = getNewTimeMul($data['diff'], $user_start_time, $user_end_time, $user_start_pause, $user_end_pause, $app[$i], $combined, 0);
                $combined = 0;
            }




            // }
        }

        $loop = $new_time;

        $data['vvv0'] = $loop;

        if (count($data['vvv0']) != 1) {

            for ($i = 0; $i < count($new_time) - 1; $i++) {

                $data['ddddd'] = $loop;

                for ($j = 0; $j < count($new_time[$i]); $j++) {

                    for ($k = $j; $k < count($new_time[$i + 1]); $k++) {

                        if ($i - 1 < 0) {

                            $timestamp = strtotime($new_time[$i][$j]) + $period[$i] * 60;
                            // $data['period'][] = $period[$i];
                        } else {

                            $timestamp = strtotime($new_time[$i][$j]) + ($period[$i - 1] + $period[$i]) * 60;
                            // $data['period'][] = ($period[$i - 1] + $period[$i]);
                        }

                        $time = date('H:i', $timestamp);

                        $data['this'][] = $new_time[$i + 1];
                        if (in_array($time,  $new_time[$i + 1])) {

                            $data['final_slot'][] = $new_time[$i][$j];

                            break;
                        }
                        // $data['zzzzz'][] = $new_time[$j + 1];

                    }
                }


                $new_time[$i + 1] = $data['final_slot'];
            }
        } else {

            $data['final_slot'] = $data['vvv0'][0];
        }


        // for($i = 0; $i < $data['diff'] * 4; $i++){

        //     $v = 0;

        //     $timestamp = strtotime($data['start_time']) + 15*60*$i;

        //     $time = date('H:i', $timestamp);

        //     for($j = 0; $j < count($data1); $j++){

        //         if(date('H:i', strtotime($data1[$j]['appointment_date'])) == $time){

        //                 $v = 1;
        //                 continue;

        //         }

        //     }

        //     if($v == 0){

        //         $timeslot[] = $time;
        //     }




        // }

        // $i = 0;

        // $time_all = array();

        // while ($i < $data['diff'] * 4) {

        //     $v = 0;

        //     $timestamp = strtotime($data['start_time']) + 15 * 60 * $i;

        //     $time = date('H:i', $timestamp);


        //     $time_all[] = $time;



        //     $i++;
        // }

        // $i = 0;

        // while ($i < $data['diff'] * 4) {

        //     $v = 0;

        //     $timestamp = strtotime($data['start_time']) + 15 * 60 * $i;

        //     $time = date('H:i', $timestamp);

        //     for ($j = 0; $j < count($data1); $j++) {

        //         if (date('H:i', strtotime($data1[$j]['appointment_date'])) == $time) {

        //             $v = 1;

        //             $dur = ceil($data1[$j]['duration'] / 15) - 1;

        //             $i = $i  + $dur;

        //             continue;
        //         }
        //     }

        //     if ($v == 0) {

        //         $timeslot[] = $time;
        //     }


        //     $i++;
        // }

        // $data['time11'] = date('H:i',  strtotime("09:00") + $_REQUEST['total'] * 60);

        // $new_time = array();
        // $test = array();

        // $i = 0;

        // while ($i < count($time_all)) {

        //     $v = 0;

        //     if (in_array($time_all[$i],  $timeslot)) {

        //         // $test[] =  ceil($_REQUEST['total'] / 15);

        //         for ($j = 0; $j <= ceil($_REQUEST['total'] / 15); $j++) {


        //             for ($k = $i; $k < $i + ceil($_REQUEST['total'] / 15); $k++) {


        //                 if ($k < count($time_all)) {
        //                     // $test[] = $time_all[$k];
        //                     if (!in_array($time_all[$k],  $timeslot)) {

        //                         $v = 1;
        //                     }
        //                 }
        //             }
        //         }

        //         if ($v == 0 && (count($time_all) - $i) >= ceil($_REQUEST['total'] / 15)) {
        //             // $test[] = ($i);
        //             $new_time[] = $time_all[$i];
        //         }
        //     }


        //     $i++;
        // }
        // array_map('intval',  explode(",", $_POST['id_service']))[$key]


        foreach (explode(",", $data['ppp']) as $key => $value) {
            $query = mysqli_query($con, "SELECT * FROM employees WHERE `id_employee` = " . $value . " ") or die(mysqli_error($con));

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $data['selected_info'][] = $row;
                }
            }
        }
    }


    $data['emp_appointment_today'] = $data1;
    $data['new_time'] = $new_time;
    // $data['availale_time'] = $timeslot;
    // $data['test'] = $test;
    // $data['all'] = $time_all;
    // $data['total_time'] = $_REQUEST['total'];

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['saveAppointment']) && $_REQUEST['saveAppointment'] == "saveAppointment") {

    // url : http://localhost/booking/api.php?getServiceIDBasedEmployees=getServiceIDBasedEmployees&service_id=136


    // INSERT INTO `booking_appointments` (`id_appointment`, `id_booking_diary`, `id_employee`, `id_customer`, `id_service`, `customer_name`, `service_name`, `appointment_date`, `duration`, `appointment_state`, `delete_reason`, `appointment_source`, `is_new_customer`, `is_requested`, `is_suggested`, `worker_lock`, `time_lock`, `experince_feedback`, `color`, `id_cabin`, `notes`, `state`, `created_at`, `created_from`, `modified_at`, `modified_from`) VALUES
    // ('P0-1556046211-1', 1, 14, 'P0-00000000-964', 156, 'Alberto Fassi', 'TAGLIO UOMO', '2019-04-26 12:00:00', 30, 'deleted', '', 'internet', 0, 0, 0, 0, 0, '', '#FFFFF59D', 0, 'Tel.: 3495883244 - Email: afassi@libero.it', 'deleted', '2019-04-24 10:59:41', 'system', '2019-04-27 09:59:36', 'P176-1527771858')

    $data = array();

    $data['zzz'] = json_decode($_POST['emp'], true);
    $info = json_decode($_POST['emp'], true);

    for ($i = 0; $i < count($info); $i++) {

        // $data['zdcdzz'] = $info[$i];
        $data['xxx'][] = "INSERT INTO booking_appointments (id_appointment, id_booking_diary, id_employee, id_customer, id_service, customer_name, service_name, appointment_date, duration, appointment_state, appointment_source, is_new_customer, notes, state  ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $info[$i]['emp'] . "' , 'P0-" . time()  . "', '" .  $info[$i]['id_service'] . "' , '" . $_POST['customer_name'] . "'  , '" .  $info[$i]['name'] . "' , '" . $_POST['appointment_date'] . "' , '" . $info[$i]['worker_duration'] . "' , 'active', 'internet', '0' , '" . $_POST['notes'] . "' , 'active')";

        $sql = "INSERT INTO booking_appointments (id_appointment, id_booking_diary, id_employee, id_customer, id_service, customer_name, service_name, appointment_date, duration, appointment_state, appointment_source, is_new_customer, notes, state  ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $info[$i]['emp'] . "' , 'P0-" . time()  . "', '" .  $info[$i]['id_service'] . "' , '" . $_POST['customer_name'] . "'  , '" .  $info[$i]['name'] . "' , '" . $info[$i]['app_time'] . "' , '" . $info[$i]['worker_duration'] . "' , 'active', 'internet', '0' , '" . $_POST['notes'] . "' , 'active')";

        if (mysqli_query($con, $sql)) {

            $data['message'] = "New record created successfully";
            http_response_code(202);
            $data['status'] = 200;
        } else {

            http_response_code(400);
            $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
            $data['status'] = 400;
            break;
        }
    }



    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['saveOneAppointment']) && $_REQUEST['saveOneAppointment'] == "saveOneAppointment") {


    $data = array();

    $data['xxx'][] = "INSERT INTO booking_appointments (id_appointment, id_booking_diary, id_employee, id_customer, id_service, customer_name, service_name, appointment_date, duration, appointment_state, appointment_source, is_new_customer, notes, state  ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $_POST['id_employee'] . "' , 'P0-" . time()  . "', '" .   $_POST['id_service'] . "' , '" . $_POST['customer_name'] . "'  , '" .  $_POST['service_name'] . "' , '" . $_POST['appointment_date'] . "' , '" . $_POST['worker_duration'] . "' , 'active', 'internet', '0' , '" . $_POST['notes'] . "' , 'active')";

    $sql = "INSERT INTO booking_appointments (id_appointment, id_booking_diary, id_employee, id_customer, id_service, customer_name, service_name, appointment_date, duration, appointment_state, appointment_source, is_new_customer, notes, state  ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $_POST['id_employee'] . "' , 'P0-" . time()  . "', '" .   $_POST['id_service'] . "' , '" . $_POST['customer_name'] . "'  , '" .  $_POST['service_name'] . "' , '" . $_POST['appointment_date'] . "' , '" . $_POST['worker_duration'] . "' , 'active', 'internet', '0' , '" . $_POST['notes'] . "' , 'active')";

    if (mysqli_query($con, $sql)) {

        $data['message'] = "New record created successfully";
        http_response_code(202);
        $data['status'] = 200;
    } else {

        http_response_code(400);
        $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
        $data['status'] = 400;
    }




    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['updatePersonalAppointment']) && $_REQUEST['updatePersonalAppointment'] == "updatePersonalAppointment") {


    $data = array();

    $data['code']  = "401";

    $data['sssq'] = "UPDATE booking_personal_appointments set id_employee = '" . $_POST['id_employee'] . "'  , id_appointment = '" . $_POST['id_appointment'] . "'";

    $query = mysqli_query($con, "UPDATE booking_personal_appointments set id_employee = '" . $_POST['id_employee'] . "', description = '" . $_POST['description'] . "', appointment_date = '" . $_POST['appointment_date'] . "',duration = '" . $_POST['duration'] . "',appointment_type = '" . $_POST['appointment_type'] . "',color = '" . $_POST['color'] . "'   where id_appointment = '" . $_POST['id_appointment'] . "'") or die(mysqli_error($con));

    if ($query === TRUE) {
        $data['code']  = "200";
    }

    $res = json_encode($data);
    echo $res;
    exit;
} else if (isset($_REQUEST['createPersonalAppointment']) && $_REQUEST['createPersonalAppointment'] == "createPersonalAppointment") {

    $data = array();

    $data['zzz'] = json_decode($_POST['emp'], true);
    $info = json_decode($_POST['emp'], true);

    $current_time = date('Y-m-d H:i:s');


    // $data['zdcdzz'] = $info[$i];
    $data['xxx'][] = "INSERT INTO booking_personal_appointments (id_appointment, id_booking_diary, id_employee, description, appointment_date, duration, appointment_type, color, state, created_at, created_from, modified_at, modified_from ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $_POST['id_employee'] . "' , '" . $_POST['description'] . "', '" . $_POST['appointment_date'] . "', '" . $_POST['duration'] . "' , '" . $_POST['appointment_type'] . "'  , '#" . $_POST['color'] . "'  , 'active' , '" .  $current_time . "' ,  'P460-1568990330', '" .  $current_time . "', 'P460-1568990330')";

    $sql = "INSERT INTO booking_personal_appointments (id_appointment, id_booking_diary, id_employee, description, appointment_date, duration, appointment_type, color, state, created_at, created_from, modified_at, modified_from ) VALUES ('P0-" . time() . "-" . ($i + 1) . "','" . $_SESSION['id_booking_diary'] . "', '" . $_POST['id_employee'] . "' , '" . $_POST['description'] . "', '" . $_POST['appointment_date'] . "', '" . $_POST['duration'] . "' , '" . $_POST['appointment_type'] . "'  , '#" . $_POST['color'] . "'  , 'active' , '" .  $current_time . "' ,  'P460-1568990330', '" .  $current_time . "', 'P460-1568990330')";

    if (mysqli_query($con, $sql)) {

        $data['message'] = "New record created successfully";
        http_response_code(202);
        $data['status'] = 200;
        $data['mysqli_insert_id($conn)'] = mysqli_insert_id($conn);
    } else {

        http_response_code(400);
        $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
        $data['status'] = 400;
    }


    $res = json_encode($data);
    echo $res;
    exit;
}


mysqli_close($con);
