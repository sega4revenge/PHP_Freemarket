<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/Functions.php';

$fun = new Functions();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['phone']) && isset($_POST['code']) && isset($_POST['password'])) {
    $phone = $_POST['phone'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    echo $fun->resetPassword($phone, $code, $password);

} else {
	echo "loi";
  $response["error"] = TRUE;
  $response["error_msg"] = "Required parameters (name, phone or password) is missing!";
   echo json_encode($response);


}
?>

