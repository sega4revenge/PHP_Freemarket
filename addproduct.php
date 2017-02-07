<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
require_once 'include/DB_Functions.php';

$db = new DB_Functions();

if (isset($_POST['productname']) && isset($_POST['price']) && isset($_POST['userid']) && isset($_POST['categoryid']) && isset($_POST['productaddress']) && isset($_POST['areaproduct'])  && isset($_POST['productstatus']) && isset($_POST['productimage']) && isset($_POST['description']) && isset($_POST['lat']) && isset($_POST['lot'])) {


      $productname = $_POST["productname"];
    $price = $_POST["price"];
    $userid = $_POST["userid"];
    $categoryid = $_POST["categoryid"];
    $productaddress = $_POST["productaddress"];
    $areaproduct = $_POST["areaproduct"];
    $productstatus = $_POST["productstatus"];
    $productimage = $_POST["productimage"];
    $description = $_POST["description"];
    $lat = $_POST["lat"];
    $lot = $_POST["lot"];

    // $now = NOW();
    $productdate = round(microtime(true) * 1000);
    $connection->set_charset('utf8');
    $query = "Insert into product(productname,price,userid,categoryid,productaddress,areaproduct,productstatus,productimage,productdate,description,lat,lot) values ('$productname','$price','$userid','$categoryid',
            '$productaddress','$areaproduct','$productstatus','$productimage','$productdate','$description','$lat','$lot');";
    mysqli_query($connection, $query) or die (mysqli_error($connection));
	  $productid = mysqli_insert_id($connection);
    $response["error"] = FALSE;
                        $response["productid"] = $productid;
                        $response["userid"] = $userid;
                        echo json_encode($response, 256);
        // $noti = $db->notification($productname, $productid, $userid);

    mysqli_close($connection);
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
  

?>