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
if (isset($_POST['userrated']) && isset($_POST['userrating'])) {

    // receiving the post params
    $userrated = $_POST['userrated'];
     $userrating = $_POST['userrating'];
	// $userrated = '28';
	// $userrating = '12';
    // check if user is already existed with the same email
    if ($db->isUserExistedFavorite($userrated,$userrating)) {
        // user already existed
        $query = "DELETE FROM favorite WHERE userid = '$userrated' and friendid = '$userrating';";
         mysqli_query($connection ,$query); 
	echo "Ok";
    } else {
  // create a new user

        // $user = $db->storeUser($username, $email, $password,$phone,$address,$area,$firebaseid);
        //  $response["error"] = TRUE;
        // $response["error_msg"] = "OK";
        // echo json_encode($response);
         $query = "Insert into favorite(userid,friendid) values ('$userrated','$userrating');";
         mysqli_query($connection ,$query);
	echo "not oK"; 
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>

