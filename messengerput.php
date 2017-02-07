<?PHP


if(isset($_POST['userid']) && isset($_POST['useridchat'])&& isset($_POST['room'])&& isset($_POST['roomname'])&& isset($_POST['roompic'])){
	
	
  	 $userid =$_POST['userid'];
	  $useridchat =$_POST['useridchat'];
 	  $room= $_POST['room'];
  	  $roomname= $_POST['roomname'];
				  $roompic= $_POST['roompic'];
			
	$conn =new mysqli("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));
	  

  $sql = "INSERT INTO messenger(userid,useridchat,room,roomname,roompic) VALUES('$userid','$useridchat','$room','$roomname','$roompic')";

  $result = $conn->query($sql);

    mysqli_close($conn);
   
	
	echo "ok";
    }
    else{
      	echo "loi";

    

}

?>

