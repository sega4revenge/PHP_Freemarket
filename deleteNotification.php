<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
   if(isset($_POST['userid']) && isset($_POST['text'])){
       $userid = $_POST["userid"];
        $text = $_POST["text"];
	// $userid = 28;
        // $text = 'oto';


        $sql1 = "Delete from notification where userid = '$userid' and text = '$text'";
$result1 = $connection->query($sql1);
        if($result1){
          echo "thÃ nh cÃ´ng!";
        }
        else {
          echo "tháº¥t báº¡i!";
        }
}
  

?>