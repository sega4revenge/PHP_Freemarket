<?php

       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
require_once 'include/DB_Functions.php';

// json response array

if (isset($_POST['productid'])) {

    // receiving the post params
    $productid = $_POST['productid'];
            $connection->set_charset('utf8');
$query = "UPDATE product set productstatus = '0' Where productid = '$productid'";
    mysqli_query($connection, $query) or die (mysqli_error($connection));
      $response = "Success ";
        echo $response;
}
else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>

