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
  		// $sql = "SELECT COUNT(*) as count,AVG(point) as rate FROM rate WHERE userrating = $userid  and point != 0";
    //     $result = $connection->query($sql);
    //     //create an array
    //     $encode = array();
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $encode[] = $row;
    //     }
    //     echo json_encode($encode, 256);
    //     if ($encode == null) {
    //         return null;
    //     }
echo "{";
$sql = "SELECT COUNT(*) as count FROM favorite WHERE friendid = $userid";
        $result = $connection->query($sql);
        while($row = $result->fetch_assoc()) {
        $response = $row["count"];
    }
            echo "\"countfavorites\":\"$response\",";  
 if ($db->isRateExisted($userid)) {
        // user already existed
      $sql = "SELECT COUNT(*) as count,AVG(point) as rate FROM rate WHERE userrating = $userid  and point != 0";
        $result = $connection->query($sql);
        while($row = $result->fetch_assoc()) {
   
        $response = $row["count"];
        $response1 = $row["rate"];
    }
            echo "\"rate1\":\"$response1\",";
            echo "\"count\":\"$response\",";  
    } else {
       echo "\"rate1\":\"0\",";
            echo "\"count\":\"0\",";
        }
echo "rate:";
// $sql = "SELECT COUNT(*) as count,AVG(point) as rate FROM rate WHERE userrating = $userid  and point != 0";

//         $result = $connection->query($sql);
//         while($row = $result->fetch_assoc()) {
   
//         $response = $row["count"];
//         $response1 = $row["rate"];
//     }
//         echo "\"rate\":\"$response1\",";
//             echo "\"count\":\"$response\",";

    // get the user by email and password
    echo $db->getRateDetailProfile($userid);
echo "}";

}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

