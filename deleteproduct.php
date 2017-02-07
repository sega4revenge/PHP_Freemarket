<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
   if(isset($_POST['productid']) && isset($_POST['productimage1']) && isset($_POST['productimage2'])){
        $productid = $_POST["productid"];
        $productimage1 = $_POST["productimage1"];
        $productimage2 = $_POST["productimage2"];
        $productimage3 = $_POST["productimage3"];
        $productimage4 = $_POST["productimage4"];

        unlink("$productimage1");
        unlink("$productimage2");
        unlink("$productimage3");
        unlink("$productimage4");

        $sql = "Delete from comments where productid = '$productid'";
$result = $connection->query($sql);
        $sql1 = "Delete from product where productid = '$productid'";
$result1 = $connection->query($sql1);
        if($result1){
          echo "thành công!";
        }
        else {
          echo "thất bại!";
        }
}
  

?>