<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */
echo "{";
echo "rate:";
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['userid'])) {

    // receiving the post params
     $userid = $_POST['userid'];
   //  $userid = 28;


    // get the user by email and password
    echo $db->getNotification($userid);

}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
echo "}";
?>

