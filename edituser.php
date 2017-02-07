<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
   if(isset($_POST['userid']) && isset($_POST['name']) && isset($_POST['address'])){
         $userid = $_POST["userid"];
     $name = $_POST["name"];
       $address = $_POST["address"];

       // $userimage = 'https://pixabay.com/static/uploads/photo/2015/10/01/21/39/background-image-967820_960_720.jpg';
       // $userid = '28';
       // $name = 'abcxyz';
       // $phone = '434343434';
       // $address = '41414124';



        $sql = "Update users set username = '$name', address = '$address' where userid = '$userid '";
$result = $connection->query($sql);



        if($result){
          echo "Update success!";
}
               else {
          echo "Update failed!";
        }
}
  

?>
