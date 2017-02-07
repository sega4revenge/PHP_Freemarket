<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
   if(isset($_POST['productid']) && isset($_POST['productname']) && isset($_POST['price']) && isset($_POST['categoryid']) && isset($_POST['productaddress']) && isset($_POST['description']) && isset($_POST['lot']) && isset($_POST['lat'])){
        $productid = $_POST["productid"];
        $productname = $_POST["productname"];
        $price = $_POST["price"];
        $categoryid = $_POST["categoryid"];
        $productaddress = $_POST["productaddress"];
        $description = $_POST["description"];
        $lat = $_POST["lat"];
        $lot = $_POST["lot"];


        $sql = "Update product set productname = '$productname', price = '$price', categoryid = '$categoryid', description = '$description',productaddress = '$productaddress', lat = '$lat', lot = '$lot' where productid = '$productid'";
$result = $connection->query($sql);
//         $sql1 = "Delete from product where productid = '$productid'";
// $result1 = $connection->query($sql1);
        if($result){
          echo "thành công!";
        }
        else {
          echo "thất bại!";
        }
}
  

?>