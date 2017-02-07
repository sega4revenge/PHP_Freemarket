<?php

/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */

require_once 'include/DB_Functions.php';
$db = new DB_Functions();
$connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));

// json response array
$response = array("error" => FALSE);

if (isset($_POST['productid']) && isset($_POST['userrated']) && isset($_POST['userrating'])) {
	
    // receiving the post params
     $productid = $_POST['productid'];
    $userrated = $_POST['userrated'];
   $userrating = $_POST['userrating'];
  //  $productid = 21;
    // $userrated = 28;
    // $userrating = 28;
if($userrated != $userrating){
   $query = "UPDATE product SET sharecount = sharecount + 1 WHERE productid = '$productid'" ;
$result = $connection->query($query);
}
    // get the user by email and password
    $product = $db->getDetailProduct($productid, $userrated, $userrating);
	
}	
  else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

