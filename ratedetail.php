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

if (isset($_POST['userid'])) {

    // receiving the post params
    $userid = $_POST['userid'];
    // $userid = 13;

echo "{";
  $sql = "SELECT COUNT(*) as count FROM favorite WHERE friendid = $userid";
        $result = $connection->query($sql);
        while($row = $result->fetch_assoc()) {
        $response = $row["count"];
    }
            echo "\"count\":\"$response\",";  
echo "rate:";
    // get the user by email and password
    echo $db->getRateDetail($userid);
    echo "}";
}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

