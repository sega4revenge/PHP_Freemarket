<?php
       $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
  $connection->set_charset('utf8');
   if(isset($_POST['userid']) && isset($_POST['text'])){
       $userid = $_POST["userid"];
        $text = $_POST["text"];
   // $userid = 28;
       // $text = 'abc';


        $sql1 = "INSERT INTO notification(userid,text) VALUES ($userid,'$text')";
$result1 = $connection->query($sql1);
	//$id = mysqli_insert_id(1);

        if($result1){
          echo "thÃƒÂ nh cÃƒÂ´ng!";
        }
        else {
          echo "thÃ¡ÂºÂ¥t bÃ¡ÂºÂ¡i!";
        }
}
  

?>