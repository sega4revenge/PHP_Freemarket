<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => FALSE);
   $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
    $connection->set_charset('utf8');
if (isset($_POST['userrated']) && isset($_POST['userrating']) && isset($_POST['point'])&& isset($_POST['contentcomment'])) {

    // receiving the post params
    $userrated = $_POST['userrated'];
    $userrating = $_POST['userrating'];
    $point = $_POST['point'];
	  $contentcomment = $_POST['contentcomment'];
	// $userrated = '28';
	// $userrating = '12';
	// $point = '2';
    $date = round(microtime(true) * 1000);

    // check if user is already existed with the same email
    if ($db->isUserExistedRating($userrated,$userrating,$point)) {
        // user already existed
       //  $response["error"] = TRUE;
        // $response["error_msg"] = "Ton Tai " . $userrated . " " . $userrating;
        // echo json_encode($response);
    } else {
  // create a new user

        // $user = $db->storeUser($username, $email, $password,$phone,$address,$area,$firebaseid);
        //  $response["error"] = TRUE;
        // $response["error_msg"] = "OK";
        // echo json_encode($response);
         $query = "Insert into rate(userrated,userrating,point,time,contentcomment) values ('$userrated','$userrating','$point','$date','$contentcomment');";
         mysqli_query($connection ,$query); 

     
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>

