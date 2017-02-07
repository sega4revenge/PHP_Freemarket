<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';

$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])&&isset($_POST['phone'])&&isset($_POST['address'])&&isset($_POST['area'])) {

    // receiving the post params
     $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$area = $_POST['area'];
	$firebaseid = $_POST['firebaseid'];// check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);
    } else {

        // create a new user
    $otp = rand(100000, 999999);
        $res = $db->storeUser($username, $email, $password,$phone,$address,$area,$firebaseid,$otp);
        if ($res == USER_CREATED_SUCCESSFULLY) {
        
        // send sms
       
              $response["error"] = false;
        $response["message"] = "SMS request is initiated! You will be receiving it shortly.";
    } else if ($res == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["message"] = "Sorry! Error occurred in registration.";
    } else if ($res == USER_ALREADY_EXISTED) {
        $response["error"] = true;
        $response["message"] = "Mobile number already existed!";
    }
   echo json_encode($response);
 }
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}





?>

